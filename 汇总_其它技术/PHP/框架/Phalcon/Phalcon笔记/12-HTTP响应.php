<?php

//Getting a response instance
$response = new \Phalcon\Http\Response();
//Set status code
$response->setStatusCode(404, "Not Found");
//Set the content of the response
$response->setContent("Sorry, the page doesn't exist");
//Send response to the client
$response->send();
-----------

发送头信息
//Setting it by its name
$response->setHeader("Content-Type", "application/pdf");
$response->setHeader("Content-Disposition", 'attachment; filename="downloaded.pdf"');
//Setting a raw header
$response->setRawHeader("HTTP/1.1 200 OK");
-----------------

HTTP头部信息由 Phalcon\HTTP\Response\Headers 管理，这个类允许在向客户端发回数据前，向客户端发送HTTP头部信息：
//Get the headers bag
$headers = $response->getHeaders();
//Get a header by its name
$contentType = $response->getHeaders()->get("Content-Type");
---------------

重定向
//Making a redirection using the local base uri
$response->redirect("posts/index");
//Making a redirection to an external URL
$response->redirect("http://en.wikipedia.org", true);
//Making a redirection specifyng the HTTP status code
$response->redirect("http://www.example.com/new-location", true, 301);

---------------

重定向不会禁用视图组件。因此，如果你想从一个controller/action重定向到另一个controller/acton上，视图将正常显示。当然，你也可以使用 $this->view->disable() 禁用视图输出.
-------------

设置页面缓存
$expireDate = new DateTime();
$expireDate->modify('+2 months');
$response->setExpires($expireDate);

如果设置一个小于现在的时间,那么将总是刷新,没有缓存:
$expireDate->modify('-10 minutes');

Cache-Control
//Starting from now, cache the page for one day
$response->setHeader('Cache-Control', 'max-age=86400');
