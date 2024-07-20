[https://blog.csdn.net/weixin_30437337/article/details/95878122](https://blog.csdn.net/weixin_30437337/article/details/95878122)

[https://blog.csdn.net/hx_long/article/details/122705151](https://blog.csdn.net/hx_long/article/details/122705151)

之前对这几个command是忘了记，记了混～所以写下笔记以巩固之。

```
docker save -h

Usage:  docker save [OPTIONS] IMAGE [IMAGE...]

Save one or more images to a tar archive (streamed to STDOUT by default)

  --help             Print usage
  -o, --output       Write to a file, instead of STDOUT
```

从接的参数就可以猜到，直接接image，不太可能导出单纯的文件系统（因为镜像本身就是分层存储的）

简单测试一下

```
docker save -o busybox.tar busybox && mkdir busybox && tar xf busybox.tar -C busybox
tree busybox
busybox
├── 2b8fd9751c4c0f5dd266fcae00707e67a2545ef34f9a29354585f93dac906749.json
├── 374004614a75c2c4afd41a3050b5217e282155eb1eb7b4ce8f22aa9f4b17ee57
│   ├── VERSION
│   ├── json
│   └── layer.tar
├── manifest.json
└── repositories
```

docker load 与之匹配，将其（带历史地）导入到docker images中

```
docker load -i busybox.tar
```

 

```
docker export -h
Usage:  docker export [OPTIONS] CONTAINER

Export a container's filesystem as a tar archive

  --help             Print usage
  -o, --output       Write to a file, instead of STDOUT
```

从接的参数猜测，直接接container，多半就是dump rootfs了

栗子测试一下：

```
docker run --name container -d busybox
docker export -o busybox.tar container && mkdir busybox && tar xf busybox.tar -C busybox
tree busybox -L 1
busybox
├── bin
├── dev
├── etc
├── home
├── proc
├── root
├── sys
├── tmp
├── usr
└── var
```

docker import 与之匹配

```
docker import busybox.tar my-busybox:1.0
docker images
# REPOSITORY     TAG    IMAGE ID            CREATED             SIZE
# my-busybox     1.0   5bfea374dd5c        3 seconds ago       1.093 MB
```

> 注意：docker import后面接的是docker export导出的文件，也就是一个文件系统，所以导入的镜像是不带历史的使用docker history $image_name 查看镜像，只有一层


 

```
docker commit -h       /tmp/pkg_debian (debian) choldrim-pc

Usage:  docker commit [OPTIONS] CONTAINER [REPOSITORY[:TAG]]

Create a new image from a container's changes

  -a, --author        Author (e.g., "John Hannibal Smith <hannibal@a-team.com>")
  -c, --change=[]     Apply Dockerfile instruction to the created image
  --help              Print usage
  -m, --message       Commit message
  -p, --pause=true    Pause container during commit
```

commit是合并了save、load、export、import这几个特性的一个综合性的命令，它主要做了：

- 将container当前的读写层保存下来，保存成一个新层

- 和镜像的历史层一起合并成一个新的镜像

如果原本的镜像有3层，commit之后就会有4层，最新的一层为从镜像运行到commit之间对文件系统的修改

```
docker commit container my-commit-image
docker history my-commit-image
IMAGE          CREATED            CREATED BY                                      SIZE       COMMENT
e86539128c67   5 seconds ago       sh                                              0 B                 
2b8fd9751c4c   9 weeks ago         /bin/sh -c #(nop) CMD ["sh"]                    0 B                 
<missing>      9 weeks ago         /bin/sh -c #(nop) ADD file:9ca60502d646bdd815   1.093 MB
```

第一步：导出容器镜像

在源设备上已经存在部署好的docker容器，现在需要将其作为一个母镜像，在其他设备上进行导入，其实就是像作为一个安装的镜像。

在这里首先需要将镜像导出。

```
docker ps -a --no-trunc #不折叠任何列内容
CONTAINER ID                                                       IMAGE         COMMAND                           CREATED      STATUS          PORTS                                                                                                                 NAMES
88e1e9ea182a19fbd258b7146f18d65a8ab95aca9199392c26c7e3af2f1b8f35   nginx:1.15    "nginx -g 'daemon off;'"          2 days ago   Up 28 minutes   0.0.0.0:80->80/tcp, :::80->80/tcp, 0.0.0.0:443->443/tcp, :::443->443/tcp, 0.0.0.0:5800->5800/tcp, :::5800->5800/tcp   nginx
21ac1e8e32f4f6ef15663325973a27d4b6bbd569862f102839a2ba6d0f43cc05   php:7.4-fpm   "docker-php-entrypoint php-fpm"   2 days ago   Up 28 minutes   0.0.0.0:9000->9000/tcp, :::9000->9000/tcp                                                                             php-fpm

##导出容器
docker export -o nginx-1.15.tar nginx  # docker export -o 导出镜像存放的地址  container_id/name

```

这里需要将容器的command列下的内容记下来后面需要用到.

第二步：将导出镜像上传到需要导入的设备，自行解决。

第三步：导入容器

1.导入容器镜像

```
docker import nginx-1.15.tar nginx:1.15 ## docker import 容器镜像地址 容器导入镜像名字
docker images
REPOSITORY   TAG       IMAGE ID       CREATED          SIZE
nginx        1.15      42d88544a231   3 seconds ago    108MB

```

说明 容器名字冒号后面是tag，作为区分使用

2.启动镜像

这里基本和新部署docker容器一致，只有部分区别。

```
docker run --name nginx -d \ #--name 命名容器
--restart=always \ #启动模式
--network docker-net \ #使用的docker网络，
-p 443:443 \ # 映射端口，宿主机端口:容器内端口
-p 80:80 \
-p 5800:5800 \
-v /home/work/web:/var/www/html \ #映射目录 宿主机目录:容器类目录
-v /home/work/docker-conf/config/nginx/conf.d:/etc/nginx/conf.d/ \
-v /home/work/docker-conf/config/nginx/nginx.conf:/etc/nginx/nginx.conf \
-v /home/work/docker-conf/logs/nginx:/var/log/nginx/ \
 nginx:1.15 nginx -g 'daemon off;' # 使用的镜像 镜像名:镜像tag 

```

重点来了，在镜像名后面跟的，就是第一步中记录下来的command列的内容，这是重点，这里不添加的话，会让容器无法启动，或者一直是正在启动的状态。