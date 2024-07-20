[https://blog.csdn.net/weixin_45340300/article/details/128716369](https://blog.csdn.net/weixin_45340300/article/details/128716369)

测试端口是否可行

ssh -T -p 443 [git@ssh.github.com](http://git@ssh.github.com)

成功

vim ~/.ssh/config

加入如下内容

```
Hostname ssh.github.com
Port 443
User git
```

![](https://gitee.com/hxc8/images5/raw/master/img/202407172359147.jpg)

验证是否可用

ssh -T [git@github.com](http://git@github.com)

应该就可以了，然后也可以git 操作了