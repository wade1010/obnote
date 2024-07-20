mkdir /opt/express



mv express /opt/express



chmod +x /opt/express/express



查看mac地址



然后用gen生成秘钥



./oeos-hd-gen-secret --mac 90:b1:1c:53:db:7e





![](https://gitee.com/hxc8/images6/raw/master/img/202407190008626.jpg)



xp02vDIsypsQyPOad8y2FUaHlA8mu734pgaqJAAaIqo=



vi /etc/init.d/express







```javascript
#!/bin/bash
# chkconfig: 3 88 88
/opt/express/express server http://127.0.0.1/opt/express/.data/disk\{1...4\} --console-address :19003 --address :19002 --secret xp02vDIsypsQyPOad8y2FUaHlA8mu734pgaqJAAaIqo=
```





chmod +x /etc/init.d/express



chkconfig --add express



chkconfig --list express



启动 

nohup service express start &



测试重启服务器是否自动重启

reboot

