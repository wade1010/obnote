<?php

如果你想手工指定映射到其他的数据库表，你可以使用 getSource() 方法：
class Robots extends \Phalcon\Mvc\Model
{
    public function getSource()
    {
        return "the_robots";
    }
}
命名空间可以用来避免类名冲突，在这种情况下，使用getSource()方法来指定数据表名称是必要的：
--------------

查找记录:
$robot = Robots::findFirst(
    array(
        "type = 'virtual'",
        "order" => "name DESC",
        "limit" => 30
    )
);
$robots = Robots::find(
    array(
        "conditions" => "type = ?1",
        "bind"       => array(1 => "virtual")
    )
);

可接受的选项:
conditions,	bind,	bindTypes,	order, limit , group,  for_update, shared_lock,	cache

如果你愿意，你也可以通过面向对象的方式创建查询，而不是使用上面讲到的关联数组的形式：
$robots = Robots::query()
    ->where("type = :type:")
    ->bind(array("type" => "mechanical"))
    ->order("name")
    ->execute();
-----------------

模型数据集
// Get all robots
$robots = Robots::find();
// Traversing with a foreach
foreach ($robots as $robot) {
    echo $robot->name, "\n";
}
// Traversing with a while
$robots->rewind();
while ($robots->valid()) {
    $robot = $robots->current();
    echo $robot->name, "\n";
    $robots->next();
}
// Count the resultset
echo count($robots);
// Alternative way to count the resultset
echo $robots->count();
// Move the internal cursor to the third robot
$robots->seek(2);
$robot = $robots->current()
// Access a robot by its position in the resultset
$robot = $robots[5];
// Check if there is a record in certain position
if (isset($robots[3]) {
   $robot = $robots[3];
}
// Get the first record in the resultset
$robot = robots->getFirst();
// Get the last record
$robot = robots->getLast();
----------

参数绑定
在 Phalcon\Mvc\Model 同样支持参数类型绑定。虽然会有比较小的性能消耗，但我们推荐你使用这种方法，因为它会清除SQL注入攻击，字符串过滤及整形数据验证等。
// Query robots binding parameters with string placeholders
$conditions = "name = :name: AND type = :type:";
//Parameters whose keys are the same as placeholders
$parameters = array(
    "name" => "Robotina",
    "type" => "maid"
);
//Perform the query
$robots = Robots::find(array(
    $conditions,
    "bind" => $parameters
));

// Query robots binding parameters with integer placeholders
$conditions = "name = ?1 AND type = ?2";
$parameters = array(1 => "Robotina", 2 => "maid");
$robots     = Robots::find(array(
    $conditions,
    "bind" => $parameters
));

// Query robots binding parameters with both string and integer placeholders
$conditions = "name = :name: AND type = ?1";
//Parameters whose keys are the same as placeholders
$parameters = array(
    "name" => "Robotina",
    1 => "maid"
);
//Perform the query
$robots = Robots::find(array(
    $conditions,
    "bind" => $parameters
));

//Bind parameters
$parameters = array(
    "name" => "Robotina",
    "year" => 2008
);
//Casting Types
$types = array(
    Phalcon\Db\Column::BIND_PARAM_STR,
    Phalcon\Db\Column::BIND_PARAM_INT
);
// Query robots binding parameters with string placeholders
$conditions = "name = :name: AND year = :year:";
$robots = Robots::find(array(
    $conditions,
    "bind" => $parameters,
    "bindTypes" => $types
));
参数绑定可以用于所有的查询方法上，比如find()和findFirst()。当然也包括一些计算类的方法，如 count(),sum(),average()等
---------------

模型之间的关系
共有四种类型的关系：一对一，一对多，多对一，多对多。
在Phalcon中，关系的定义必须在model的initialize()方法中进行定义，通过方法belongsTo(),hasOne(), hasMany(), hasManyToMany() 进行关联关系. 这几个方法都需要3个参数，即： 当前模型属性，关联模型名称，关联模型的属性。
hasMany			Defines a 1-n relationship
hasOne			Defines a 1-1 relationship
belongsTo		Defines a n-1 relationship
hasManyToMany	Defines a n-n relationship
class Parts extends \Phalcon\Mvc\Model
{
    public function initialize()
    {
        $this->hasMany("id", "RobotsParts", "parts_id");
    }
}
class RobotsParts extends \Phalcon\Mvc\Model
{
    public function initialize()
    {
        $this->belongsTo("robots_id", "Robots", "id");
        $this->belongsTo("parts_id", "Parts", "id");
    }
}

当明确定义了模型之间的关系后，就很容易通过查找到的记录找到相关模型的记录
$robot = Robots::findFirst(2);
foreach ($robot->getRobotsParts() as $robotPart) {
    echo $robotPart->getParts()->name, "\n";
}
------------

虚拟外键
默认情况下，关联关系并不定义外键约束，也就是说，如果你尝试insert/update数据的话，将不会进行外键验证，Phalcon也不会提示验证信息。你可以修改此行为，增加一个参数定义这种关系。
RobotsPart模型可以这样修改，以实现此功能：
class RobotsParts extends \Phalcon\Mvc\Model
{
    public function initialize()
    {
        $this->belongsTo("robots_id", "Robots", "id", array(
            "foreignKey" => true
        ));
        $this->belongsTo("parts_id", "Parts", "id", array(
            "foreignKey" => array(
                "message" => "The part_id does not exist on the parts model"
            )
        ));
    }
}
--------------

数量统计是数据库中常用的功能，如COUNT,SUM,MAX,MIN,AVG. 
// How many employees are?
$rowcount = Employees::count();
// How many different areas are assigned to employees?
$rowcount = Employees::count(array("distinct" => "area"));
// How many employees are in the Testing area?
$rowcount = Employees::count("area = 'Testing'");
//Count employees grouping results by their area
$group = Employees::count(array("group" => "area"));
foreach ($group as $row) {
   echo "There are ", $group->rowcount, " in ", $group->area;
}
// Count employees grouping by their area and ordering the result by count
$group = Employees::count(
    array(
        "group" => "area",
        "order" => "rowcount"
    )
);
其他的方法同样适用: sum(), average(), maximum(), minimum().
-----------

缓存结果集:
当 Phalcon\Mvc\Model 需要缓存结果集时，它会依赖于容器中的”modelsCache”这个服务。
首先注册服务:
//Set the models cache service
$di->set('modelsCache', function(){
    //Cache data for one day by default
    $frontCache = new Phalcon\Cache\Frontend\Data(array(
        "lifetime" => 86400
    ));
    //Memcached connection settings
    $cache = new Phalcon\Cache\Backend\Memcached($frontCache, array(
        "host" => "localhost",
        "port" => "11211"
    ));
    return $cache;
});
使用:
// Get products without caching
$products = Products::find();
// Just cache the resultset. The cache will expire in 1 hour (3600 seconds)
$products = Products::find(array("cache" => true));
// Cache the resultset only for 5 minutes
$products = Products::find(array("cache" => 300));
// Cache the resultset with a key pre-defined
$products = Products::find(array("cache" => array("key" => "my-products-key")));
// Cache the resultset with a key pre-defined and for 2 minutes
$products = Products::find(
    array(
        "cache" => array(
            "key"      => "my-products-key",
            "lifetime" => 120
        )
    )
);
// Using a custom cache
$products = Products::find(array("cache" => $myCache));
-------------

创建/更新记录
$robot       = new Robots();
$robot->type = "mechanical";
$robot->name = "Astro Boy";
$robot->year = 1952;
if ($robot->save() == false) {
    echo "Umh, We can't store robots right now: \n";
    foreach ($robot->getMessages() as $message) {
        echo $message, "\n";
    }
} else {
    echo "Great, a new robot was saved successfully!";
}

save方法还可以直接通过传入一个数组的形式进行保存数据
$robot->save(array(
    "type" => "mechanical",
    "name" => "Astro Boy",
    "year" => 1952
));

数据直接赋值或通过数组绑定，这些数据都会根据相关的数据类型被escaped/sanitized，所以你可以传递一个不安全的数组，而不必担心发生SQL注入：
$robot = new Robots();
$robot->save($_POST);

只能创建不能更新的记录:
//This record only must be created
if ($robot->create() == false) {
    echo "Umh, We can't store robots right now: \n";
    foreach ($robot->getMessages() as $message) {
        echo $message, "\n";
    }
} 

如果数据库的自增字段(识别列)需要指定,则:
public function getSequenceName()
{
	return "robots_sequence_name";
}

Phalcon\Mvc\Model 有一个消息传递子系统，它提供了一个灵活的输出方式，或存储在insert/update过程中的验证消息。
if ($robot->save() == false) {
    foreach ($robot->getMessages() as $message) {
        echo "Message: ", $message->getMessage();
        echo "Field: ", $message->getField();
        echo "Type: ", $message->getType();
    }
}
---------------

验证事件及事件管理
模型允许你实现事件，当执行insert和update的时候，这些事件将被抛出。

Operation			Name						Can stop operation?		Explanation
Inserting/Updating	beforeValidation			YES						Is executed before the fields are validated for not nulls or foreign keys
Inserting			beforeValidationOnCreate	YES						Is executed before the fields are validated for not nulls or foreign keys when an insertion operation is being made
Updating			beforeValidationOnUpdate	YES						Is executed before the fields are validated for not nulls or foreign keys when an updating operation is being made
Inserting/Updating	onValidationFails			YES (already stopped)	Is executed after an integrity validator fails
Inserting			afterValidationOnCreate		YES						Is executed after the fields are validated for not nulls or foreign keys when an insertion operation is being made
Updating			afterValidationOnUpdate		YES						Is executed after the fields are validated for not nulls or foreign keys when an updating operation is being made
Inserting/Updating	afterValidation				YES						Is executed after the fields are validated for not nulls or foreign keys
Inserting/Updating	beforeSave					YES						Runs before the required operation over the database system
Updating			beforeUpdate				YES						Runs before the required operation over the database system only when an updating operation is being made
Inserting			beforeCreate				YES						Runs before the required operation over the database system only when an inserting operation is being made
Updating			afterUpdate					NO						Runs after the required operation over the database system only when an updating operation is being made
Inserting			afterCreate					NO						Runs after the required operation over the database system only when an inserting operation is being made
Inserting/Updating	afterSave					NO						Runs after the required operation over the database system

public function beforeCreate()
{
	$this->created_at = date('Y-m-d H:i:s');
}
public function beforeUpdate()
{
	$this->modified_in = date('Y-m-d H:i:s');
}
...

此外，该组件将与 Phalcon\Events\Manager 一同工作，这意味着当事件被触发时，我们可以创建监听器。
//Registering the modelsManager service
$di->setShared('modelsManager', function() {
    $eventsManager = new Phalcon\Events\Manager();
    //Attach an anonymous function as a listener for "model" events
    $eventsManager->attach('model', function($event, $model){
        if (get_class($model) == 'Robots') {
            if ($event->getType() == 'beforeSave') {
                if ($modle->name == 'Scooby Doo') {
                    echo "Scooby Doo isn't a robot!";
                    return false;
                }
            }
        }
        return true;
    });
    //Setting a default EventsManager
    $modelsManager = new Phalcon\Mvc\Models\Manager();
    $modelsManager->setEventsManager($eventsManager);
    return $modelsManager;
});

我们建议验证方法被声明为protected.
有些事件返回false用于指示停止当前操作。如果一个事件没有返回任何东西，Phalcon\Mvc\Model 将假设它返回true。

这些事件可以和内部验证器搭配使用:
use Phalcon\Mvc\Model\Validator\InclusionIn;
use Phalcon\Mvc\Model\Validator\Uniqueness;
class Robots extends \Phalcon\Mvc\Model
{
    public function validation()
    {
        $this->validate(new InclusionIn(
            array(
                "field"  => "type",
                "domain" => array("Mechanical", "Virtual")
            )
        ));
        $this->validate(new Uniqueness(
            array(
                "field"   => "name",
                "message" => "The robot name must be unique"
            )
        ));
        return $this->validationHasFailed() != true;
    }
}
可以使用的内部验证器有: PresenceOf,	Email, ExclusionIn, InclusionIn, Numericality, Regex, Uniqueness, StringLength

除了使用这些内置验证器，你还可以创建你自己的验证器：
use \Phalcon\Mvc\Model\Validator,
    \Phalcon\Mvc\Model\ValidatorInterface;
class UrlValidator extends Validator implements ValidatorInterface
{
    public function validate($model)
    {
        $field = $this->getOption('field');
        $value = $model->$field;
        $filtered = filter_var($value, FILTER_VALIDATE_URL);
        if (!$filtered) {
            $this->appendMessage("The URL is invalid", $field, "UrlValidator");
            return false;
        }
        return true;
    }
}
使用:
class Customers extends \Phalcon\Mvc\Model
{
    public function validation()
    {
        $this->validate(new UrlValidator(
            array(
                "field"  => "url",
            )
        ));
        if ($this->validationHasFailed() == true) {
            return false;
        }
    }
}
--------------

避免SQL注入攻击
如果我们只使用PDO来安全的存储一条记录，我们需要编写以下代码：
$productTypesId = 1;
$name = 'Artichoke';
$price = 10.5;
$active = 'Y';
$sql = 'INSERT INTO products VALUES (null, :productTypesId, :name, :price, :active)';
$sth = $dbh->prepare($sql);
$sth->bindParam(':productTypesId', $productTypesId, PDO::PARAM_INT);
$sth->bindParam(':name', $name, PDO::PARAM_STR, 70);
$sth->bindParam(':price', doubleval($price));
$sth->bindParam(':active', $active, PDO::PARAM_STR, 1);
$sth->execute();

好消息是，Phalcon自动为您做到这一点：
$product = new Products();
$product->product_types_id = 1;
$product->name = 'Artichoke';
$product->price = 10.5;
$product->active = 'Y';
$product->create();
--------------

Skipping Columns
有时候，有一些数据使用数据库系统的触发器或默认值，因此我们在insert/update的时候，会忽略掉这些属性：
//Skips fields/columns on both INSERT/UPDATE operations
$this->skipAttributes(array('year', 'price'));
//Skips only when inserting
$this->skipAttributesOnCreate(array('created_at'));
//Skips only when updating
$this->skipAttributesOnUpdate(array('modified_in'));

强制一个默认值，可以以下列方式进行：
$robot->created_at = new Phalcon\Db\RawValue('default');
---------------

删除记录
$robot = Robots::findFirst(11);
if ($robot != false) {
    if ($robot->delete() == false) {
        echo "Sorry, we can't delete the robot right now: \n";
        foreach ($robot->getMessages() as $message) {
            echo $message, "\n";
        }
    } else {
        echo "The robot was deleted successfully!";
    }
}
delete() 允许删除一条记录,多条记录要循环删除.
这里我与几个朋友讨论过, model是这样，先查，后删，而且删多条还要循环, 对性能十分不利. 如果直接删除，就用 
$phql = "DELETE FROM Cars WHERE id = 101";
$manager->executeQuery($phql);

删除动作的事件:
Operation	Name			Can stop operation?		Explanation
Deleting	beforeDelete	YES						Runs before the delete operation is made
Deleting	afterDelete		NO						Runs after the delete operation was made
-------

验证失败的事件
Operation					Name				Explanation
Insert or Update			notSave				Triggered when the INSERT or UPDATE operation fails for any reason
Insert, Delete or Update	onValidationFails	Triggered when any data manipulation operation fails
--------------

事务管理(Transactions)
try {
    //Create a transaction manager
    $manager = new Phalcon\Mvc\Model\Transaction\Manager();
    // Request a transaction
    $transaction = $manager->get();
    $robot = new Robots();
    $robot->setTransaction($transaction);
    $robot->name = "WALLÂ·E";
    $robot->created_at = date("Y-m-d");
    if ($robot->save() == false) {
        $transaction->rollback("Cannot save robot");
    }
    $robotPart = new RobotParts();
    $robotPart->setTransaction($transaction);
    $robotPart->type = "head";
    if ($robotPart->save() == false) {
        $transaction->rollback("Cannot save robot part");
    }
    //Everything goes fine, let's commit the transaction
    $transaction->commit();

} catch(Phalcon\Mvc\Model\Transaction\Failed $e) {
    echo "Failed, reason: ", $e->getMessage();
}

可以定义到服务容器:
$di->setShared('transactions', function(){
    return new Phalcon\Mvc\Model\Transaction\Manager();
});
-----------

独立的列映射
ORM支持独立的列映射，它允许开发人员在模型中的属性不同于数据库的字段名称。Phalcon能够识别新的列名，并会相应的进行重命名，以对应数据库中的字段。 
这是一个伟大的功能，当你需要重命名数据库中的字段，而不必担心代码中所有的查询。示例如下：
class Robots extends Phalcon\Mvc\Model
{
    public function columnMap()
    {
        //Keys are the real names in the table and
        //the values their names in the application
        return array(
            'id' => 'code',
            'the_name' => 'theName',
            'the_type' => 'theType',
            'the_year' => 'theYear'
        );
    }
}
当有下面的情况时，你可以考虑使用新的别名：
在relationships/validators中，必须使用新的名称
列名会导致ORM的异常发生
------------

模型元数据
$robot = new Robots();
// Get Phalcon\Mvc\Model\Metadata instance
$metaData = $robot->getDI()->getModelsMetaData();
// Get robots fields names
$attributes = $metaData->getAttributes($robot);
print_r($attributes);
// Get robots fields data types
$dataTypes = $metaData->getDataTypes($robot);
print_r($dataTypes);

应用程序在一个生产阶段时，没有必要总是从数据库系统中查询元数据，你可以使用以下的几种适配器把这些元数据缓存起来：
Adapter		API
Memory		Phalcon\Mvc\Model\MetaData\Memory
Session		Phalcon\Mvc\Model\MetaData\Session
Apc			Phalcon\Mvc\Model\MetaData\Apc
Files		TPhalcon\Mvc\Model\MetaData\Files

注册服务
$di->setShared('modelsMetadata', function() {
    // Create a meta-data manager with APC
    $metaData = new Phalcon\Mvc\Model\MetaData\Apc(
        array(
            "lifetime" => 86400,
            "suffix"   => "my-suffix"
        )
    );
    return $metaData;
});

Phalcon可以自动的获得元数据，不强制开发人员必须手工设定他们。 
请注意，手工定义元数据时，添加/修改/删除 数据表字段的时候，必须手工添加／修改／删除 元数据对应列，以保证一切正常工作。
具体例子查看手册
---------------

指定不同的模式
如果模型映射的表不是默认的schemas/databases，你可以通过 getSchema 方法手工指定它：
class Robots extends \Phalcon\Mvc\Model
{
    public function getSchema()
    {
        return "toys";
    }
}
------------

建立多个数据库连接
public function initialize()
{
	$this->setConnectionService('dbPostgres');
}
--------------

记录SQL日志
Phalcon\Mvc\Model 内部由 Phalcon\Db 支持。Phalcon\Logger 与 Phalcon\Db 交互工作，可以提供数据库抽象层的日志记录功能，从而使我们能够记录下SQL语句
$di->set('db', function() {
    $eventsManager = new Phalcon\Events\Manager();
    $logger = new Phalcon\Logger\Adapter\File("app/logs/debug.log");
    //Listen all the database events
    $eventsManager->attach('db', function($event, $connection) use ($logger) {
        if ($event->getType() == 'beforeQuery') {
            $logger->log($connection->getSQLStatement(), \Phalcon\Logger::INFO);
        }
    });
    $connection = new \Phalcon\Db\Adapter\Pdo\Mysql(array(
        "host" => "localhost",
        "username" => "root",
        "password" => "secret",
        "dbname" => "invo"
    ));
    //Assign the eventsManager to the db adapter instance
    $connection->setEventsManager($eventsManager);
    return $connection;
});
------------

剖析SQL语句
感谢 Phalcon\Db ，作为 Phalcon\Mvc\Model 的基本组成部分，剖析ORM产生的SQL语句变得可能，以便分析数据库的性能问题，同时你可以诊断性能问题，并发现瓶颈。
$di->set('profiler', function(){
    return new Phalcon\Db\Profiler();
});

$di->set('db', function() use ($di) {
    $eventsManager = new Phalcon\Events\Manager();
    //Get a shared instance of the DbProfiler
    $profiler = $di->getProfiler();
    //Listen all the database events
    $eventsManager->attach('db', function($event, $connection) use ($profiler) {
        if ($event->getType() == 'beforeQuery') {
            $profiler->startProfile($connection->getSQLStatement());
        }
        if ($event->getType() == 'afterQuery') {
            $profiler->stopProfile();
        }
    });
    $connection = new \Phalcon\Db\Adapter\Pdo\Mysql(array(
        "host" => "localhost",
        "username" => "root",
        "password" => "secret",
        "dbname" => "invo"
    ));
    //Assign the eventsManager to the db adapter instance
    $connection->setEventsManager($eventsManager);
    return $connection;
});

分析查询:
// Send some SQL statements to the database
Robots::find();
Robots::find(array("order" => "name");
Robots::find(array("limit" => 30);
//Get the generated profiles from the profiler
$profiles = $di->getShared('profiler')->getProfiles();
foreach ($profiles as $profile) {
   echo "SQL Statement: ", $profile->getSQLStatement(), "\n";
   echo "Start Time: ", $profile->getInitialTime(), "\n";
   echo "Final Time: ", $profile->getFinalTime(), "\n";
   echo "Total Elapsed Time: ", $profile->getTotalElapsedSeconds(), "\n";
}
