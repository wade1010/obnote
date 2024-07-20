设置固定IP

配置文件命名规则

无线：ifcfg- + {热点名}



vim /etc/sysconfig/network-scripts/ifcfg-xxx



这是默认值

```javascript
ESSID=CMCC-XXX-5G
MODE=Managed
KEY_MGMT=WPA-PSK
SECURITYMODE=open
MAC_ADDRESS_RANDOMIZATION=default
TYPE=Wireless
PROXY_METHOD=none
BROWSER_ONLY=no
BOOTPROTO=dhcp
DEFROUTE=yes
IPV4_FAILURE_FATAL=no
IPV6INIT=yes
IPV6_AUTOCONF=yes
IPV6_DEFROUTE=yes
IPV6_FAILURE_FATAL=no
IPV6_ADDR_GEN_MODE=stable-privacy
NAME=CMCC-CXH-5G
UUID=b00bbd0e-8c7b-4e77-8480-f30352512d70
ONBOOT=yes
```



修改

BOOTPROTO=static #默认为dhcp，改成静态的

ONBOOT=yes # 系统启动时是否激活网卡，如果为no就改为yes

添加

IPADDR=192.168.1.119 # 本机ip

NETMASK=255.255.255.0 # 子网掩码

GATEWAY=192.168.1.1 # 默认网关



其他的内容不变



```javascript
ESSID=CMCC-XXX-5G
MODE=Managed
KEY_MGMT=WPA-PSK
SECURITYMODE=open
MAC_ADDRESS_RANDOMIZATION=default
TYPE=Wireless
PROXY_METHOD=none
BROWSER_ONLY=no
BOOTPROTO=static
DEFROUTE=yes
IPV4_FAILURE_FATAL=no
IPV6INIT=yes
IPV6_AUTOCONF=yes
IPV6_DEFROUTE=yes
IPV6_FAILURE_FATAL=no
IPV6_ADDR_GEN_MODE=stable-privacy
NAME=CMCC-CXH-5G
UUID=b00bbd0e-8c7b-4e77-8480-f30352512d70
ONBOOT=yes
IPADDR=192.168.1.119
NETMASK=255.255.255.0
GATEWAY=192.168.1.1
```





重启网络服务



# 查看网络状态 

systemctl status network.service

# 重启网络 

systemctl restart network.service







如果报错 Failed to start LSB: Bring up/down networking

https://blog.csdn.net/weicaijiang/article/details/80746824



