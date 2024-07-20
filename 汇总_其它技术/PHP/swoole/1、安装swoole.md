1. 查看官网说明https://wiki.swoole.com/wiki/page/6.html

1. 安装前必须保证系统已经安装了下列软件

- php-7.0 或更高版本

- gcc-4.8 或更高版本

- make

- autoconf

- pcre (CentOS系统可以执行命令：yum install pcre-devel)

1. 下载源码

1.  https://github.com/swoole/swoole-src/releases 进入后点击“<>Code”获取最新版下载地址或使用git clone https://github.com/swoole/swoole-src.git swoole下载后进入 swoole文件夹

1. phpize (ubuntu 没有安装phpize可执行命令：sudo apt-get install php-dev来安装phpize)

1. ./configure

1. make   (这里我有个报错，是因为不是PHP7，所以要升级到PHP7，使用make clean 清除)

1. (sudo) make install  不是root就用sudo

1. 下面是官方编译阐述参考

1. phpize && \
./configure \
--enable-coroutine \
--enable-openssl  \
--enable-http2  \
--enable-async-redis \
--enable-sockets \
--enable-mysqlnd && \
make clean && make && sudo make install

1. 编译完成之后会生成一个so文件

1. 重新编译的话，先自行命令 make clean 再重新来

1. 配置php.ini

1. 修改php.ini 末行加入extension=swoole.so

1. 通过php -m或phpinfo()来查看是否成功加载了swoole.so，如果没有可能是php.ini的路径不对，可以使用php --ini来定位到php.ini的绝对路径。

1. php --ri swoole 如果输出了swoole的扩展信息就说明你安装成功了!99.999%的人在此步成功就可以直接使用swoole了