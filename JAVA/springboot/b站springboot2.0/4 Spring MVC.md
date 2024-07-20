![](D:/download/youdaonote-pull-master/data/Technology/JAVA/springboot/b站springboot2.0/images/WEBRESOURCE0171f3da003005a40233ed4bdec757c0截图.png)

![](D:/download/youdaonote-pull-master/data/Technology/JAVA/springboot/b站springboot2.0/images/WEBRESOURCEb98dbc46334cbbc7a1f3992a33e6245a截图.png)

![](D:/download/youdaonote-pull-master/data/Technology/JAVA/springboot/b站springboot2.0/images/WEBRESOURCE1a257599ee2de01baa4dc9f3c2787218截图.png)

![](D:/download/youdaonote-pull-master/data/Technology/JAVA/springboot/b站springboot2.0/images/WEBRESOURCE46d25fbf247b84e7888ce9a3c7acf9f8截图.png)

![](D:/download/youdaonote-pull-master/data/Technology/JAVA/springboot/b站springboot2.0/images/WEBRESOURCE76b955cd223c3c80d007898862d2e352截图.png)

然后就可以在jar包启动的时候把上面的参数加入

比如原来启动jar的命令是

java -jar  ./target/spring-webmvc-0.0.1-SNAPSHOT-war-exec.jar

加入JVM参数

java -agentlib:jdwp=transport=dt_socket,server=y,suspend=n,address=5005 -jar  ./target/spring-webmvc-0.0.1-SNAPSHOT-war-exec.jar

![](D:/download/youdaonote-pull-master/data/Technology/JAVA/springboot/b站springboot2.0/images/WEBRESOURCEc3bfbdf1d4f4129b0da17b5904e96993截图.png)

![](D:/download/youdaonote-pull-master/data/Technology/JAVA/springboot/b站springboot2.0/images/WEBRESOURCEfbb046f224db1073f21c49ba665ca85b截图.png)

![](D:/download/youdaonote-pull-master/data/Technology/JAVA/springboot/b站springboot2.0/images/WEBRESOURCEe1f6b2258cf9ac17b024821204948db8截图.png)

这时候就连接上了。

然后就可以访问web页面或者端口，触发断点

![](D:/download/youdaonote-pull-master/data/Technology/JAVA/springboot/b站springboot2.0/images/WEBRESOURCE223e4cb25cdf6ca69be582e9fcfdf6f5截图.png)

![](D:/download/youdaonote-pull-master/data/Technology/JAVA/springboot/b站springboot2.0/images/WEBRESOURCE74b7752ce64bc93b5f6e9c14fffb5fbd截图.png)

![](D:/download/youdaonote-pull-master/data/Technology/JAVA/springboot/b站springboot2.0/images/WEBRESOURCE734436950de4e43be1b2b2ad330c8a0b截图.png)

![](D:/download/youdaonote-pull-master/data/Technology/JAVA/springboot/b站springboot2.0/images/WEBRESOURCE7492169582d4bfb4e9b3cda17372482b截图.png)