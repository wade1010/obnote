首先MAC是自带Apache的。你不用brew装PHP会默认是系统的apache。但是你用brew装，会拷贝一份httpd到/usr/local/etc

这个目录下有Apache的配置

apachectl -S 查看或者apachectl -t -D DUMP_INCLUDES