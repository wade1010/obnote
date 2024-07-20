<?php

什么时候使用缓存
	进行复杂的数据计算，每次返回相同的结果(不经常修改)
	使用了大量的助手工具，并且生成的输出几乎问题一样的
	不断的访问数据库，且这些数据很少改变
注意 即便已经使用了缓存，过一段时间后，你应该检查你的缓存的命中率，尤其你使用的是Memcache或者Apc时。使用后端提供的相关工具，很容易完成命中率检查。
---------

缓存行为
缓存的执行分为两个部分：
	Frontend: 这一部分主要负责检查KEY是否过期以及在存储到backend之前/从backend取数据之后执行额外的数据转换
	Backend: 这部分主要负责沟通，并根据前端的需求读写数据。
----------

片断缓存
片断缓存是缓存HTML或者TEXT文本的一部分，然后原样返回。输出自动捕获来自ob_* 函数或PHP输出，以便它可以保存到缓存中。 
下面的例子演示了这种用法。 它接收PHP生成的输出，并将其存储到一个文件中，文件的内容172800秒(2天)更新一次。
//Create an Output frontend. Cache the files for 2 days
$frontCache = new Phalcon\Cache\Frontend\Output(array(
    "lifetime" => 172800
));
// Create the component that will cache from the "Output" to a "File" backend
// Set the cache file directory - it's important to keep the "/" at the end of
// the value for the folder
$cache = new Phalcon\Cache\Backend\File($frontCache, array(
    "cacheDir" => "../app/cache/"
));
// Get/Set the cache file to ../app/cache/my-cache.html
$content = $cache->start("my-cache.html");
// If $content is null then the content will be generated for the cache
if ($content === null) {
    //Print date and time
    echo date("r");
    //Generate a link to the sign-up action
    echo Phalcon\Tag::linkTo(
        array(
            "user/signup",
            "Sign Up",
            "class" => "signup-button"
        )
    );
    // Store the output into the cache file
    $cache->save();
} else {
    // Echo the cached output
    echo $content;
}
-----------

缓存任意数据
Caching Arbitrary Data¶
缓存是应用程序重要的组成部分。缓存可以减少数据库负载，重复使用常用的数据（但不更新），从而加快了您的应用程序。

File Backend Example
缓存适配器之一’File’，此适配器的属性只有一个，它用来指定缓存文件的存储位置。使用 cacheDir选项进行控制，且 必须 以’/’结尾。
// Cache the files for 2 days using a Data frontend
$frontCache = new Phalcon\Cache\Frontend\Data(array(
    "lifetime" => 172800
));
// Create the component that will cache "Data" to a "File" backend
// Set the cache file directory - important to keep the "/" at the end of
// of the value for the folder
$cache = new Phalcon\Cache\Backend\File($frontCache, array(
    "cacheDir" => "../app/cache/"
));
// Try to get cached records
$cacheKey = 'robots_order_id.cache';
$robots    = $cache->get($cacheKey);
if ($robots === null) {
    // $robots is null because of cache expiration or data does not exist
    // Make the database call and populate the variable
    $robots = Robots::find(array("order" => "id"));
    // Store it in the cache
    $cache->save($cacheKey, $robots);
}
// Use $robots :)
foreach ($robots as $robot) {
   echo $robot->name, "\n";
}

Memcached Backend Example
上面的例子稍微改变一下(主要是配置方面)，就可以使用Memcache做为后端存储器了。
//Cache data for one hour
$frontCache = new Phalcon\Cache\Frontend\Data(array(
    "lifetime" => 3600
));
// Create the component that will cache "Data" to a "Memcached" backend
// Memcached connection settings
$cache = new Phalcon\Cache\Backend\Memcached($frontCache, array(
    "host" => "localhost",
    "port" => "11211"
));
// Try to get cached records
$cacheKey = 'robots_order_id.cache';
$robots    = $cache->get($cacheKey);
if ($robots === null) {
    // $robots is null because of cache expiration or data does not exist
    // Make the database call and populate the variable
    $robots = Robots::find(array("order" => "id"));
    // Store it in the cache
    $cache->save($cacheKey, $robots);
}
// Use $robots :)
foreach ($robots as $robot) {
   echo $robot->name, "\n";
}
-------------

查询缓存
缓存唯一标识符元素为KEY，在后端文件中，KEY值即是实际文件名。从缓存中检索数据，我们只需要通过KEY来调用即可。如果该KEY不存在，get方法将返回null。
// Retrieve products by key "myProducts"
$products = $cache->get("myProducts");
如果你想知道缓存中都有哪些KEY，你可以调用queryKeys方法：
// Query all keys used in the cache
$keys = $cache->queryKeys();
foreach ($keys as $key) {
    $data = $cache->get($key);
    echo "Key=", $key, " Data=", $data;
}
//Query keys in the cache that begins with "my-prefix"
$keys = $cache->queryKeys("my-prefix");
-----------

删除缓存数据
很多时候，你需要强行删除无效的缓存条目(由于数据更新的原因)，唯一的要求就是，你得知道该缓存的KEY。
// Delete an item with a specific key
$cache->queryKeys("someKey");
// Delete all items from the cache
$keys = $cache->queryKeys();
foreach ($keys as $key) {
    $cache->delete($key);
}
-----------

检测缓存是否存在
通过给定的KEY值，可以检测缓存是否存在。
if ($cache->exists("someKey")) {
    echo $cache->get("someKey");
}
------------

前端适配器
The available frontend adapters that are used as interfaces or input sources to the cache are:
Adapter		Description																	Example
Output		Read input data from standard PHP output									Phalcon\Cache\Frontend\Output
Data		It’s used to cache any kind of PHP data (big arrays, objects, text, etc). 
			The data is serialized before stored in the backend.						Phalcon\Cache\Frontend\Data
Base64		It’s used to cache binary data. The data is serialized using 
			base64_encode before be stored in the backend.								Phalcon\Cache\Frontend\Base64
None		It’s used to cache any kind of PHP data without serializing them.			Phalcon\Cache\Frontend\Data
--------------

实现自定义的前端适配器
The Phalcon\Cache\FrontendInterface interface must be implemented in order to create your own frontend adapters or extend the existing ones.
-----------

后端适配器
可用的后端存储器列表：
Adapter	Description	Info	Required Extensions	Example
File	Stores data to local plain files	 	 	Phalcon\Cache\Backend\File
Memcached	Stores data to a memcached server	Memcached	memcache	Phalcon\Cache\Backend\Memcache
APC	Stores data to the Alternative PHP Cache (APC)	APC	APC extension	Phalcon\Cache\Backend\Apc
Mongo	Stores data to Mongo Database	MongoDb	Mongo	Phalcon\Cache\Backend\Mongo
-------

实现自定义后端适配器
The Phalcon\Cache\BackendInterface interface must be implemented in order to create your own backend adapters or extend the existing ones.
-----------

文件缓存选项
This backend will store cached content into files in the local server. The available options for this backend are:
Option		Description
cacheDir	A writable directory on which cached files will be placed
------------

Memcached缓存选项
This backend will store cached content on a memcached server. The available options for this backend are:

Option			Description
host			memcached host
port			memcached port
persistent		create a persitent connection to memcached?
--------------

APC缓存选项
This backend will store cached content on Alternative PHP Cache (APC). This cache backend does not require any additional configuration options.
------------

Mongo缓存选项
This backend will store cached content on a MongoDB server. The available options for this backend are:
Option			Description
server			A MongoDB connection string
db				Mongo database name
collection		Mongo collection in the database