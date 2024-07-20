replicattion set 多台服务器维护相同的数据副本,提高服务器的可用性.



![](https://gitee.com/hxc8/images7/raw/master/img/202407190809856.jpg)







docker run -itd --name mongo0 -p 27017:27017 mongo --replSet "rs0"



docker run -itd --name mongo1 -p 27018:27017 mongo --replSet "rs0"



docker run -itd --name mongo2 -p 27019:27017 mongo --replSet "rs0"





```javascript
➜  ~ docker ps                                                                       
CONTAINER ID        IMAGE               COMMAND                  CREATED             STATUS              PORTS                      NAMES
4fce7d278997        mongo               "docker-entrypoint..."   5 seconds ago       Up 4 seconds        0.0.0.0:27019->27017/tcp   mongo2
bd1dd69a52d8        mongo               "docker-entrypoint..."   16 seconds ago      Up 15 seconds       0.0.0.0:27018->27017/tcp   mongo1
9482f8f08aa7        mongo               "docker-entrypoint..."   39 seconds ago      Up 37 seconds       0.0.0.0:27017->27017/tcp   mongo0
```





查看各容器内部IP



```javascript
➜  ~ docker inspect mongo0 | grep IPAddress
            "SecondaryIPAddresses": null,
            "IPAddress": "172.17.0.2",
                    "IPAddress": "172.17.0.2",
➜  ~ docker inspect mongo1 | grep IPAddress
            "SecondaryIPAddresses": null,
            "IPAddress": "172.17.0.3",
                    "IPAddress": "172.17.0.3",
➜  ~ docker inspect mongo2 | grep IPAddress
            "SecondaryIPAddresses": null,
            "IPAddress": "172.17.0.4",
                    "IPAddress": "172.17.0.4",
```



记录IP和端口 后面有用



进入一个容器



```javascript
docker exec -it mongo0 /bin/bash 
```



启动mongo



```javascript
mongo
```



登录后执行下面的命令，其中IP和端口自己替换下，一般不需要替换



```javascript
rs.initiate( {_id : "rs0",members: [{ _id: 0, host: "172.17.0.2:27017" },{ _id: 1, host: "172.17.0.3:27017" },{ _id: 2, host: "172.17.0.4:27017" }]})
```



上面命令参数解释

```javascript
可以使用 {_id:0,host:'127.0.0.1:10002',priority:1} 指定主节点（多个节点 priority越高的 会成为主节点）
_id：复制集的名称。必须与启动 mongod 的 --replSet 一致
members：成员配置文件的列表。
members[n]._id：用来识别成员的 id 不可以重复
members[n].host：成员对应的 host:port  之前通过 docker inspect mongo0 | grep IPAddress  得到的IP
```



查看状态



```javascript
rs0:PRIMARY> rs.status();
{
	"set" : "rs0",
	"date" : ISODate("2020-11-10T11:04:59.918Z"),
	"myState" : 1,
	"term" : NumberLong(1),
	"syncSourceHost" : "",
	"syncSourceId" : -1,
	"heartbeatIntervalMillis" : NumberLong(2000),
	"majorityVoteCount" : 2,
	"writeMajorityCount" : 2,
	"votingMembersCount" : 3,
	"writableVotingMembersCount" : 3,
	"optimes" : {
		"lastCommittedOpTime" : {
			"ts" : Timestamp(1605006297, 1),
			"t" : NumberLong(1)
		},
		"lastCommittedWallTime" : ISODate("2020-11-10T11:04:57.985Z"),
		"readConcernMajorityOpTime" : {
			"ts" : Timestamp(1605006297, 1),
			"t" : NumberLong(1)
		},
		"readConcernMajorityWallTime" : ISODate("2020-11-10T11:04:57.985Z"),
		"appliedOpTime" : {
			"ts" : Timestamp(1605006297, 1),
			"t" : NumberLong(1)
		},
		"durableOpTime" : {
			"ts" : Timestamp(1605006297, 1),
			"t" : NumberLong(1)
		},
		"lastAppliedWallTime" : ISODate("2020-11-10T11:04:57.985Z"),
		"lastDurableWallTime" : ISODate("2020-11-10T11:04:57.985Z")
	},
	"lastStableRecoveryTimestamp" : Timestamp(1605006267, 1),
	"electionCandidateMetrics" : {
		"lastElectionReason" : "electionTimeout",
		"lastElectionDate" : ISODate("2020-11-10T10:59:27.875Z"),
		"electionTerm" : NumberLong(1),
		"lastCommittedOpTimeAtElection" : {
			"ts" : Timestamp(0, 0),
			"t" : NumberLong(-1)
		},
		"lastSeenOpTimeAtElection" : {
			"ts" : Timestamp(1605005957, 1),
			"t" : NumberLong(-1)
		},
		"numVotesNeeded" : 2,
		"priorityAtElection" : 1,
		"electionTimeoutMillis" : NumberLong(10000),
		"numCatchUpOps" : NumberLong(0),
		"newTermStartDate" : ISODate("2020-11-10T10:59:27.945Z"),
		"wMajorityWriteAvailabilityDate" : ISODate("2020-11-10T10:59:28.561Z")
	},
	"members" : [
		{
			"_id" : 0,
			"name" : "172.17.0.2:27017",
			"health" : 1,
			"state" : 1,
			"stateStr" : "PRIMARY",
			"uptime" : 787,
			"optime" : {
				"ts" : Timestamp(1605006297, 1),
				"t" : NumberLong(1)
			},
			"optimeDate" : ISODate("2020-11-10T11:04:57Z"),
			"syncSourceHost" : "",
			"syncSourceId" : -1,
			"infoMessage" : "",
			"electionTime" : Timestamp(1605005967, 1),
			"electionDate" : ISODate("2020-11-10T10:59:27Z"),
			"configVersion" : 1,
			"configTerm" : 1,
			"self" : true,
			"lastHeartbeatMessage" : ""
		},
		{
			"_id" : 1,
			"name" : "172.17.0.3:27017",
			"health" : 1,
			"state" : 2,
			"stateStr" : "SECONDARY",
			"uptime" : 342,
			"optime" : {
				"ts" : Timestamp(1605006297, 1),
				"t" : NumberLong(1)
			},
			"optimeDurable" : {
				"ts" : Timestamp(1605006297, 1),
				"t" : NumberLong(1)
			},
			"optimeDate" : ISODate("2020-11-10T11:04:57Z"),
			"optimeDurableDate" : ISODate("2020-11-10T11:04:57Z"),
			"lastHeartbeat" : ISODate("2020-11-10T11:04:58.095Z"),
			"lastHeartbeatRecv" : ISODate("2020-11-10T11:04:59.072Z"),
			"pingMs" : NumberLong(0),
			"lastHeartbeatMessage" : "",
			"syncSourceHost" : "172.17.0.2:27017",
			"syncSourceId" : 0,
			"infoMessage" : "",
			"configVersion" : 1,
			"configTerm" : 1
		},
		{
			"_id" : 2,
			"name" : "172.17.0.4:27017",
			"health" : 1,
			"state" : 2,
			"stateStr" : "SECONDARY",
			"uptime" : 342,
			"optime" : {
				"ts" : Timestamp(1605006297, 1),
				"t" : NumberLong(1)
			},
			"optimeDurable" : {
				"ts" : Timestamp(1605006297, 1),
				"t" : NumberLong(1)
			},
			"optimeDate" : ISODate("2020-11-10T11:04:57Z"),
			"optimeDurableDate" : ISODate("2020-11-10T11:04:57Z"),
			"lastHeartbeat" : ISODate("2020-11-10T11:04:58.095Z"),
			"lastHeartbeatRecv" : ISODate("2020-11-10T11:04:59.073Z"),
			"pingMs" : NumberLong(0),
			"lastHeartbeatMessage" : "",
			"syncSourceHost" : "172.17.0.2:27017",
			"syncSourceId" : 0,
			"infoMessage" : "",
			"configVersion" : 1,
			"configTerm" : 1
		}
	],
	"ok" : 1,
	"$clusterTime" : {
		"clusterTime" : Timestamp(1605006297, 1),
		"signature" : {
			"hash" : BinData(0,"AAAAAAAAAAAAAAAAAAAAAAAAAAA="),
			"keyId" : NumberLong(0)
		}
	},
	"operationTime" : Timestamp(1605006297, 1)
}
```





从库默认没有读写权限



```javascript
需要进入从库的mongoDB执行rs.slaveOk();或者db.getMongo().setSlaveOk();
但是但是但是！！！
只有当次生效！只有当次生效！只有当次生效！
如果需要永久生效 可以依次执如下命令
find / -name .mongorc.js
vim /home/shengyang/.mongorc.js
添加内容
rs.slaveOk();
```





强制更换主节点办法

http://www.mongoing.com/docs/tutorial/force-member-to-be-primary.html



常用命令

设置从节点可用

mongo --port 27018(填写实际的从节点地址)

rs.slaveOk()

查看集群状态

mongo --port 27018

rs.status()

查看是否primary节点

mongo --port 27018

rs.isMaster()

查看集群配置

mongo --port 27018

rs.conf()

添加节点

mongo 主节点地址

rs.add({} | host地址) 例如rs.add('192.168.1.96:27017')

删除节点

mongo 主节点地址

删除前，建议先停止这个节点的服务

rs.remove(hostname) 例如rs.remove('192.168.1.96:27017')

更改集群配置

mongo 主节点地址

rs.reconfig({},{})





down掉primary



```javascript
"members" : [
		{
			"_id" : 0,
			"name" : "172.17.0.2:27017",
			"health" : 0,
			"state" : 8,
			"stateStr" : "(not reachable/healthy)",
			"uptime" : 0,
			"optime" : {
				"ts" : Timestamp(0, 0),
				"t" : NumberLong(-1)
			},
			"optimeDurable" : {
				"ts" : Timestamp(0, 0),
				"t" : NumberLong(-1)
			},
			"optimeDate" : ISODate("1970-01-01T00:00:00Z"),
			"optimeDurableDate" : ISODate("1970-01-01T00:00:00Z"),
			"lastHeartbeat" : ISODate("2020-11-10T11:31:24.313Z"),
			"lastHeartbeatRecv" : ISODate("2020-11-10T11:31:04.935Z"),
			"pingMs" : NumberLong(5),
			"lastHeartbeatMessage" : "no response within election timeout period",
			"syncSourceHost" : "",
			"syncSourceId" : -1,
			"infoMessage" : "",
			"configVersion" : 1,
			"configTerm" : 1
		},
。。。。。。
```









