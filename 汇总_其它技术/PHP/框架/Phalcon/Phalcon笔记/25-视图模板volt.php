<?php

注册DI:
$di->set('view', function() {
    $view = new \Phalcon\Mvc\View();
    $view->setViewsDir('../app/views/');
    $view->registerEngines(array(
        ".volt" => 'Phalcon\Mvc\View\Engine\Volt'
    ));
    return $view;
});
----------------

{% .. %}之中包含表达式, {{ .. }} 之中包含输出结果, {# ... #}之中包含注释, 可以多行
----------------

controller里可以这样赋值:
public function showAction() {
	$post = Post::findFirst();
	$this->view->title = $post->title;
	$this->view->post = $post;
	$this->view->menu = Menu::find();
}
----------------

对象: {{ post.title }} {# for $post->title #} 
数组: {{ post['title'] }} {# for $post['title'] #}
----------------

变量还可以使用filter, 使用|:
{{ post.title|e }}
{{ post.content|striptags }}
{{ name|capitalize|trim }}
{{ "<h1>Hello<h1>"|escape }}
{{ "   hello   "|trim }}
{{ "some\ntext"|nl2br }}
{% set keys=['first': 1, 'second': 2, 'third': 3]|keys %}
过滤规则有:
e 				Applies Phalcon\Escaper->escapeHtml to the value 
escape 			Applies Phalcon\Escaper->escapeHtml to the value 
escape_css 		Applies Phalcon\Escaper->escapeCss to the value 
escape_js 		Applies Phalcon\Escaper->escapeJs to the value 
escape_attr 	Applies Phalcon\Escaper->escapeHtmlAttr to the value 
trim 			Applies the trim PHP function to the value. Removing extra spaces 
left_trim 		Applies the ltrim PHP function to the value. Removing extra spaces 
right_trim 		Applies the rtrim PHP function to the value. Removing extra spaces 
striptags 		Applies the striptags PHP function to the value. Removing HTML tags 
slashes 		Applies the slashes PHP function to the value. Escaping values 
stripslashes 	Applies the stripslashes PHP function to the value. Removing escaped quotes 
capitalize 		Capitalizes a string by applying the ucwords PHP function to the value 
lower 			Change the case of a string to lowercase 
upper 			Change the case of a string to uppercase 
length 			Counts the string length or how many items are in an array or object 
nl2br 			Changes newlines \n by line breaks (<br />). Uses the PHP function nl2br 
sort 			Sorts an array using the PHP function asort 
keys 			Returns the array keys using array_keys 
join 			Joins the array parts using a separator join 
format 			Formats a string using sprintf. 
json_encode 	Converts a value into its JSON representation 
json_decode 	Converts a value from its JSON representation to a PHP representation 
abs 			Applies the abs PHP function to a value. 
url_encode 		Applies the urlencode PHP function to the value 
default 		Sets a default value in case that the evaluated expression is empty (is not set or evaluates to a falsy value) 
convert_encoding Converts a string from one charset to another 
----------------

FOR循环:
{% set numbers = ['one': 1, 'two': 2, 'three': 3] %}
{% for name, value in numbers %}
	{% if index is even %}
        {% continue %}
    {% endif %}
	Name: {{ name }} Value: {{ value }}
{% endfor %}

{% for value in numbers if value < 2 %}
  Value: {{ value }}
{% endfor %}

{% for name, value in numbers if name != 'two' %}
  Name: {{ name }} Value: {{ value }}
{% endfor %}

带else的for循环是指结果集为空的情况下执行else后的语句:
{% for robot in robots %}
    Robot: {{ robot.name|e }} Part: {{ part.name|e }} <br/>
{% else %}
    There are no robots to show
{% endfor %}
----------------

IF分支:
{% if robot.type == "cyborg" %}
<li>{{ robot.name|e }}</li>
{% else %}
<li>{{ robot.name|e }} (not a cyborg)</li>
{% endif %}
----------------

循环上下文是一组特殊的变量,来控制FOR中的上下文：
{% for robot in robots %}
    {% if loop.first %}
        <table>
        <tr>
            <th>#</th>
            <th>Id</th>
            <th>Name</th>
        </tr>
    {% endif %}
        <tr>
            <td>{{ loop.index }}</td>
            <td>{{ robot.id }}</td>
            <td>{{ robot.name }}</td>
        </tr>
    {% if loop.last %}
        </table>
    {% endif %}
{% endfor %}
支持的loop上下文有:
loop.index 		The current iteration of the loop. (1 indexed) 
loop.index0 	The current iteration of the loop. (0 indexed) 
loop.revindex 	The number of iterations from the end of the loop (1 indexed) 
loop.revindex0 	The number of iterations from the end of the loop (0 indexed) 
loop.first 		True if in the first iteration. 
loop.last 		True if in the last iteration. 
loop.length 	The number of items to iterate 
----------------

变量赋值:
{% set name = robot.name %}
{% set fruits = ['Apple', 'Banana', 'Orange'], name = robot.name, active = true %}
{% set price += 100.00 %}
----------------

表达式:
打印: 	{{ (1 + 1) * 2 }}
不打印: {% do (1 + 1) * 2 %}
----------------

数组:
{{ ['Apple', 1, 2.5, false, null] }}
{{ [[1, 2], [3, 4], [5, 6]] }}
{{ ['first': 1, 'second': 4/2, 'third': '3'] }}
{% set myHash = {'first': 1, 'second': 4/2, 'third': '3'} %}
----------------

测验:
{% if position is odd %}
	{{ name }}
{% endif %}
支持的关键字:
defined 	Checks if a variable is defined (isset) 
empty 		Checks if a variable is empty 
even 		Checks if a numeric value is even 
odd 		Checks if a numeric value is odd 
numeric 	Checks if value is numeric 
scalar 		Checks if value is scalar (not an array or object) 
iterable 	Checks if a value is iterable. Can be traversed by a “for” statement 
divisibleby Checks if a value is divisible by other value 
sameas 		Checks if a value is identical to other value 
type 		Checks if a value is of the specified type 
----------------

宏命令:
宏命令类似于PHP的函数:
{%- macro related_bar(related_links) %}
    <ul>
        {%- for rellink in related_links %}
            <li><a href="{{ url(link.url) }}" title="{{ link.title|striptags }}">{{ link.text }}</a></li>
        {%- endfor %}
    </ul>
{%- endmacro %}
{# Print related links #}
{{ related_bar(links) }}

{%- macro error_messages(message, field, type) %}
    <div>
        <span class="error-type">{{ type }}</span>
        <span class="error-field">{{ field }}</span>
        <span class="error-message">{{ message }}</span>
    </div>
{%- endmacro %}
{# Call the macro #}
{{ error_messages('type': 'Invalid', 'message': 'The name is invalid', 'field': 'name') }}

{%- macro my_input(name, class) %}
    {% return text_field(name, 'class': class) %}
{%- endmacro %}
{# Call the macro #}
{{ '<p>' ~ my_input('name', 'input-text') ~ '</p>' }}
--------------------

使用Tag Helpers:
{{ javascript_include("js/jquery.js") }}
{{ form('products/save', 'method': 'post') }}
    <label>Name</label>
    {{ text_field("name", "size": 32) }}
    <label>Type</label>
    {{ select("type", productTypes, 'using': ['id', 'name']) }}
    {{ submit_button('Send') }}
</form>
支持的标签:
Phalcon\Tag::linkTo 			link_to 
Phalcon\Tag::textField 			text_field 
Phalcon\Tag::passwordField 		password_field 
Phalcon\Tag::hiddenField 		hidden_field 
Phalcon\Tag::fileField 			file_field 
Phalcon\Tag::checkField 		check_field 
Phalcon\Tag::radioField 		radio_field 
Phalcon\Tag::dateField 			date_field 
Phalcon\Tag::emailField 		email_field 
Phalcon\Tag::numberField 		number_field 
Phalcon\Tag::submitButton 		submit_button 
Phalcon\Tag::selectStatic 		select_static 
Phalcon\Tag::select 			select 
Phalcon\Tag::textArea 			text_area 
Phalcon\Tag::form 				form 
Phalcon\Tag::endForm 			end_form 
Phalcon\Tag::getTitle 			get_title 
Phalcon\Tag::stylesheetLink 	stylesheet_link 
Phalcon\Tag::javascriptInclude 	javascript_include 
Phalcon\Tag::image 				image 
Phalcon\Tag::friendlyTitle 		friendly_title 
------------------

内置函数:
content 		Includes the content produced in a previous rendering stage 
get_content 	Same as ‘content’ 
partial 		Dynamically loads a partial view in the current template 
super 			Render the contents of the parent block 
time 			Calls the PHP function with the same name 
date 			Calls the PHP function with the same name 
dump 			Calls the PHP function ‘var_dump’ 
version 		Returns the current version of the framework 
constant 		Reads a PHP constant 
url 			Generate a URL using the ‘url’ service 
---------------------

视图集成:
<div id="footer">{{ partial("partials/footer", ['links': $links]) }}</div>
<div id="footer">{% include "partials/footer" with ['links': links] %}</div>
-------------------

模板继承:
{# templates/base.volt #}
<!DOCTYPE html>
<html>
    <head>
        {% block head %}
            <link rel="stylesheet" href="style.css" />
        {% endblock %}
        <title>{% block title %}{% endblock %} - My Webpage</title>
    </head>
    <body>
        <div id="content">{% block content %}{% endblock %}</div>
        <div id="footer">
            {% block footer %}&copy; Copyright 2012, All rights reserved.{% endblock %}
        </div>
    </body>
</html>

{% extends "templates/base.volt" %}
{% block title %}Index{% endblock %}
{% block head %}<style type="text/css">.important { color: #336699; }</style>{% endblock %}
{% block content %}
    <h1>Index</h1>
    <p class="important">Welcome on my awesome homepage.</p>
{% endblock %}
--------------------------

多重继承:
{# main.volt #}
<!DOCTYPE html>
<html>
    <head>
        <title>Title</title>
    </head>
    <body>
        {% block content %}{% endblock %}
    </body>
</html>

{# layout.volt #}
{% extends "main.volt" %}
{% block content %}
    <h1>Table of contents</h1>
{% endblock %}

{# index.volt #}
{% extends "layout.volt" %}
{% block content %}
    {{ super() }}
    <ul>
        <li>Some option</li>
        <li>Some other option</li>
    </ul>
{% endblock %}
-------------------

Autoescape mode:
{% autoescape true %}
    Autoescaped: {{ robot.name }}
    {% autoescape false %}
        No Autoescaped: {{ robot.name }}
    {% endautoescape %}
{% endautoescape %}
--------------

配置VOLT:
//Register Volt as a service
$di->set('voltService', function($view, $di) {
    $volt = new Volt($view, $di);
    $volt->setOptions(array(
        "compiledPath" => "../app/compiled-templates/",
        "compiledExtension" => ".compiled"
    ));
    return $volt;
});

//Register Volt as template engine
$di->set('view', function() {
    $view = new View();
    $view->setViewsDir('../app/views/');
    $view->registerEngines(array(
        ".volt" => 'voltService'
    ));
    return $view;
});
支持的参数:
Option 				Description 																													Default 
compiledPath 		A writable path where the compiled PHP templates will be placed 																./ 
compiledExtension 	An additional extension appended to the compiled PHP file 																		.php 
compiledSeparator 	Volt replaces the directory separators / and \ by this separator in order to create a single file in the compiled directory 	%% 
stat 				Whether Phalcon must check if exists differences between the template file and its compiled path 								true 
compileAlways 		Tell Volt if the templates must be compiled in each request or only when they change 											false 
prefix 				Allows to prepend a prefix to the templates in the compilation path 															null 
-------------

添加函数:
$compiler->addFunction('dump', 'print_r');

$compiler->addFunction('widget', function($resolvedArgs, $exprArgs) {
    return 'MyLibrary\Widgets::get(' . $resolvedArgs . ')';
});

$compiler->addFunction('contains_text', function($resolvedArgs, $exprArgs) {
    if (function_exists('mb_stripos')) {
        return 'mb_stripos(' . $resolvedArgs . ')';
    } else {
        return 'stripos(' . $resolvedArgs . ')';
    }
});
---------------

添加filter:
$compiler->addFilter('hash', 'md5');
$compiler->addFilter('capitalize', 'lcfirst');
-------------

缓存视图片段:
{% cache "sidebar" %}
    <!-- generate this content is slow so we are going to cache it -->
{% endcache %}

{# cache the sidebar by 1 hour #}
{% cache "sidebar" 3600 %}
    <!-- generate this content is slow so we are going to cache it -->
{% endcache %}
-------------------

注入DI:
{# Inject the 'flash' service #}
<div id="messages">{{ flash.output() }}</div>
-------------------






































