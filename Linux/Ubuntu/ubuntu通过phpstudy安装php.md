ubuntu通过phpstudy安装php

```
wget -O install.sh https://notdocker.xp.cn/install.sh && sudo bash install.sh
```

![](D:/download/youdaonote-pull-master/data/Technology/Linux/Ubuntu/images/WEBRESOURCE8a000343fb80175dafe9e598a69a89e5截图.png)

更改PHP版本

![](D:/download/youdaonote-pull-master/data/Technology/Linux/Ubuntu/images/WEBRESOURCE5bf35b192c3a879ecaff721f0f29e86a截图.png)

![](D:/download/youdaonote-pull-master/data/Technology/Linux/Ubuntu/images/WEBRESOURCE09a66527adf550fd74ea45a728762ed2截图.png)

但是发现非root用户使用不了php

![](D:/download/youdaonote-pull-master/data/Technology/Linux/Ubuntu/images/WEBRESOURCE2db5d4d6dadda44340f2f68e142fea75截图.png)

因为没有权限

![](D:/download/youdaonote-pull-master/data/Technology/Linux/Ubuntu/images/WEBRESOURCE3f962c90f520a9625a262dd9c9d98bf6截图.png)

这里简单粗暴 给了 777权限，就OK了