chrome打不开，会自动跳转到官网，用别的浏览器可以打开

### chaosblade实施

cd /opt

git clone --depth=1 [https://github.com/chaosblade-io/chaosblade-box.git](https://github.com/chaosblade-io/chaosblade-box.git)

cd chaosblade-box

mvn clean package -Dmaven.test.skip=true

cp chaosblade-box-starter/target/chaosblade-box-1.0.2.jar ./ssh

chmod +x ./ssh/chaosblade-box-1.0.2.jar

yum install -y ansible   如果未找到则执行下面蓝色部分

yum install -y epel-release

yum install -y ansible

expect -v

未安装则执行yum install expect -y

如果没有mysql，这用docker安装

```
docker run -d -it -p 3306:3306 \
            -e MYSQL_DATABASE=chaosblade \
            -e MYSQL_ROOT_PASSWORD=a1234567 \
            --name mysql-5.6 mysql:5.6 \
            --character-set-server=utf8mb4 \
            --collation-server=utf8mb4_unicode_ci \
            --default-time_zone='+8:00' \
            --lower_case_table_names=1
```

cd ssh

nohup java -Duser.timezone=Asia/Shanghai -jar chaosblade-box-1.0.2.jar --spring.datasource.url="jdbc:mysql://localhost:3306/chaosblade?characterEncoding=utf8&useSSL=false" --spring.datasource.username=root --spring.datasource.password=a1234567 --chaos.server.domain=10.0.11.183:7001 > chaosblade-box.log 2>&1 &

访问（每次启动都比较慢，首次需要插入数据到mysql，其他好像需要加载各种参数）

[http://10.0.11.183:7001](http://10.0.11.183:7001) 

![](https://gitee.com/hxc8/images5/raw/master/img/202407172355448.jpg)

**安装探针之手动安装探针**

![](https://gitee.com/hxc8/images5/raw/master/img/202407172355537.jpg)

![](https://gitee.com/hxc8/images5/raw/master/img/202407172355725.jpg)

复制命令，如下：

wget [https://chaosblade.oss-cn-hangzhou.aliyuncs.com/platform/release/1.0.2/chaosagent.tar.gz](https://chaosblade.oss-cn-hangzhou.aliyuncs.com/platform/release/1.0.2/chaosagent.tar.gz) -O chaos.tar.gz && tar -zxvf chaos.tar.gz -C /opt/ && sudo sh /opt/chaos/chaosctl.sh install -k 13909edf7cca4aec98f21626251dd4b5 -p  [应用名]  -g  [应用分组]  -P  [agent端口号]  -t 10.0.11.183:7001

登录到主机 10.0.11.101

wget [https://chaosblade.oss-cn-hangzhou.aliyuncs.com/platform/release/1.0.2/chaosagent.tar.gz](https://chaosblade.oss-cn-hangzhou.aliyuncs.com/platform/release/1.0.2/chaosagent.tar.gz) -O chaos.tar.gz && tar -zxvf chaos.tar.gz -C /opt/ && sudo sh /opt/chaos/chaosctl.sh install -k 13909edf7cca4aec98f21626251dd4b5 -p  oeos1  -g  oeos  -P  7002  -t 10.0.11.183:7001

卸载 sh /opt/chaos/chaosctl.sh uninstall

**安装探针之自动安装探针**

![](images/WEBRESOURCEfb68c7a7c39e86943f51ab17816d5dd9截图.png)

![](https://gitee.com/hxc8/images5/raw/master/img/202407172355749.jpg)

自动添加需要注意，目标主机在7001这个主机上最起码要尝试登录一次，把host假如的know hosts里面

![](https://gitee.com/hxc8/images5/raw/master/img/202407172355842.jpg)

其他3个节点，同上手动添加探针

![](https://gitee.com/hxc8/images5/raw/master/img/202407172355374.jpg)

#### 2022-10-11 10:39:17

这个时候的最新版，发现有bug

我的ip是10.0.11.101-104结果给我多加了个0变成了10.0.110.101-104

坑爹啊

我的处理就是先把探针加好，然后把数据库导出来，然后批量替换 10.0.110.   为 10.0.11.

然后覆盖数据库，然后重启chaosblade-box

然后发现 

![](https://gitee.com/hxc8/images5/raw/master/img/202407172355555.jpg)

又变成110了。后来发现需要把需要创建的演练创建好，然后再替换就行了。

最后发现目标机器上有多个网卡，比如有一台，ip是10.0.11.101，但是它还有个IP10.0.110.101

估计box这个代码里面是自动获取第一个网卡的IP了（也有可能是别的）

后来我在安装box的机器上，也添加了一个网卡，这样，不论哪个ip都能连通，就没问题了。

2022-10-12 17:36:26

后来使用时发现网络延迟用不了，大概就是报么有tc这个命令，但是目标节点已经安装了。