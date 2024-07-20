<?php

对于这个URL: http://127.0.0.1/blog/posts/show/301 , 控制器是posts, action是show
那么视图组件自动找到相对应的三个视图文件是:
Name				File
Action View			app/views/posts/show.phtml
Controller Layout	app/views/layouts/posts.phtml	
Main Layout			app/views/index.phtml
------------

使用模板(Using Templates)
Templates也是视图文件的一部分，但他们是可共享的。他们作为控制器的布局文件，你必须把它们放到layouts目录下。
class PostsController extends \Phalcon\Mvc\Controller
{
    public function initialize()
    {
        $this->view->setTemplateAfter('common');
    }
}

<!-- app/views/layouts/common.phtml -->
<ul class="menu">
    <li><a href="/">Home</a></li>
    <li><a href="/articles">Articles</a></li>
    <li><a href="/contact">Contact us</a></li>
</ul>
<div class="content"><?php echo $this->getContent() ?></div>
----------

局部模板
局部模板文件是另一种打破视图渲染顺序的方式，它更易于管理，且可在应用程序中重复使用。
<?php $this->partial("shared/ad_banner") ?>
<h1>Robots</h1>
<p>Check out our specials for robots:</p>
...
<?php $this->partial("shared/footer") ?>
-------------

从控制器传值到视图
$this->view->setVar("posts", Posts:find());
-----------

控制显示层次
你可能需要在视图中控制显示的层次，PhalconMvc\View::setRenderLevel() 提供这种功能。
此方法可以从控制器或从上级视图层调用改变渲染过程。
class PostsController extends \Phalcon\Mvc\Controller
{
    public function findAction()
    {
        // This is an Ajax response so don't generate any kind of view
        $this->view->setRenderLevel(\Phalcon\Mvc\View::LEVEL_NO_RENDER);
        //...
    }
    public function showAction($postId)
    {
        // Shows only the view related to the action
        $this->view->setRenderLevel(\Phalcon\Mvc\View::LEVEL_ACTION_VIEW);
    }
}
支持的参数:
Class Constant			Description
LEVEL_NO_RENDER			Indicates to avoid generating any kind of presentation.
LEVEL_ACTION_VIEW		Generates the presentation to the view associated to the action.
LEVEL_BEFORE_TEMPLATE	Generates presentation templates prior to the controller layout.
LEVEL_LAYOUT			Generates the presentation to the controller layout.
LEVEL_AFTER_TEMPLATE	Generates the presentation to the templates after the controller layout.
LEVEL_MAIN_LAYOUT		Generates the presentation to the main layout. File views/index.phtml
----------

在视图中使用模型
<div class="categories">
<?php
foreach (Catergories::find("status = 1") as $category) {
   echo "<span class='category'>", $category->name, "</span>";
}
?>
</div>
虽然可以使用, 但不建议这么使用。因为它不可以在发生错误或异常的时候从一个控制流程跳转到另一个控制器。
------------

选择视图
你也可以通过 Phalcon\Mvc\View::pick() 方法改变显示流程：
public function listAction()
{
	// Pick "views-dir/products/search" as view to render
	$this->view->pick("products/search");
}
-------------

分段缓存
设置视图缓存的服务:
//Set the views cache service
$di->set('viewCache', function(){
    //Cache data for one day by default
    $frontCache = new Phalcon\Cache\Frontend\Output(array(
        "lifetime" => 86400
    ));
    //Memcached connection settings
    $cache = new Phalcon\Cache\Backend\Memcached($frontCache, array(
        "host" => "localhost",
        "port" => "11211"
    ));
    return $cache;
}, true);

几个例子:
public function showAction()
{
	//Cache the view using the default settings
	$this->view->cache(true);
}
public function downloadAction()
{
	//Passing a custom service
	$this->view->cache(
		array(
			"service"  => "myCache",
			"lifetime" => 86400,
			"key"      => "resume-cache",
		)
	);
}
public function indexAction()
{
	//Check if the cache with key "downloads" exists or has expired
	if ($this->view->getCache()->exists('downloads')) {
		//Query the latest downloads
		$latest = Downloads::find(array('order' => 'created_at DESC'));
		$this->view->setVar('latest', $latest);
	}
	//Enable the cache with the same key "downloads"
	$this->view->cache(array('key' => 'downloads'));
}
----------

禁止视图
以前我都是用exit, 其实提供了更好方法:
//Disable the view
$this->view->disable();
//The same
$this->view->setRenderLevel(Phalcon\Mvc\View::LEVEL_NO_RENDER);
--------

视图中使用模版引擎
关于这话题有兴趣可以看手册.这里不介绍.
---------

在视图中使用服务
如视图文件中包含”url”这个服务，你可以直接使用它：
<script type="text/javascript">
$.ajax({
    url: "<?php echo $this->url->get("cities/get") ?>"
})
.done(function() {
    alert("Done!");
});
</script>
------

独立的组件模式
$view = new \Phalcon\Mvc\View();
$view->setViewsDir("../app/views/");
// Passing variables to the views, these will be created as local variables
$view->setVar("someProducts", $products);
$view->setVar("someFeatureEnabled", true);
//Start the output buffering
$view->start();
//Render all the view hierarchy related to the view products/list.phtml
$view->render("products", "list");
//Finish the output buffering
$view->finish();
echo $view->getContent();
---------------

视图事件
Event Name				Triggered										Can stop operation?
beforeRender			Triggered before start the render process		Yes
beforeRenderView		Triggered before render an existing view		Yes
afterRenderView			Triggered after render an existing view			No
afterRender				Triggered after complete the render process		No

下面的示例演示如何将事件监听器绑定到组件：
$di->set('view', function() {
    //Create an event manager
    $eventsManager = new Phalcon\Events\Manager();
    //Attach a listener for type "view"
    $eventsManager->attach("view", function($event, $view) {
        echo $event->getType(), ' - ', $view->getActiveRenderPath(), PHP_EOL;
    });
    $view = new \Phalcon\Mvc\View();
    $view->setViewsDir("../app/views/");
    //Bind the eventsManager to the view component
    $view->setEventsManager($eventManagers);
    return $view;
});

