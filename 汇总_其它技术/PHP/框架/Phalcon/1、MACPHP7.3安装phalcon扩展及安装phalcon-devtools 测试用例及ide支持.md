代码自动生成工具

https://github.com/phalcon/phalcon-devtools



1、安装扩展



```javascript
The PSR extension is required to be loaded before Phalcon. Please ensure that it is available in your system
```



先安装 psr扩展



https://github.com/jbboehr/php-psr



```javascript
git clone https://github.com/jbboehr/php-psr.git
cd php-psr
phpize
./configure
make
make test
sudo make install
```





 echo extension=psr.so | tee -a /usr/local/etc/php/7.3/php.ini 



2、编译安装phalcon扩展



https://pecl.php.net/package/phalcon



```javascript
wget https://pecl.php.net/get/phalcon-4.1.0.tgz
tar -zxvf phalcon-4.1.0.tgz && cd phalcon-4.1.0
phpize
./configure
make && make install
```



php.ini加入



extension=phalcon.so



```javascript
php -m | grep phal
phalcon
```



表明安装成功



3、composer安装phalcon-devtools



composer global require phalcon/devtools



https://docs.phalcon.io/4.0/en/devtools





如果全局不能使用phalcon命令



在.bashrc 我用的是zsh  文件是.zshrc  结尾加入下面代码



```javascript
export PATH="$HOME/.composer/vendor/bin:$PATH"
```



```javascript
 $ phalcon

Phalcon DevTools (4.1.0)

Available commands:
  info             (alias of: i)
  commands         (alias of: list, enumerate)
  controller       (alias of: create-controller)
  module           (alias of: create-module)
  model            (alias of: create-model)
  all-models       (alias of: create-all-models)
  project          (alias of: create-project)
  scaffold         (alias of: create-scaffold)
  migration        (alias of: create-migration)
  webtools         (alias of: create-webtools)
  serve            (alias of: server)
  console          (alias of: shell, psysh)
```





phalcon project phalcon_demo



![](https://gitee.com/hxc8/images8/raw/master/img/202407191106737.jpg)





生成一个controller





phalcon create-controller --name test



![](https://gitee.com/hxc8/images8/raw/master/img/202407191106246.jpg)





修改数据库配置

![](https://gitee.com/hxc8/images8/raw/master/img/202407191106732.jpg)





生成model





phalcon model user 



后面是表名



```javascript
> phalcon model user 

Phalcon DevTools (4.1.0)

                                                   
  Success: Model "User" was successfully created.  
                                                   

```







安装phpunit



composer require --dev phpunit/phpunit:^9.0



创建目录 tests/Unit/



创建 phpunit.xml



```javascript
<?xml version="1.0" encoding="UTF-8"?>

<phpunit backupGlobals="false"
         backupStaticAttributes="false"
         verbose="true"
         colors="true"
         convertErrorsToExceptions="true"
         convertNoticesToExceptions="true"
         convertWarningsToExceptions="true"
         processIsolation="false"
         stopOnFailure="false">

    <testsuite name="Phalcon - Unit Test">
        <directory>./tests/Unit</directory>
    </testsuite>
</phpunit>
```





安装Phalcon Incubator Test



```javascript
composer require --dev phalcon/incubator-test:^v1.0.0-alpha.1
```



First create a base Unit Test called AbstractUnitTest.php in your tests/Unit directory:



```javascript
<?php

declare(strict_types=1);

namespace Tests\Unit;

use Phalcon\Di;
use Phalcon\Di\FactoryDefault;
use Phalcon\Incubator\Test\PHPUnit\UnitTestCase;
use PHPUnit\Framework\IncompleteTestError;

abstract class AbstractUnitTest extends UnitTestCase
{
    private bool $loaded = false;

    protected function setUp(): void
    {
        parent::setUp();

        $di = new FactoryDefault();

        Di::reset();
        Di::setDefault($di);

        $this->loaded = true;
    }

    public function __destruct()
    {
        if (!$this->loaded) {
            throw new IncompleteTestError(
                "Please run parent::setUp()."
            );
        }
    }
}
```



创建第一个测试用例FirstUnitTest.php

```javascript
<?php

declare(strict_types=1);

namespace Tests\Unit;

class FirstUnitTest extends AbstractUnitTest
{
    public function testTestCase(): void
    {
        $this->assertEquals(
            "roman",
            "roman",
            "This will pass"
        );

        $this->assertEquals(
            "hope",
            "ava",
            "This will fail"
        );
    }
}
```



跑测试用例



./vendor/bin/phpunit



直接用phpunit 可能报错。是你系统的phpunit版本太低  升级 或者用vendor下面的



```javascript
$ phpunit
PHPUnit 9.1.4 by Sebastian Bergmann and contributors.

Runtime:       PHP 7.4.5 with Xdebug 2.9.5
Configuration: /var/www//phpunit.xml


Time: 3 ms, Memory: 3.25Mb

There was 1 failure:

1) Test\Unit\UnitTest::testTestCase
This will fail
Failed asserting that two strings are equal.
--- Expected
+++ Actual
@@ @@
-'hope'
+'ava'

/var/www/tests/Unit/UnitTest.php:25

FAILURES!
Tests: 1, Assertions: 2, Failures: 1.
```





phalcon ide IDE 自动完成代码提示



![](https://gitee.com/hxc8/images8/raw/master/img/202407191106175.jpg)

