

[mysqldiff.tar.gz](attachments/WEBRESOURCE738b8f141913dc085c7fceb8b65862c8mysqldiff.tar.gz)

1. 首先查看机器有没有安装mysql

1. 没有则安装

1. 查看是否有mysqlshow这个命令，没有则软连接到bin目录下 （ln -sf /usr/local/mysql/bin/mysqlshow  /usr/local/bin/mysqlshow）或者  /usr/bin/mysqlshow

1. 再SCP上面的tar包到指定目录

1. 解压后，进入目录

1. perl -MCPAN -e shell

1. 再分别执行install_mysqldiff.sh的脚本(这里面有几条语句，复制到终端)，中途可能报错，根据报错内容，install模块即可

1. 测试有没有mysqldiff命令 假如没有，可以重装或者将mysqldiff软连接到/usr/bin下面（ln -sf /opt/mysqldiff/MySQL-Diff-0.50/bin/mysqldiff /usr/bin/mysqldiff）

1. 测试

1. 先修改xxx.config.sh文件，将里面的数据库连接改成自己的

1. 然后执行 sh diff.sh     xxx.config.sh（如sh diff.sh crm/fix.config.sh）