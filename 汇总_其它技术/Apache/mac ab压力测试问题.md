ab认为第一次请求的内容是成功内容 后面请求要是不一样就认为是失败



待验证



错误问题：

apr_socket_recv: Connection reset by peer (54)

 

是由于使用的MacOSX默认自带的ab限制了并发数导致的。

解决办法：下载最新的apache并重新编译，备份原来的ab并将新编译的ab替换到原来的路径。

先下载文件：httpd-2.4.25.tar.bz2，在编译的时候说没有apr和apr-util，先对这两个进行安装；

官网下载地址：http://apr.apache.org/downloa... 和 http://apache.fayea.com/httpd/







```javascript
//解压
tar -zxvf apr-1.5.2.tar.gz
//进入解压后目录
./configure --prefix=/usr/local/apr

make & make install

//同理
tar -zxvf apr-util-1.5.4.tar.gz

./configure --prefix=/usr/local/apr-util --with-apr=/usr/local/apr

make & make install

//同理
tar -zxvf httpd-2.4.25.tar.bz2

./configure --with-apr=/usr/local/apr --with-apr-util=/usr/local/apr-util
make & make install
```

