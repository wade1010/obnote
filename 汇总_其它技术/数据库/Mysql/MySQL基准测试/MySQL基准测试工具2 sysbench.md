测试cpu



sysbench --test=cpu --cpu-max-prime=20000 --num-threads=4 run



测试磁盘IO



sysbench --test=fileio --file-total-size=1G prepare  准备数据



sysbench --test=fileio --help查看参数





sysbench --test=fileio  --num-threads=8 --init-rng=on --file-total-size=1G --file-test-mode=rndrw --report-interval=1 run



测试oltp



生成数据 prepare

![](https://gitee.com/hxc8/images7/raw/master/img/202407190811936.jpg)



1万行 10个表



开始测试 run

![](https://gitee.com/hxc8/images7/raw/master/img/202407190811780.jpg)

