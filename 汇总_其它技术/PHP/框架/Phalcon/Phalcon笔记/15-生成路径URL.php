<?php

获取基础路径
$url = new Phalcon\Mvc\Url();
echo $url->getBaseUri();
-------

默认情况下，Phalcon 会自动检测应用程序的baseUri.但如果你想提高应用程序性能的话，最好还是手工设置：
$url = new Phalcon\Mvc\Url();
$url->setBaseUri('/invo/');
---------

服务容器
$di->set('url', function(){
    $url = new Phalcon\Mvc\Url();
    $url->setBaseUri('/invo/');
    return $url;
});
-----------

$route->add('/blog/{$year}/{month}/{title}', array(
    'controller' => 'posts',
    'action' => 'show'
))->setName('show-post');

生成URL还可以通过以下方式：
//This produces: /blog/2012/01/some-blog-post
$url->get(array(
    'for' => 'show-post',
    'year' => 2012,
    'month' => '01',
    'title' => 'some-blog-post'
));
--------------

不使用重写规则
$url = new Phalcon\Mvc\Url();
//Pass the URI in $_GET["_url"]
$url->setBaseUri('/invo/index.php?_url=/');
//This produce: /invo/index.php?_url=/products/save
echo $url->get("products/save");


