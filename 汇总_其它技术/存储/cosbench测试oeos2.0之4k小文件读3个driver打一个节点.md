



准备数据

```javascript
<?xml version="1.0" encoding="UTF-8" ?>
<workload name="s3-sample" description="sample benchmark for s3">

  <storage type="s3" config="accesskey=oeosadmin;secretkey=oeosadmin;endpoint=http://172.16.1.231:19003;path_style_access=true" />

  <workflow>

    <workstage name="init">
      <work type="init" workers="1" config="cprefix=ffffflll;containers=r(1,2)" />
    </workstage>

    <workstage name="prepare">
      <work type="prepare" workers="10000" config="cprefix=ffffflll;containers=r(1,2);objects=r(1,100000);sizes=c(4)KB" />
    </workstage>
  </workflow>
</workload>
```



4K 30000workers

```javascript
<?xml version="1.0" encoding="UTF-8" ?>
<workload name="s3-sample" description="sample benchmark for s3">

  <storage type="s3" config="accesskey=oeosadmin;secretkey=oeosadmin;endpoint=http://172.16.1.231:19003;path_style_access=true" />

  <workflow>

    <workstage name="main">
      <work name="main" workers="30000" runtime="30">
        <operation type="read" ratio="100" config="cprefix=ffffflll;containers=u(1,2);objects=u(1,100000)" />
      </work>
    </workstage>
  </workflow>

</workload>
```

第一次

![](https://gitee.com/hxc8/images6/raw/master/img/202407190009656.jpg)

第二次

![](https://gitee.com/hxc8/images6/raw/master/img/202407190009688.jpg)

第三次

![](https://gitee.com/hxc8/images6/raw/master/img/202407190009703.jpg)



4K 15000 workers

第一次

![](https://gitee.com/hxc8/images6/raw/master/img/202407190009568.jpg)

第二次

![](https://gitee.com/hxc8/images6/raw/master/img/202407190009414.jpg)

第三次

![](https://gitee.com/hxc8/images6/raw/master/img/202407190009716.jpg)



8000 wkrs

第一次

![](https://gitee.com/hxc8/images6/raw/master/img/202407190009663.jpg)

第二次

![](https://gitee.com/hxc8/images6/raw/master/img/202407190009876.jpg)

第三次

![](https://gitee.com/hxc8/images6/raw/master/img/202407190009897.jpg)



4000 wkrs

第一次

![](https://gitee.com/hxc8/images6/raw/master/img/202407190009989.jpg)

第二次

![](https://gitee.com/hxc8/images6/raw/master/img/202407190009828.jpg)

第三次

![](https://gitee.com/hxc8/images6/raw/master/img/202407190009806.jpg)



2000 wkrs

第一次

![](https://gitee.com/hxc8/images6/raw/master/img/202407190009786.jpg)

第二次

![](https://gitee.com/hxc8/images6/raw/master/img/202407190009849.jpg)

第三次

![](https://gitee.com/hxc8/images6/raw/master/img/202407190009049.jpg)

第四次

![](https://gitee.com/hxc8/images6/raw/master/img/202407190009117.jpg)



1500 wkrs

第一次

![](https://gitee.com/hxc8/images6/raw/master/img/202407190009034.jpg)

第二次

![](https://gitee.com/hxc8/images6/raw/master/img/202407190009000.jpg)

第三次

![](https://gitee.com/hxc8/images6/raw/master/img/202407190009890.jpg)



1000 wkrs	

第一次

![](https://gitee.com/hxc8/images6/raw/master/img/202407190009980.jpg)

第二次

![](https://gitee.com/hxc8/images6/raw/master/img/202407190009046.jpg)

第三次

![](https://gitee.com/hxc8/images6/raw/master/img/202407190009417.jpg)



800 wkrs

第一次

![](https://gitee.com/hxc8/images6/raw/master/img/202407190009461.jpg)

第二次

![](https://gitee.com/hxc8/images6/raw/master/img/202407190009409.jpg)

第三次

![](https://gitee.com/hxc8/images6/raw/master/img/202407190009355.jpg)



600 wkrs

第一次

![](https://gitee.com/hxc8/images6/raw/master/img/202407190009279.jpg)

第二次

![](https://gitee.com/hxc8/images6/raw/master/img/202407190009244.jpg)

第三次

![](https://gitee.com/hxc8/images6/raw/master/img/202407190009335.jpg)



400wkrs

第一次

![](https://gitee.com/hxc8/images6/raw/master/img/202407190009537.jpg)

第二次

![](https://gitee.com/hxc8/images6/raw/master/img/202407190009502.jpg)

第三次

![](https://gitee.com/hxc8/images6/raw/master/img/202407190009317.jpg)



300 wkrs

第一次

![](https://gitee.com/hxc8/images6/raw/master/img/202407190009440.jpg)

第二次

![](https://gitee.com/hxc8/images6/raw/master/img/202407190009459.jpg)

第三次

![](https://gitee.com/hxc8/images6/raw/master/img/202407190009367.jpg)



200 wkrs 

第一次

![](https://gitee.com/hxc8/images6/raw/master/img/202407190009243.jpg)

第二次

![](https://gitee.com/hxc8/images6/raw/master/img/202407190009292.jpg)

第三次

![](https://gitee.com/hxc8/images6/raw/master/img/202407190009128.jpg)



100wkrs

第一次

![](https://gitee.com/hxc8/images6/raw/master/img/202407190009138.jpg)

第二次

![](https://gitee.com/hxc8/images6/raw/master/img/202407190009076.jpg)

第三次

![](https://gitee.com/hxc8/images6/raw/master/img/202407190009308.jpg)



50wkrs

第一次

![](https://gitee.com/hxc8/images6/raw/master/img/202407190009280.jpg)









