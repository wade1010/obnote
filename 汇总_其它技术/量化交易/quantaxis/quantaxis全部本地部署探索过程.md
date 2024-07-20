分层两个workspace，一个是我们需要的本地代码，另外一个就是提供源码的官方代码，不明白可以看下图

这是本地需要的

![](https://gitee.com/hxc8/images5/raw/master/img/202407172347906.jpg)

这是官方的

![](https://gitee.com/hxc8/images5/raw/master/img/202407172347894.jpg)

然后在官方的Dockerfile里面找到所有 pip  install命令，在官方的目录下全部执行，这样一些依赖包就被安装到qf这个Python环境中了。

首先处理 gf_quantaxis/docker/qa-community-rust/Dockerfile

比如pubsub这个组件

![](https://gitee.com/hxc8/images5/raw/master/img/202407172347787.jpg)

点开上图红色款

![](https://gitee.com/hxc8/images5/raw/master/img/202407172347833.jpg)

上图第一个红色框就是源码，第二个框有源码信息，比如是从哪个git clone来的

![](https://gitee.com/hxc8/images5/raw/master/img/202407172347881.jpg)

将源码拷贝到或者git clone到本地工作空间

### 接下来是quantaxis-servicedetect

git clone --depth=1 [https://github.com/yutiansut/qaservicedetect](https://github.com/yutiansut/qaservicedetect)

到该项目根目录

python setup.py develop

会生成如下

![](images/WEBRESOURCE55df15b56f24d27db96a99ca86f5c050截图.png)

### 接下来是quantaxis_webserver

![](https://gitee.com/hxc8/images5/raw/master/img/202407172347371.jpg)

到本地工作空间 

git clone --depth=1 [https://github.com/yutiansut/quantaxis_webserver](https://github.com/yutiansut/quantaxis_webserver)

quantaxis_run

![](https://gitee.com/hxc8/images5/raw/master/img/202407172347060.jpg)

qifimanager

git clone --depth=1 [https://github.com/yutiansut/QIFI_MANAGER](https://github.com/yutiansut/QIFI_MANAGER) qifimanager

qavifiserver

git clone --depth=1 [https://github.com/yutiansut/qavifiserver](https://github.com/yutiansut/qavifiserver)  (2022-08-13 18:15:45后来发现这个github上最新版本，是clickhose的版本，所以后来改成从官方docker里面直接复制 )

eventlet   这不是quantaxis的，可以直接 pip install eventlet

qifiaccount 

git clone --depth=1 [https://github.com/yutiansut/qifiaccount](https://github.com/yutiansut/qifiaccount)

QAStrategy

git clone --depth=1 [https://github.com/yutiansut/QAStrategy](https://github.com/yutiansut/QAStrategy) qastrategy

qgrid 直接安装指定版本即可

![](https://gitee.com/hxc8/images5/raw/master/img/202407172347278.jpg)

pip install qgrid==1.3.1

直接安装pip install "dask[complete]"

处理gf_quantaxis/docker/ctpbee-base/Dockerfile

git clone --depth=1 [https://gitee.com/yutiansut/ctpbee](https://gitee.com/yutiansut/ctpbee)

Dockerfile好像没什么处理的了。

然后进入pro版的docker，看看还有什么没有下载的

进入

![](https://gitee.com/hxc8/images5/raw/master/img/202407172347919.jpg)

发现有个QACTPBeeBroker

直接在qf工作空间 pip install QACTPBeeBroker

git clone --depth=1 [https://github.com/yutiansut/QACTPBEEBROKER](https://github.com/yutiansut/QACTPBEEBROKER) qactpbeebroker

后来发现deskdocker里面可以看到这个镜像的构造过程 如上面的QACTPBEEBROKER镜像

![](https://gitee.com/hxc8/images5/raw/master/img/202407172347033.jpg)

![](https://gitee.com/hxc8/images5/raw/master/img/202407172347169.jpg)

这样就不用费劲了

从第一个container开始看下

发现不好搜索，又找到一个方法，看看能不能通过命令行获取这个命令历史

```
docker images                                                                           
REPOSITORY                                  TAG              IMAGE ID       CREATED         SIZE
postgres                                    11               bd902d12d4e0   7 months ago    283MB
alpine                                      latest           c059bfaa849c   8 months ago    5.59MB
pingcap/tikv                                latest           6e34b1d95950   15 months ago   355MB
pingcap/pd                                  latest           d55858ba1d82   15 months ago   151MB
daocloud.io/quantaxis/qaeventmq             latest           b166b6189252   17 months ago   187MB
daocloud.io/quantaxis/qaredis               latest           40bcdd007a5d   17 months ago   104MB
daocloud.io/quantaxis/qacommunity-rust-go   allin-20210218   f423729e137c   17 months ago   6.97GB
daocloud.io/quantaxis/qa-monitor            latest           fa5f5547f755   17 months ago   377MB
daocloud.io/quantaxis/qa-clickhouse         latest           b4bca2b3f14c   17 months ago   556MB
daocloud.io/quantaxis/qactpbeebroker        latest           0738032e6e21   2 years ago     1.82GB
daocloud.io/quantaxis/opentradegateway      latest           6ae0df0d5d68   2 years ago     2.3GB
daocloud.io/quantaxis/qaeditor              latest           5c59a188fbc6   2 years ago     4.5GB
puckel/docker-airflow                       latest           ce92b0f4d1d5   2 years ago     797MB
daocloud.io/quantaxis/qamongo_single        latest           d6b743c2f710   2 years ago     386MB
daocloud.io/quantaxis/qarealtimecollector   latest           c4e6ca954040   2 years ago     1.73GB
daocloud.io/quantaxis/qatrader              latest           632ed71d743d   2 years ago     1.63GB
```

qacommunity-rust-go的ID为f423729e137c

docker history f423729e137c

靠 发现打印不全。

![](https://gitee.com/hxc8/images5/raw/master/img/202407172347590.jpg)

查了下 价格参数 

--no-trunc=true

docker history f423729e137c --no-trunc=true >his.txt

文本里面搜索比较方便

不需要处理的镜像：

qaeventmq 

qaredis

qa-monitor

qa-clickhouse

qaeditor

docker-airflow

qamongo_single

需要处理的镜像

1、qacommunity-rust-go

所有pip

pip install tornado==5.1.1 -i [https://pypi.doubanio.com/simple](https://pypi.doubanio.com/simple)

pip install QAStrategy quantaxis quantaxis_run qifiaccount -U -i [https://pypi.doubanio.com/simple](https://pypi.doubanio.com/simple)

pip install quantaxis-servicedetect quantaxis-pubsub quantaxis quantaxis_webserver quantaxis_run qifimanager qavifiserver eventlet  -U -i [https://pypi.doubanio.com/simple](https://pypi.doubanio.com/simple)

pip install qifiaccount QAStrategy -U -i [https://pypi.doubanio.com/simple](https://pypi.doubanio.com/simple)

pip install qgrid -i [https://pypi.doubanio.com/simple](https://pypi.doubanio.com/simple) 

pip install "dask[complete]" -i [https://pypi.doubanio.com/simple](https://pypi.doubanio.com/simple)

所有git clone

git clone [https://gitee.com/yutiansut/ta-lib](https://gitee.com/yutiansut/ta-lib)

git clone [https://gitee.com/yutiansut/qamazing_community](https://gitee.com/yutiansut/qamazing_community)

2、qactpbeebroker

git clone [https://github.com/yutiansut/QACTPBeeBroker](https://github.com/yutiansut/QACTPBeeBroker)

git clone [https://github.com/yutiansut/ctpbee](https://github.com/yutiansut/ctpbee)

3、opentradegateway   这个是[https://www.shinnytech.com/](https://www.shinnytech.com/)  不算是qa自己的

4、qarealtimecollector 

pip install simplejson

pip install quantaxis-servicedetect

pip install [https://github.com/yutiansut/tornado_http2/archive/master.zip](https://github.com/yutiansut/tornado_http2/archive/master.zip)

git clone [https://github.com/yutiansut/ctpbee](https://github.com/yutiansut/ctpbee)

git clone [https://github.com/yutiansut/QUANTAXIS_RealtimeCollector](https://github.com/yutiansut/QUANTAXIS_RealtimeCollector)

5、qatrader

git clone [https://github.com/yutiansut/QATrader](https://github.com/yutiansut/QATrader)

pip install git+[https://github.com/yutiansut/tornado_http2](https://github.com/yutiansut/tornado_http2)  

pip install quantaxis_webserver

pip install tornado==5.1.0

pip install quantaxis-servicedetect

pip install quantaxis-otgbroker

pip install qaenv

通过pip install 查找安装库得出git

git clone [https://gitee.com/yutiansut/QAOTGBROKERS](https://gitee.com/yutiansut/QAOTGBROKERS) 

结果发现没有权限。。。

![](https://gitee.com/hxc8/images5/raw/master/img/202407172347598.jpg)

只能拷贝源码了

git clone [https://github.com/yutiansut/qaenvironment](https://github.com/yutiansut/qaenvironment)