piclist官方文档 [https://piclist.cn/app.html](https://piclist.cn/app.html)

### 安装PicList

[linux(ubuntu20.04)+PicGo(gui版)+github+typora搭建笔记](note://WEBbf7a5cdcc33f13300d60cdbbe16aa904)

刚搞完typora+picgo，发现不好使，只有linux本地用typora才行，比如windows上面的typora使用不了远端的picgo，picgo好像也不支持使用http协议传文件(没仔细研究)

后来发现obsidian（windows上面的）可以配置picgo server（linux）使用http协议上传，但是直接使用发现有问题，

![](https://gitee.com/hxc8/images7/raw/master/img/202407190804946.jpg)

最开始的错误是 找不到 xclip,使用 sudo apt-get install xclip 就解决了。后来就出现上面的报错了。也没找到配置。估计是有点问题。后来看ob配置里面提到piclist。查下资料，是在picgo基础上，二次开发，感觉挺好。

参考了

[https://github.com/Kuingsmile/PicList/blob/dev/README_cn.md](https://github.com/Kuingsmile/PicList/blob/dev/README_cn.md)

vim docker-compose.yml   最好把末尾的piclist123456改成你自己的，这是个秘钥。在调用上传接口的时候，需要作为参数传到server端，后面会有用到

```
version: '3.3'

services:
  node:
    image: 'kuingsmile/piclist:latest'
    container_name: piclist
    restart: always
    ports:
      - 36677:36677
    volumes:
      - './piclist:/root/.piclist'
    command: node /usr/local/bin/picgo-server -k piclist123456
```

启动 docker-compose up -d   发现服务器上拉不下来，科学上网也不行，国内源也不行。。。。

直接使用gui把

上一篇已经配置过了PicGo,而这个PicList支持一键从PicGo迁移

![](https://gitee.com/hxc8/images7/raw/master/img/202407190804320.jpg)

但是迁移之后，插件里面的github-plus需要再安装下

![](https://gitee.com/hxc8/images7/raw/master/img/202407190804432.jpg)

### 配置obsidian

obsidian安装插件

首先插件市场搜索picgo会出现Image auto upload，这个就是PicGo安装此插件并启用即可

![](https://gitee.com/hxc8/images7/raw/master/img/202407190804590.jpg)

改下监听地址，端口最好也改下，并设置个鉴权秘钥（这个其实就是docker启动后面那个参数，可惜docker部署没成功，就不纠结了），如下图 

![](https://gitee.com/hxc8/images7/raw/master/img/202407190804787.jpg)

就把下图配置里面的Ip改成你server的ip即可

![](D:/download/youdaonote-pull-master/data/Technology/工具/images/WEBRESOURCEc8f3eed6719476f4b9bd9a29b194ce68image.png)

记得加上参数key，因为你设置了鉴权秘钥，没设置，可以不需要

```
http://yourip:18888/upload?key=a123456
http://yourip:18888/delete?key=a123456
```

最终测试（下图左边是linux服务器上的piclist，右边是windows的obsidian）

![](https://gitee.com/hxc8/images7/raw/master/img/202407190804257.jpg)

### 利用github多端同步

在github创建一个obsidian的仓库，

#### 配置windows

然后在本地找obsidian本地仓库对应的目录，该目录下面会自动生成一个叫note的目录,该目录下有个.obsidian文件夹（如下图），进入该目录，初始化仓库即可。

![](https://gitee.com/hxc8/images7/raw/master/img/202407190804379.jpg)

```
git init
git add .
git commit -m "first commit"
git branch -M main
git remote add origin git@github.com:你的名字/xxxx.git
git push -u origin main
```

执行完之后，如下图

![](https://gitee.com/hxc8/images7/raw/master/img/202407190804637.jpg)

在Obsidian上下载Git插件

![](https://gitee.com/hxc8/images7/raw/master/img/202407190804063.jpg)

设置git插件

![](https://gitee.com/hxc8/images7/raw/master/img/202407190804436.jpg)

![](https://gitee.com/hxc8/images7/raw/master/img/202407190804820.jpg)

也可以为git插件设置快捷键，主动push等

![](https://gitee.com/hxc8/images7/raw/master/img/202407190804599.jpg)

![](https://gitee.com/hxc8/images7/raw/master/img/202407190804286.jpg)

这样，后续可以在空闲时间自动commit 和push

并且也可以通过Ctrl+Shift+k 先commit修改的文件

通过Ctrl+Shift+p 来push

#### 配置Android手机

参考

[https://zhuanlan.zhihu.com/p/620225805](https://zhuanlan.zhihu.com/p/620225805)

这里[https://obsidian.md/download](https://obsidian.md/download) 下载apk进行安装

[https://f-droid.org/zh_Hans/packages/com.manichord.mgit/](https://f-droid.org/zh_Hans/packages/com.manichord.mgit/)  下载mgit

我是先设置了个根目录，如下图

![](https://gitee.com/hxc8/images7/raw/master/img/202407190804466.jpg)

后面用obsidian就好找了

还有就是手机端mgit提交的时候，需要设置邮箱 用户名之类的，这个在右上角设置里面有。

手机端增加笔记后，在mgit操作

大概流程   暂存所有->提交（填下提交信息）->推送。就ok了

#### 转为使用gitee

后来发现传到github的图片在没有科学上网的情况下访问不了，我还加了一个cdn，以为能访问，结果那个cdn也被和谐的。

![](https://gitee.com/hxc8/images7/raw/master/img/202407190804269.jpg)

上图红色框里面的插件都安装失败，所以就使用最上面的gitee 2.0.7了

配置

![](https://gitee.com/hxc8/images7/raw/master/img/202407190804520.jpg)

填入信息 

图床配置名 ：这个起个名字，待会在图床里面能看到，PicList默认是没gitee的

owner填入用户名，举个例子，[https://gitee.com/oschina/awesome-llm](https://gitee.com/oschina/awesome-llm) 里面的oschina

repo填入仓库名，举个例子，[https://gitee.com/oschina/awesome-llm](https://gitee.com/oschina/awesome-llm)里面的awesome-llm

path填入目录名称

token就是gitee的私人令牌，设置->私人令牌->生成新令牌(我是全选了,也可以选前3个)不同的就查下资料，很简单

![](https://gitee.com/hxc8/images7/raw/master/img/202407190804693.jpg)

点击确定后，在图床里面就多了我们添加的

![](https://gitee.com/hxc8/images7/raw/master/img/202407190804003.jpg)

后面测试都能成功上传，但是遇到一个坑，picList没地方配置branch(我没找到),默认是master，但是我最开始创建gitee的时候选了默认分支是main.导致上传成功了，但是返回的url是访问不到图片的，我对了返回url和gitee里面图片的地址发现默认分支有问题，所以我又创建了个master分支，解决了。也可以最开始的时候默认选择master.

### 迁移有道云笔记

我在大佬的基础上简单二次开发的 [https://github.com/wade1010/youdaonote-pull](https://github.com/wade1010/youdaonote-pull)  支持PicList

详细看README