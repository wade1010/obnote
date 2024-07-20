[http://strugglesquirrel.com/2019/01/03/%E6%89%8B%E5%8A%A8%E7%BC%96%E8%AF%91ceph/](http://strugglesquirrel.com/2019/01/03/%E6%89%8B%E5%8A%A8%E7%BC%96%E8%AF%91ceph/)

do_cmake首先创建并初始化编译前的环境，创建编译所需的条件，这个过程比较长，官方也说默认情况下会创建一个debug环境，导致这个时间非常久，想要快点则可以指定参数，不让其创建debug环境

```
[tanweijie@cs-2-41 ceph]$ ARGS="-DCMAKE_BUILD_TYPE=RelWithDebInfo" ./do_cmake.sh
```

[http://events.jianshu.io/p/cb53b351c32f](http://events.jianshu.io/p/cb53b351c32f)

[https://durantthorvalds.top/2020/10/21/Ceph%E6%89%8B%E5%8A%A8%E9%85%8D%E7%BD%AE%E9%9B%86%E7%BE%A4%E9%83%A8%E7%BD%B2/](https://durantthorvalds.top/2020/10/21/Ceph%E6%89%8B%E5%8A%A8%E9%85%8D%E7%BD%AE%E9%9B%86%E7%BE%A4%E9%83%A8%E7%BD%B2/)

[https://durantthorvalds.top/](https://durantthorvalds.top/)

[https://durantthorvalds.top/2020/10/21/Ceph%E6%89%8B%E5%8A%A8%E9%85%8D%E7%BD%AE%E9%9B%86%E7%BE%A4%E9%83%A8%E7%BD%B2/#](https://durantthorvalds.top/2020/10/21/Ceph%E6%89%8B%E5%8A%A8%E9%85%8D%E7%BD%AE%E9%9B%86%E7%BE%A4%E9%83%A8%E7%BD%B2/#)