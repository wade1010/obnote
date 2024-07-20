curl --proto '=https' --tlsv1.2 -sSf https://tiup-mirrors.pingcap.com/install.sh | sh



source ~/.bash_profile



tiup playground v5.0.0 --db 2 --pd 3 --kv 3 --monitor --host 0.0.0.0



```javascript
CLUSTER START SUCCESSFULLY, Enjoy it ^-^
To connect TiDB: mysql --host 192.168.199.187 --port 4001 -u root -p (no password) --comments
To connect TiDB: mysql --host 192.168.199.187 --port 4000 -u root -p (no password) --comments
To view the dashboard: http://192.168.199.187:2379/dashboard
To view the Prometheus: http://192.168.199.187:34084
To view the Grafana: http://192.168.199.187:3000
```



新开启一个 session 以访问 TiDB 数据库。

使用 TiUP client 连接 TiDB：

tiup client

也可使用 MySQL 客户端连接 TiDB：

mysql --host 127.0.0.1 --port 4000 -u root





网络要求



![](https://gitee.com/hxc8/images7/raw/master/img/202407190809519.jpg)









