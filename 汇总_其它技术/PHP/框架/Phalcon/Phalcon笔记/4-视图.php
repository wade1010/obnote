<?php

�������URL: http://127.0.0.1/blog/posts/show/301 , ��������posts, action��show
��ô��ͼ����Զ��ҵ����Ӧ��������ͼ�ļ���:
Name				File
Action View			app/views/posts/show.phtml
Controller Layout	app/views/layouts/posts.phtml	
Main Layout			app/views/index.phtml
------------

ʹ��ģ��(Using Templates)
TemplatesҲ����ͼ�ļ���һ���֣��������ǿɹ���ġ�������Ϊ�������Ĳ����ļ������������Ƿŵ�layoutsĿ¼�¡�
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

�ֲ�ģ��
�ֲ�ģ���ļ�����һ�ִ�����ͼ��Ⱦ˳��ķ�ʽ���������ڹ����ҿ���Ӧ�ó������ظ�ʹ�á�
<?php $this->partial("shared/ad_banner") ?>
<h1>Robots</h1>
<p>Check out our specials for robots:</p>
...
<?php $this->partial("shared/footer") ?>
-------------

�ӿ�������ֵ����ͼ
$this->view->setVar("posts", Posts:find());
-----------

������ʾ���
�������Ҫ����ͼ�п�����ʾ�Ĳ�Σ�PhalconMvc\View::setRenderLevel() �ṩ���ֹ��ܡ�
�˷������Դӿ���������ϼ���ͼ����øı���Ⱦ���̡�
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
֧�ֵĲ���:
Class Constant			Description
LEVEL_NO_RENDER			Indicates to avoid generating any kind of presentation.
LEVEL_ACTION_VIEW		Generates the presentation to the view associated to the action.
LEVEL_BEFORE_TEMPLATE	Generates presentation templates prior to the controller layout.
LEVEL_LAYOUT			Generates the presentation to the controller layout.
LEVEL_AFTER_TEMPLATE	Generates the presentation to the templates after the controller layout.
LEVEL_MAIN_LAYOUT		Generates the presentation to the main layout. File views/index.phtml
----------

����ͼ��ʹ��ģ��
<div class="categories">
<?php
foreach (Catergories::find("status = 1") as $category) {
   echo "<span class='category'>", $category->name, "</span>";
}
?>
</div>
��Ȼ����ʹ��, ����������ôʹ�á���Ϊ���������ڷ���������쳣��ʱ���һ������������ת����һ����������
------------

ѡ����ͼ
��Ҳ����ͨ�� Phalcon\Mvc\View::pick() �����ı���ʾ���̣�
public function listAction()
{
	// Pick "views-dir/products/search" as view to render
	$this->view->pick("products/search");
}
-------------

�ֶλ���
������ͼ����ķ���:
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

��������:
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

��ֹ��ͼ
��ǰ�Ҷ�����exit, ��ʵ�ṩ�˸��÷���:
//Disable the view
$this->view->disable();
//The same
$this->view->setRenderLevel(Phalcon\Mvc\View::LEVEL_NO_RENDER);
--------

��ͼ��ʹ��ģ������
�����⻰������Ȥ���Կ��ֲ�.���ﲻ����.
---------

����ͼ��ʹ�÷���
����ͼ�ļ��а�����url��������������ֱ��ʹ������
<script type="text/javascript">
$.ajax({
    url: "<?php echo $this->url->get("cities/get") ?>"
})
.done(function() {
    alert("Done!");
});
</script>
------

���������ģʽ
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

��ͼ�¼�
Event Name				Triggered										Can stop operation?
beforeRender			Triggered before start the render process		Yes
beforeRenderView		Triggered before render an existing view		Yes
afterRenderView			Triggered after render an existing view			No
afterRender				Triggered after complete the render process		No

�����ʾ����ʾ��ν��¼��������󶨵������
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

