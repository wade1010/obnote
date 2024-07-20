wget https://github.com/akopytov/sysbench/archive/refs/tags/1.0.14.tar.gz



tar zxvf 1.0.14.tar.gz



cd sysbench-1.0.14



yum -y install make automake libtool pkgconfig libaio-devel

# For MySQL support, replace with mysql-devel on RHEL/CentOS 5

yum -y install mariadb-devel

# For PostgreSQL support

yum -y install postgresql-devel





./autogen.sh
# Add --with-pgsql to build with PostgreSQL support
./configure

make -j && make install





mkdir -p /opt/test/sysbench



cd /opt/test/sysbench



vim config



```javascript
mysql-host=192.168.199.11
mysql-port=4000
mysql-user=root
mysql-password=
mysql-db=sbtest
time=600
threads=16
report-interval=10
db-driver=mysql
```





mysql --host 192.168.199.187 --port 4000 -u root -p



```javascript
set global tidb_disable_txn_auto_retry = off;
```



然后退出客户端。

重新启动 MySQL 客户端执行以下 SQL 语句，创建数据库 sbtest：



mysql --host 192.168.199.187 --port 4000 -u root -p

```javascript
create database sbtest;
```



rm -rf /usr/local/share/sysbench/oltp_common.lua



cd /usr/local/share/sysbench



wget https://raw.githubusercontent.com/pingcap/tidb-bench/master/sysbench/sysbench-patch/oltp_common.lua





调整 Sysbench 脚本创建索引的顺序。Sysbench 按照“建表->插入数据->创建索引”的顺序导入数据。对于 TiDB 而言，该方式会花费更多的导入时间。你可以通过调整顺序来加速数据的导入。

假设使用的 Sysbench 版本为 1.0.14，可以通过以下两种方式来修改：

1. 直接下载为 TiDB 修改好的 oltp_common.lua 文件，覆盖 /usr/share/sysbench/oltp_common.lua 文件。

1. 将 /usr/share/sysbench/oltp_common.lua 的第 235 行到第 240 行移动到第 198 行以后。





cd /opt/test/sysbench



sysbench --config-file=config oltp_point_select --tables=32 --table-size=10000000 prepare





