2016-05-16
=====================

记录日志比较好的方法
---------------------

###日志方法
		; configuration for php SeasLog module 
		extension = seaslog.so 
		seaslog.default_basepath = /log/seaslog-test    ;默认log根目录 
		seaslog.default_logger = default                ;默认logger目录 
		seaslog.disting_type = 1                            ;是否以type分文件 1是 0否(默认) 
		seaslog.disting_by_hour = 1                      ;是否每小时划分一个文件 1是 0否(默认) 
		seaslog.use_buffer = 1                              ;是否启用buffer 1是 0否(默认) 
		seaslog.buffer_size = 100                         ;buffer中缓冲数量 默认0(不使用buffer_size) 
		seaslog.level = 0                                       ;记录日志级别 默认0(所有日志) 

[查看链接：http://www.xker.com/page/e2015/03/172246.html](http://www.xker.com/page/e2015/03/172246.html)
