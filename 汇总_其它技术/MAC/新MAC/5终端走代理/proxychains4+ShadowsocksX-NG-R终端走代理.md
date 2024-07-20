### 安装
```
brew install proxychains-ng
```

### 查看端口


顺序是点ShadowsocksX-NG-R图标->高级代理设置

如果没改ShadowsocksX-NG-R配置。默认是1086

![image](https://gitee.com/hxc8/images7/raw/master/img/202407190749467.jpg)

### 修改配置

> vim /usr/local/etc/proxychains.conf

在 ProxyList下面（配置最下方）添加如下

> socks5 127.0.0.1 1086

保存


#### 测试 proxychains4 wget www.google.com 


```
 ~$ proxychains4 wget www.google.com                                   
[proxychains] config file found: /usr/local/etc/proxychains.conf
[proxychains] preloading /usr/local/Cellar/proxychains-ng/4.14/lib/libproxychains4.dylib
[proxychains] DLL init: proxychains-ng 4.14
--2020-05-14 11:38:16--  http://www.google.com/
正在解析主机 www.google.com (www.google.com)... 224.0.0.1
正在连接 www.google.com (www.google.com)|224.0.0.1|:80... [proxychains] Strict chain  ...  127.0.0.1:1086  ...  www.google.com:80  ...  OK
已连接。
已发出 HTTP 请求，正在等待回应... 200 OK
长度：未指定 [text/html]
正在保存至: “index.html”

index.html              [ <=>                ]  11.63K  --.-KB/s  用时 0s      

2020-05-14 11:38:18 (85.4 MB/s) - “index.html” 已保存 [11907]
```

