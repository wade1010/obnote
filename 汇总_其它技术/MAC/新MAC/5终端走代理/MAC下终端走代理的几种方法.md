方法1:

在终端中直接运行命令

export http_proxy=http://proxyAddress:port

这个办法的好处是简单直接，并且影响面很小（只对当前终端有效）。

方法2:

把代理服务器地址写入shell配置文件.bashrc或者.zshrc

直接在.bashrc或者.zshrc添加下面内容

export http_proxy="http://localhost:port"

export https_proxy="http://localhost:port"

以使用shadowsocks代理为例，ss的代理端口为1080,那么应该设置为

export http_proxy="http://127.0.0.1:1080"

export https_proxy="http://127.0.0.1:1080"

localhost就是一个域名，域名默认指向 127.0.0.1，两者是一样的。

然后ESC后:wq保存文件，接着在终端中执行source ~/.bashrc

或者退出当前终端再起一个终端。 

这个办法的好处是把代理服务器永久保存了，下次就可以直接用了。

方法3:

改相应工具的配置，比如apt的配置

sudo vim /etc/apt/apt.conf

在文件末尾加入下面这行

Acquire::http::Proxy "http://proxyAddress:port"

保存apt.conf文件即可。关于apt的代理设置可以参考这里

关于git的代理设置看这里:用shadowsocks加速git clone

方法4:

利用proxychains在终端使用socks5代理

补充：

如果代理服务器需要登陆，这时可以直接把用户名和密码写进去

http_proxy=http://userName:password@proxyAddress:port
