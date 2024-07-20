tikv安装磁盘建议

[https://docs.pingcap.com/zh/tidb/stable/hardware-and-software-requirements](https://docs.pingcap.com/zh/tidb/stable/hardware-and-software-requirements)

TiKV 硬盘大小配置建议 PCI-E SSD 不超过 2 TB，普通 SSD 不超过 1.5 TB。

PD	数据盘和日志盘建议最少各预留 20 GB	低于 90%  和TIKV分开

TiKV 数据盘和日志盘建议最少各预留 100 GB	低于 80%

du -sh /var/lib/oeos/kv

du -sh /var/lib/oeos/es

101:

es  40M

kv:  4.9G

102:

es   43M

kv 4.9G

103:

es   312K

kv  4.9G