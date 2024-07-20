4k  1000workers   

```javascript
<?xml version="1.0" encoding="UTF-8" ?>
<workload name="s3-sample" description="sample benchmark for s3">

  <storage type="s3" config="accesskey=oeosadmin;secretkey=oeosadmin;endpoint=http://oeos.com:19003;path_style_access=true" />

  <workflow>

    <workstage name="init">
      <work type="init" workers="1" config="cprefix=99test;containers=r(1,1)" />
    </workstage>
    <workstage name="main">
      <work name="write" workers="1000" runtime="30">
        <operation type="write" ratio="100" config="cprefix=99test;containers=r(1,1);objects=s(1,10000000);sizes=c(4)KB" />
      </work>
    </workstage>

  </workflow>

</workload>
```



![](https://gitee.com/hxc8/images6/raw/master/img/202407190010999.jpg)





4k 800workers

```javascript
<?xml version="1.0" encoding="UTF-8" ?>
<workload name="s3-sample" description="sample benchmark for s3">

  <storage type="s3" config="accesskey=oeosadmin;secretkey=oeosadmin;endpoint=http://oeos.com:19003;path_style_access=true" />

  <workflow>

    <workstage name="init">
      <work type="init" workers="1" config="cprefix=100test;containers=r(1,1)" />
    </workstage>
    <workstage name="main">
      <work name="write" workers="800" runtime="30">
        <operation type="write" ratio="100" config="cprefix=100test;containers=r(1,1);objects=s(1,10000000);sizes=c(4)KB" />
      </work>
    </workstage>

  </workflow>

</workload>
```



![](https://gitee.com/hxc8/images6/raw/master/img/202407190010092.jpg)





4k 600workers

```javascript
<?xml version="1.0" encoding="UTF-8" ?>
<workload name="s3-sample" description="sample benchmark for s3">

  <storage type="s3" config="accesskey=oeosadmin;secretkey=oeosadmin;endpoint=http://oeos.com:19003;path_style_access=true" />

  <workflow>

    <workstage name="init">
      <work type="init" workers="1" config="cprefix=101test;containers=r(1,1)" />
    </workstage>
    <workstage name="main">
      <work name="write" workers="600" runtime="30">
        <operation type="write" ratio="100" config="cprefix=101test;containers=r(1,1);objects=s(1,10000000);sizes=c(4)KB" />
      </work>
    </workstage>

  </workflow>

</workload>
```



![](https://gitee.com/hxc8/images6/raw/master/img/202407190010055.jpg)







4k 400workers

```javascript
<?xml version="1.0" encoding="UTF-8" ?>
<workload name="s3-sample" description="sample benchmark for s3">

  <storage type="s3" config="accesskey=oeosadmin;secretkey=oeosadmin;endpoint=http://oeos.com:19003;path_style_access=true" />

  <workflow>

    <workstage name="init">
      <work type="init" workers="1" config="cprefix=102test;containers=r(1,2)" />
    </workstage>
    <workstage name="main">
      <work name="write" workers="400" runtime="30">
        <operation type="write" ratio="100" config="cprefix=102test;containers=r(1,2);objects=s(1,10000000);sizes=c(4)KB" />
      </work>
    </workstage>

  </workflow>

</workload>
```



![](https://gitee.com/hxc8/images6/raw/master/img/202407190010323.jpg)







4k 16384workers

```javascript
<?xml version="1.0" encoding="UTF-8" ?>
<workload name="s3-sample" description="sample benchmark for s3">

  <storage type="s3" config="accesskey=oeosadmin;secretkey=oeosadmin;endpoint=http://oeos.com:19003;path_style_access=true" />

  <workflow>

    <workstage name="init">
      <work type="init" workers="1" config="cprefix=103test;containers=r(1,2)" />
    </workstage>
    <workstage name="main">
      <work name="write" workers="16384" runtime="30">
        <operation type="write" ratio="100" config="cprefix=103test;containers=r(1,2);objects=s(1,10000000);sizes=c(4)KB" />
      </work>
    </workstage>

  </workflow>

</workload>
```



![](https://gitee.com/hxc8/images6/raw/master/img/202407190010709.jpg)



4k 8192workers

```javascript
<?xml version="1.0" encoding="UTF-8" ?>
<workload name="s3-sample" description="sample benchmark for s3">

  <storage type="s3" config="accesskey=oeosadmin;secretkey=oeosadmin;endpoint=http://oeos.com:19003;path_style_access=true" />

  <workflow>

    <workstage name="init">
      <work type="init" workers="1" config="cprefix=104test;containers=r(1,2)" />
    </workstage>
    <workstage name="main">
      <work name="write" workers="8192" runtime="30">
        <operation type="write" ratio="100" config="cprefix=104test;containers=r(1,2);objects=s(1,10000000);sizes=c(4)KB" />
      </work>
    </workstage>

  </workflow>

</workload>
```



![](https://gitee.com/hxc8/images6/raw/master/img/202407190010867.jpg)

4k 4000workers

```javascript
<?xml version="1.0" encoding="UTF-8" ?>
<workload name="s3-sample" description="sample benchmark for s3">

  <storage type="s3" config="accesskey=oeosadmin;secretkey=oeosadmin;endpoint=http://oeos.com:19003;path_style_access=true" />

  <workflow>

    <workstage name="init">
      <work type="init" workers="1" config="cprefix=105test;containers=r(1,2)" />
    </workstage>
    <workstage name="main">
      <work name="write" workers="4000" runtime="30">
        <operation type="write" ratio="100" config="cprefix=105test;containers=r(1,2);objects=s(1,10000000);sizes=c(4)KB" />
      </work>
    </workstage>

  </workflow>

</workload>
```



![](https://gitee.com/hxc8/images6/raw/master/img/202407190010036.jpg)





