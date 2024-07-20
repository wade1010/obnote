pt-table-sync是解决主从数据不一致的绝佳工具，也可也用于两个不是主从数据库之间数据同步，不能同步ddl，只能同步数据,以下为常用例子：

其中h=192.168.56.101为源端，h=192.168.56.102为目标端：

1.sync两个独立数据库，无主从复制关系，同步数据库中所有的表，并排除特定数据库：

pt-table-sync --charset=utf8 --ignore-databases=mysql,sys u=admin,p=admin,h=192.168.56.101,P=3306 dsn=u=admin,p=admin,h=192.168.56.103,P=3306 --execute --print

如果为主从可以使用--no-check-slave 忽略主从关系，建议先使用--print查看有哪些不一致，然后使用--execute进行同步：

打印出不一致数据：

pt-table-sync --charset=utf8 --ignore-databases=Mysql,sys --no-check-slave u=admin,p=admin,h=192.168.56.101,P=3306 dsn=u=admin,p=admin,h=192.168.56.102,P=3306 --print

同步数据并打印出同步语句：

pt-table-sync --charset=utf8 --ignore-databases=MYSQL,sys --no-check-slave u=admin,p=admin,h=192.168.56.101,P=3306 dsn=u=admin,p=admin,h=192.168.56.102,P=3306 --execute --print

1. 同步指定库或者指定表

只对指定的库进行数据sync：

pt-table-sync --charset=utf8 --ignore-databases=mysql,sys --databases=data u=admin,p=admin,h=192.168.56.101,P=3306 dsn=u=admin,p=admin,h=192.168.56.102,P=3306 --execute --print

只对指定的表进行数据sync,多个表用逗号隔开：

pt-table-sync --charset=utf8 --ignore-databases=mysql,sys --databases=data --tables=t_shop_order,t_shop_order_detail u=admin,p=admin,h=192.168.56.101,P=3306 dsn=u=admin,p=admin,h=192.168.56.102,P=3306 --execute --print

--tables也可以使用数据库名和表：

--tables=database_name.table_name

忽略某些库或者忽略某些表

--ignore-databases=指定要忽略的库

--ignore-tables=database_name.table_name 指定要忽略的表

3.如果是主从复制，可以加上--sync-to-master参数进行数据sync：

需要同步的表有主键或者唯一键，其中192.168.56.102为备库：

pt-table-sync --sync-to-master --charset=utf8 --ignore-databases=mysql,sys u=admin,p=admin,h=192.168.56.102,P=3306 --execute --print

sync同步多个slave备库，其中h=192.168.56.102,P=3306, h=192.168.56.103为备库：

pt-table-sync --sync-to-master --charset=utf8 --ignore-databases=mysql,sys u=admin,p=admin,h=192.168.56.102,P=3306 , u=admin,p=admin,h=192.168.56.103,P=3306 --execute --print

4.pt-table-sync 帮助说明：

pt-table-sync --help