### 一、假设本地装好ShadowsocksX-NG-R且能正常代理访问

### 二、点击ShadowsocksX-NG-R->复制终端代理

我复制出来的内容
    
> export http_proxy=http://127.0.0.1:1087;export https_proxy=http://127.0.0.1:1087;


### 三、使用方法

#### 1、在你打开的终端直接使用

这个办法的好处是简单直接且影响面很小因为只对当前终端有效

执行前后对比

```
wget www.twitter.com
--2020-05-14 11:45:54--  http://www.twitter.com/
正在解析主机 www.twitter.com (www.twitter.com)... 74.86.235.236, 2001::453f:b20d
正在连接 www.twitter.com (www.twitter.com)|74.86.235.236|:80... 
^C

export http_proxy=http://127.0.0.1:1087;export https_proxy=http://127.0.0.1:1087;

wget www.twitter.com
--2020-05-14 11:46:09--  http://www.twitter.com/
正在连接 127.0.0.1:1087... 已连接。
已发出 Proxy 请求，正在等待回应... 301 Moved Permanently
位置：https://www.twitter.com/ [跟随至新的 URL]
--2020-05-14 11:46:17--  https://www.twitter.com/
正在连接 127.0.0.1:1087... 已连接。
已发出 Proxy 请求，正在等待回应... ^C
```

#### 2、利用命令 alias 添加控制开关

vim ~/.zshrc

添加


```
# proxy list
alias onsocketproxy='export socket_proxy=socks5://127.0.0.1:1086'
alias offsocketproxy='unset socket_proxy'
alias onhttpproxy='export http_proxy=http://127.0.0.1:1087;export https_proxy=http://127.0.0.1:1087;'
alias offhttpproxy='unset http_proxy;unset https_proxy;'
```

##### 测试下


```
[0] % wget www.twitter.com                                          
--2020-05-14 11:54:16--  http://www.twitter.com/
正在解析主机 www.twitter.com (www.twitter.com)... 67.228.126.62, 67.228.126.62, 75.126.150.210, ...
正在连接 www.twitter.com (www.twitter.com)|67.228.126.62|:80... 

^C

cmbp : ~
[130] % onhttpproxy                                                   

cmbp : ~
[0] % wget www.twitter.com
--2020-05-14 11:54:32--  http://www.twitter.com/
正在连接 127.0.0.1:1087... 已连接。
已发出 Proxy 请求，正在等待回应... 301 Moved Permanently
位置：https://www.twitter.com/ [跟随至新的 URL]
--2020-05-14 11:54:33--  https://www.twitter.com/
正在连接 127.0.0.1:1087... 已连接。
已发出 Proxy 请求，正在等待回应... 301 Moved Permanently
位置：https://twitter.com/ [跟随至新的 URL]
--2020-05-14 11:54:33--  https://twitter.com/
正在连接 127.0.0.1:1087... 已连接。
^C

```


#### 3、配置写入shell配置文件.zshrc/.bashrc

我的是.zshrc   vim .zshrc添加下面配置

export http_proxy="http://127.0.0.1:1080"

export https_proxy="http://127.0.0.1:1080"

这个是所有终端都是使用的。影响力度比较大