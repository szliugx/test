Yii日志
============================
Yii提供了一个高度自定义化和高扩展性的日志框架。根据使用场景的不同，你可以很容易的对各种消息就行记录、过滤、合并，比如说文本文件，数据库文件，邮件。
使用Yii的日志框架包含如下步骤：
调用日志记录的方法
------------------------------
在主应用的配置文件(例如basic下面的web.php)中配置好日志的过滤和导出的设置
检查不同场景下经过过滤之后的日志信息
记录日志
记录日志其实就是简简单单的调用如下的方法：
[[Yii::trace()]]: 记录关于某段代码运行的相关消息。主要是用于开发环境。
[[Yii::info()]]: 在某些位置记录一些比较有用的信息的时候使用。
[[Yii::warning()]]: 当某些期望之外的事情发生的时候，使用该方法。
[[Yii::error()]]: 当某些需要立马解决的致命问题发生的时候，调用此方法记录相关信息。
上面的这些方法虽然根据不同的level和类型来记录信息，但是实际上它们调用的是同一个方法function($message, $category = 'application')。其中$message就是要记录的信息，$category表示的是这个日志的归属类。下面的代码表示在默认的‘application'分类下面记录了一条trace类型的信息。
    Yii::trace('start calculating average revenue');
提示:记录的$message可以是简单的string也可以是复杂的数组、对象。你应该根据不同的场景下日志记录的职责选取合适的$message类型。默认情况下，如果你记录的$message不是String,日志在导出的时候都会调用[[yii\helpers\VarDumper::export()]] 方法来输出一个string类型的消息。
为了更好的组织管理及筛选日志消息，通常情况下应当为每一种日志分配合适的类别。你可以选择一种有明显等级区分意思的分类，用以方便根据不同的目的来筛选不同分类的日志。一种简单且有效的命名方式就是使用PHP的魔术常量METHOD来作为分类的名称。Yii框架里面的核心代码在做日志记录的时候就是这么干的。比如说：
    Yii::trace('start calculating average revenue', __METHOD__);
在有常量METHOD出现的地方，它表示的就是当前的方法的名称（并且加上了当前方法所属的类的完整前缀）。举个例子吧，如果在app\controllers\RevenueController这个类里面的calculate方法里面有上面的那行代码，那么此时的METHOD表示的就是‘app\controllers\RevenueController::calculate'。
提示：上面所说的方法事实上只是单例对象[[yii\log\Logger|logger object]] 的[[yii\log\Logger::log()|log()]]方法的简单使用，我们可以通过Yii::getLogger()方法来获得这个单例对象。当我们记录了足够的日志信息或者当前的应用运行结束了，日志对象将调用[yii\log\Dispatcher|message dispatcher]] 方法把记录的日志信息写入到配置的目的位置。
log targets
一个log target是[[yii\log\Target]]或者其子类的实例。它根据严重的等级和分类类过滤日志，然后把日志导出到合适的媒介上面去。就比如说，一个[[yii\log\DbTarget|database target]] 对象就会把过滤之后的日志信息导出到对应数据库。
你可以在应用的配置文件中的日志组件处注册多个log targets,就像下面这样：
    return [
    // the "log" component must be loaded during bootstrapping time
    'bootstrap' => ['log'],
    
    'components' => [
      'log' => [
        'targets' => [
          [
            'class' => 'yii\log\DbTarget',
            'levels' => ['error', 'warning'],
          ],
          [
            'class' => 'yii\log\EmailTarget',
            'levels' => ['error'],
            'categories' => ['yii\db\*'],
            'message' => [
              'from' => ['log@example.com'],
              'to' => ['admin@example.com', 'developer@example.com'],
              'subject' => 'Database errors at example.com',
            ],
          ],
        ],
      ],
    ],
    ];
