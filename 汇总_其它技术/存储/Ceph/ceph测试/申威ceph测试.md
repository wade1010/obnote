[https://github.com/get-set/fio-bench-disks-ceph](https://github.com/get-set/fio-bench-disks-ceph)

[https://www.51cto.com/article/716909.html](https://www.51cto.com/article/716909.html)

[https://www.ngui.cc/el/1009348.html?action=onClick](https://www.ngui.cc/el/1009348.html?action=onClick)     

[https://blog.csdn.net/bandaoyu/article/details/113190057](https://blog.csdn.net/bandaoyu/article/details/113190057)

[https://zhuanlan.zhihu.com/p/288290558?utm_source=wechat_session](https://zhuanlan.zhihu.com/p/288290558?utm_source=wechat_session)

[https://blog.csdn.net/weixin_42319496/article/details/125940687](https://blog.csdn.net/weixin_42319496/article/details/125940687)

[https://www.cnblogs.com/dengchj/p/13096320.html](https://www.cnblogs.com/dengchj/p/13096320.html)

[https://www.cnblogs.com/gaohong/p/5818086.html](https://www.cnblogs.com/gaohong/p/5818086.html)

[https://blog.csdn.net/get_set/article/details/108092302](https://blog.csdn.net/get_set/article/details/108092302)

[【批量下载】03_Ceph Bluestore Performance by_Yuan Zhou等.zip](attachments/WEBRESOURCEa1914ac45a7499f5bd0075de4ef14f2d【批量下载】03_Ceph Bluestore Performance by_Yuan Zhou等.zip)

#### 网络基线性能

sudo yum install iperf

sudo apt install iperf

服务端：iperf -s -p 6900

客户端: iperf -c 192.168.1.xxx -p 6900

服务端：

```
$ iperf -s -p 6900                                                                                                                                   [21:33:08]
------------------------------------------------------------
Server listening on TCP port 6900
TCP window size:  128 KByte (default)
------------------------------------------------------------
[  4] local 192.168.100.17 port 6900 connected with 192.168.100.18 port 60180
[ ID] Interval       Transfer     Bandwidth
[  4]  0.0-10.0 sec  1.10 GBytes   942 Mbits/sec
```

客户端：

```
$ iperf -c 192.168.100.17 -p 6900
------------------------------------------------------------
Client connecting to 192.168.100.17, TCP port 6900
TCP window size:  654 KByte (default)
------------------------------------------------------------
[  3] local 192.168.100.18 port 60180 connected with 192.168.100.17 port 6900
[ ID] Interval       Transfer     Bandwidth
[  3]  0.0-10.0 sec  1.10 GBytes   944 Mbits/sec

```

#### 磁盘性能测试

通过查看/sys/block/sda/queue/rotational

通过cat /sys/block/sda/queue/rotational进行查看，返回值0即为SSD；返回1即为HDD。

4K随机写入

```
[bob@bogon ~]$ sudo fio -ioengine=libaio -direct=1 -invalidate=1 -name=test -bs=4K -iodepth=1 -rw=randwrite -runtime=30 -filename=/dev/sdb
[sudo] bob 的密码：
test: (g=0): rw=randwrite, bs=(R) 4096B-4096B, (W) 4096B-4096B, (T) 4096B-4096B, ioengine=libaio, iodepth=1
fio-3.7
Starting 1 process
Jobs: 1 (f=1): [w(1)][100.0%][r=0KiB/s,w=43.1MiB/s][r=0,w=11.0k IOPS][eta 00m:00s]
test: (groupid=0, jobs=1): err= 0: pid=224218: Wed Apr 19 22:32:38 2023
write: IOPS=11.5k, BW=44.7MiB/s (46.9MB/s)(1342MiB/30001msec)
slat (usec): min=16, max=2606, avg=18.83, stdev= 5.93
clat (usec): min=2, max=8094, avg=63.66, stdev=319.39
lat (usec): min=59, max=8116, avg=83.30, stdev=319.59
clat percentiles (usec):
|  1.00th=[   46],  5.00th=[   47], 10.00th=[   47], 20.00th=[   48],
| 30.00th=[   48], 40.00th=[   48], 50.00th=[   48], 60.00th=[   49],
| 70.00th=[   49], 80.00th=[   50], 90.00th=[   52], 95.00th=[   58],
| 99.00th=[   85], 99.50th=[  106], 99.90th=[ 7767], 99.95th=[ 7832],
| 99.99th=[ 7898]
bw (  KiB/s): min=34784, max=54720, per=100.00%, avg=45866.20, stdev=5003.27, samples=59
iops        : min= 8696, max=13680, avg=11466.54, stdev=1250.82, samples=59
lat (usec)   : 4=0.01%, 10=0.01%, 50=82.51%, 100=16.90%, 250=0.34%
lat (usec)   : 500=0.01%, 750=0.01%, 1000=0.01%
lat (msec)   : 2=0.05%, 4=0.01%, 10=0.17%
cpu          : usr=7.56%, sys=32.90%, ctx=343640, majf=0, minf=8
IO depths    : 1=100.0%, 2=0.0%, 4=0.0%, 8=0.0%, 16=0.0%, 32=0.0%, >=64=0.0%
submit    : 0=0.0%, 4=100.0%, 8=0.0%, 16=0.0%, 32=0.0%, 64=0.0%, >=64=0.0%
complete  : 0=0.0%, 4=100.0%, 8=0.0%, 16=0.0%, 32=0.0%, 64=0.0%, >=64=0.0%
issued rwts: total=0,343533,0,0 short=0,0,0,0 dropped=0,0,0,0
latency   : target=0, window=0, percentile=100.00%, depth=1
Run status group 0 (all jobs):
WRITE: bw=44.7MiB/s (46.9MB/s), 44.7MiB/s-44.7MiB/s (46.9MB/s-46.9MB/s), io=1342MiB (1407MB), run=30001-30001msec
Disk stats (read/write):
sdb: ios=39/342197, merge=0/0, ticks=15/16012, in_queue=0, util=61.70%
[bob@bogon ~]$ sudo fio -ioengine=libaio -direct=1 -invalidate=1 -name=test -bs=4K -iodepth=1 -rw=write -runtime=30 -filename=/dev/sdb
test: (g=0): rw=write, bs=(R) 4096B-4096B, (W) 4096B-4096B, (T) 4096B-4096B, ioengine=libaio, iodepth=1
fio-3.7
Starting 1 process
Jobs: 1 (f=1): [W(1)][100.0%][r=0KiB/s,w=56.3MiB/s][r=0,w=14.4k IOPS][eta 00m:00s]
test: (groupid=0, jobs=1): err= 0: pid=224597: Wed Apr 19 22:33:37 2023
write: IOPS=12.0k, BW=50.8MiB/s (53.2MB/s)(1523MiB/30001msec)
slat (usec): min=16, max=2573, avg=18.65, stdev= 6.74
clat (usec): min=2, max=8048, avg=54.65, stdev=218.39
lat (usec): min=59, max=8067, avg=74.04, stdev=218.58
clat percentiles (usec):
|  1.00th=[   46],  5.00th=[   46], 10.00th=[   46], 20.00th=[   47],
| 30.00th=[   47], 40.00th=[   47], 50.00th=[   48], 60.00th=[   48],
| 70.00th=[   48], 80.00th=[   49], 90.00th=[   51], 95.00th=[   54],
| 99.00th=[   73], 99.50th=[   85], 99.90th=[  159], 99.95th=[ 7767],
| 99.99th=[ 7898]
bw (  KiB/s): min=42032, max=58088, per=99.86%, avg=51902.42, stdev=3867.00, samples=59
iops        : min=10508, max=14522, avg=12975.59, stdev=966.74, samples=59
lat (usec)   : 4=0.01%, 10=0.01%, 50=88.83%, 100=10.89%, 250=0.18%
lat (usec)   : 500=0.01%, 1000=0.01%
lat (msec)   : 2=0.01%, 4=0.01%, 10=0.08%
cpu          : usr=7.13%, sys=33.67%, ctx=389843, majf=0, minf=10
IO depths    : 1=100.0%, 2=0.0%, 4=0.0%, 8=0.0%, 16=0.0%, 32=0.0%, >=64=0.0%
submit    : 0=0.0%, 4=100.0%, 8=0.0%, 16=0.0%, 32=0.0%, 64=0.0%, >=64=0.0%
complete  : 0=0.0%, 4=100.0%, 8=0.0%, 16=0.0%, 32=0.0%, 64=0.0%, >=64=0.0%
issued rwts: total=0,389815,0,0 short=0,0,0,0 dropped=0,0,0,0
latency   : target=0, window=0, percentile=100.00%, depth=1
Run status group 0 (all jobs):
WRITE: bw=50.8MiB/s (53.2MB/s), 50.8MiB/s-50.8MiB/s (53.2MB/s-53.2MB/s), io=1523MiB (1597MB), run=30001-30001msec
Disk stats (read/write):
sdb: ios=39/388480, merge=0/0, ticks=15/17815, in_queue=0, util=62.37%
```

4K线性写入

```
[bob@bogon ~]$ sudo fio -ioengine=libaio -direct=1 -invalidate=1 -name=test -bs=4K -iodepth=128 -rw=write -runtime=30 -filename=/dev/sdb
test: (g=0): rw=write, bs=(R) 4096B-4096B, (W) 4096B-4096B, (T) 4096B-4096B, ioengine=libaio, iodepth=128
fio-3.7
Starting 1 process
Jobs: 1 (f=1): [W(1)][100.0%][r=0KiB/s,w=128MiB/s][r=0,w=32.8k IOPS][eta 00m:00s]
test: (groupid=0, jobs=1): err= 0: pid=226337: Wed Apr 19 22:38:28 2023
  write: IOPS=32.9k, BW=128MiB/s (135MB/s)(3851MiB/30001msec)
    slat (usec): min=13, max=245, avg=22.08, stdev= 5.52
    clat (usec): min=58, max=4144, avg=3867.32, stdev=38.32
     lat (usec): min=75, max=4160, avg=3890.62, stdev=38.32
    clat percentiles (usec):
     |  1.00th=[ 3818],  5.00th=[ 3818], 10.00th=[ 3818], 20.00th=[ 3851],
     | 30.00th=[ 3851], 40.00th=[ 3851], 50.00th=[ 3851], 60.00th=[ 3884],
     | 70.00th=[ 3884], 80.00th=[ 3884], 90.00th=[ 3916], 95.00th=[ 3916],
     | 99.00th=[ 3949], 99.50th=[ 3949], 99.90th=[ 3982], 99.95th=[ 3982],
     | 99.99th=[ 4080]
   bw (  KiB/s): min=130312, max=132232, per=99.98%, avg=131433.95, stdev=413.40, samples=59
   iops        : min=32578, max=33058, avg=32858.51, stdev=103.35, samples=59
  lat (usec)   : 100=0.01%, 250=0.01%, 500=0.01%, 750=0.01%, 1000=0.01%
  lat (msec)   : 2=0.01%, 4=99.96%, 10=0.03%
  cpu          : usr=12.67%, sys=87.33%, ctx=2069, majf=0, minf=9
  IO depths    : 1=0.1%, 2=0.1%, 4=0.1%, 8=0.1%, 16=0.1%, 32=0.1%, >=64=100.0%
     submit    : 0=0.0%, 4=100.0%, 8=0.0%, 16=0.0%, 32=0.0%, 64=0.0%, >=64=0.0%
     complete  : 0=0.0%, 4=100.0%, 8=0.0%, 16=0.0%, 32=0.0%, 64=0.0%, >=64=0.1%
     issued rwts: total=0,985946,0,0 short=0,0,0,0 dropped=0,0,0,0
     latency   : target=0, window=0, percentile=100.00%, depth=128

Run status group 0 (all jobs):
  WRITE: bw=128MiB/s (135MB/s), 128MiB/s-128MiB/s (135MB/s-135MB/s), io=3851MiB (4038MB), run=30001-30001msec

Disk stats (read/write):
  sdb: ios=39/982343, merge=0/0, ticks=14/57950, in_queue=0, util=99.73%
[bob@bogon ~]$ sudo fio -ioengine=libaio -direct=1 -invalidate=1 -name=test -bs=4K -iodepth=128 -rw=randwrite -runtime=30 -filename=/dev/sdb
test: (g=0): rw=randwrite, bs=(R) 4096B-4096B, (W) 4096B-4096B, (T) 4096B-4096B, ioengine=libaio, iodepth=128
fio-3.7
Starting 1 process
Jobs: 1 (f=1): [w(1)][100.0%][r=0KiB/s,w=120MiB/s][r=0,w=30.7k IOPS][eta 00m:00s]
test: (groupid=0, jobs=1): err= 0: pid=226765: Wed Apr 19 22:39:36 2023
  write: IOPS=30.5k, BW=119MiB/s (125MB/s)(3570MiB/30001msec)
    slat (usec): min=14, max=266, avg=21.78, stdev= 6.03
    clat (usec): min=63, max=4546, avg=4171.17, stdev=57.18
     lat (usec): min=80, max=4574, avg=4194.35, stdev=57.20
    clat percentiles (usec):
     |  1.00th=[ 4080],  5.00th=[ 4113], 10.00th=[ 4113], 20.00th=[ 4113],
     | 30.00th=[ 4146], 40.00th=[ 4146], 50.00th=[ 4178], 60.00th=[ 4178],
     | 70.00th=[ 4178], 80.00th=[ 4228], 90.00th=[ 4228], 95.00th=[ 4228],
     | 99.00th=[ 4293], 99.50th=[ 4359], 99.90th=[ 4424], 99.95th=[ 4424],
     | 99.99th=[ 4490]
   bw (  KiB/s): min=119632, max=123224, per=99.97%, avg=121814.88, stdev=1005.94, samples=59
   iops        : min=29908, max=30806, avg=30453.69, stdev=251.49, samples=59
  lat (usec)   : 100=0.01%, 250=0.01%, 500=0.01%, 750=0.01%, 1000=0.01%
  lat (msec)   : 2=0.01%, 4=0.02%, 10=99.98%
  cpu          : usr=15.67%, sys=84.30%, ctx=4963, majf=0, minf=7
  IO depths    : 1=0.1%, 2=0.1%, 4=0.1%, 8=0.1%, 16=0.1%, 32=0.1%, >=64=100.0%
     submit    : 0=0.0%, 4=100.0%, 8=0.0%, 16=0.0%, 32=0.0%, 64=0.0%, >=64=0.0%
     complete  : 0=0.0%, 4=100.0%, 8=0.0%, 16=0.0%, 32=0.0%, 64=0.0%, >=64=0.1%
     issued rwts: total=0,913963,0,0 short=0,0,0,0 dropped=0,0,0,0
     latency   : target=0, window=0, percentile=100.00%, depth=128

Run status group 0 (all jobs):
  WRITE: bw=119MiB/s (125MB/s), 119MiB/s-119MiB/s (125MB/s-125MB/s), io=3570MiB (3744MB), run=30001-30001msec

Disk stats (read/write):
  sdb: ios=39/910231, merge=0/0, ticks=12/50321, in_queue=0, util=99.50%
```

单线程单队列

4k_randwrite: (groupid=0, jobs=1): err= 0: pid=273330: Thu Apr 20 11:46:39 2023

write: IOPS=13.2k, BW=51.4MiB/s (53.9MB/s)(1543MiB/30001msec)

slat (usec): min=16, max=2650, avg=19.14, stdev= 4.41

clat (usec): min=2, max=8033, avg=52.07, stdev=160.47

lat (usec): min=59, max=8053, avg=71.99, stdev=160.59

4k_randread: (groupid=1, jobs=1): err= 0: pid=273522: Thu Apr 20 11:46:39 2023

read: IOPS=10.6k, BW=41.2MiB/s (43.2MB/s)(1237MiB/30001msec)

slat (usec): min=16, max=2683, avg=19.32, stdev= 6.15

clat (usec): min=2, max=8108, avg=70.86, stdev=83.58

lat (usec): min=54, max=8128, avg=90.93, stdev=83.82

64k_write: (groupid=2, jobs=1): err= 0: pid=273698: Thu Apr 20 11:46:39 2023

write: IOPS=4640, BW=290MiB/s (304MB/s)(8702MiB/30001msec)

slat (nsec): min=22680, max=84240, avg=28818.28, stdev=3085.12

clat (usec): min=135, max=8164, avg=182.60, stdev=44.00

lat (usec): min=198, max=8190, avg=212.26, stdev=44.16

64k_read: (groupid=3, jobs=1): err= 0: pid=273882: Thu Apr 20 11:46:39 2023

read: IOPS=4301, BW=269MiB/s (282MB/s)(8066MiB/30001msec)

slat (nsec): min=21640, max=96640, avg=23930.47, stdev=1595.89

clat (usec): min=152, max=8195, avg=204.86, stdev=54.22

lat (usec): min=215, max=8219, avg=229.57, stdev=54.30

单线程多队列64

4k_randwrite: (groupid=0, jobs=1): err= 0: pid=279206: Thu Apr 20 12:02:18 2023

write: IOPS=32.0k, BW=125MiB/s (131MB/s)(3751MiB/30001msec)

slat (usec): min=13, max=108, avg=20.88, stdev= 5.69

clat (usec): min=47, max=2247, avg=1971.31, stdev=24.75

lat (usec): min=72, max=2265, avg=1993.53, stdev=24.77

4k_randread: (groupid=1, jobs=1): err= 0: pid=279391: Thu Apr 20 12:02:18 2023

read: IOPS=29.8k, BW=116MiB/s (122MB/s)(3488MiB/30001msec)

slat (usec): min=9, max=1226, avg=24.75, stdev= 9.36

clat (usec): min=153, max=5194, avg=2119.35, stdev=80.50

lat (usec): min=180, max=5210, avg=2145.90, stdev=82.14

64k_write: (groupid=2, jobs=1): err= 0: pid=279590: Thu Apr 20 12:02:18 2023

write: IOPS=7090, BW=443MiB/s (465MB/s)(12.0GiB/30009msec)

slat (usec): min=10, max=4246, avg=17.27, stdev=13.04

clat (usec): min=1624, max=16959, avg=9004.82, stdev=525.00

lat (usec): min=1663, max=16972, avg=9022.87, stdev=524.57

64k_read: (groupid=3, jobs=1): err= 0: pid=279773: Thu Apr 20 12:02:18 2023

read: IOPS=8032, BW=502MiB/s (526MB/s)(14.7GiB/30008msec)

slat (usec): min=8, max=114, avg=13.49, stdev= 4.79

clat (usec): min=2493, max=15484, avg=7949.69, stdev=513.80

lat (usec): min=2533, max=15501, avg=7963.83, stdev=513.42

多线程多队列 5线程64队列

4k_randwrite: (groupid=0, jobs=5): err= 0: pid=286209: Thu Apr 20 12:21:48 2023

write: IOPS=37.1k, BW=145MiB/s (152MB/s)(4352MiB/30001msec)

slat (usec): min=15, max=1208, avg=126.31, stdev=16.75

clat (usec): min=47, max=17435, avg=8481.08, stdev=278.43

lat (usec): min=93, max=17518, avg=8608.34, stdev=282.10

4k_randread: (groupid=1, jobs=5): err= 0: pid=286401: Thu Apr 20 12:21:48 2023

read: IOPS=37.5k, BW=146MiB/s (154MB/s)(4392MiB/30001msec)

slat (usec): min=14, max=10171, avg=120.98, stdev=43.25

clat (usec): min=42, max=18651, avg=8403.12, stdev=324.17

lat (usec): min=103, max=18709, avg=8525.53, stdev=328.17

64k_write: (groupid=2, jobs=5): err= 0: pid=286593: Thu Apr 20 12:21:48 2023

write: IOPS=6756, BW=422MiB/s (443MB/s)(12.4GiB/30039msec)

slat (usec): min=10, max=179, avg=17.72, stdev= 5.16

clat (msec): min=2, max=296, avg=47.31, stdev=91.68

lat (msec): min=2, max=296, avg=47.33, stdev=91.68

64k_read: (groupid=3, jobs=5): err= 0: pid=286782: Thu Apr 20 12:21:48 2023

read: IOPS=6989, BW=437MiB/s (458MB/s)(12.8GiB/30041msec)

slat (usec): min=8, max=508, avg=11.95, stdev= 5.02

clat (msec): min=2, max=367, avg=45.74, stdev=90.31

lat (msec): min=2, max=368, avg=45.75, stdev=90.31

#### 搭建集群

sudo make install 

sudo vim /etc/ceph/ceph.conf

```
[global]
fsid = a7f64266-0894-4f1e-a635-d0aeaca0e993
mon initial members = monitor
mon host = 192.168.100.18
auth cluster required = cephx
auth service required = cephx
auth client required = cephx
osd pool default size = 1
osd pool default min size = 1
osd pool default pg num = 16
osd pool default pgp num = 16
osd crush chooseleaf type = 1
mon_allow_pool_delete = true
```

MON=1 OSD=0 MDS=0 MGR=1 RGW=0 NFS=0 ../src/vstart.sh -n -d

bin/ceph -s -c /home/bob/workspace/ceph-16.2.9-2/build/ceph.conf