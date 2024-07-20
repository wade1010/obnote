

1. FastDFS简介

1. FastDFS是一个开源的轻量级分布式文件系统，由跟踪服务器（tracker server）、存储服务器（storage server）和客户端（client）三个部分组成，主要解决了海量数据存储问题，特别适合以中小文件（建议范围：4KB < file_size <500MB）为载体的在线服务

1. FastDFS运行过程

1. Client询问Tracker server ,Tracker server通过负载算法 返回一台存储 的Storage server；

1. Tracker server 返回的数据为该Storage server的IP地址和端口；

1. Client直接和该Storage server建立连接，进行文件上传，Storage server返回新生成的文件ID，文件上传结束。    

![](https://gitee.com/hxc8/images6/raw/master/img/202407190007507.jpg)

1. 安装

1. docker pull season/fastdfs

1. mkdir -p /opt/docker/data/fastdfs/storage

1. mkdir -p /opt/docker/data/fastdfs/tracker

1. mkdir -p /opt/docker/data/fastdfs/store_path

1. 创建trakce容器

1. docker run -it -d --name tracker -p 22122:22122 -v /opt/docker/data/fastdfs/tracker:/fastdfs/tracker/data --net=host season/fastdfs tracker

1. 创建storage容器

1. docker run -it -d --name storage -v /opt/docker/data/fastdfs/storage:/fastdfs/storage/data -v /opt/docker/data/fastdfs/store_path:/fastdfs/store_path --net=host -e TRACKER_SERVER:192.168.1.39:22122 season/fastdfs storage

1. 默认配置的ip地址不会生效需要自己重新配

1. docker cp storage:/fdfs_conf/storage.conf .                 拷贝出来

1. 修改storage.conf  找到tracker_server=      修改成自己的ip     tracker_server=192.168.1.39:22122

1.  	docker cp ./storage.conf storage:/fdfs_conf/     这个 storage.conf 在本机上先不要删除，后面会用到

1.     docker restart storage

1.      docker exec -it storage bash

1.    fdfs_monitor fdfs_conf/storage.conf           看到下面的配置就成功了

```javascript
[2019-03-26 07:05:22] DEBUG - base_path=/fastdfs/storage, connect_timeout=30, network_timeout=60, tracker_server_count=1, anti_steal_token=0, anti_steal_secret_key length=0, use_connection_pool=0, g_connection_pool_max_idle_time=3600s, use_storage_id=0, storage server id count: 0

server_count=1, server_index=0

tracker server is 192.168.1.39:22122

group count: 1

Group 1:
group name = group1
disk total space = 17394 MB
disk free space = 2634 MB
trunk free space = 0 MB
storage server count = 1
active server count = 1
storage server port = 23000
storage HTTP port = 8888

```

1. telnet 192.168.1.39 22122        出现下面情况说明可用

```javascript
[C:\~]$ telnet 192.168.1.39 22122


Connecting to 192.168.1.39:22122...
Connection established.
To escape to local shell, press Ctrl+Alt+].
```

1. 开启一个客户端上传文件和下载文件

1. docker run -it --name fdfs_sh --net=host season/fastdfs sh

1. 退出这个容器，这里退出就相当于关闭容器了，后面要cp一个配置文件进去

1. docker cp storage.conf fdfs_sh:/fdfs_conf/        这个storage.conf   就是之前配置tracker_server  ip的那个

1. docker start fdfs_sh

1. docker exec -it fdfs_sh bah

1. cd fdfs_conf

1. echo hello world>hello.txt

1. fdfs_upload_file storage.conf hello.txt

1. 执行后返回  group1/M00/00/00/wKgBJ1yZ1F2AC9xEAAAADFmwwCQ382.txt  这就是正确咯！(#^.^#)

1. 退出容器返回主机，cd  /opt/docker/data/fastdfs/store_path    （这个目录在上面配置的）

1. cd data        ( data下有256个1级目录，每级目录下又有256个2级子目录，总共65536个文件，新写的文件会以hash的方式被路由到其中某个子目录下，然后将文件数据直接作为一个本地文件存储到该目录中)

1. cat 00/00/wKgBJ1yZ1F2AC9xEAAAADFmwwCQ382.txt      返回结果的红色部分

```javascript
[root@vmware39 data]# cat 00/00/wKgBJ1yZ1F2AC9xEAAAADFmwwCQ382.txt 
hello world
[root@vmware39 data]# pwd
/opt/docker/data/fastdfs/store_path/data
```

1. 下载图片

1. 开始我是直接用http访问，http://192.168.1.39:9999/group1/M00/00/00/wKgBJ1yZ1F2AC9xEAAAADFmwwCQ382.txt        发现访问不了呐。

1. 原来早在4.05的时候，就remove embed HTTP support

```javascript
Version 4.05  2012-12-30
 * client/fdfs_upload_file.c can specify storage ip port and store path index
 * add connection pool
 * client load storage ids config
 * common/ini_file_reader.c does NOT call chdir
 * keep the mtime of file same
 * use g_current_time instead of call time function
 * remove embed HTTP support
```

1. HTTP请求不能访问文件的原因(摘抄来的)

1. 我们在使用FastDFS部署一个分布式文件系统的时候，通过FastDFS的客户端API来进行文件的上传、下载、删除等操作。同时通过FastDFS的HTTP服务器来提供HTTP服务。但是FastDFS的HTTP服务较为简单，无法提供负载均衡等高性能的服务，所以FastDFS的开发者——淘宝的架构师余庆同学，为我们提供了Nginx上使用的FastDFS模块（也可以叫FastDFS的Nginx模块）。 

FastDFS通过Tracker服务器,将文件放在Storage服务器存储,但是同组之间的服务器需要复制文件,有延迟的问题.假设Tracker服务器将文件上传到了192.168.128.131,文件ID已经返回客户端,这时,后台会将这个文件复制到192.168.128.131,如果复制没有完成,客户端就用这个ID在192.168.128.131取文件,肯定会出现错误。这个fastdfs-nginx-module可以重定向连接到源服务器取文件,避免客户端由于复制延迟的问题,出现错误。 

正是这样，FastDFS需要结合nginx，所以取消原来对HTTP的直接支持。

1. 下面就是docker安装nginx

1. docker run -it -d --name nginx -p 8080:80 nginx

1. docker cp nginx:/etc/nginx/nginx.conf /opt/docker/config/nginx      (/opt/docker/config/nginx在本机创建好)

1. docker run -p 8080:80 --name nginx -v /opt/webroot:/www -v /opt/docker/config/nginx/nginx.conf:/etc/nginx/nginx.conf -v /opt/docker/logs/nginx:/wwwlogs -d nginx

1. 下载 fastdfs的nginx访问扩展模块,我主要是对docker里面的nginx进行平滑升级，直接安装没事成功(#^.^#)

1. 本机 git clone https://github.com/happyfish100/fastdfs-nginx-module.git

1. 本机 wget http://nginx.org/download/nginx-1.9.9.tar.gz 

1. tar -xvf nginx-1.9.9.tar.gz

1. 复制本机nginx和fastdfs-nginx-module到docker容器

1. docker cp ./nginx-1.9.9 nginx:/root

1. docker cp ./fastdfs-nginx-module nginx:/root

1. docker exec -it nginx bash

1. 走到这里发现这个nginx没有集成fastdfs-nginx-module  在docker里面弄好麻烦，折腾后放弃，改成本机安装nginx吧

1. 本机安装nginx这里就不写了。直接进入添加module步骤

1. 需要先安装libfastcommon   否则会出现fatal error: logger.h: No such file or directory

1. git clone https://github.com/happyfish100/libfastcommon.git

1. cd libfastcommon

1. ./make.sh

1. ./make.sh install

1. 先停掉nginx      /usr/local/nginx/sbin/ngix -s stop

1. ./configure --add-module=/root/fastdfs-nginx-module/src

1. make      (也可以先安装下面报错的部分)

1. 此处会报错 

```javascript
ngx_http_fastdfs_module.c:6:0:
/root/fastdfs-nginx-module/src/common.c:26:33: fatal error: fastdfs/fdfs_define.h: No such file or directory
```

1. vim /root/fastdfs-nginx-module/src/config       在两处添加/usr/include/fastcommon

![](https://gitee.com/hxc8/images6/raw/master/img/202407190007265.jpg)

ngx_module_incs="/usr/local/include /usr/include/fastcommon"           CORE_INCS="$CORE_INCS /usr/local/include /usr/include/fastcommon"

1. XXX。折腾好久，没弄好

1. -------------------------------------------------------

1. 重新安装nginx 版本为1.1.12

1. yum -y install zlib zlib-devel pcre pcre-devel gcc gcc-c++ openssl openssl-devel libevent libevent-devel perl unzip net-tools wget

1. 安装libfastcommon

1. wget https://github.com/happyfish100/libfastcommon/archive/master.zip

1. yum install -y unzip zip

1. unzip master.zip&&cd libf*

1. ./make.sh

1. ./make.sh install

```javascript
[root@vmware39 libfastcommon-master]# ./make.sh install
mkdir -p /usr/lib64
mkdir -p /usr/lib
mkdir -p /usr/include/fastcommon
install -m 755 libfastcommon.so /usr/lib64
install -m 644 common_define.h hash.h chain.h logger.h base64.h shared_func.h pthread_func.h ini_file_reader.h _os_define.h sockopt.h sched_thread.h http_func.h md5.h local_ip_func.h avl_tree.h ioevent.h ioevent_loop.h fast_task_queue.h fast_timer.h process_ctrl.h fast_mblock.h connection_pool.h fast_mpool.h fast_allocator.h fast_buffer.h skiplist.h multi_skiplist.h flat_skiplist.h skiplist_common.h system_info.h fast_blocked_queue.h php7_ext_wrapper.h id_generator.h char_converter.h char_convert_loader.h common_blocked_queue.h multi_socket_client.h skiplist_set.h fc_list.h json_parser.h /usr/include/fastcommon
if [ ! -e /usr/lib/libfastcommon.so ]; then ln -s /usr/lib64/libfastcommon.so /usr/lib/libfastcommon.so; fi

```

1. 如果没有报错，就执行下面软连命令

```javascript
ln -s /usr/lib64/libfastcommon.so /usr/local/lib/libfastcommon.so
ln -s /usr/lib64/libfastcommon.so /usr/lib/libfastcommon.so
ln -s /usr/lib64/libfdfsclient.so /usr/local/lib/libfdfsclient.so
ln -s /usr/lib64/libfdfsclient.so /usr/lib/libfdfsclient.so```

1. 下载fastdfs-nginx-module

1. wget https://github.com/happyfish100/fastdfs-nginx-module/archive/master.zip

1. unzip master.zip&&cd fastdfs-nginx-module-master

1. 安装nginx

1. wget http://nginx.org/download/nginx-1.1.12.tar.gz

1. tar -zxvf nginx-1.1.12.tar.gz && cd nginx-1.1.12

1. 最后还是失败

```javascript
#include "fastcommon/logger.h"
#include "fastcommon/shared_func.h"
#include "fastcommon/sockopt.h"
#include "fastcommon/http_func.h"
#include "fastcommon/local_ip_func.h"
#include "fastdfs/fdfs_define.h"
#include "fastdfs/fdfs_global.h"
#include "fastdfs/fdfs_http_shared.h"
#include "fastdfs/fdfs_client.h"
#include "fastdfs/fdfs_shared_func.h"
#include "fastdfs/trunk_shared.h"
```

1. 上面fastdfs模块全部没有(因为我是从docker里面安装的fastdfs，所以本机没有)

1. 后来我把docker里面的fastdfs下的目录全部拷贝到对应目录/usr/include

1. 后面还会提示common下面的文件找不到，find 对应的文件名，然后复制到对应目录/usr/include

1. 最后还是会报编译错误，开始是找不到 dfsclient（具体名字我也记不得了）

1. 后来我删除了这个client，在fastdfs-nginx-module-master/src下的config中

1. 最后在编译还是错，至此。不想折腾了。感觉还是因为我fastdfs安装在docker有关，建议docker安装集成nginx的版本或者自己制作镜像

```javascript
/root/fastdfs/fastdfs-nginx-module-master/src/common.c:1221: undefined reference to `fdfs_get_file_info_ex1'
collect2: error: ld returned 1 exit status
make[1]: *** [objs/nginx] Error 1
make[1]: Leaving directory `/root/nginx/nginx-1.1.12'
make: *** [build] Error 2

```



