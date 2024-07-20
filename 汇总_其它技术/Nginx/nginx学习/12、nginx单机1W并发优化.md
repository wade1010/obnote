

![](https://gitee.com/hxc8/images7/raw/master/img/202407190801928.jpg)

虚拟机信息



```javascript
➜  nginx lscpu
Architecture:          x86_64
CPU op-mode(s):        32-bit, 64-bit
Byte Order:            Little Endian
CPU(s):                2
On-line CPU(s) list:   0,1
Thread(s) per core:    1
Core(s) per socket:    1
座：                 2
NUMA 节点：         1
厂商 ID：           GenuineIntel
CPU 系列：          6
型号：              158
型号名称：        Intel(R) Core(TM) i7-7700HQ CPU @ 2.80GHz
步进：              9
CPU MHz：             2807.991
BogoMIPS：            5615.98
超管理器厂商：  VMware
虚拟化类型：     完全
L1d 缓存：          32K
L1i 缓存：          32K
L2 缓存：           256K
L3 缓存：           6144K
NUMA 节点0 CPU：    0,1

```



未做任何优化前  ab不能执行



```javascript
➜  nginx /usr/local/apache/bin/ab -c 2000 -n 20000 http://192.168.1.52:8080/index.html
This is ApacheBench, Version 2.3 <$Revision: 1879490 $>
Copyright 1996 Adam Twiss, Zeus Technology Ltd, http://www.zeustech.net/
Licensed to The Apache Software Foundation, http://www.apache.org/

Benchmarking 192.168.1.52 (be patient)
socket: Too many open files (24)
```



修改： ulimit -n 50000 再次查看那么就是 50000

  

qps 大概 12393



```javascript
➜  nginx /usr/local/apache/bin/ab -c 2000 -n 20000 http://192.168.1.52:8080/index.html
This is ApacheBench, Version 2.3 <$Revision: 1879490 $>
Copyright 1996 Adam Twiss, Zeus Technology Ltd, http://www.zeustech.net/
Licensed to The Apache Software Foundation, http://www.apache.org/

Benchmarking 192.168.1.52 (be patient)
Completed 2000 requests
Completed 4000 requests
Completed 6000 requests
Completed 8000 requests
Completed 10000 requests
Completed 12000 requests
Completed 14000 requests
Completed 16000 requests
Completed 18000 requests
Completed 20000 requests
Finished 20000 requests


Server Software:        nginx/1.16.1
Server Hostname:        192.168.1.52
Server Port:            8080

Document Path:          /index.html
Document Length:        612 bytes

Concurrency Level:      2000
Time taken for tests:   1.618 seconds
Complete requests:      20000
Failed requests:        0
Total transferred:      16900000 bytes
HTML transferred:       12240000 bytes
Requests per second:    12361.60 [#/sec] (mean)
Time per request:       161.791 [ms] (mean)
Time per request:       0.081 [ms] (mean, across all concurrent requests)
Transfer rate:          10200.74 [Kbytes/sec] received

Connection Times (ms)
              min  mean[+/-sd] median   max
Connect:        0   96 271.2      8    1022
Processing:     3   25  42.3     12     412
Waiting:        0   21  40.1      9     412
Total:          6  122 291.7     19    1422

Percentage of the requests served within a certain time (ms)
  50%     19
  66%     38
  75%     49
  80%     74
  90%    160
  95%   1038
  98%   1048
  99%   1243
 100%   1422 (longest request)

```



修改 nginx.conf



```javascript
worker_rlimit_nofile 10000;
worker_connections  10240;
keepalive_timeout  0;
```



修改后重启Nginx



nginx /usr/local/apache/bin/ab -c 2000 -n 20000 http://192.168.1.52:8080/index.html



qps大概13000





调大并发和总数   发现执行到几万条就不停止了 是系统受限制了。修改一些系统配置



/usr/local/apache/bin/ab -c 8000 -n 120000 http://192.168.1.52:8080/index.html



```javascript
➜  nginx /usr/local/apache/bin/ab -c 8000 -n 120000 http://192.168.1.52:8080/index.html
This is ApacheBench, Version 2.3 <$Revision: 1879490 $>
Copyright 1996 Adam Twiss, Zeus Technology Ltd, http://www.zeustech.net/
Licensed to The Apache Software Foundation, http://www.apache.org/

Benchmarking 192.168.1.52 (be patient)
Completed 12000 requests
Completed 24000 requests
Completed 36000 requests
Completed 48000 requests
Completed 60000 requests
Completed 72000 requests
apr_socket_recv: Connection reset by peer (104)
Total of 76051 requests completed
```



start.sh 输入如下内容

```javascript
echo 50000 > /proc/sys/net/core/somaxconn
echo 1 > /proc/sys/net/ipv4/tcp_tw_recycle
echo 1 >  /proc/sys/net/ipv4/tcp_tw_reuse
echo 0 >  /proc/sys/net/ipv4/tcp_syncookies
```





执行脚本

huanyuan.sh

```javascript
echo 128 > /proc/sys/net/core/somaxconn
echo 0 > /proc/sys/net/ipv4/tcp_tw_recycle
echo 0 >  /proc/sys/net/ipv4/tcp_tw_reuse
echo 1 >  /proc/sys/net/ipv4/tcp_syncookies
```



再执行 ab   发现并发还没 -c 2000 -n 20000  高

 

```javascript
 100%  15467 (longest request)
➜  nginx /usr/local/apache/bin/ab -c 8000 -n 120000 http://192.168.1.52:8080/index.html
This is ApacheBench, Version 2.3 <$Revision: 1879490 $>
Copyright 1996 Adam Twiss, Zeus Technology Ltd, http://www.zeustech.net/
Licensed to The Apache Software Foundation, http://www.apache.org/

Benchmarking 192.168.1.52 (be patient)
Completed 12000 requests
Completed 24000 requests
Completed 36000 requests
Completed 48000 requests
Completed 60000 requests
Completed 72000 requests
Completed 84000 requests
Completed 96000 requests
Completed 108000 requests
Completed 120000 requests
Finished 120000 requests


Server Software:        nginx/1.16.1
Server Hostname:        192.168.1.52
Server Port:            8080

Document Path:          /index.html
Document Length:        612 bytes

Concurrency Level:      8000
Time taken for tests:   13.432 seconds
Complete requests:      120000
Failed requests:        0
Total transferred:      101400000 bytes
HTML transferred:       73440000 bytes
Requests per second:    8934.00 [#/sec] (mean)
Time per request:       895.456 [ms] (mean)
Time per request:       0.112 [ms] (mean, across all concurrent requests)
Transfer rate:          7372.29 [Kbytes/sec] received

Connection Times (ms)
              min  mean[+/-sd] median   max
Connect:        0  365 971.8     17    7079
Processing:     3   45  77.0     23    1646
Waiting:        0   39  75.8     18    1646
Total:          6  411 986.4     43    7892

Percentage of the requests served within a certain time (ms)
  50%     43
  66%     68
  75%    455
  80%    472
  90%   1070
  95%   3021
  98%   3093
  99%   7048
 100%   7892 (longest request)

```



修改 -c -n  发现本虚拟机最多 qps 14000 左右



/usr/local/apache/bin/ab -c 4000 -n 120000 http://192.168.1.52:8080/index.html



```javascript
 100%   7260 (longest request)
➜  nginx /usr/local/apache/bin/ab -c 4000 -n 120000 http://192.168.1.52:8080/index.html
This is ApacheBench, Version 2.3 <$Revision: 1879490 $>
Copyright 1996 Adam Twiss, Zeus Technology Ltd, http://www.zeustech.net/
Licensed to The Apache Software Foundation, http://www.apache.org/

Benchmarking 192.168.1.52 (be patient)
Completed 12000 requests
Completed 24000 requests
Completed 36000 requests
Completed 48000 requests
Completed 60000 requests
Completed 72000 requests
Completed 84000 requests
Completed 96000 requests
Completed 108000 requests
Completed 120000 requests
Finished 120000 requests


Server Software:        nginx/1.16.1
Server Hostname:        192.168.1.52
Server Port:            8080

Document Path:          /index.html
Document Length:        612 bytes

Concurrency Level:      4000
Time taken for tests:   8.522 seconds
Complete requests:      120000
Failed requests:        0
Total transferred:      101400000 bytes
HTML transferred:       73440000 bytes
Requests per second:    14081.72 [#/sec] (mean)
Time per request:       284.056 [ms] (mean)
Time per request:       0.071 [ms] (mean, across all concurrent requests)
Transfer rate:          11620.17 [Kbytes/sec] received

Connection Times (ms)
              min  mean[+/-sd] median   max
Connect:        0  155 701.7      8    7039
Processing:     3   17  36.7     11     820
Waiting:        0   14  36.3      9     817
Total:          6  172 710.1     19    7443

Percentage of the requests served within a certain time (ms)
  50%     19
  66%     22
  75%     26
  80%     29
  90%    190
  95%   1028
  98%   3020
  99%   3049
 100%   7443 (longest request)
```



/usr/local/apache/bin/ab -c 3000 -n 100000 http://192.168.1.52:8080/index.html

最终 能稳定在14000左右



---



一：优化思路

   (1)建立socket连接

   (2)打开文件,并沿socket返回。

二：优化



 (1) 修改nginx.conf 进程数量 默认是1024 改成20140 

 

  worker_rlimit_nofile  10000;

   (2)修改最大连接数 somaxconn

     默认打开128个文件 ：more /proc/sys/net/core/somaxconn

     修改：echo 50000 > /proc/sys/net/core/somaxconn

   (3)加快tcp连接的回收

     tcp的回收,默认是0：cat /proc/sys/net/ipv4/tcp_tw_recycle

     修改加快tcp回收：echo 1 > /proc/sys/net/ipv4/tcp_tw_recycle

   (4)修改成不做洪水抵遇

     默认值是1： more /proc/sys/net/ipv4/tcp_syncookies

     修改：echo 0 > /proc/sys/net/ipv4/tcp_syncookies

  （5）修改nginx.conf注释

     keepalive_timeout 65;

   (6)ab -c 10000 -n 500000  http://127.0.0.1/index.html

   (7)如果有失败查看错误日志

     tail nginx.log

     如果日志中出现：cket: Too many open files (24)  

     超过1024个线程 出现错误，说打开文件太多了。

     

     查看支持多少个线程：ulimit -n 一般默认是1024个 最大65535

    修改： ulimit -n 50000 再次查看那么就是 50000







优化过程

 

 

 

 

1:判断nginx的瓶颈

 

1.1: 首先把ab测试端的性能提高,使之能高并发的请求.

易出问题: too many open files

原因 :  ab在压力测试时,打开的socket过多

解决: ulimit -n 30000 (重启失效)

观察结果: nginx 不需要特殊优化的情况下, 5000个连接,1秒内响应.

满足要求,但 wating状态的连接过多.

 

1.2: 解决waiting进程过多的问题.

解决办法: keepalive_timeout = 0;  

即: 请求结果后,不保留tcp连接.

在高并发的情况下, keepalive会占据大量的socket连接.

结果: waiting状态的连接明显减少.

 

1.3: 解决服务端 too many open files

分析: nginx要响应,

1是要建立socket连接,

2 是要读本地文件

这两个者限制.

 

 

![](https://gitee.com/hxc8/images7/raw/master/img/202407190801266.jpg)



 

由上图可看出,nginx的问题容易出在2点上:

1: nginx接受的tcp连接多,能否建立起来?

2: nginx响应过程,要打开许多文件 ,能否打开?

 

第1个问题: 在内核层面(见下)

第2个问题 (见下)

 

 

系统内核层面:

net.core.somaxconn = 4096 允许等待中的监听

net.ipv4.tcp_tw_recycle = 1  tcp连接快速回收

net.ipv4.tcp_tw_reuse = 1    tcp连接重用   

net.ipv4.tcp_syncookies = 0  不抵御洪水攻击

ulimit -n 30000

 

 

Nginx层面:

解决: nginx.conf 下面: work_connection 加大

worker_connections  10240;

Worker_rlimit_nofiles 10000;

Keepalive_timeout 0;