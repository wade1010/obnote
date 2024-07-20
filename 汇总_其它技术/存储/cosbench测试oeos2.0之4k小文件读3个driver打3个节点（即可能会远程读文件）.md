之前rfa最多2W OP/S



主备数据

```javascript
<?xml version="1.0" encoding="UTF-8" ?>
<workload name="s3-sample" description="sample benchmark for s3">

  <storage type="s3" config="accesskey=oeosadmin;secretkey=oeosadmin;endpoint=http://oeos.com:19003;path_style_access=true" />

  <workflow>

    <workstage name="init">
      <work type="init" workers="1" config="cprefix=3driver;containers=r(1,3)" />
    </workstage>
    <workstage name="main">
      <work name="write1" workers="10000" driver="driver1" runtime="30">
        <operation type="write" ratio="100" config="cprefix=3driver;containers=u(1,1);objects=s(1,50000);sizes=c(4)KB" />
      </work>
       <work name="write2" workers="10000" driver="driver2" runtime="30">
        <operation type="write" ratio="100" config="cprefix=3driver;containers=u(2,2);objects=s(1,50000);sizes=c(4)KB" />
      </work>
       <work name="write3" workers="10000" driver="driver3" runtime="30">
        <operation type="write" ratio="100" config="cprefix=3driver;containers=u(3,3);objects=s(1,50000);sizes=c(4)KB" />
      </work>
    </workstage>

  </workflow>

</workload>
```



```javascript
<?xml version="1.0" encoding="UTF-8" ?>
<workload name="s3-sample" description="sample benchmark for s3">

  <storage type="s3" config="accesskey=oeosadmin;secretkey=oeosadmin;endpoint=http://oeos.com:19003;path_style_access=true" />

  <workflow>
  	
    <workstage name="main">
      <work name="read" workers="800" runtime="20">
        <operation type="read" ratio="100" config="cprefix=3driver;containers=u(1,3);objects=u(1,50000)" />
      </work>
    </workstage>

  </workflow>

</workload>
```



3000 wkrs

![](https://gitee.com/hxc8/images6/raw/master/img/202407190009338.jpg)



2000 wkrs

![](https://gitee.com/hxc8/images6/raw/master/img/202407190009303.jpg)



1000 wkrs	

![](https://gitee.com/hxc8/images6/raw/master/img/202407190009255.jpg)



800 wkrs

![](https://gitee.com/hxc8/images6/raw/master/img/202407190009362.jpg)

600wkrs

![](https://gitee.com/hxc8/images6/raw/master/img/202407190009329.jpg)

400wkrs

![](https://gitee.com/hxc8/images6/raw/master/img/202407190009288.jpg)

300wkrs

![](https://gitee.com/hxc8/images6/raw/master/img/202407190009283.jpg)

200wkrs

![](https://gitee.com/hxc8/images6/raw/master/img/202407190009285.jpg)

100wkrs

![](https://gitee.com/hxc8/images6/raw/master/img/202407190009186.jpg)

