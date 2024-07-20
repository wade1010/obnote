nginx 这个轻量级、高性能的 web server 主要可以干两件事情：



　　〉直接作为http server(代替apache，对PHP需要FastCGI处理器支持)；

　　〉另外一个功能就是作为反向代理服务器实现负载均衡



　　以下我们就来举例说明如何使用 nginx 实现负载均衡。因为nginx在处理并发方面的优势，现在这个应用非常常见。当然了Apache的 mod_proxy和mod_cache结合使用也可以实现对多台app server的反向代理和负载均衡，但是在并发处理方面apache还是没有 nginx擅长。



　　1)环境：



　　a. 我们本地是Windows系统，然后使用VirutalBox安装一个虚拟的Linux系统。

　　在本地的Windows系统上分别安装nginx(侦听8080端口)和apache(侦听80端口)。在虚拟的Linux系统上安装apache(侦听80端口)。

　　这样我们相当于拥有了1台nginx在前端作为反向代理服务器；后面有2台apache作为应用程序服务器(可以看作是小型的server cluster。;-) )；



　　b. nginx用来作为反向代理服务器，放置到两台apache之前，作为用户访问的入口；

　　nginx仅仅处理静态页面，动态的页面(php请求)统统都交付给后台的两台apache来处理。

　　也就是说，可以把我们网站的静态页面或者文件放置到nginx的目录下；动态的页面和数据库访问都保留到后台的apache服务器上。



　　c. 如下介绍两种方法实现server cluster的负载均衡。

　　我们假设前端nginx(为127.0.0.1:80)仅仅包含一个静态页面index.html；

　　后台的两个apache服务器(分别为localhost:80和158.37.70.143:80)，一台根目录放置phpMyAdmin文件夹和test.php(里面测试代码为print “server1“;)，另一台根目录仅仅放置一个test.php(里面测试代码为 print “server2“;)。



　　2)针对不同请求 的负载均衡：



　　a. 在最简单地构建反向代理的时候 (nginx仅仅处理静态不处理动态内容，动态内容交给后台的apache server来处理)，我们具体的设置为：在nginx.conf中修改：

　　复制代码 代码如下:



　　location ~ \.php$ {

　　proxy_pass 158.37.70.143:80 ;

　　}



　　〉 这样当客户端访问localhost:8080/index.html的时候，前端的nginx会自动进行响应；

　　〉当用户访问localhost:8080/test.php的时候(这个时候nginx目录下根本就没有该文件)，但是通过上面的设置 location ~ \.php$(表示正则表达式匹配以.php结尾的文件，详情参看location是如何定义和匹配的 http://wiki.nginx.org/NginxHttpCoreModule) ，nginx服务器会自动pass给 158.37.70.143的apache服务器了。该服务器下的test.php就会被自动解析，然后将html的结果页面返回给nginx，然后 nginx进行显示(如果nginx使用memcached模块或者squid还可以支持缓存)，输出结果为打印server2。



　　如上是最为简单的使用nginx做为反向代理服务器的例子；



　　b. 我们现在对如上例子进行扩展，使其支持如上的两台服务器。

　　我们设置nginx.conf的server模块部分，将对应部分修改为：

　　复制代码 代码如下:



　　location ^~ /phpMyAdmin/ {

　　proxy_pass 127.0.0.1:80 ;

　　}

　　location ~ \.php$ {

　　proxy_pass 158.37.70.143:80 ;

　　}



　　上面第一个部分location ^~ /phpMyAdmin/，表示不使用正则表达式匹配(^~)，而是直接匹配，也就是如果客户端访问的 URL是以http://localhost:8080/phpMyAdmin/ 开头的话(本地的nginx目录下根本没有phpMyAdmin目录)，nginx会自动pass到127.0.0.1:80 的Apache服务器，该服务器对phpMyAdmin目录下的页面进行解析，然后将结果发送给nginx，后者显示；

　　如果客户端访问URL是http://localhost/test.php 的话，则会被pass到158.37.70.143:80 的apache进行处理。



　　因此综上，我们实现了针对不同请求的负载均衡。

　　〉如果用户访问静态页面index.html，最前端的nginx直接进行响应；

　　〉如果用户访问test.php页面的话，158.37.70.143:80 的Apache进行响应；

　　〉如果用户访问目录phpMyAdmin下的页面的话，127.0.0.1:80 的Apache进行响应；



　　3)访问同一页面 的负载均衡：

　　即用户访问http://localhost:8080/test.php 这个同一页面的时候，我们实现两台服务器的负载均衡 (实际情况中，这两个服务器上的数据要求同步一致，这里我们分别定义了打印server1和server2是为了进行辨认区别)。



　　a. 现在我们的情况是在windows下nginx是localhost侦听8080端口；

　　两台apache，一台是127.0.0.1:80(包含test.php页面但是打印server1)，另一台是虚拟机的158.37.70.143:80(包含test.php页面但是打印server2)。



　　b. 因此重新配置nginx.conf为：

　　〉首先在nginx的配置文件nginx.conf的http模块中添加，服务器集群server cluster(我们这里是两台)的定义：

　　复制代码 代码如下:



　　upstream myCluster {

　　server 127.0.0.1:80 ;

　　server 158.37.70.143:80 ;

　　}



　　表示这个server cluster包含2台服务器

　　〉然后在server模块中定义，负载均衡：

　　复制代码 代码如下:



　　location ~ \.php$ {

　　proxy_pass http://myCluster ; #这里的名字和上面的cluster的名字相同

　　proxy_redirect off;

　　proxy_set_header Host $host;

　　proxy_set_header X-Real-IP $remote_addr;

　　proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;

　　}



　　这样的话，如果访问http://localhost:8080/test.php 页面的话，nginx目录下根本没有该文件，但是它会自动将其pass到myCluster定义的服务区机群中，分别由127.0.0.1:80;或者158.37.70.143:80;来做处理。

　　上面在定义upstream的时候每个server之后没有定义权重，表示两者均衡；如果希望某个更多响应的话例如：

　　复制代码 代码如下:



　　upstream myCluster {

　　server 127.0.0.1:80 weight=5;

　　server 158.37.70.143:80 ;

　　}



　　这样表示5/6的几率访问第一个server,1/6访问第二个。另外还可以定义max_fails和fail_timeout等参数。



　　综上，我们使用nginx的反向代理服务器reverse proxy server的功能，将其布置到多台apache server的前端。

　　nginx仅仅用来处理静态页面响应和动态请求的代理pass，后台的apache server作为app server来对前台pass过来的动态页面进行处理并返回给nginx。



　　通过以上的架构，我们可以实现nginx和多台apache构成的机群cluster的负载均衡。

　　两种均衡：

　　1)可以在nginx中定义访问不同的内容，代理到不同的后台server； 如上例子中的访问phpMyAdmin目录代理到第一台server上；访问test.php代理到第二台server上；

　　2)可以在nginx中定义访问同一页面，均衡 (当然如果服务器性能不同可以定义权重来均衡)地代理到不同的后台server上。 如上的例子访问test.php页面，会均衡地代理到server1或者server2上。

　　实际应用中，server1和server2上分别保留相同的app程序和数据，需要考虑两者的数据同步。

