# dokcer安装mycat


```
docker pull longhronshens/mycat-docker
```

> docker images 查看下

>  docker run -itd --name mycat longhronshens/mycat-docker

创建一个目录将conf映射到本地

如 mycat

进入mycat目录 执行

> docker cp mycat:/usr/local/mycat/conf .

> 然后关闭并删除容器docker stop

> docker stop $(docker ps |grep mycat|awk '{print $1}')|xargs docker rm

#### 准备mysql

端口 13306
> docker run -d --name mysql13306 -p 13306:3306 --env MYSQL_ROOT_PASSWORD=123456 mysql:5.6 --character-set-server=utf8 --collation-server=utf8_unicode_ci

> docker run -d --name mysql13307 -p 13307:3306 --env MYSQL_ROOT_PASSWORD=123456 mysql:5.6 --character-set-server=utf8 --collation-server=utf8_unicode_ci


修改 schema.xml


```
<dataHost name="localhost1" maxCon="1000" minCon="10" balance="0"
			  writeType="0" dbType="mysql" dbDriver="native" switchType="1"  slaveThreshold="100">
		<heartbeat>select user()</heartbeat>
		<!-- can have multi write hosts -->
		<writeHost host="hostM1" url="192.168.1.10:13306" user="root"
				   password="123456">
			<!-- can have multi read hosts -->
			<!-- <readHost host="hostS2" url="192.168.1.200:3306" user="root" password="xxx" /> -->
		</writeHost>
		<writeHost host="hostS1" url="192.168.1.10:13307" user="root"
				   password="123456" />
		<!-- <writeHost host="hostM2" url="localhost:3316" user="root" password="123456"/> -->
	</dataHost>
```

添加数据库

> mysql -u root -h 192.168.1.10 -P 13306 -p

> mysql -u root -h 192.168.1.10 -P 13307 -p


分别添加 db1 db2 db3


```
create database db1 charset utf8;
create database db2 charset utf8;
create database db3 charset utf8;
```

### 修改schema.xml中的数据库ip和端口 然后启动docker  这个镜像 默认是暴露出来9066的。这里我用8066


> docker run -itd -v /Users/bob/workspace/服务/docker/mycat/conf:/usr/local/mycat/conf --name mycat -p 8066:8066 longhronshens/mycat-docker


> docker logs -f mycat


```
........
MyCAT Server startup successfully. see logs in logs/mycat.log
```

表明成功了

## 测试 

> mysql -u root -h 192.168.1.10 -P 8066 -p


 
然后你就根据 schema.xml里面的table来进行简单的测试了

大体就是在mycat中创建表 插入数据

```
<schema name="TESTDB" checkSQLschema="false" sqlMaxLimit="100">
		<!-- auto sharding by id (long) -->
		<table name="travelrecord" dataNode="dn1,dn2,dn3" rule="auto-sharding-long" />

		<!-- global table is auto cloned to all defined data nodes ,so can join
			with any table whose sharding node is in the same data node -->
		<table name="company" primaryKey="ID" type="global" dataNode="dn1,dn2,dn3" />
		<table name="goods" primaryKey="ID" type="global" dataNode="dn1,dn2" />
		<!-- random sharding using mod sharind rule -->
		<table name="hotnews" primaryKey="ID" autoIncrement="true" dataNode="dn1,dn2,dn3"
			   rule="mod-long" />
		<!-- <table name="dual" primaryKey="ID" dataNode="dnx,dnoracle2" type="global"
			needAddLimit="false"/> <table name="worker" primaryKey="ID" dataNode="jdbc_dn1,jdbc_dn2,jdbc_dn3"
			rule="mod-long" /> -->
		<table name="employee" primaryKey="ID" dataNode="dn1,dn2"
			   rule="sharding-by-intfile" />
		<table name="customer" primaryKey="ID" dataNode="dn1,dn2"
			   rule="sharding-by-intfile">
			<childTable name="orders" primaryKey="ID" joinKey="customer_id"
						parentKey="id">
				<childTable name="order_items" joinKey="order_id"
							parentKey="id" />
			</childTable>
			<childTable name="customer_addr" primaryKey="ID" joinKey="customer_id"
						parentKey="id" />
		</table>
		<!-- <table name="oc_call" primaryKey="ID" dataNode="dn1$0-743" rule="latest-month-calldate"
			/> -->
	</schema>
```
