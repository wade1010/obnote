<?php

Phalcon\Filter 组件提供了一组常用的用于过滤以及处理用户输入数据的助手工具。
$filter = new \Phalcon\Filter();
// returns "someone@example.com"
$filter->sanitize("some(one)@exa\mple.com", "email");
// returns "hello"
$filter->sanitize("hello<<", "string");
// returns "100019"
$filter->sanitize("!100a019", "int");
// returns "100019.01"
$filter->sanitize("!100a019.01a", "float");
-----------

你可以在控制器中访问 Phalcon\Filter 对象
// Sanitizing price from input
$price = $this->request->getPost("price", "double");
// Sanitizing post data
$email = $this->request->getPost("customerEmail", "email");
// Sanitizing params
$productId = $this->filter->sanitize($productId, "int"); 
---------

删除和修改
$filter = new \Phalcon\Filter();
// returns "Hello"
$filter->filter("<h1>Hello</h1>", "striptags");
// returns "Hello"
$filter->filter("  Hello   ", "trim");
---------

内置属性:
string		Strip tags
email		Remove all characters except letters, digits and !#$%&*+-/=?^_`{|}~@.[].
int			Remove all characters except digits, plus and minus sign.
float		Remove all characters except digits, dot, plus and minus sign.
alphanum	Remove all characters except [a-zA-Z0-9]
striptags	Applies the strip_tags function
trim		Applies the trim function
lower		Applies the strtolower function
upper		Applies the strtoupper function
-------

自定义filters:
$filter = new \Phalcon\Filter();
//Using an anonymous function
$filter->add('md5', function($value) {
    return preg_replace('/[^0-9a-f]/', '', $value);
});
//Sanitize with the "md5" filter
$filtered = $filter->sanitize($possibleMd5, "md5");

