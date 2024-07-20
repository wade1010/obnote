<?php

可以使用别名
<?php use \Phalcon\Tag as Tag; ?>
----------

文档类型
Phalcon中，使用 Phalcon\Tag::setDoctype() 助手可以设置HTML文档类型
<?php \Phalcon\Tag::setDoctype(\Phalcon\Tag::HTML401_STRICT); ?>	// 设置
<?= \Phalcon\Tag::getDoctype() ?>		// 获取
-----------

生成链接
<!-- for the default route -->
<?= Tag::linkTo("products/search") ?>
<!-- for a named route -->
<?= Tag::linkTo(array('for' => 'show-product', 'id' => 123, 'name' => 'carrots')) ?>
其实，文档中所有的URLs都是通过组件 Phalcon\Mvc\Url 生成的。
----------

创建表单
<?php use \Phalcon\Tag as Tag; ?>
<!-- Sending the form by method POST -->
<?= Tag::form("products/search") ?>
    <label for="q">Search:</label>
    <?= Tag::textField("q") ?>
    <?= Tag::submitButton("Search") ?>
</form>
<!-- Specyfing another method or attributes for the FORM tag -->
<?= Tag::form(array("products/search", "method" => "get")); ?>
    <label for="q">Search:</label>
    <?= Tag::textField("q"); ?>
    <?= Tag::submitButton("Search"); ?>
</form>
--------------

创建表单元素
<?php echo Phalcon\Tag::textField(array(
    "parent_id",
    "value"=> "5"
)) ?>
<?php echo Phalcon\Tag::textArea(array(
    "comment",
    "This is the content of the text-area",
    "cols" => "6",
    "rows" => 20
)) ?>
<?php echo Phalcon\Tag::passwordField("password") ?>
<?php echo Phalcon\Tag::hiddenField(array(
    "parent_id",
    "value"=> "5"
)) ?>
-----------

生成选择菜单
// Using data from a resultset
echo Phalcon\Tag::select(
    array(
        "productId",
        Products::find("type = 'vegetables'"),
        "using" => array("id", "name")
    )
);
// Using data from an array
echo Phalcon\Tag::selectStatic(
    array(
        "status",
        array(
            "A" => "Active",
            "I" => "Inactive",
        )
    )
);

有时，为了显示的需要，你想要添加一个空值的option项：
// Creating a Select Tag with an empty option
echo Phalcon\Tag::select(
    array(
        "productId",
        Products::find("type = 'vegetables'"),
        "using" => array("id", "name")
    ),
    'useEmpty' => true,
	'emptyText' => 'Please, choose one...',
    'emptyValue' => '@'
);
---------

HTML属性
所有的助手都接收一个数组，数组的第一个参数作为名称，其他的用于生成额外的HTML属性。
<?php \Phalcon\Tag::textField(
    array(
        "price",
        "size"        => 20,
        "maxlength"   => 30,
        "placeholder" => "Enter a price",
    )
) ?>
会产生下面的HTML代码：
<input type="text" name="price" id="price" size="20" maxlength="30" placeholder="Enter a price" />
------------

设置辅助值
在视图中对表单元素设置特定值是一个良好的用户体验，你可以在控制器中通过 Phalcon\Tag::setDefaultValue() 设置默认值。
class ProductsController extends \Phalcon\Mvc\Controller
{
    public function indexAction()
    {
        Phalcon\Tag::setDefaultValue("color", "Blue");
    }
}
在视图文件中，使用 selectStatic 助手提供一些预设值，名称为 “color”：
echo \Phalcon\Tag::selectStatic(
    array(
        "color",
        array(
            "Yellow" => "Yellow",
            "Blue"   => "Blue",
            "Red"    => "Red"
        )
    )
);
下面是生成的HTML代码，同时值为 “Blue” 的option选项被默认选中：
<select id="color" name="color">
    <option value="Yellow">Yellow</option>
    <option value="Blue" selected="selected">Blue</option>
    <option value="Red">Red</option>
</select>
---------------

动态标题
Phalcon\Tag 助手还提供了可以在控制器中动态修改标题的功能。下面的例子演示了这一点：
class PostsController extends \Phalcon\Mvc\Controller
{
    public function initialize()
    {
        Phalcon\Tag::setTitle(" Your Website");
    }
    public function indexAction()
    {
        Phalcon\Tag::prependTitle("Index of Posts - ");
    }
}
<html>
    <head>
        <title><?php \Phalcon\Tag::getTitle(); ?></title>
    </head>
</html>
下面是生成的HTML代码：
<html>
    <head>
        <title>Index of Posts - Your Website</title>
    </head>
</html>
------------

静态资源助手
Images
// Generate <img src="/your-app/img/hello.gif">
echo \Phalcon\Tag::image("img/hello.gif");
// Generate <img alt="alternative text" src="/your-app/img/hello.gif">
echo \PhalconTag::image(
    array(
       "img/hello.gif",
       "alt" => "alternative text"
    )
);

Stylesheets
// Generate <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Rosario" type="text/css">
echo \Phalcon\Tag::stylesheetLink("http://fonts.googleapis.com/css?family=Rosario", false);
// Generate <link rel="stylesheet" href="/your-app/css/styles.css" type="text/css">
echo \Phalcon\Tag::stylesheetLink("css/styles.css");

Javascript
// Generate <script src="http://localhost/javascript/jquery.min.js" type="text/javascript"></script>
echo \Phalcon\Tag::javascriptInclude("http://localhost/javascript/jquery.min.js", false);
// Generate <script src="/your-app/javascript/jquery.min.js" type="text/javascript"></script>
echo \Phalcon\Tag::javascriptInclude("javascript/jquery.min.js");
--------------

TAG支持的方法有:
    [0] => setDI
    [1] => getDI
    [2] => getUrlService
    [3] => getEscaperService
    [4] => getAutoescape
    [5] => setAutoescape
    [6] => setDefault
    [7] => setDefaults
    [8] => displayTo
    [9] => hasValue
    [10] => getValue
    [11] => resetInput
    [12] => linkTo
    [13] => textField
    [14] => numericField
    [15] => emailField
    [16] => dateField
    [17] => passwordField
    [18] => hiddenField
    [19] => fileField
    [20] => checkField
    [21] => radioField
    [22] => imageInput
    [23] => submitButton
    [24] => selectStatic
    [25] => select
    [26] => textArea
    [27] => form
    [28] => endForm
    [29] => setTitle
    [30] => appendTitle
    [31] => prependTitle
    [32] => getTitle
    [33] => stylesheetLink
    [34] => javascriptInclude
    [35] => image
    [36] => friendlyTitle
    [37] => setDocType
    [38] => getDocType
    [39] => tagHtml
    [40] => tagHtmlClose