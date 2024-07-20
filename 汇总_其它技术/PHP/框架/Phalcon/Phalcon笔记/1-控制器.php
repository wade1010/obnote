<?php

初始化控制器
class PostsController extends \Phalcon\Mvc\Controller
{
    public $settings;
    public function initialize() 
	{
        $this->settings = array(
            "mySetting" => "value"
        );
    }
}
----------

访问注入服务:
比如我们注册了一个storage DI服务,我们就可以这样使用:
public function saveAction()
{
	//Injecting the service by just accessing the property with the same name
	$this->storage->save('/some/file');
	//Accessing the service from the DI
	$this->di->get('storage')->save('/some/file');
	//Another way to access the service using the magic getter
	$this->di->getStorage()->save('/some/file');
	//Another way to access the service using the magic getter
	$this->getDi()->getStorage()->save('/some/file');
}
-------------

请求,响应,回话, 跳转等都在相关专题里.这里不复述.
------------

在应用程序的控制器中经常会需要访问控制列表，多语言，缓存，模板引擎等。
在这种情况下，我们一般建议你创建一个 “base controller”,以防重复造轮子，保持代码 DRY .
----------

控制器本身也可以充当监听的身份，通过 dispatcher 事件，在控制器中实现 dispatcher的事件方法，控制器的方法名要与事件名相同。
class PostsController extends \Phalcon\Mvc\Controller
{
    public function beforeExecuteRoute($dispatcher)
    {
        // This is executed before every found action
        if ($dispatcher->getActionName() == 'save') {
            $this->flash->error("You don't have permission to save posts");
            return false;
        }
    }
    public function afterExecuteRoute($dispatcher)
    {
        // Executed after every found action
    }
}
