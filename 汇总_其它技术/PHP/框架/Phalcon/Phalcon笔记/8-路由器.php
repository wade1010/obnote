<?php

router组件允许定义用户请求对应到哪个控制器或Action。router解析 URI 以确定这些信息。路由器有两种模式：MVC模式和匹配模式(match-only)。

定义路由
// Create the router
$router = new \Phalcon\Mvc\Router();
//Define a route
$router->add(
    "/admin/users/my-profile",
    array(
        "controller" => "users",
        "action"     => "profile",
    )
);
//Another route
$router->add(
    "/admin/users/change-password",
    array(
        "controller" => "users",
        "action"     => "changePassword",
    )
);
$router->handle();

更灵活的方式
// Create the router
$router = new \Phalcon\Mvc\Router();
//Define a route
$router->add(
    "/admin/:controller/a/:action/:params",
    array(
        "controller" => 1,
        "action"     => 2,
        "params"     => 3,
    )
);

add()方法接收一个模式，可选择使用预定义占位符和正则表达式修饰符。所有的路由模式必须以斜线字符（/）开始。正则表达式语法使用与 PCRE regular expressions 相同的语法。需要注意的是，不必要添加正则表达式分隔符。所有的路由模式是不区分大小写的。
支持以下占位符：
Placeholder			Regular Expression		Usage
/:module			/([a-zA-Z0-9_-]+)		Matches a valid module name with alpha-numeric characters only
/:controller		/([a-zA-Z0-9_-]+)		Matches a valid controller name with alpha-numeric characters only
/:action			/([a-zA-Z0-9_]+)		Matches a valid action name with alpha-numeric characters only
/:params			(/.*)*					Matches a list of optional words separated by slashes
/:namespace			/([a-zA-Z0-9_-]+)		Matches a single level namespace name
/:int				/([0-9]+)				Matches an integer parameter

Parameters with Names
$router->add(
    "/news/([0-9]{4})/([0-9]{2})/([0-9]{2})/:params",
    array(
            "controller" => "posts",
            "action"     => "show",
            "year"       => 1, // ([0-9]{4})
            "month"      => 2, // ([0-9]{2})
            "day"        => 3, // ([0-9]{2})
            "params"     => 4, // :params
    )
);
在控制器内部，可以通过以下方式访问这些参数：
// Return "year" parameter
$year = $this->dispatcher->getParam("year");
// Return "month" parameter
$month = $this->dispatcher->getParam("month");
// Return "day" parameter
$day = $this->dispatcher->getParam("day");

Short Syntax
// Short form
$router->add("/posts/{year:[0-9]+}/{title:[a-z\-]+}", "Posts::show");
// Array form:
$router->add(
    "/posts/([0-9]+)/([a-z\-]+)",
    array(
       "controller" => "posts",
       "action"     => "show",
       "year"       => 1,
       "title"      => 2,
    )
);

Routing to Modules
$router = new Phalcon\Mvc\Router(false);
$router->add('/:module/:controller/:action/:params', array(
    'module' => 1,
    'controller' => 2,
    'action' => 3,
    'params' => 4
));

也可绑定到特定的命名空间上：
$router->add("/:namespace/login", array(
    'namespace' => 1,
    'controller' => 'login',
    'action' => 'index'
));

controller也可指定全称：
$router->add("/login", array(
    'controller' => 'Backend\Controllers\Login',
    'action' => 'index'
));

有时候，我们想要限制到一个特定的HTTP方法，比如创建一个RESTful的应用程序时：
// This route only will be matched if the HTTP method is GET
$router->addGet("/products/edit/{id}", "Posts::edit");
// This route only will be matched if the HTTP method is POST
$router->addPost("/products/save", "Posts::save");
// This route will be matched if the HTTP method is POST or PUT
$router->add("/products/update")->via(array("POST", "PUT"));
------------

Naming Routes
每个被添加的路由都存储到对象 Phalcon\Mvc\Router\Route 中，这个类封装了路由的细节。例如，我们可以给应用程序中的路由设置一个唯一的名称。如果你想创建URLs,这将非常有用。
$route = $router->add("/posts/{year}/{title}", "Posts::show");
$route->setName("show-posts");
//or just
$router->add("/posts/{year}/{title}", "Posts::show")->setName("show-posts");
然后，我们可以使用 Phalcon\Mvc\Url 组件通过路由的名称创建一个路由：
// returns /posts/2012/phalcon-1-0-released
$url->get(array("for" => "show-posts", "year" => "2012", "title" => "phalcon-1-0-released"));
----------

用法示例
下面是自定义路由的例子：
// matches "/system/admin/a/edit/7001"
$router->add(
    "/system/:controller/a/:action/:params",
    array(
        "controller" => 1,
        "action"     => 2,
        "params"     => 3,
    )
);
// matches "/es/news"
$router->add(
    "/([a-z]{2})/:controller",
    array(
        "controller" => 2,
        "action"     => "index",
        "language"   => 1,
    )
);
// matches "/es/news"
$router->add(
    "/{language:[a-z]{2}}/:controller",
    array(
        "controller" => 2,
        "action"     => "index",
    )
);
// matches "/admin/posts/edit/100"
$router->add(
    "/admin/:controller/:action/:int",
    array(
        "controller" => 1,
        "action"     => 2,
        "id"         => 3,
    )
);
// matches "/posts/2010/02/some-cool-content"
$router->add(
    "/posts/([0-9]{4})/([0-9]{2})/([a-z\-]+)",
    array(
        "controller" => "posts",
        "action"     => "show",
        "year"       => 1,
        "month"      => 2,
        "title"      => 4,
    )
);
// matches "/manual/en/translate.adapter.html"
$router->add(
    "/manual/([a-z]{2})/([a-z\.]+)\.html",
    array(
        "controller" => "manual",
        "action"     => "show",
        "language"   => 1,
        "file"       => 2,
    )
);
// matches /feed/fr/le-robots-hot-news.atom
$router->add(
    "/feed/{lang:[a-z]+}/{blog:[a-z\-]+}\.{type:[a-z\-]+}",
    "Feed::get"
);
---------------

Default Behavior
Phalcon\Mvc\Router 有一个默认提供了一个非常简单的路由，总是匹配这样的模式：/:controller/:action/:params 。
如果你不想在应用程序中使用路由的默认行为，你可以创建一个路由，并把false参数传递给它：
// Create the router without default routes
$router = new \Phalcon\Mvc\Router(false);
-------------

Setting default paths
你可以对module,controller,action设置默认值，当在路由中找不到路径时，它将自动填充它：
//Individually
$router->setDefaultController("index");
$router->setDefaultAction("index");
//Using an array
$router->setDefaults(array(
    "controller" => "index",
    "action" => "index"
));
