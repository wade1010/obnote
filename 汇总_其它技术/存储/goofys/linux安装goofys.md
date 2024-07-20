wget https://github.com/kahing/goofys/releases/latest/download/goofys



chmod +x goofys





mkdir  /opt/goofys



mkdir ~/.aws



vi ~/.aws/credentials



```javascript
[default]
aws_access_key_id = oeosadmin
aws_secret_access_key = oeosadmin
```



-f  前端执行

goofys1 是桶名

/opt/goofys 是挂载目录

/goofys -f --endpoint http://172.16.1.232:19003/ goofys1 /opt/goofys

/goofys --endpoint http://172.16.1.232:9000/ s3fs-test /opt/goofys



umount  /opt/goofys





可以多节点挂载，缓存采用close-to-open模式

不会自动同步，当你打开目录时会同步





拓展

```javascript
Client cache（在内存中），缓存读写的数据。存在问题，Cache Consistency。
对于Cache Consistency的解决办法：Flush-on-close（或close-to-open）consistency；
文件关闭时，把缓存的已修改的文件数据，写回NFS Server。然后每次在使用缓存的数据前，必须检查是否过时。
方法是用 GETATTR请求去poll（轮询），获得最新的文本属性；然后比较文件修改时间
```

