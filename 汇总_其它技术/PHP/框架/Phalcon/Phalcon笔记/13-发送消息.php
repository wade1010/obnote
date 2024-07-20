<?php

可以选择直接发送或者发送到session . 这是由适配器决定的: 
Phalcon\Flash\Direct
Phalcon\Flash\Session
-------

使用方法(Usage)
通常情况下，消息发送这个服务被注册到服务容器中，如果你使用 Phalcon\DI\FactoryDefault,那么默认将自动注册 “flash”服务为类型 Phalcon\Flash\Direct：
//Set up the flash service
$di->set('flash', function() {
    return new \Phalcon\Flash\Direct();
});
-----

支持的类型有四种:
$this->flash->error("too bad! the form had errors");
$this->flash->success("yes!, everything went very smoothly");
$this->flash->notice("this a very important information");
$this->flash->warning("best check yo self, you're not looking too good.");

你也可以增加你自己的消息类型：
$this->flash->message("debug", "this is debug message, you don't say");
-----------

可以覆盖CSS:
//Register the flash service with custom CSS classes
$di->set('flash', function(){
    $flash = new \Phalcon\Flash\Direct(array(
        'error' => 'alert alert-error',
        'success' => 'alert alert-success',
        'notice' => 'alert alert-info',
    ));
    return $flash;
});
---------------

页面做了一个”forward”类型的重定向，那么就没有必要把消息存储到用户会话中，但是如果你做了一个HTTP重定向，你需要把消息存储到用户会话中：
 //Using direct flash
$this->flash->success("Your information were stored correctly!");
//Forward to the index action
return $this->dispatcher->forward(array("action" => "index"));

//Using session flash
$this->flashSession->success("Your information were stored correctly!");
//Make a full HTTP redirection
return $this->response->redirect("contact/index");
在这种情况下，你需要手工设置消息在相应视图中的显示位置：
<!-- app/views/contact/index.phtml -->
<p><?php $this->flashSession->output() ?></p>