Sysbench测试CPU性能



sysbench --test=cpu --cpu-max-prime=20000 --num-threads=4 run sysbench 0.5:  multi-threaded system evaluation benchmark







Sysbench测试磁盘IO性能



Sysbench --test=fileio --file-total-size=10G prepare

解释: 创建10G的内容,供测试用

Sysbench --test=fileio --file-total-size=10G --file-test-mode=rndrw run

解释:针对10G文件,做随机读写,测试IO

--file-test-mode 还可以为

seqwr：顺序写入

seqrewq：顺序重写

seqrd：顺序读取

rndrd：随机读取

rndwr：随机写入

rndrw：混合随机读写



测试顺序读

sysbench --test=fileio --file-total-size=10G --file-test-mode=seqrd run





测试随机读

sysbench --test=fileio --file-total-size=10G --file-test-mode=rndrd run



Sysbench测试事务性能

sysbench --test=/path/to/sysbench-source/tests/db/oltp.lua --mysql-table-engine=innodb \

--mysql-user=root --db-driver=mysql --mysql-db=test  \

--oltp-table-size=3000

--mysql-socket=/var/lib/mysql/mysql.sock prepare





sysbench oltp_read_write.lua --mysql-host=127.0.0.1 --mysql-port=3306 --mysql-db=test --mysql-user=root --mysql-password='' --table_size=200000000 --tables=1 --threads=500 --events=500000 --report-interval=10 --time=0 prepare