注意:日志组件必须在bootstrap中配置，这样才能把日志信息分发到对应的log target.
上面的代码里面，两个log target注册到了[[yii\log\Dispatcher::targets]]里面。
第一个筛选出错误和警告信息并且把这些信息保存到了数据库。
第二个筛选出分类以yii\db*开头的错误信息，并把这些信息通过邮件发送到admin@example.com 和 developer@example.com.
Yii有下面这些内置的log targets,你可以参考API文档来学习具体怎么去配置和使用它们。
[[yii\log\DbTarget]]:把日志信息保存到数据库。
[[yii\log\EmailTarget]]: 把日志信息发送到指定的邮箱，上面的例子就是。
[[yii\log\FileTarget]]: 把日志写到到文件。
[[yii\log\SyslogTarget]]: 调用PHP的syslog()方法将日志写入到系统日志。
接下来，我们就来看看常见的log target具有的功能。
消息过滤
就每一种log target而言，你可以配置它的 [[yii\log\Target::levels|levels]] 和 [[yii\log\Target::categories|categories]]属性类设置它的严重程度以及归属的分类。
[[yii\log\Target::levels|levels]]属性的采用一个数组里面的一个或者多个值，这个数组包含如下值：
error：对应[[Yii::error()]]记录的消息
warning：对应[[Yii::warning()]]记录的消息
info ：对应 [[Yii::info()]]记录的信息
trace：对应 [[Yii::trace()]]记录的信息.
profile ：对应[[Yii::beginProfile()]] 和 [[Yii::endProfile()]]记录的信息，这种方式下面更多详细信息会被记录。
如果你没有指定[[yii\log\Target::levels|levels]] 的值，那么任何level的信息都会被记录。
[[yii\log\Target::categories|categories]] 属性的值是数组，这个数组里面的值可以是一个具体的分类名称，也可以是类似正则的匹配模式。只有在target能在这个数组里面找到对应的分类名或者符合某一个匹配模式，他才会处理这些消息。这里的匹配模式的组成是在分类的名称后面加上一个号。如果这个分类恰好和这个匹配模式的号前的字符匹配，那么也就是这个分类找到对应匹配值。举个例来说，在类[[yii\db\Command]]中的yii\db\Command::execute和yii \db\Command:: query 方法使用类名类记录相关日志信息，那么这个时候他们都匹配模式yii\db*
同样的，如果我们没有指定[[yii\log\Target::categories|categories]]，那么每一种分类的日志信息都会被处理。
除了通过[[yii\log\Target::categories|categories]] 属性来设置分类的白名单外，你也可以通过 [[yii\log\Target::except|except]]属性来设置分类的黑名单。属于黑名单的分类日志信息不会被target处理。
下面的配置指定了一个分类匹配yii\db*或者 yii\web\HttpException:*，但又不包括yii\web\HttpException:404的分类，而且它只处理错误和警告的日志信息。
    [
    'class' => 'yii\log\FileTarget',
    'levels' => ['error', 'warning'],
    'categories' => [
      'yii\db\*',
      'yii\web\HttpException:*',
    ],
    'except' => [
      'yii\web\HttpException:404',
    ],
    ]
注意：当错误的句柄捕获到HTTP的异常的时候，记录的日志信息会以yii\web\HttpException:ErrorCode的这种格式
记录，例如[[yii\web\NotFoundHttpException]] 就会被记录成yii\web\HttpException:404
消息格式化
日志targets用多种格式来导出日志。举个例子，如果你的日志target是[[yii\log\FileTarget]]，那么你在你的程序中记录日志的时候，应该会找到类似于文件runtime/log/app.log 记录的如下的信息：
?

    2014-10-04 18:10:15 [::1][][-][trace][yii\base\Module::getModule] Loading module: debug
默认情况下，[[yii\log\Target::formatMessage()]]:会帮我们把日志信息格式化成下面的这种格式：
?

    Timestamp [IP address][User ID][Session ID][Severity Level][Category] Message Text
你可以通过给[[yii\log\Target::prefix]] 属性配置一个自定义的回调函数来 自定义日志的前缀。下面的代码就实现了在每条日志信息前面加上了用户的ID（ip地址，sessionId等敏感信息因为个人隐私被去掉了）。
?

    [
    'class' => 'yii\log\FileTarget',
    'prefix' => function ($message) {
      $user = Yii::$app->has('user', true) ? Yii::$app->get('user') : null;
      $userID = $user ? $user->getId(false) : '-';
      return "[$userID]";
    }
    ]
除了日志消息的前缀，日志的target还把一些上下文信息附加在了每一批的日志记录中。默认情况下，全局的PHP变量包含$_GET, $_POST, $_FILES, $_COOKIE, $_SESSION 和 $_SERVER. 你可以通过配置 [[yii\log\Target::logVars]] 来调整日志记录的全局变量。下面的代码表示的是只记录$_SERVER相关的变量。
?

    [
    'class' => 'yii\log\FileTarget',
    'logVars' => ['_SERVER'],
    ]
当 'logVars'为空的时候，表示不记录相关的上下文信息。如果你想自定义上下文信息的提供方式，你可以覆写[[yii\log\Target::getContextMessage()]] 方法。
消息的trace等级
在开发的过程当中，我们总是期望能够知道每一条日志消息是来自哪里。在Yii中你可以通过配置[[yii\log\Dispatcher::traceLevel|traceLevel]] 属性来实现。配置的示例如下：
?

    return [
    'bootstrap' => ['log'],
    'components' => [
      'log' => [
        'traceLevel' => YII_DEBUG ? 3 : 0,
        'targets' => [...],
      ],
    ],
    ];
