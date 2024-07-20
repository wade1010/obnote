下载地址

https://golang.google.cn/dl/  

或

https://studygolang.com/dl





1、这里下载的版本是1.16.5, cd /root  后执行    wget https://studygolang.com/dl/golang/go1.16.5.linux-amd64.tar.gz

2、解压tar -xzvf go1.16.5.linux-amd64.tar.gz到当前目录。

3、接着找到你机器上的go安装位置，可以通过echo $GOROOT查看。一般为usr/local/go。

4、将解压的文件夹go移动到usr/local目录下，如果移动不能覆盖内容，那么直接删除掉usr/local/go目录，再进行移动。

5、验证下

[root@localhost ~]#go version

go version go1.16.5 linux/amd64