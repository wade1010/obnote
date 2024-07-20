<?php

Phalcon\Loader 提供四种方式自动加载类文件，你可以一次只使用其中一个，或者混合使用。
-------------

命名空间方式:
可以通过传递一个关联数组，key是命名空间前辍，value是类文件存放的目录。value末尾一定要以”/”结尾。
// Creates the autoloader
$loader = new \Phalcon\Loader();
//Register some namespaces
$loader->registerNamespaces(
    array(
       "Example\Base"    => "vendor/example/base/",
       "Example\Adapter" => "vendor/example/adapter/",
       "Example"         => "vendor/example/",
    )
);
// register autoloader
$loader->register();
// The required class will automatically include the
// file vendor/example/adapter/Some.php
$some = new Example\Adapter\Some();
-------------

前缀方式:
//Register some prefixes
$loader->registerPrefixes(
    array(
       "Example_Base"    => "vendor/example/base/",
       "Example_Adapter" => "vendor/example/adapter/",
       "Example_"         => "vendor/example/",
    )
);
----------

目录方式:
在注册的目录中找到类文件。在性能方面，不建议使用此种方式, 注册目录时的顺序是非常重要的
// Register some directories
$loader->registerDirs(
    array(
        "library/MyComponent/",
        "library/OtherComponent/Other/",
        "vendor/example/adapters/",
        "vendor/example/"
    )
);
----------

名称和路径方式:
这种加载方面是最快的一种加载方式。然而，随着应用程序的增长，更多的类及文件需要加载，这将使维护工作变得非常麻烦，因为不太建议使用。
// Register some classes
$loader->registerClasses(
    array(
        "Some"         => "library/OtherComponent/Other/Some.php",
        "Example\Base" => "vendor/example/adapters/Example/BaseClass.php",
    )
);
-----------------

其他扩展名文件的加载:
//Set file extensions to check
$loader->setExtensions(array("php", "inc", "phb"));

