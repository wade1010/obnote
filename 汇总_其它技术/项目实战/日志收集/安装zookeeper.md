官方下载

https://zookeeper.apache.org/releases.html#download

镜像下载 

http://mirror.bit.edu.cn/apache/zookeeper/







mac brew安装zookeeper



brew install zookeeper



```javascript
==> Pouring zookeeper-3.6.2.catalina.bottle.tar.gz
==> Caveats
To have launchd start zookeeper now and restart at login:
  brew services start zookeeper
Or, if you don't want/need a background service you can just run:
  zkServer start
==> Summary
🍺  /usr/local/Cellar/zookeeper/3.6.2: 1,025 files, 37MB
```





cd /usr/local/Cellar/zookeeper/3.6.2/bin



zkServer start  或者 brew services start zookeeper

zkServer status

zkServer stop 或者 brew services stop zookeeper





配置文件目录再

/usr/local/etc/zookeeper/zoo.cfg



