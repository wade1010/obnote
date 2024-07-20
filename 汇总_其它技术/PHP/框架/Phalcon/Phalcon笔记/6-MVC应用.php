<?php

多模块
程序的文件结构类似如下：
multiple/
  apps/
    frontend/
       controllers/
       models/
       views/
       Module.php
    backend/
       controllers/
       models/
       views/
       Module.php
  public/
    css/
    img/
    js/
	
Module.php是每个Module特定的设置：
<?php
namespace Multiple\Backend;
use Phalcon\Mvc\ModuleDefinitionInterface;
class Module implements ModuleDefinitionInterface
{
    /**
     * Register a specific autoloader for the module
     */
    public function registerAutoloaders()
    {
        $loader = new \Phalcon\Loader();

        $loader->registerNamespaces(
            array(
                'Multiple\Backend\Controllers' => '../apps/backend/controllers/',
                'Multiple\Backend\Models'      => '../apps/backend/models/',
            )
        );
        $loader->register();
    }
    /**
     * Register specific services for the module
     */
    public function registerServices($di)
    {
        //Registering a dispatcher
        $di->set('dispatcher', function() {
            $dispatcher = new \Phalcon\Mvc\Dispatcher();
            $dispatcher->setDefaultNamespace("Multiple\Backend\Controllers\\");
            return $dispatcher;
        });
        //Registering the view component
        $di->set('view', function() {
            $view = new \Phalcon\Mvc\View();
            $view->setViewsDir('../apps/backend/views/');
            return $view;
        });
    }
}
一个特殊的引导文件，用以载入 multi-module MVC 结构：
<?php
$di = new \Phalcon\DI\FactoryDefault();
//Specify routes for modules
$di->set('router', function () {

    $router = new \Phalcon\Mvc\Router();

    $router->setDefaultModule("frontend");

    $router->add(
        "/login",
        array(
            'module'     => 'backend',
            'controller' => 'login',
            'action'     => 'index',
        )
    );

    $router->add(
        "/admin/products/:action",
        array(
            'module'     => 'backend',
            'controller' => 'products',
            'action'     => 1,
        )
    );

    $router->add(
        "/products/:action",
        array(
            'controller' => 'products',
            'action'     => 1,
        )
    );

    return $router;

});
try {
    //Create an application
    $application = new \Phalcon\Mvc\Application();
    $application->setDI($di);

    // Register the installed modules
    $application->registerModules(
        array(
            'frontend' => array(
                'className' => 'Multiple\Frontend\Module',
                'path'      => '../apps/frontend/Module.php',
            ),
            'backend'  => array(
                'className' => 'Multiple\Backend\Module',
                'path'      => '../apps/backend/Module.php',
            )
        )
    );
    //Handle the request
    echo $application->handle()->getContent();

} catch(Phalcon\Exception $e){
    echo $e->getMessage();
}

如果你想把配置文件完全写入到引导文件，你可以使用一个匿名函数的方式来注册 Module :
<?php
//Creating a view component
$view = new \Phalcon\Mvc\View();
// Register the installed modules
$application->registerModules(
    array(
        'frontend' => function($di) use ($view) {
            $di->setShared('view', function() use ($view) {
                $view->setViewsDir('../apps/frontend/views/');
                return $view;
            });
        },
        'backend' => function($di) use ($view) {
            $di->setShared('view', function() use ($view) {
                $view->setViewsDir('../apps/frontend/views/');
                return $view;
            });
        }
    )
);
--------------

应用事件
Event Name				Triggered
beforeStartModule		Before initialize a module, only when modules are registered
afterStartModule		After initialize a module, only when modules are registered
beforeHandleRequest		Before execute the dispatch loop
afterHandleRequest		After execute the dispatch loop
下面的示例演示如何在此组件上添加监听器：
<?php
$eventsManager = new Phalcon\Events\Manager();
$application->setEventsManager($eventsManager);
$eventsManager->attach(
    "application",
    function($event, $application) {
        // ...
    }
);
