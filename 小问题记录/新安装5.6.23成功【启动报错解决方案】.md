在虚拟机下安装了lnmp环境，都安装成功了，但是MySQL启动报错
========================================================
错误一:启动时，提醒/usr/local/mysql/var/vm-vagrant.pid文件不能更新
-------------------------------------------
		删除了如下三个文件(如果只有第一个文件，没关系，照删)：
		[root@vm-vagrant var]# rm ibdata1  ib_logfile0  ib_logfile1 
		rm: remove regular file `ibdata1'? yes      
		rm: remove regular file `ib_logfile0'? yes
		rm: remove regular file `ib_logfile1'? yes
		
		到这里了启动还是报错了

错误二：日志中出现[ERROR] Can't open the mysql.plugin table. Please run mysql_upgrade to create it.
------------------------------------------
		网上大多数的解决办法是在运行初始化权限表的时候使用增加参数--datadir ，命令格式为：
		shell> scripts/mysql_install_db --user=mysql --datadir=/usr/local/mysql/var

### 我是这种方法解决

### 重启，可用！
