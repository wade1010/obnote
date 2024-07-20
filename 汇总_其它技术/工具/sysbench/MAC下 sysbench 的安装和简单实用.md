### 安装
> brew install sysbench

## 找到测试脚本目录

找到内置的lua脚本 可以用find命令查找 

我的目录是

> /usr/local/Cellar/sysbench/1.0.20/share/sysbench



```
/usr/local/Cellar/sysbench/1.0.20/share/sysbench> ll
total 120
-rwxr-xr-x  1 xxx  staff   1.4K  4 24  2020 bulk_insert.lua
-rw-r--r--  1 xxx  staff    14K  4 24  2020 oltp_common.lua
-rwxr-xr-x  1 xxx  staff   1.3K  4 24  2020 oltp_delete.lua
-rwxr-xr-x  1 xxx  staff   2.4K  4 24  2020 oltp_insert.lua
-rwxr-xr-x  1 xxx  staff   1.2K  4 24  2020 oltp_point_select.lua
-rwxr-xr-x  1 xxx  staff   1.6K  4 24  2020 oltp_read_only.lua
-rwxr-xr-x  1 xxx  staff   1.8K  4 24  2020 oltp_read_write.lua
-rwxr-xr-x  1 xxx  staff   1.1K  4 24  2020 oltp_update_index.lua
-rwxr-xr-x  1 xxx  staff   1.1K  4 24  2020 oltp_update_non_index.lua
-rwxr-xr-x  1 xxx  staff   1.4K  4 24  2020 oltp_write_only.lua
-rwxr-xr-x  1 xxx  staff   1.9K  4 24  2020 select_random_points.lua
-rwxr-xr-x  1 xxx  staff   2.1K  4 24  2020 select_random_ranges.lua
drwxr-xr-x  5 xxx  staff   160B 11  6 13:49 tests
```

## 测试

####  bulk_insert.lua

##### prepare阶段，生成需要的测试表

生成一个表

> sysbench bulk_insert.lua --mysql-host=127.0.0.1 --mysql-port=3306 --mysql-db=test --mysql-user=root --mysql-password='' prepare

生成十个表
> sysbench bulk_insert.lua --mysql-host=127.0.0.1 --mysql-port=3306 --mysql-db=test --mysql-user=root --mysql-password='' --tables=1 --threads=10 prepare 

##### run阶段 插入测试数据

十个表插入数据

> sysbench bulk_insert.lua --mysql-host=127.0.0.1 --mysql-port=3306 --mysql-db=test --mysql-user=root --mysql-password='' --table_size=200000000 --tables=1 --threads=10 --events=500000 --report-interval=10 --time=0 run


##### cleanup清理阶段
> sysbench bulk_insert.lua --mysql-host=127.0.0.1 --mysql-port=3306 --mysql-db=test --mysql-user=root --mysql-password='' --tables=1 --threads=10 cleanup 


####  oltp_read_write.lua

prepare

> sysbench oltp_read_write.lua --mysql-host=127.0.0.1 --mysql-port=3306 --mysql-db=test --mysql-user=root --mysql-password='' --table_size=200000 --tables=10 --threads=10 prepare

run

> sysbench oltp_read_write.lua --mysql-host=127.0.0.1 --mysql-port=3306 --mysql-db=test --mysql-user=root --mysql-password='' --table_size=200000 --tables=10 --threads=10 run

cleanup

> sysbench oltp_read_write.lua --mysql-host=127.0.0.1 --mysql-port=3306 --mysql-db=test --mysql-user=root --mysql-password='' --table_size=200000 --tables=10 --threads=10 cleanup

#### 其他lua脚本  可执行测试
