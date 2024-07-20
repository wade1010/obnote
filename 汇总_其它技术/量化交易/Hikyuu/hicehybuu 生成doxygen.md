最好开启代理

```
$ xmake doxygen                                                                                                                                                                        exit 255
note: install or modify (m) these packages (pass -y to skip confirm)?
in xmake-repo:
  -> bison 3.8.2 [private, from:doxygen]
  -> flex 2.6.4 [private, from:doxygen]
  -> doxygen 1.9.6 
please input: y (y/n/m)
y
  => download http://ftp.gnu.org/gnu/bison/bison-3.8.2.tar.gz .. ok
  => install bison 3.8.2 .. ok                            
  => downloading flex .. (1/curl) ⠋^C
runner@ubuntu20 in hikyuu on master
$ export https_proxy=http://127.0.0.1:7890;export http_proxy=http://127.0.0.1:7890;export all_proxy=socks5://127.0.0.1:7890                                                            exit 130
runner@ubuntu20 in hikyuu on master
$ xmake doxygen                                                                                                            
note: install or modify (m) these packages (pass -y to skip confirm)?
in xmake-repo:
  -> flex 2.6.4 [private, from:doxygen]
  -> doxygen 1.9.6 
please input: y (y/n/m)
y
  => download https://github.com/westes/flex/releases/download/v2.6.4/flex-2.6.4.tar.gz .. ok
  => install flex 2.6.4 .. ok     
  => download https://github.com/doxygen/doxygen/releases/download/Release_1_9_6/doxygen-1.9.6.src.tar.gz .. ok
  => install doxygen 1.9.6 .. ok     
generating ..🍺
result: %s/html/index.html
doxygen ok!

```

然后再pycharm里面找到这个html/index.html

![](https://gitee.com/hxc8/images5/raw/master/img/202407172332587.jpg)

选择对应的浏览器

![](https://gitee.com/hxc8/images5/raw/master/img/202407172332928.jpg)

然后浏览器大概就能看到如下内容

![](https://gitee.com/hxc8/images5/raw/master/img/202407172332517.jpg)