<?php

CLI applications are executed from the command line. They are useful to create cron jobs, scripts, command utilities and more.

最小的目录结构:
	app/config/config.php
	app/tasks/MainTask.php
	app/cli.php <– main bootstrap file
----------

创建bootstrap
	use Phalcon\DI\FactoryDefault\CLI as CliDI;
	use Phalcon\CLI\Console as ConsoleApp;
	define('VERSION', '1.0.0');
	
	//Using the CLI factory default services container
	$di = new CliDI();
	// Define path to application directory
	defined('APPLICATION_PATH')
	|| define('APPLICATION_PATH', realpath(dirname(__FILE__)));
	
	/**
	* Register the autoloader and tell it to register the tasks directory
	*/
	$loader = new \Phalcon\Loader();
	$loader->registerDirs(
		 array(
			 APPLICATION_PATH . '/tasks'
		 )
	);
	$loader->register();

	// Load the configuration file (if any)
	if(is_readable(APPLICATION_PATH . '/config/config.php')) {
		 $config = include APPLICATION_PATH . '/config/config.php';
		 $di->set('config', $config);
	}

	//Create a console application
	$console = new ConsoleApp();
	$console->setDI($di);

	/**
	* Process the console arguments
	*/
	$arguments = array();
	$params = array();

	foreach($argv as $k => $arg) {
		 if($k == 1) {
			 $arguments['task'] = $arg;
		 } elseif($k == 2) {
			 $arguments['action'] = $arg;
		 } elseif($k >= 3) {
			$params[] = $arg;
		 }
	}
	if(count($params) > 0) {
		$arguments['params'] = $params;
	}

	// define global constants for the current task and action
	define('CURRENT_TASK', (isset($argv[1]) ? $argv[1] : null));
	define('CURRENT_ACTION', (isset($argv[2]) ? $argv[2] : null));

	try {
		 // handle incoming arguments
		 $console->handle($arguments);
	}
	catch (\Phalcon\Exception $e) {
		 echo $e->getMessage();
		 exit(255);
	}
	
	命令行下启用用例:
	$ php app/cli.php
------------

任务
cli应用至少要有一个默认的任务mainTask和一个默认的行为:mainAction.
class mainTask extends \Phalcon\CLI\Task
{
    public function mainAction() {
         echo "\nThis is the default task and the default action \n";
    }
    /**
    * @param array $params
    */
   public function testAction(array $params) {
       echo sprintf('hello %s', $params[0]) . PHP_EOL;
       echo sprintf('best regards, %s', $params[1]) . PHP_EOL;
   }
}

命令:
$ php app/cli.php main test world universe

hello world
best regards, universe
------------

任务链:
要支持任务链首先定义DI:
$di->setShared('console', $console);
try {
    // handle incoming arguments
    $console->handle($arguments);
}
然后就可以使用控制台的任何任务了:
class MainTask extends \Phalcon\CLI\Task {
    public function mainAction() {
        echo "\nThis is the default task and the default action \n";

        $this->console->handle(array(
           'task' => 'main',
           'action' => 'test'
        ));
    }
    public function testAction() {
        echo '\nI will get printed too!\n';
    }
}