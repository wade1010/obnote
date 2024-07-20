重启网卡，master变为fault状态，但没继续变为backup状态。
检查日志发现有错误信息
Keepalived_vrrp: Netlink: filter function error
Keepalived_healthcheckers: Netlink: filter function error
查找网络资料得知，keepalive不支持网络设备热插拔。ip link可以看出设备id变化了。
此时需要reload keepalived或者直接restart。
办法：网卡发生重启后，需检查keepalived是否正常


也不知道对不对，留作看看吧