上面的示例在YII_DEBUG为true的时候将[[yii\log\Dispatcher::traceLevel|traceLevel]] 设置为3，反之设置为0. 意思是什么呢？3表示每一条日志记录都会记录与之相关的三层栈调用信息，0表示不记录任何相关的栈调用信息
提示：没有必要总是记录调用的堆栈信息，比较耗性能。所以，你应该只在你开发的时候或者用于调试的情况下使用该功能。
消息的清空和导出
就如同上面说的，记录的消息以数组的形式保存在[[yii\log\Logger|logger object]]中。为了限制这个数组消耗过多的内存，当这个数组包含的内容大小达到某个量的时候会被对应的target从内存中转移到对应的目标（文件，数据库...）中。你可以通过设置 [[yii\log\Dispatcher::flushInterval|flushInterval]] 的值来决定量的大小。像下面这样：
?

    return [
    'bootstrap' => ['log'],
    'components' => [
      'log' => [
        'flushInterval' => 100,  // default is 1000
        'targets' => [...],
      ],
    ],
    ];
注意：在应用运行结束的时候也会刷新内存，这样做事为了让日志的target能够记录完整的信息。
把日志信息从内存刷到对应存放的地方的这一动作不是立即发生的。事实上，和上面一样，都是当内存中的日志大小达到一定程度才会发生。你可以像下面的示例一样通过配置不同target的[[yii\log\Target::exportInterval|exportInterval]]值，来达到修改的目的：

    [
    'class' => 'yii\log\FileTarget',
    'exportInterval' => 100, // default is 1000
    ]
因为清空和导出的设定，默认情况下你调用 Yii::trace() 或者其他的日志记录方法的时候不会在日志target下立马看到日志消息。这对某些长时间运行的控制台程序是一个问题。不过这个问题是可以解决的，方法入下面的代码，你需要把[[yii\log\Dispatcher::flushInterval|flushInterval]] 和[[yii\log\Target::exportInterval|exportInterval]] 的值都设置成1：
?

    return [
    'bootstrap' => ['log'],
    'components' => [
      'log' => [
        'flushInterval' => 1,
        'targets' => [
          [
            'class' => 'yii\log\FileTarget',
            'exportInterval' => 1,
          ],
        ],
      ],
    ],
    ];
注意：如此频繁的清空和导出日志消息会降低系统的性能。
切换日志的targets
你可以通过设置[[yii\log\Target::enabled|enabled]] 属性来禁止日志的target。就如同下面的代码描述的一样：
?
1
Yii::$app->log->targets['file']->enabled = false;
上面的代码需要你在配置文件里面有一个下面的配置：
?

    return [
    'bootstrap' => ['log'],
    'components' => [
      'log' => [
        'targets' => [
          'file' => [
            'class' => 'yii\log\FileTarget',
          ],
          'db' => [
            'class' => 'yii\log\DbTarget',
          ],
        ],
      ],
    ],
    ];
创建一个新的target
首先，创建一个新的日志target是很简单的。你主要做的事情是实现[[yii\log\Target::export()]] 方法并且把数组类型的消息[[yii\log\Target::messages]]发送到指定的存储媒介上去就行了。在这个过程中你可以调用[[yii\log\Target::formatMessage()]] 方法来格式化每一条日志消息。至于更多的细节你可以在Yiid的发行版本里找到详细的信息。
性能评测
性能评测是一种比较特别的日志记录。它通常用来获取某些模块执行时间的数据，以此来找到性能的问题所在。比如说，[[yii\db\Command]] 这个类就用性能评测日志来获得每一条sql查询所花费的时间。
要使用该类日志，你首先要做的时确定你要测试的代码范围。然后在每一段代码之间你都应该要保持它们是闭合的，就像下面这个样子：
?

    \Yii::beginProfile('myBenchmark');
    ...code block being profiled...
    \Yii::endProfile('myBenchmark');
myBenchmark只是一个标识，用于你在查看对应日志记录的时候快速定位。
在beginProfile和endProfile之间是可以再嵌套的，但是必须保证正确的闭合关系，如下所示：
?

    \Yii::beginProfile('block1');
     
    // some code to be profiled
     
    \Yii::beginProfile('block2');
      // some other code to be profiled
    \Yii::endProfile('block2');
     
    \Yii::endProfile('block1');
如果上面的闭合关系出错了，对应的记录都不会正常工作。
对于每一块被评测的代码，日志的level都是profile。你可以再日志的target中配置这些信息并导出它们。 Yii内建了 Yii debugger来展示评测的结果。