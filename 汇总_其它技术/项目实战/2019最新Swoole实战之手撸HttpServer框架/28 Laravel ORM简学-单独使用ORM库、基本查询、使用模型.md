composer require illuminate/database





```javascript
<?php
require './vendor/autoload.php';

use Illuminate\Database\Capsule\Manager as Capsule;

$db = new Capsule;
//创建连接
$db->addConnection([
    'driver' => 'mysql',
    'host' => 'localhost',
    'database' => 'test',
    'username' => 'root',
    'password' => '',
    'charset' => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix' => '',
]);
// 设置为全局可访问Make this Capsule instance available globally via static methods... (optional)
$db->setAsGlobal();
//启动ORM Setup the Eloquent ORM... (optional; unless you've used setEventDispatcher())
$db->bootEloquent();

//ORM
var_dump($db::table('user')->get());

//原生
$result = $db::connection()->select('select * from user');
var_dump($result);
```

