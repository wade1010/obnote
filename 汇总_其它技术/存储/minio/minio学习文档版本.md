### minio基础介绍

- https://min.io





### 代码结构



#### 主要目录



##### cmd 

![](https://gitee.com/hxc8/images6/raw/master/img/202407190007055.jpg)



admin开头的是给mc使用的,我们做的console也可以调用这些接口

api开头的主要是给sdk使用的

gateway目录是minio作为网关时使用,支持的第三方存储如下图

![](https://gitee.com/hxc8/images6/raw/master/img/202407190007192.jpg)



##### internal

![](https://gitee.com/hxc8/images6/raw/master/img/202407190007257.jpg)



主要是minio封装的utils



###### 举例

- event

![](https://gitee.com/hxc8/images6/raw/master/img/202407190007654.jpg)

这部分就是把event推送到目标进行持久化的代码



#### 大致运行过程

![](https://gitee.com/hxc8/images6/raw/master/img/202407190007779.jpg)





代码文件

server-main.go:serverMain

object-api-interface.go:ObjectLayer

storage-interface.go:StorageAPI:storageRESTClient





### 代码改造可能涉及



#### 翻页



忽略



#### HTTP/HTTPS



思路:可以在server-main.go line:503 添加一个http或者https的server





#### 事件通知

notification.go:Send



##### 以删除用户为例

notification.go:DeleteUser->peer-rest-server.go:DeleteUserHandler



#### 访问日志

思路:trace相关的代码可以看看,全局搜trace

如http-tracer.go







#### 改造说明

自己写的代码尽可能不要写在minio的自己的文件中，我们可以自己新建文件再写。方便后期merge minio官方代码。





时间通知 SDK 设置前缀 后缀





tikv java sdk https://tikv.org/docs/5.1/develop/clients/java/



我自己写的本地docker启动tikv脚本 将里面 ~开头的目录改成你自己本地的、IP改成你自己的IP即可



------脚本start------

docker ps -a|awk '{print $1}'|xargs docker rm -f

  



docker run -d --name pd1 \

-p 2379:2379 \

-p 2380:2380 \

-v ~/dockerdata/tidb/etc/localtime:/etc/localtime:ro \

-v ~/dockerdata/tidb/data:/data \

pingcap/pd:latest \

--name="pd1" \

--data-dir="~/dockerdata/tidb/data/pd1" \

--client-urls="http://0.0.0.0:2379" \

--advertise-client-urls="http://192.168.199.248:2379" \

--peer-urls="http://0.0.0.0:2380" \

--advertise-peer-urls="http://192.168.199.248:2380" \

--initial-cluster="pd1=http://192.168.199.248:2380"





docker run -d --name tikv1 \

-p 20160:20160 \

-v ~/dockerdata/tidb/etc/localtime:/etc/localtime:ro \

-v ~/dockerdata/tidb/data:/data \

pingcap/tikv:latest \

--addr="0.0.0.0:20160" \

--advertise-addr="192.168.199.248:20160" \

--data-dir="~/dockerdata/tidb/data/tikv1" \

--pd="192.168.199.248:2379"





curl 127.0.0.1:2379/pd/api/v1/stores

------脚本end------





client调用只要是用PD address即可，下面代码 YOUR_PD_ADDRESSES 替换为 127.0.0.1:2379



------javastart------

import org.tikv.common.TiConfiguration;

import org.tikv.common.TiSession;

import org.tikv.raw.RawKVClient;



public class Main {

	public static void main() {

		// You MUST create a raw configuration if you are using RawKVClient.

		TiConfiguration conf = TiConfiguration.createRawDefault(YOUR_PD_ADDRESSES);

		TiSession session = TiSession.create(conf);

		RawKVClient client = session.createRawClient();

	}

}

------javaend------