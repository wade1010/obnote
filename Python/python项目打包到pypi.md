https://blog.csdn.net/u011519550/article/details/105253075

https://juejin.cn/post/6844903906158313485

https://cloud.tencent.com/developer/article/1757852

根据上面第一个链接，写个项目，然后配置setup.py，然后打包
然后根据上面第二个或者第三个链接，将打包好的链接传到pypi

第一次使用pypi上传功能，我开通的是 api token，这个还需要开启二次认证，我下载的是google authenticator,成功开通后，这个API很长，就显示一遍，保存好。



![image.png](https://gitee.com/hxc8/images10/raw/master/img/202409111759763.png)



```shell
pip install twine
```



```shell
twine upload dist/*
```



![image.png](https://gitee.com/hxc8/images10/raw/master/img/202409111801071.png)


去 https://pypi.org/manage/projects/  查看项目如下

![image.png](https://gitee.com/hxc8/images10/raw/master/img/202409111803526.png)



上传之后，pip install 的时候，如果是自己配置了国内源，一般同步没那么快，可以用下面命令，指定默认源进行下载

```shell
 pip install graphrag-ui -i https://pypi.org/simple
```

![image.png](https://gitee.com/hxc8/images10/raw/master/img/202409131521307.png)
