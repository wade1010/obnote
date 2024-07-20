## 多库 账号密码 推荐设置为一致

### 下载

> https://downloads.mysql.com/archives/proxy/

### 安装 解压后即可使用

> tar -zxvf mysql-proxy-0.8.5-linux-glibc2.3-x86-64bit.tar.gz

```
➜  mysql-proxy ll
总用量 8.0K
drwxr-xr-x 2 7161 wheel   75 8月  19 2014 bin
drwxr-xr-x 2 7161 wheel 4.0K 8月  19 2014 include
drwxr-xr-x 6 7161 wheel 4.0K 8月  19 2014 lib
drwxr-xr-x 2 7161 wheel   75 8月  19 2014 libexec
drwxr-xr-x 8 7161 wheel  320 8月  19 2014 licenses
drwxr-xr-x 3 7161 wheel   17 8月  19 2014 share
```

目录share中有很多lua脚本 后面可以使用其中的读写分离测试




### 使用

进入 bin 目录

> ./mysql-proxy --help

```
➜ ./mysql-proxy --help  
Usage:
  mysql-proxy [OPTION...] - MySQL Proxy

Help Options:
  -?, --help                                              Show help options
  --help-all                                              Show all help options
  --help-proxy                                            Show options for the proxy-module

Application Options:
  -V, --version                                           Show version
  --defaults-file=<file>                                  configuration file
  --verbose-shutdown                                      Always log the exit code when shutting down
  --daemon                                                Start in daemon-mode
  --user=<user>                                           Run mysql-proxy as user
  --basedir=<absolute path>                               Base directory to prepend to relative paths in the config
  --pid-file=<file>                                       PID file in case we are started as daemon
  --plugin-dir=<path>                                     path to the plugins
  --plugins=<name>                                        plugins to load
  --log-level=(error|warning|info|message|debug)          log all messages of level ... or higher
  --log-file=<file>                                       log all messages in a file
  --log-use-syslog                                        log all messages to syslog
  --log-backtrace-on-crash                                try to invoke debugger on crash
  --keepalive                                             try to restart the proxy if it crashed
  --max-open-files                                        maximum number of open files (ulimit -n)
  --event-threads                                         number of event-handling threads (default: 1)
  --lua-path=<...>                                        set the LUA_PATH
  --lua-cpath=<...>                                       set the LUA_CPATH
```

没看到 proxy


>  ./mysql-proxy --help-proxy

```
➜  bin ./mysql-proxy --help-proxy
Usage:
  mysql-proxy [OPTION...] - MySQL Proxy

proxy-module
  -P, --proxy-address=<host:port>                         listening address:port of the proxy-server (default: :4040)
  -r, --proxy-read-only-backend-addresses=<host:port>     address:port of the remote slave-server (default: not set)
  -b, --proxy-backend-addresses=<host:port>               address:port of the remote backend-servers (default: 127.0.0.1:3306)
  --proxy-skip-profiling                                  disables profiling of queries (default: enabled)
  --proxy-fix-bug-25371                                   fix bug #25371 (mysqld > 5.1.12) for older libmysql versions
  -s, --proxy-lua-script=<file>                           filename of the lua script (default: not set)
  --no-proxy                                              don't start the proxy-module (default: enabled)
  --proxy-pool-no-change-user                             don't use CHANGE_USER to reset the connection coming from the pool (default: enabled)
  --proxy-connect-timeout                                 connect timeout in seconds (default: 2.0 seconds)
  --proxy-read-timeout                                    read timeout in seconds (default: 8 hours)
  --proxy-write-timeout                                   write timeout in seconds (default: 8 hours)

```


## 利用之前的主主复制 测试

> ./mysql-proxy --proxy-backend-addresses=192.168.1.52:3306 --proxy-backend-addresses=192.168.1.52:13306

或

> ./mysql-proxy -b 192.168.1.52:3306 -b 192.168.1.52:13306


```
➜  bin ./mysql-proxy --proxy-backend-addresses=192.168.1.52:3306 --proxy-backend-addresses=192.168.1.52:13306
2020-11-07 19:29:02: (critical) plugin proxy 0.8.5 started
```

#### 链接 mysql_proxy  默认端口是4040

## 测试读写分离 使用系统自带的lua脚本 

脚本在根目录下的 share/doc/mysql-proxy/rw-splitting.lua

测试的时候可以改下 这个脚本


```
vi /usr/local/mysql-proxy/lua/rw-splitting.lua
if not proxy.global.config.rwsplit then
 proxy.global.config.rwsplit = {
  min_idle_connections = 1, #默认超过4个连接数时，才开始读写分离，改为1
  max_idle_connections = 1, #默认8，改为1
  is_debug = false
 }
end

```


### 改成配置文件启动

> vim mysql-proxy.cnf

插入如下配置


```
[mysql-proxy]
user=root #运行mysql-proxy用户
admin-username=proxy #主从mysql共有的用户
admin-password=proxy #用户的密码
proxy-address=192.168.1.52:4040 #mysql-proxy运行ip和端口，不加端口，默认4040
proxy-read-only-backend-addresses=192.168.1.52:13306 #指定后端从slave读取数据
proxy-backend-addresses=192.168.1.52:3306 #指定后端主master写入数据
proxy-lua-script=/home/cheng/Desktop/data/docker/mysql/mysql-proxy/share/doc/mysql-proxy/rw-splitting.lua #指定读写分离配置文件位置
admin-lua-script=/home/cheng/Desktop/data/docker/mysql/mysql-proxy/share/doc/mysql-proxy/admin-sql.lua #指定管理脚本
log-file=/home/cheng/Desktop/data/docker/mysql/mysql-proxy/mysql-proxy.log #日志位置
log-level=info #定义log日志级别，由高到低分别有(error|warning|info|message|debug)
daemon=true    #以守护进程方式运行
keepalive=true #mysql-proxy崩溃时，尝试重启
```

> chmod 660 mysql-porxy.cnf


## 启动但是报错

> ./mysql-proxy --defaults-file=/home/xxx/Desktop/data/docker/mysql/mysql-proxy/mysql-proxy.cnf


```
2020-11-07 20:28:20: (critical) Key file contains key 'daemon' which has value that cannot be interpreted.
2020-11-07 20:28:20: (message) Initiating shutdown, requested from mysql-proxy-cli.c:367
2020-11-07 20:28:20: (message) shutting down normally, exit code is: 1
```

在mysql论坛找到了解决办法，/etc/mysql-proxy.cnf可能有非ASCII字符,删掉所有注释,启动成功了。


```
[mysql-proxy]
user=root
admin-username=proxy
admin-password=proxy
proxy-address=192.168.1.52:4040
proxy-read-only-backend-addresses=192.168.1.52:13306
proxy-backend-addresses=192.168.1.52:3306
proxy-lua-script=/home/cheng/Desktop/data/docker/mysql/mysql-proxy/share/doc/mysql-proxy/rw-splitting.lua
admin-lua-script=/home/cheng/Desktop/data/docker/mysql/mysql-proxy/share/doc/mysql-proxy/admin-sql.lua
log-file=/home/cheng/Desktop/data/docker/mysql/mysql-proxy/mysql-proxy.log
log-level=info
daemon=true
keepalive=true
```


> netstat -tupln | grep 4040 


```
tcp        0      0 192.168.1.52:4040       0.0.0.0:*               LISTEN      36266/mysql-proxy 
```

#### 登录mysql_proxy 这里要用192.168.1.52 

> mysql -h 192.168.1.52 -P 4040 -u root -p 