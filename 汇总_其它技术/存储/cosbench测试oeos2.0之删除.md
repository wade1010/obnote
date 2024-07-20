

```javascript
<?xml version="1.0" encoding="UTF-8" ?>
<workload name="s3-sample" description="sample benchmark for s3">

  <storage type="s3" config="accesskey=oeosadmin;secretkey=oeosadmin;endpoint=http://oeos.com:19003;path_style_access=true" />

  <workflow>

    <workstage name="init">
      <work type="init" workers="1" config="cprefix=cosbench4;containers=r(1,1)" />
    </workstage>

    <workstage name="prepare">
      <work type="prepare" workers="1000" config="cprefix=cosbench4;containers=r(1,1);objects=r(1,100000);sizes=c(8)KB" />
    </workstage>

    <workstage name="main">
      <work name="delete" workers="200" driver="driver1" runtime="60">
        <operation type="delete" ratio="100" config="cprefix=cosbench4;containers=u(1,1);objects=r(1,33000)" />
      </work>
      <work name="delete" workers="200" driver="driver2" runtime="60">
        <operation type="delete" ratio="100" config="cprefix=cosbench4;containers=u(1,1);objects=r(33001,66000)" />
      </work>
      <work name="delete" workers="200" driver="driver3" runtime="60">
        <operation type="delete" ratio="100" config="cprefix=cosbench4;containers=u(1,1);objects=r(66001,100000)" />
      </work>
      
    </workstage>

  </workflow>

</workload>
```

