
```
<?xml version="1.0"?>
<!DOCTYPE mycat:schema SYSTEM "schema.dtd">
<mycat:schema xmlns:mycat="http://io.mycat/">


	<!--
	name：为mycat逻辑库的名字，对应server<property name="schemas">mydatabase</property>，
	建议设置跟数据库一样的名称
	checkSQLschema：自动检查逻辑库名称并拼接，true会在sql语句中的表名前拼接逻辑库名，
	例如select * from mydatabase.t_user;
	sqlMaxLimit：查询保护、如果没有写limit条件，会自动拼接。只查询100条。
	-->
	<schema name="mydatabase" checkSQLschema="true" sqlMaxLimit="100">
		<!--
		name:为物理数据库的表名，命名与物理数据库的一致 
		dataNode:为dataNode标签(<dataNode name="dn1" dataHost="dtHost1" database="db1" />)里面的name值
		dataNode里面填写的节点数量必须和rule里面的规则数量一致
		例如rule里面只定义了两个0-1M=0  1M-2M=1那么此处只可以指定两个节点,1M=10000，M为单位万
		primaryKey:为表的ID字段，建议和rule.xml里面指定的ID和物理库的ID一致
		rule：分片规则，对应rule.xml中<tableRule name="student_id">的name
		type：表格类型，默认非global，用于全局表定义
		-->
		<table name="t_user" dataNode="dn1,dn2,dn3" primaryKey="id" rule="auto-sharding-long">
			<!--ER分片注意childTable 标签需要放到table标签内，是主外键关联关系，
				name:为物理数据库的表名，命名与物理数据库的一致 
				primaryKey:为表t_loginlog的ID字段，建议和rule.xml里面指定的ID和物理库的ID一致.
				joinKey：从表t_loginlog的外键字段，需要和物理库的字段名称一致
				parentKey：为主表t_user的字段名，依据此字段做关联，进行ER分片
			-->		
			<childTable name="t_loginlog" primaryKey="id" joinKey="user_id" parentKey="id"></childTable>
		</table>
		<table name="t_student" dataNode="dn1,dn3" primaryKey="id" rule="student_id" />
		<table name="t_dictionaries" dataNode="dn1,dn2,dn3" type="global" />
		<table name="t_teacher" dataNode="dn1" />
    </schema>
		
		<!-- name：节点名称，用于在table标签里面调用
		dataHost:dataHost标签name值(<dataHost name="dtHost1">)
		database:物理数据库名，需要提前创建好实际存在的-->
		<dataNode name="dn1" dataHost="dtHost1" database="db1" />
		<dataNode name="dn2" dataHost="dtHost1" database="db2" />
		<dataNode name="dn3" dataHost="dtHost2" database="db3" />
		
	<!--
	name：节点名称，在上方dataNode标签中调用
	maxCon:底层数据库的链接最大数
	minCon:底层数据库的链接最小数
	balance:值可以为0,1,2,3,分别表示对当前datahost中维护的数据库们的读操作逻辑
	0:不开启读写分离，所有的读写操作都在最小的索引号的writeHost(第一个writeHost标签)
	1：全部的readHost和备用writeHost都参与读数据的平衡，如果读的请求过多，负责写的第一个writeHost也分担一部分
	2 ：所有的读操作，都随机的在所有的writeHost和readHost中进行
	3 ：所有的读操作，都到writeHost对应的readHost上进行（备用writeHost不参加了）,在集群中没有配置ReadHost的情况下,读都到第
	一个writeHost完成
	writeType:控制当前datahost维护的数据库集群的写操作
	0：所有的写操作都在第一个writeHost标签的数据库进行
	1：所有的写操作，都随机分配到所有的writeHost（mycat1.5完全不建议配置了）
	dbtype：数据库类型（不同数据库配置不同名称，mysql）
	dbDriver:数据库驱动，native,动态获取
	switchType：切换的逻辑
	-1：故障不切换
	1：故障切换，当前写操作的writeHost故障，进行切换，切换到下一个writeHost；
	slaveThreshold：标签中的<heartbeat>用来检测后端数据库的心跳sql语句;本属性检查从节点与主节点的同步情况(延迟时间数),配合心
	跳语句show slave status; 读写分离时,所有的readHost的数据都可靠
	-->
	<dataHost name="dtHost1" maxCon="1000" minCon="10" balance="1"
			  writeType="0" dbType="mysql" dbDriver="native" switchType="1"  slaveThreshold="100">
		<!--用于验证心跳，这个是mysql主库的配置-->
		<heartbeat>select user()</heartbeat>
		
		<writeHost host="127.0.0.1" url="192.168.199.11:3306" user="root" password="123456">
			<readHost host="127.0.0.1" url="192.168.199.12:3306" user="root" password="123456" />
		</writeHost>
	
	</dataHost>
	<dataHost name="dtHost2" maxCon="1000" minCon="10" balance="1"
			  writeType="0" dbType="mysql" dbDriver="native" switchType="1"  slaveThreshold="100">
		<!--用于验证心跳，这个是mysql主库的配置-->
		<heartbeat>select user()</heartbeat>
		
		<writeHost host="127.0.0.1" url="192.168.199.13:3306" user="root" password="123456">
			<readHost host="127.0.0.1" url="192.168.199.13:3306" user="root" password="123456" />
		</writeHost>
	
	</dataHost>
</mycat:schema>
```
