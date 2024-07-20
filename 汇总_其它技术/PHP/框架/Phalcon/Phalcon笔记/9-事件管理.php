<?php
此组件的目的是通过创建挂钩点拦截框架中大部分组件的执行。这些挂钩点允许开发者获取状态信息，操作数据或改变一个组件在执行过程中的流程。

例一:
class MyDbListener
{
    protected $_profiler;
    protected $_logger;
    public function __construct()
    {
        $this->_profiler = new \Phalcon\Db\Profiler();
        $this->_logger = new \Phalcon\Logger\Adapter\File("../apps/logs/db.log");
    }
    public function beforeQuery($event, $connection)
    {
        $this->_profiler->startProfile($connection->getSQLStatement());
    }
    public function afterQuery($event, $connection)
    {
        $this->_logger->log($connection->getSQLStatement(), \Phalcon\Logger::INFO);
        $this->_profiler->stopProfile();
    }
    public function getProfiler()
    {
        return $this->_profiler;
    }
}

<?php
$eventsManager = new \Phalcon\Events\Manager();
//Create a database listener
$dbListener = new MyDbListener()
//Listen all the database events
$eventsManager->attach('db', $dbListener);
$connection = new \Phalcon\Db\Adapter\Pdo\Mysql(array(
    "host" => "localhost",
    "username" => "root",
    "password" => "secret",
    "dbname" => "invo"
));
//Assign the eventsManager to the db adapter instance
$connection->setEventsManager($eventsManager);
//Send a SQL command to the database server
$connection->query("SELECT * FROM products p WHERE p.status = 1");

foreach($dbListener->getProfiler()->getProfiles() as $profile){
    echo "SQL Statement: ", $profile->getSQLStatement(), "\n";
    echo "Start Time: ", $profile->getInitialTime(), "\n"
    echo "Final Time: ", $profile->getFinalTime(), "\n";
    echo "Total Elapsed Time: ", $profile->getTotalElapsedSeconds(), "\n";
}
------------

例二:
class MyComponent implements \Phalcon\Events\EventsAwareInterface
{
    protected $_eventsManager;
    public function setEventsManager($eventsManager)
    {
        $this->_eventsManager = $eventsManager;
    }
    public function getEventsManager()
    {
        return $this->_eventsManager
    }
    public function someTask()
    {
        $this->_eventsManager->fire("my-component:beforeSomeTask", $this);

        // do some task

        $this->_eventsManager->fire("my-component:afterSomeTask", $this);
    }
}
class SomeListener
{
    public function beforeSomeTask($event, $myComponent)
    {
        echo "Here, beforeSomeTask\n";
    }
    public function afterSomeTask($event, $myComponent)
    {
        echo "Here, afterSomeTask\n";
    }
}
<?php

//Create an Events Manager
$eventsManager = new Phalcon\Events\Manager();
//Create the MyComponent instance
$myComponent = new MyComponent();
//Bind the eventsManager to the instance
$myComponent->setEventsManager($myComponent);
//Attach the listener to the EventsManager
$eventsManager->attach('my-component', new SomeListener());
//Execute methods in the component
$myComponent->someTask();
--------------

如果监听器只对一个特定类型的事件感兴趣，你可以直接绑定：
<?php
//The handler will only be executed if the event triggered is "beforeSomeTask"
$eventManager->attach('my-component:beforeSomeTask', function($event, $component) {
    //...
});
------------

许多监听器可能会添加相同的事件，这意味着，对于同类类型的事件，许多监听器都会被触发,一些事件可以被撤销
$eventsManager->attach('db', function($event, $connection){
    //We stop the event if it is cancelable
    if ($event->isCancelable()) {
        //Stop the event, so other listeners will not be notified about this
        $event->stop();
    }
    //...
});
------------

事件默认是可取消的,你可以使用fire方法传递第四个参数，值为”false”，以达到不可取消的目的
$eventsManager->fire("my-component:afterSomeTask", $this, $extraData, false);







