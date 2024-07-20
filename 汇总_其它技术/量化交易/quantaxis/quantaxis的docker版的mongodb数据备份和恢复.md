### **数据库备份(备份到宿主机当前目录，文件名：dbbackup.tar)：**

1. 停止服务

```
docker-compose stop

```

1. 备份到当前目录

```
docker run  --rm -v qamg:/data/db \
-v $(pwd):/backup alpine \
tar zcvf /backup/dbbackup.tar /data/db

```

1. 停止服务

```
docker-compose stop

```

1. 还原当前目录下的dbbackup.tar到mongod数据库

```
docker run  --rm -v qamg:/data/db \
-v $(pwd):/backup alpine \
sh -c "cd /data/db \
&& rm -rf diagnostic.data \
&& rm -rf journal \
&& rm -rf configdb \
&& cd / \
&& tar xvf /backup/dbbackup.tar"

```

1. 重新启动服务

```
docker-compose up -d
```