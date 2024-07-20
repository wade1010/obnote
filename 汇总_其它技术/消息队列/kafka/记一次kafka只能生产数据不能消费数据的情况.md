记一次kafka只能生产数据不能消费数据的情况

kafka-topics --create --bootstrap-server 10.0.11.101:9092 --topic test

kafka-console-producer --bootstrap-server 10.0.11.102:9092 --topic test  这个是生产者

kafka-console-consumer --bootstrap-server 10.0.11.102:9092 --topic test --from-beginning    这个是消费者

问题：

生产者终端可以不断的发送数据进去，但是消费者终端不会有任何输出

通过启动一个新的kafka（配置文件采用默认的，就是端口改一下别的，不冲突就行），发现也是同一个现象。

通过docker快速部署一个zk集群，端口12181跟原来的2181不一致即可，然后修改kafka里面的zk集群，改成这个新部署的，

然后发现可以正常消费了。

暴力解决，把原来的zk都停掉，然后找到zoo.cfg里面的数据和日志目录，全部删掉，然后重启，然后再启动kafka，就完事了。