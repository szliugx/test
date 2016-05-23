###微信开发中网页授权access_token与基础支持的access_token异同
  
问题1：网页授权access_token与分享的jssdk中的access_token一样吗？
  
  

答：不一样。网页授权access_token 是一次性的，而基础支持的access_token的是有时间限制的：7200s。
  

 

问题2：网页授权access_token与基础支持的access_token不同，那微信分享中的access_token，是不是基础支持的access_token 
  
答：是
  
网页授权access_token 只能获取到一个微信用户信息，是与微信用户一对一的关系，
  
而基础支持的access_token，在有效期内就可以使用access_token和openId 获取微信用户信息
  


> 问题3：网页授权access_token有次数限制吗?
  
答：没有限制
  


> 问题4：通过网页授权获取用户基本信息，使用jssdk中的access_token竟然也能获取到用户数据，这是什么情况
  
答：查资料网页授权access_token与分享的jssdk中的access_token不是不一样吗。这个需要再验证核实。


> 问题5：关于access_token 获取的次数限制？
  

		答：
		接口 　　 　　　　　　每日限额
		获取access_token 　　	2000
		自定义菜单创建 　　	1000
		自定义菜单查询 　　	10000
		获取用户基本信息 　　	5000000
		获取网页授权access_token　　 无
		刷新网页授权access_token　　 无
		网页授权获取用户信息　　 无
  

		请参考 接口频率限制说明

> 问题6：微信开发用户的openid获取有几种方式？
  

答：有两种方式，都是被动式。

> 1，通过url 跳转，腾讯的sns社会化登陆，获取openid 。
  

比如：//取得openid  


    $oauth2Url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=$appid&secret=$secret&code=$code&grant_type=authorization_code";  

    $oauth2 = getJson($oauth2Url);$openid = $oauth2['openid'];  

> 2，通过用户发送消息，通过fromuser 获取openid

 

> 参考官方回答:  

网页授权接口调用凭证,注意：此access_token与基础支持的access_token不同  
  
关于网页授权access_token和普通access_token的区别  

1、微信网页授权是通过OAuth2.0机制实现的，在用户授权给公众号后，公众号可以获取到一个网页授权特有的接口调用凭证（网页授权access_token），通过网页授权access_token可以进行授权后接口调用，如获取用户基本信息.  

2、其他微信接口，需要通过基础支持中的“获取access_token”接口来获取到的普通access_token调用。  


> 附：网页授权获取用户基本信息基本流程

