
```shell
[global]
index-url=https://pypi.tuna.tsinghua.edu.cn/simple/
extra-index-url=
        http://mirrors.aliyun.com/pypi/simple/
        http://pypi.douban.com/simple
        http://pypi.mirrors.ustc.edu.cn/simple/

[install]
trusted-host=
        pypi.tuna.tsinghua.edu.cn
        mirrors.aliyun.com
        pypi.douban.com
        pypi.mirrors.ustc.edu.cn

proxy_servers:
  http: http://xxx.xxx.x.xx:8080
  https: https://xxx.xxx.x.xx:8080
ssl_verify: false
```

在index-url源找不到所需要的包，才会在extra-index-url中搜索，
trusted-host中添加信任主机，不然会遇到很多SSL信任问题
proxy_servers 添加公司/学校等的代理服务器

不用代理，如下


```shell
[global]
index-url=https://pypi.tuna.tsinghua.edu.cn/simple/
extra-index-url=
        https://mirrors.aliyun.com/pypi/simple/
        http://pypi.douban.com/simple/
        http://pypi.mirrors.ustc.edu.cn/simple/

[install]
trusted-host=
        pypi.tuna.tsinghua.edu.cn
        mirrors.aliyun.com
        pypi.douban.com
        pypi.mirrors.ustc.edu.cn

ssl_verify: false
```
