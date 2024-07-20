#### 环境
VMware虚拟机1： 192.168.1.39

VMware虚拟机2： 192.168.1.40

想配置的VIP地址：192.168.111.88
### 现象
keepalived.conf中vip配置好后，通过ip addr可以看到vip已经顺利挂载，vip也能漂移。能在master上ping通，但是无法在其他机器上ping通(比如master在 192.168.1.39上，39是能ping通111.88，但是我在 192.168.1.40上就ping不通)，并且防火墙都已关闭，SELinux已关闭。

### 排查
- journalctl -ex  查看两台机器日志，没发现任何问题
- 开启了百度谷歌之旅
#### 搜索来的解决方案
>原因是keepalived.conf配置中默认vrrp_strict打开了，需要把它注释掉。重启keepalived即可ping通
- 结果发现我的配置文件中根本没这个vrrp_strict,肯定不是这个原因。排除

---

>[后来看到这篇文章](https://blog.csdn.net/charthyf/article/details/81456872)
        - 可能是对lvs手册不太熟悉，我只试了添加virtual_server和real_server 
```
virtual_server 192.168.111.88 8080 {
    delay_loop 6
    lb_algo rr
    lb_kind NAT
    protocol TCP
    real_server 192.168.1.39 8080
    {
         weight 1
    }
}
```
两台机器的配置都加了，还是原来的问题。再仔细看了下原文"NAT模式和路由器NAT模式类似，用于访问client和real_server在不同网段实现通信。如果你在一个局域网内做负载均衡选用NAT，那恭喜你，你肯定是无法访问",我就把vip改成10.0.0.164(回头写笔记的时候，这里很惭愧啊。没懂这里，懂的话，其实已经能解决了)
```
virtual_server 10.0.0.164 8080 {
    delay_loop 6
    lb_algo rr
    lb_kind NAT
    protocol TCP
    real_server 192.168.1.39 8080
    {
         weight 1
    }
}
```
发现还是原来的问题。不行。


---
>后来看到说arp绑定问题,需要清楚arp绑定
这个不懂，我就查看了下怎么看arp绑定
```
arp -n
```
发现两台机器一样，以为没有问题，就跳过了。(回头看下应该是有问题的，最后解决了再说)

---
>再看了一篇说是云服务器的vip需要申请，跟服务商申请。反正就是云服务器跟虚拟机不一样，这里我就没继续研究了

---
抓包看问题
>tcpdump -i ens33 arp -v

>tcpdump -i ens33 vrrp -n

我也没看出啥。￣□￣｜｜

---
最后看到下面这个，才知道问题所在

>因为交换机上没配相关路由吧，跨网段的时候是会存在路由问题的，如果你把VIP也设置为192.168.2.*应该就没这个问题了。
因为其他机器放问192.168.100.100的时候会默认去192.168.100.*的网段去寻找主机，所以就找不到具体的物理地址了。
还有个办法就是在2.x的主机上都配置静态路由，add route ， 把192.168.100.100的路由配置到192.168.2.254（貌似这个是网关？）
建议还是换成2.x的地址更合理。
我们一般做地址规划的时候，200以内都是物理ip，200以上都给VIP预留，就是为了避免这种问题

所有我改了vip地址

VMware虚拟机1： 192.168.1.39

VMware虚拟机2： 192.168.1.40

想配置的VIP地址：192.168.111.88

改成

VMware虚拟机1： 192.168.1.39

VMware虚拟机2： 192.168.1.40

想配置的VIP地址：192.168.1.222

重启keepalived。发现正常了(#^.^#)


再回头看下arp
master看arp
```
[root@vmware39 keepalived]# arp -n
Address                  HWtype  HWaddress           Flags Mask            Iface
192.168.1.40             ether   00:0c:29:e6:6b:d5   C                     ens33
192.168.1.1              ether   14:30:04:a3:fe:d5   C                     ens33
192.168.1.33             ether   00:e0:4c:36:05:bf   C                     ens33
```
slave看arp
```
[root@vmware40 keepalived]# arp -n
Address                  HWtype  HWaddress           Flags Mask            Iface
192.168.1.1              ether   14:30:04:a3:fe:d5   C                     ens33
192.168.1.39             ether   00:0c:29:5d:57:e5   C                     ens33
192.168.1.222            ether   00:0c:29:5d:57:e5   C                     ens33
192.168.1.33             ether   00:e0:4c:36:05:bf   C                     ens33

```
是不是恍然大悟。上面所有办法，在遇到ping不通的情况都可以试试