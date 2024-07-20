<?php

注入服务:
$di->setShared('session', function(){
    $session = new Phalcon\Session\Adapter\Files();
    $session->start();
    return $session;
});
---------

使用:
public function indexAction()
{
	//Set a session variable
	$this->session->set("user-name", "Michael");
}
public function welcomeAction()
{
	//Check if the variable is defined
	if ($this->session->has("user-name")) {
		//Retrieve its value
		$name = $this->session->get("user-name");
	}
}
----------

移除和销毁
//Remove a session variable
$this->session->remove("user-name");
//Destroy the whole session
$this->session->destroy();
-------

Phalcon\Session\Bag 组件帮助把session数据导入到 “namespaces”。通过这种方式，你可以轻松的创建一组会话变量到应用程序中，只需设置变量为 “bag”,它会自动存储为session数据：
$user       = new Phalcon\Session\Bag();
$user->name = "Kimbra Johnson";
$user->age  = 22;
------------

public function indexAction()
{
	// Create a persistent variable "name"
	$this->persistent->name = "Laura";
}
public function welcomeAction()
{
	if (isset($this->persistent->name)) {
		echo "Welcome, ", $this->persistent->name;
	}
}
通过 ($this->session) 添加的变量，可在整个应用程序进行访问。而通过 ($this->persistent) 添加的变量，只能在当前类访问
----------

实现自定义适配器
<?php

class MySessionHandler implements Phalcon\Session\AdapterInterface
{
    /**
     * MySessionHandler construtor
     *
     * @param array $options
     */
    public function __construct($options=null)
    {
    }
    /**
     * Starts session, optionally using an adapter
     *
     * @param array $options
     */
    public function start()
    {
    }
    /**
     * Sets session options
     *
     * @param array $options
     */
    public function setOptions($options)
    {
    }
    /**
     * Get internal options
     *
     * @return array
     */
    public function getOptions()
    {
    }
    /**
     * Gets a session variable from an application context
     *
     * @param string $index
     */
    public function get($index)
    {
    }
    /**
     * Sets a session variable in an application context
     *
     * @param string $index
     * @param string $value
     */
    public function set($index, $value)
    {
    }
    /**
     * Check whether a session variable is set in an application context
     *
     * @param string $index
     */
    public function has($index)
    {
    }
    /**
     * Removes a session variable from an application context
     *
     * @param string $index
     */
    public function remove($index)
    {
    }
    /**
     * Returns active session id
     *
     * @return string
     */
    public function getId()
    {
    }
    /**
     * Check whether the session has been started
     *
     * @return boolean
     */
    public function isStarted()
    {
    }
    /**
     * Destroys the active session
     *
     * @return boolean
     */
    public function destroy()
    {
    }
}
