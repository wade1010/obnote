

![](https://gitee.com/hxc8/images7/raw/master/img/202407190026471.jpg)





index.php



```javascript
<?php
require_once __DIR__. '/vendor/autoload.php';

use App\annotations\Value;
use App\core\ClassFactory;
use App\test\MyRedis;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;


//注册 注解的namespace
AnnotationRegistry::registerAutoloadNamespace("App\annotations");



$myredis=ClassFactory::loadClass(MyRedis::class);
var_dump($myredis);
```





MyRedis.php

```javascript
<?php
namespace App\test;
use App\annotations\Value;

class MyRedis{
    /**
     * @Value(name="url")
     */
    public $conn_url;


}
```





Value.php

```javascript
<?php
namespace App\annotations;
use Doctrine\Common\Annotations\Annotation\Target;

/**
 * @Annotation
 * @Target({"PROPERTY"})
 */
class Value{

    public $name;
    public function do(){
       $ini=parse_ini_file("./env");
       if(isset($ini[$this->name])){
           return $ini[$this->name];
       }
      return "";
    }

}
```



ClassFactory.php

```javascript
<?php
namespace App\core;
use App\test\MyRedis;
use Doctrine\Common\Annotations\AnnotationReader;

class ClassFactory{
    public static function loadClass($classname){

           $ref_class=new \ReflectionClass($classname);
           ///////////只处理 属性注解
         $properties= $ref_class->getProperties();
         //  return
          $reader=new  AnnotationReader();
          foreach ($properties as $property){
              $annos=$reader->getPropertyAnnotations($property);
              foreach ($annos as $anno){
                 $getValue=$anno->do(); // 假设do 返回我们的业务数据
                 $retObj=$ref_class->newInstance();
                 $property->setValue($retObj,$getValue);
                 return $retObj;
              }
          }
            return false;

    }
}
```

