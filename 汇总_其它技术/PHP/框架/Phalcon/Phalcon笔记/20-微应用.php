<?php

创建微应用:
$app = new Phalcon\Mvc\Micro();
----------

定义路由:
$app->get('/say/hello/{name}', function ($name) {
    echo "<h1>Hello! $name</h1>";
});
// With a method in an object
$myController = new MyController();
$app->get('/say/hello/{name}', array($myController, "someAction"));

//Matches if the HTTP method is GET or POST
$app->map('/repos/store/refs')->via(array('GET', 'POST'));
-----------

带参数的路由:
//This route have two parameters and each of them have a format
$app->get('/posts/{year:[0-9]+}/{title:[a-zA-Z\-]+}', function ($year, $title) {
    echo "<h1>Title: $title</h1>";
    echo "<h2>Year: $year</h2>";
});
--------------

内部操作服务器响应:
$app->get('/show/data', function () use ($app) {
    //Set the Content-Type header
    $app->response->setContentType('text/plain')->sendHeaders();
    //Print a file
    readfile("data.txt");
	
	// 重定向
	$app->response->redirect("new/welcome");
});
----------

也可以调用注入器的服务,比如配置
$di = new \Phalcon\DI\FactoryDefault();
$di->set('config', function() {
    return new \Phalcon\Config\Adapter\Ini("config.ini");
});
$app = new Phalcon\Mvc\Micro();
$app->setDI($di);
$app->get('/', function () use ($app) {
    //Read a setting from the config
    echo $app->config->app_name;
});
$app->post('/contact', function () use ($app) {
    $app->flash->success('Yes!, the contact was made!');
});
---------

NOT FOUND
$app->notFound(function () use ($app) {
    $app->response->setStatusCode(404, "Not Found")->sendHeaders();
    echo 'This is crazy, but this page was not found!';
});
-----------

在微应用里使用模型,就是加一个自动加载:
$loader = new \Phalcon\Loader();
$loader->registerDirs(array(
    __DIR__.'/models/'
))->register();
$app = new \Phalcon\Mvc\Micro();
$app->get('/products/find', function(){
    foreach (Products::find() as $product) {
        echo $product->name, '<br>';
    }
});
-----------

定义微应用事件:
//Create a events manager
$eventManager = \Phalcon\Events\Manager();
//Listen all the application events
$eventManager->attach('micro', function($event, $app) {
    if ($event->getType() == 'beforeExecuteRoute') {
        if ($app->session->get('auth') == false) {
            $app->flashSession->error("The user isn't authenticated");
            $app->response->redirect("/");
        }
    }
});
$app = new Phalcon\Mvc\Micro();
//Bind the events manager to the app
$app->setEventsManager($eventsManager);
(支持的事件有: beforeHandleRoute,beforeExecuteRoute,afterExecuteRoute,beforeNotFoundafterHandleRoute)
