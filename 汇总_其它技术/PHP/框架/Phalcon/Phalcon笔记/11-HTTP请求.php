<?php

Phalcon\HTTP\Request 封装 信息的请求，允许你在一个面向对象的方法来访问它
// Getting a request instance
$request = new \Phalcon\Http\Request();
// Check whether the request was made with method POST
if ($request->isPost() == true) {
    // Check whether the request was made with Ajax
    if ($request->isAjax() == true) {
        echo "Request was made using POST and AJAX";
    }
}
----------

获取数据
Phalcon\HTTP\Request 允许你访问$_REQUEST, $_GET 和 $_POST 这些数组中的值，并且可以通过”filter” (by default Phalcon\Filter) 服务对他们进行过滤或消毒
// Manually applying the filter
$filter = new Phalcon\Filter();
$email  = $filter->sanitize($_POST["user_email"], "email");
// Manually applying the filter to the value
$filter = new Phalcon\Filter();
$email  = $filter->sanitize($request->getPost("user_email"), "email");
// Automatically applying the filter
$email = $request->getPost("user_email", "email");
// Setting a default value if the param is null
$email = $request->getPost("user_email", "email", "some@example.com");
// Setting a default value if the param is null without filtering
$email = $request->getPost("user_email", null, "some@example.com");
----------

在控制器中使用request
$this->request->getPost("name");
-----------

文件上传
public function uploadAction()
{
	// Check if the user has uploaded files
	if ($this->request->hasFiles() == true) {
		// Print the real file names and sizes
		foreach ($this->request->getUploadedFiles() as $file) {
			//Print file details
			echo $file->getName(), " ", $file->getSize(), "\n";
			//Move the file into the application
			$file->moveTo('files/');
		}
	}
}
----------------

working with header
// get the Http-X-Requested-With header
$requestedWith = $response->getHeader("X_REQUESTED_WITH");
if ($requestedWith == "XMLHttpRequest") {
    echo "The request was made with Ajax";
}
// Same as above
if ($request->isAjax()) {
    echo "The request was made with Ajax";
}
// Check the request layer 
if ($request->isSecureRequest() == true) {
    echo "The request was made using a secure layer";
}
// Get the servers's ip address. ie. 192.168.0.100
$ipAddress = $request->getServerAddress();
// Get the client's ip address ie. 201.245.53.51
$ipAddress = $request->getClientAddress();
// Get the User Agent (HTTP_USER_AGENT)
$userAgent = $request->getUserAgent();
// Get the best acceptable content by the browser. ie text/xml
$contentType = $request->getAcceptableContent();
// Get the best charset accepted by the browser. ie. utf-8
$charset = $request->getBestCharset();
// Get the best language accepted configured in the browser. ie. en-us
$language = $request->getBestLanguage();
--------------

request 所有的方法:
array (
  0 => 'setDI',
  1 => 'getDI',
  2 => 'get',
  3 => 'getPost',
  4 => 'getQuery',
  5 => 'getServer',
  6 => 'has',
  7 => 'hasPost',
  8 => 'hasQuery',
  9 => 'hasServer',
  10 => 'getHeader',
  11 => 'getScheme',
  12 => 'isAjax',
  13 => 'isSoapRequested',
  14 => 'isSecureRequest',
  15 => 'getRawBody',
  16 => 'getJsonRawBody',
  17 => 'getServerAddress',
  18 => 'getServerName',
  19 => 'getHttpHost',
  20 => 'getClientAddress',
  21 => 'getMethod',
  22 => 'getUserAgent',
  23 => 'isMethod',
  24 => 'isPost',
  25 => 'isGet',
  26 => 'isPut',
  27 => 'isPatch',
  28 => 'isHead',
  29 => 'isDelete',
  30 => 'isOptions',
  31 => 'hasFiles',
  32 => 'getUploadedFiles',
  33 => 'getHeaders',
  34 => 'getHTTPReferer',
  35 => 'getAcceptableContent',
  36 => 'getBestAccept',
  37 => 'getClientCharsets',
  38 => 'getBestCharset',
  39 => 'getLanguages',
  40 => 'getBestLanguage',
)

