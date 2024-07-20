官网地址

https://kafka.apache.org/



```javascript
 $ brew install kafka   
==> Downloading https://mirrors.ustc.edu.cn/homebrew-bottles/bottles/kafka-2.6.0
######################################################################## 100.0%
==> Pouring kafka-2.6.0.catalina.bottle.tar.gz
==> Caveats
To have launchd start kafka now and restart at login:
  brew services start kafka
Or, if you don't want/need a background service you can just run:
  zookeeper-server-start /usr/local/etc/kafka/zookeeper.properties & kafka-server-start /usr/local/etc/kafka/server.properties
==> Summary
🍺  /usr/local/Cellar/kafka/2.6.0: 186 files, 62.4MB
```









go get -u github.com/Shopify/sarama



ack 



partitioner 可以随机分区





https://gopkg.in/Shopify/sarama.v2









/usr/local/etc/kafka/server.properties





找到里面的 num.partitions=1 改成自己想要的数



日志文件 /usr/local/var/lib/kafka-logs