[htop-2.1.0.tar.gz](attachments/WEBRESOURCEd2249e6fad14582e9b1f06f55c75b97chtop-2.1.0.tar.gz)

对于经常在mac上使用top命令的童鞋来说，htop会是一个更加好看和更加好用的命令，下面就是在mac上安装htop的步骤

1.首先去htop官网去下载，我下的是最新的2.0.2版本，网址是https://hisham.hm/htop/releases/      ,下载最新版本 

2.切换到你下载目录，解压这个文件 解压命令是 tar zxvf htop-2.0.2.tar.gz 

3.切换到已经解压过的文件夹 执行 ./configure

4.执行命令 sudo make  (2018-02-06 18:41:20 安装失败)

5.（可选择）执行sudo make check命令

6.执行sudo make install命令

7.（可选择）执行sudo make installcheck

8. 此时htop命令已经好了，只需把他移到/bin 目录下就行了 执行sudo mv htop /bin 

9. 此时就可以删除解压后的htop文件夹了

 在终端中执行htop就可以了，如图

![20170923103421313](https://gitee.com/hxc8/images7/raw/master/img/202407190750670.jpg)

