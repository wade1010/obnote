5041  0.0 0.0  71328  2096? Ssl 09:25  0:00/usr/local/sinasrv2/bin/memcached -u www -d -m 10 -c 10000 -l127.0.0.1 -p 7603

www 18842  0.0 0.0  71328  2084? Ssl Feb06  0:00/usr/local/sinasrv2/bin/memcached -u www -d -m 64 -c 10000 -l127.0.0.1 -p 7600

www 30817  0.0  0.0136716  2108? Ssl 09:04  0:00/usr/local/sinasrv2/bin/memcached -u www -d -m 10 -c 10000 -l127.0.0.1 -p 7602



先用addServer方法添加进去，集群中有上述三个实例:

$memcache = new Memcache;

$memcache->addServer('127.0.0.1', 7600);

$memcache->addServer('127.0.0.1', 7602);

$memcache->addServer('127.0.0.1', 7603);



分别set进去3个值：

$key = 'a';

$value = 'aa';

$memcache->set($key,$value);



$key1 = 'b';

$value1 = 'bb';

$memcache->set($key1,$value1);



$key2 = 'c';

$value2 = 'cc';

$memcache->set($key2,$value2);



telnet 这三个端口发现：

a、c在7603端口

b在7600端口



停掉7603端口的实例：

在php端取不到a和c的值，能取到b的值



向停掉7603端口的集群中重新set进去上述3个值，然后再取：

a在7602端口

b、c在7600端口

虽然7603在集群中，但不可用，所以memcache将set的数据存到了可用的实例中



启动7603端口的实例：

再向集群中重新set进去上述3个值，然后再取：

a、c在7603端口

b在7600端口

但a过期的值在7602端口，c过期的值也在7600端口



上述测试说明：

1、在memcache集群中，如果有单台服务器down掉，如果剩下的集群中的实例能承载所有的业务，memcache新加入的数据会忽略坏掉的实例，继续提供服务

2、在高并发情况下，如果有一个memcached实例down掉了，然后这个memcached恢复后，不要在高峰时间将down掉的服务启动起来，否则会有1/n的数据穿透到后端DB



使用php的memcached类：



起3个实例7600,7602,7603



www 5041  0.0 0.0  71328  2096? Ssl 09:25  0:00/usr/local/sinasrv2/bin/memcached -u www -d -m 10 -c 10000 -l127.0.0.1 -p 7603

www 18842  0.0 0.0  71328  2084? Ssl Feb06  0:00/usr/local/sinasrv2/bin/memcached -u www -d -m 64 -c 10000 -l127.0.0.1 -p 7600

www 30817  0.0  0.0136716  2108? Ssl 09:04  0:00/usr/local/sinasrv2/bin/memcached -u www -d -m 10 -c 10000 -l127.0.0.1 -p 7602



先用addServer方法添加进去，集群中有上述三个实例:

$memcache = new Memcached;

$memcache->addServer('127.0.0.1', 7600);

$memcache->addServer('127.0.0.1', 7602);

$memcache->addServer('127.0.0.1', 7603);



分别set进去3个值：

$key = 'a';

$value = 'aa';

$memcache->set($key,$value);



$key1 = 'b';

$value1 = 'bb';

$memcache->set($key1,$value1);



$key2 = 'c';

$value2 = 'cc';

$memcache->set($key2,$value2);



telnet 这三个端口发现：

a、c在7600端口

b在7603端口



停掉7600端口的实例：

在php端取不到a和c的值，能取到b的值



向停掉7600端口的集群中重新set进去上述3个值，然后再取：

b值能够取到

a、c依然无法取到

7600在集群中，但不可用，所以memcached无法将a、c的值存进去，所以也取不到，这个和memcache有本质区别



启动7600端口的实例：

再向集群中重新set进去上述3个值，然后再取：

a、c在7600端口

b在7603端口

其他端口无过期数据。



上述测试说明：

1、在memcached集群中，如果有单台服务器down掉，那么原先属于这台服务器的所有key-value对均无法get和set，全部可能穿透到后端

2、在高并发情况下，如果有一个memcached实例down掉了，必须尽快恢复该实例



综上：



管理使用memcache的运维人员一定要知道php开发使用的是哪类，然后根据使用方案确定在恢复时候的恢复方案