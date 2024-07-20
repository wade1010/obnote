经过多次测试，确定cosbench 存在bug



<operation type="write" ratio="100" config="cprefix=98test;containers=r(1,1);objects=u(1,10000000);sizes=c(4)KB" />

<operation type="write" ratio="100" config="cprefix=98test;containers=r(1,1);objects=s(1,10000000);sizes=c(4)KB" />

<operation type="write" ratio="100" config="cprefix=98test;containers=r(1,1);objects=r(1,10000000);sizes=c(4)KB" />



这个objects参数后面的 u、s、r 对于文件名的分配会重复。



![](https://gitee.com/hxc8/images6/raw/master/img/202407190010635.jpg)





![](https://gitee.com/hxc8/images6/raw/master/img/202407190010652.jpg)

