### 国内源下载ES

到 https://mirrors.huaweicloud.com/elasticsearch/7.9.2/ 选择自己需要的版本，我这里是7.9.2

> wget https://mirrors.huaweicloud.com/elasticsearch/7.9.2/elasticsearch-7.9.2-linux-x86_64.tar.gz

> tar -xzvf elasticsearch-7.9.2-linux-x86_64.tar.gz

> cd elasticsearch-7.9.2

> vim config/jvm.options

```
-Xms256m
-Xmx256m
##-Xms1g
##-Xmx1g
```

#### 外网访问问题

> vim config/elasticsearch.yml

```
network.host: 0.0.0.0
```

###### 再启动 会报错
```
ERROR: [4] bootstrap checks failed
[1]: max file descriptors [4096] for elasticsearch process is too low, increase to at least [65535]
[2]: max number of threads [3766] for user [cheng] is too low, increase to at least [4096]
[3]: max virtual memory areas vm.max_map_count [65530] is too low, increase to at least [262144]
[4]: the default discovery settings are unsuitable for production use; at least one of [discovery.seed_hosts, discovery.seed_providers, cluster.initial_master_nodes] must be configured
ERROR: Elasticsearch did not exit normally - check the logs at /home/Downloads/elasticsearch-7.9.2/logs/elasticsearch.log
```

###### 解决方案
参考 https://blog.csdn.net/wd2014610/article/details/89532638


#### 安装ik_max_word

参考GitHub https://github.com/medcl/elasticsearch-analysis-ik

下面命令里面 7.9.2 替换成你自己的版本即可

> ./bin/elasticsearch-plugin install https://github.com/medcl/elasticsearch-analysis-ik/releases/download/v7.9.2/elasticsearch-analysis-ik-7.9.2.zip

##### 自定义词库

> vim config/analysis-ik/IKAnalyzer.cfg.xml

其中 ext_dict.txt  就是你自定义的词库 我是放在同级目录

```
<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE properties SYSTEM "http://java.sun.com/dtd/properties.dtd">
<properties>
	<comment>IK Analyzer 扩展配置</comment>
	<!--用户可以在这里配置自己的扩展字典 -->
	<entry key="ext_dict">ext_dict.txt</entry>
	 <!--用户可以在这里配置自己的扩展停止词字典-->
	<entry key="ext_stopwords"></entry>
	<!--用户可以在这里配置远程扩展字典 -->
	<!-- <entry key="remote_ext_dict">words_location</entry> -->
	<!--用户可以在这里配置远程扩展停止词字典-->
	<!-- <entry key="remote_ext_stopwords">words_location</entry> -->
</properties>
```

### 添加 es启动用户


```
创建一个非root用户，作为elasticsearch启动用户

创建用户组 groupadd esgroup
创建用户 useradd esuser -g esgroup -p 123456
赋予权限 chown -R esuser:esgroup elasticsearch-7.9.2 (用户名：组 es目录)
```

### 启动 es

将文件移动到/usr/local下

> mv elasticsearch-7.9.2 /usr/local/

> cd /usr/local/

> su esuser

> ./elasticsearch-7.9.3/bin/elasticsearch

查看下是否报错

没错就可以关闭 然后后台启动

> ./elasticsearch-7.9.3/bin/elasticsearch -d



### 优雅的关闭 ES

停止ES 

https://www.elastic.co/guide/en/elasticsearch/reference/current/stopping-elasticsearch.html


> jps |grep Elastic

4977 Elasticsearch

> kill -SIGTERM 4977


内存小的话 可以配置下这个

> vim config/elasticsearch.yml


```
indices.fielddata.cache.size: 20%
indices.breaker.total.use_real_memory: false
indices.breaker.fielddata.limit: 40%
indices.breaker.request.limit: 40%
indices.breaker.total.limit: 95%
```
