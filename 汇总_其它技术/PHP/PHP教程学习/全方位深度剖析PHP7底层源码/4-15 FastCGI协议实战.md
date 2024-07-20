查看网卡



```javascript
➜  php-fpm.d ifconfig
ens33: flags=4163<UP,BROADCAST,RUNNING,MULTICAST>  mtu 1500
        inet 192.168.1.52  netmask 255.255.255.0  broadcast 192.168.1.255
        inet6 fe80::6449:c2e5:2749:c7f4  prefixlen 64  scopeid 0x20<link>
        ether 00:0c:29:dd:e9:e3  txqueuelen 1000  (Ethernet)
        RX packets 152286  bytes 169613218 (161.7 MiB)
        RX errors 0  dropped 0  overruns 0  frame 0
        TX packets 97651  bytes 18057648 (17.2 MiB)
        TX errors 0  dropped 0 overruns 0  carrier 0  collisions 0

lo: flags=73<UP,LOOPBACK,RUNNING>  mtu 65536
        inet 127.0.0.1  netmask 255.0.0.0
        inet6 ::1  prefixlen 128  scopeid 0x10<host>
        loop  txqueuelen 1000  (Local Loopback)
        RX packets 272  bytes 40124 (39.1 KiB)
        RX errors 0  dropped 0  overruns 0  frame 0
        TX packets 272  bytes 40124 (39.1 KiB)
        TX errors 0  dropped 0 overruns 0  carrier 0  collisions 0

virbr0: flags=4099<UP,BROADCAST,MULTICAST>  mtu 1500
        inet 192.168.122.1  netmask 255.255.255.0  broadcast 192.168.122.255
        ether 52:54:00:29:1a:cb  txqueuelen 1000  (Ethernet)
        RX packets 0  bytes 0 (0.0 B)
        RX errors 0  dropped 0  overruns 0  frame 0
        TX packets 0  bytes 0 (0.0 B)
        TX errors 0  dropped 0 overruns 0  carrier 0  collisions 0
```





这是 lo





php fastcgi 端口是9000







tcpdump -i lo port 9000 -XX -S











![](https://gitee.com/hxc8/images8/raw/master/img/202407191104206.jpg)

 