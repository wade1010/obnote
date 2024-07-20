写在开头，这是我最开始折腾的笔记，个人感觉这一套不好用，后面还折腾了一系列，感觉好用。

### 下载PicGo

[https://github.com/Molunerfinn/PicGo/releases](https://github.com/Molunerfinn/PicGo/releases)

Linux下载.AppImage结尾的

```
2024-7-16 22:33:05 的最新版是 https://picgo-release.molunerfinn.com/2.4.0-beta.7/PicGo-2.4.0-beta.7.AppImage
```

chmod +x PicGo-2.4.0-beta.7.AppImage

### 设置开机自启动  后来发现瞎折腾，软件支持开机自启动，可以跳过这一步骤

![](https://gitee.com/hxc8/images7/raw/master/img/202407190804718.jpg)

```
cd /etc/init.d/
sudo vim picgo
然后输入如下内容，注意替换为你的路径
```

```
#!/bin/bash
/home/xxx/PicGo-2.4.0-beta.7.AppImage  
```

```text
sudo chmod 777 picgo
```

添加开机启动

```text
sudo update-rc.d picgo defaults 90
```

删除开机启动

```text
sudo update-rc.d -f picgo remove
```

列出所有启动项，可查看开机自启动服务的状态：

```text
sudo systemctl list-unit-files
sudo systemctl list-unit-files|grep picgo
```

后来发现这个PicGo用root启动有问题

![](https://gitee.com/hxc8/images7/raw/master/img/202407190804948.jpg)

后来删除了picgo这个脚本，让后使用系统自带的，如下图，英文版本的话叫StartupApplications

![](https://gitee.com/hxc8/images7/raw/master/img/202407190804026.jpg)

![](https://gitee.com/hxc8/images7/raw/master/img/202407190804712.jpg)

![](https://gitee.com/hxc8/images7/raw/master/img/202407190805025.jpg)

启动后

![](https://gitee.com/hxc8/images7/raw/master/img/202407190805614.jpg)

### 配置github

参考[https://blog.csdn.net/weixin_49765221/article/details/135489266](https://blog.csdn.net/weixin_49765221/article/details/135489266)

![](https://gitee.com/hxc8/images7/raw/master/img/202407190805070.jpg)

![](https://gitee.com/hxc8/images7/raw/master/img/202407190805570.jpg)

安装nodejs

```
sudo apt install nodejs -y  #后来发现不行。可能原因默认版本太低了。10.几的版本
```

改成如下安装

```
wget https://nodejs.org/dist/v18.15.0/node-v18.15.0-linux-x64.tar.xz
tar -xf node-v18.15.0-linux-x64.tar.xz
mv node-v18.15.0-linux-x64 /usr/local/node
cd /usr/local/bin
ln -s /usr/local/node/bin/node node
ln -s /usr/local/node/bin/npm npm
npm config set registry https://registry.npmmirror.com   #得设置国内源，要不然很慢，设置后，几秒钟OK
```

安装成功后重启下PicGo就可以安装了

![](https://gitee.com/hxc8/images7/raw/master/img/202407190805878.jpg)

![](https://gitee.com/hxc8/images7/raw/master/img/202407190805153.jpg)

![](https://gitee.com/hxc8/images7/raw/master/img/202407190805372.jpg)

![](https://gitee.com/hxc8/images7/raw/master/img/202407190805998.jpg)

![](https://gitee.com/hxc8/images7/raw/master/img/202407190805370.jpg)

```
上图最后加速地址为 https://cdn.jsdelivr.net/gh/你的github名字/仓库名字              要跟上图第二行配置的一模一样
```

然后点击上传区，死活不成功

后来在windows安装，同样配置也不行，然后找到了日志就明白了（linux里面也有日志，只是第一次接触，不熟悉）

![](https://gitee.com/hxc8/images7/raw/master/img/202407190805623.jpg)

我的那个仓库很久以前创还能得，用的还是master分支。。。

修改成master后就能上传成功了

![](https://gitee.com/hxc8/images7/raw/master/img/202407190805305.jpg)

### Typora

#### Linux

参考 [https://blog.csdn.net/weixin_73546177/article/details/132033297](https://blog.csdn.net/weixin_73546177/article/details/132033297)

#### windows

我这里选择了绿色版本

[https://www.ghxi.com/typora.html](https://www.ghxi.com/typora.html)  里面点击了[http://www.123pan.com/s/HQeA-UX1Sh](http://www.123pan.com/s/HQeA-UX1Sh)

![](https://gitee.com/hxc8/images7/raw/master/img/202407190805454.jpg)

我其实主要是想要里面的winmm.dll

![](https://gitee.com/hxc8/images7/raw/master/img/202407190805487.jpg)

typora安装包，我使用官方下载 [https://download2.typoraio.cn/windows/typora-setup-x64-1.9.5.exe](https://download2.typoraio.cn/windows/typora-setup-x64-1.9.5.exe)

安装好typora之后，把上面的winmm.dll文件复制到typora的安装目录

![](https://gitee.com/hxc8/images7/raw/master/img/202407190805699.jpg)

然后就可以打开typora验证下

![](https://gitee.com/hxc8/images7/raw/master/img/202407190805854.jpg)

![](https://gitee.com/hxc8/images7/raw/master/img/202407190805751.jpg)

点击文件，进入偏好设置，关闭自动更新

![](https://gitee.com/hxc8/images7/raw/master/img/202407190805065.jpg)

![](https://gitee.com/hxc8/images7/raw/master/img/202407190805277.jpg)

点击图像

如下图，选择好对应的程序即可

![](https://gitee.com/hxc8/images7/raw/master/img/202407190805670.jpg)

验证，如下图，看到成功即可。

![](https://gitee.com/hxc8/images7/raw/master/img/202407190805492.jpg)

其他设置

![](D:/download/youdaonote-pull-master/data/Technology/工具/images/WEBRESOURCEa8340cb444a6ac468c37ae9e8bc72b96image.png)

#### 多端同步

参考 [https://blog.csdn.net/a867255865/article/details/127391924#t5](https://blog.csdn.net/a867255865/article/details/127391924#t5)

我是使用了百度盘 参考这里 [https://blog.csdn.net/Jc_Stu/article/details/135666390#t9](https://blog.csdn.net/Jc_Stu/article/details/135666390#t9)

打开百度同步，如下图

![](https://gitee.com/hxc8/images7/raw/master/img/202407190805482.jpg)

 

WIN+E，侧边栏就能看到如下图

![](https://gitee.com/hxc8/images7/raw/master/img/202407190805795.jpg)

然后设置typora

![](https://gitee.com/hxc8/images7/raw/master/img/202407190805922.jpg)

顺序为从左点到右，在百度网盘同步空间里面创建一个note的目录，就可以了。

后面你创建的笔记，就会保存到note目录，然后该目录会被百度盘同步到云端。另外一个设备同样配置，就可以从另外一台电脑同步笔记了