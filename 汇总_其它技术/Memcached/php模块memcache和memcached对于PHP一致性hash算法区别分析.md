大家都知道“一致性hash算法”是当添加或删除存储节点时，对存储在memcached上的数据影响较小的一种算法。那么在php的两个扩展库中，都可以使用该算法，只是设置方法有所不同。

Memcache

修改php.ini添加：

[Memcache]

Memcache.allow_failover = 1

……

……

Memcache.hash_strategy =consistent

Memcache.hash_function =crc32

……

……

或在php中使用ini_set方法：

Ini_set(‘memcache.hash_strategy','standard');

Ini_set(‘memcache.hash_function','crc32');



Memcached

$mem = new memcached();

$mem->setOption(Memcached::OPT_DISTRIBUTION,Memcached::DISTRIBUTION_CONSISTENT);

$mem->setOption(Memcached::OPT_LIBKETAMA_COMPATIBLE,true);