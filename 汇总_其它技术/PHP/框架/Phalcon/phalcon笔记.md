网上下载的pdf或者官方文档说明代码示例有些错误。



$di->set和$di->setShare()是单利模式 不需要再实例化了。每次请求都是同一个





forward:



是controller内部跳转



```javascript
initialize 每次进入controller都会执行  比如一个action中forward到另外一个控制器中

onConstruct  只初始化一次
```



![](https://gitee.com/hxc8/images8/raw/master/img/202407191105709.jpg)





model中



```javascript
public function initialize()
{
    $this->setSchema("test");
    $this->setSource("user");
    echo __METHOD__, '<br>';
}
//保存的时候内容不发生变化 不会触发
public function beforeSave()
{
    echo __METHOD__, '<br>';
}
//保存的时候内容不发生变化 不会触发
public function afterSave()
{
    echo __METHOD__, '<br>';
}
public function afterFetch()
{
    if ($this->age <= 0) {
        $this->age = 112221;
    }
}
```





```javascript
User::find('id<80 and age>100');

User::find([
    'condition' => 'id>100',
    'limit' => 10,
    'offset' => 3,
    'order' => 'id desc'
]);
```







Array ( [id] => 2 [name] => test8085 [age] => 112221 )







model参数查询



```javascript
$id = 2;

$result = User::find([
    'conditions' => 'id = ?2',//数字和下面bind对应
    'bind' => [2 => $id]
]);
print_r($result->toArray());

$result = User::find([
    'conditions' => 'id = :id:',
    'bind' => ['id' => $id]
]);
print_r($result->toArray());
```





通过hydration控制输出



```javascript
$id = 2;

$result = User::find([
    'conditions' => 'id = ?2',//数字和下面bind对应
    'bind' => [2 => $id],
    'hydration' => Resultset::HYDRATE_ARRAYS//Resultset::HYDRATE_OBJECTS//Resultset::HYDRATE_RECORDS默认的
]);
foreach ($result as $obj) {
    var_dump($obj);
}
```





指定field



```javascript
$id = 2;

$result = User::find([
    'conditions' => 'id = ?2',//数字和下面bind对应
    'bind' => [2 => $id],
    'columns' => 'age,name'
]);
foreach ($result as $obj) {
    var_dump($obj);
}
```





取最大最小 等聚合

```javascript
$result = User::maximum(["column" => "id"]);
print_r($result);

$result = User::minimum(["column" => "id"]);
print_r($result);

$result = User::average(["column" => "id"]);
print_r($result);
```



更新操作



```javascript
        $user = User::findFirst(2);
        $user->name = 2222;
        $user->age = 2222;
//        var_dump($user->save());//都行
        var_dump($user->update());//都行
```



user model

```javascript
public function initialize()
{
    $this->skipAttributesOnUpdate(['age']);//不更新age字段
    $this->useDynamicUpdate(true);//加速性能,需要更新的字段更新
    $this->setSchema("test");
    $this->setSource("user");
    echo __METHOD__, '<br>';
}
```





删除 



phal orm没有提供批量删除，





di  管理事务

Dependency Injection

Transactions are reused no matter where the transaction object is retrieved. A new transaction is generated only when a commit() or rollback() is performed. You can use the service container to create the global transaction manager for the entire application:

```javascript
<?php

use Phalcon\Mvc\Model\Transaction\Manager;

$container->setShared(
    'transactions',
    function () {
        return new Manager();
    }
);
```

Then access it from a controller or view:

```javascript
<?php

use Phalcon\Mvc\Controller;
use Phalcon\Mvc\Model\Transaction\Manager;

/**
 * @property Manager $transactions
 */
class ProductsController extends Controller
{
    public function saveAction()
    {
        $manager = $this->di->getTransactions();

        $manager = $this->transactions;

        $transaction = $manager->get();

        // ...
    }
}
```

NOTE: While a transaction is active, the transaction manager will always return the same transaction across the application. {: .alert .alert-info }





session

![](https://gitee.com/hxc8/images8/raw/master/img/202407191105054.jpg)

