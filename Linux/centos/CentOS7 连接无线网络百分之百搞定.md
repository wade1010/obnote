yum install iw



iw list

```javascript
Wiphy phy0
	max # scan SSIDs: 20
	max scan IEs length: 422 bytes
	max # sched scan SSIDs: 20
	max # match sets: 11
。。。。。。。。。。省略
```

无线网卡可以识别



再使用iw  dev

```javascript
[root@localhost ~]# iw  dev
phy#0
	Interface wlp0s20f3
		ifindex 3
		wdev 0x1
		addr 02:c9:93:52:a6:48
		type managed
```

可以看到无线网卡的设备名 wlp0s20f3   到此设备是找到了，至少硬件已经驱动成功



执行yum install NetworkManager-wifi，实现networkmanager对wifi的统一管理，



安装完毕后需要重启。



重启完毕执行

nmcli dev status    查看设备状态

```javascript
[root@localhost ~]# nmcli dev status
DEVICE             TYPE      STATE   CONNECTION 
eno1               ethernet  已连接  eno1       
wlp0s20f3          wifi      已断开  --         
p2p-dev-wlp0s20f3  wifi-p2p  已断开  --         
lo                 loopback  未托管  --
```

可以看到无线设备已经接入管理，



接下来就运行 nmtui 打开命令行的图形化界面



nmtui



选择 启用连接

![](D:/download/youdaonote-pull-master/data/Technology/Linux/centos/images/CC5439913C64426E9FBCCEB638C16CCDimage.png)



然后就可以看到WiFi了





![](D:/download/youdaonote-pull-master/data/Technology/Linux/centos/images/025EF8093B8B46768615C1D00CA24566image.png)





选择你知道密码的



![](D:/download/youdaonote-pull-master/data/Technology/Linux/centos/images/087B1154C3CD4678A5874FB5B2F35951image.png)



链接成功会有个*号



![](D:/download/youdaonote-pull-master/data/Technology/Linux/centos/images/1CC9E3C442FB488B9DE21473DCA6A217image.png)

