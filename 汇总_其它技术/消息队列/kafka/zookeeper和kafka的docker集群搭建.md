

![](https://gitee.com/hxc8/images7/raw/master/img/202407190740336.jpg)



README的内容如下：

```javascript
可使用如下命名导入centos:6.6 image，避免在线下载centos:6.6的image，从而提高创建kafka与zookeeper image的速度
bzip2 -d -c <centos.6.6.tar.bz2 | docker load

加载完成后，可通过docker images查看centos:6.6是否加载成功，如果成功，则可继续使用视频中的方法创建kafka与zookeeper的image
```





[kafka.Dockerfile](attachments/WEBRESOURCEb6fd21c0e8ad5d328d09ae0fc9ffaf21kafka.Dockerfile)



[zookeeper.Dockerfile](attachments/WEBRESOURCEa22faac498fada5a4b09a39c3a8e30f2zookeeper.Dockerfile)



[docker-compose.yml](attachments/WEBRESOURCE7bf2db5a2adbade3f47006e441d2f356docker-compose.yml)

