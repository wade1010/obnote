准备数据

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



读取配置

```javascript
<?xml version="1.0" encoding="UTF-8" ?>
<workload name="s3-sample" description="sample benchmark for s3">

  <storage type="s3" config="accesskey=oeosadmin;secretkey=oeosadmin;endpoint=http://oeos.com:19003;path_style_access=true" />

  <workflow>
  	
    <workstage name="main">
      <work name="read1" workers="1000" driver="driver1" runtime="30">
        <operation type="read" ratio="100" config="cprefix=3driver;containers=u(1,1);objects=u(1,50000)" />
      </work>
      <work name="read2" workers="1000" driver="driver2" runtime="30">
        <operation type="read" ratio="100" config="cprefix=3driver;containers=u(2,2);objects=u(1,50000)" />
      </work>
      <work name="read3" workers="1000" driver="driver3" runtime="30">
        <operation type="read" ratio="100" config="cprefix=3driver;containers=u(3,3);objects=u(1,50000)" />
      </work>
    </workstage>

  </workflow>

</workload>
```



各 2000 wkrs

![](https://gitee.com/hxc8/images6/raw/master/img/202407190009753.jpg)

各 1000 wkrs



![](https://gitee.com/hxc8/images6/raw/master/img/202407190010713.jpg)

各800 wkrs

![](https://gitee.com/hxc8/images6/raw/master/img/202407190010828.jpg)

各600 wkrs

![](https://gitee.com/hxc8/images6/raw/master/img/202407190010882.jpg)

各500 wkrs

![](https://gitee.com/hxc8/images6/raw/master/img/202407190010992.jpg)

各400 wkrs

![](https://gitee.com/hxc8/images6/raw/master/img/202407190010078.jpg)

各300 wkrs

![](https://gitee.com/hxc8/images6/raw/master/img/202407190010111.jpg)

各200 wkrs

![](https://gitee.com/hxc8/images6/raw/master/img/202407190010263.jpg)

各100 wkrs

![](https://gitee.com/hxc8/images6/raw/master/img/202407190010431.jpg)

