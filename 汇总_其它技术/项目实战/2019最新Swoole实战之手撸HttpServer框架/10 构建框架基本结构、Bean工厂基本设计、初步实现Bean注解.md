BeanFactory.php



```javascript
<?php declare(strict_types=1);

namespace Core;

use App\controllers\UserController;
use DI\ContainerBuilder;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;

class BeanFactory
{
    private static $env = [];//配置文件
    private static $container;//ioc容器

    public static function init()
    {
        self::$env = parse_ini_file(ROOT_PATH . '/env');
        $builder = new ContainerBuilder();//初始化容器builder
        $builder->useAnnotations(true);//启用注解
        self::$container = $builder->build();//容器初始化
        self::scanBeans();//扫描
    }

    public static function getEnv($key, $default = '')
    {
        return self::$env[$key] ?? $default;
    }

    public static function getBean($class)
    {
        return self::$container->has($class) ? self::$container->get($class) : NULL;
    }

    private static function scanBeans()
    {
        $annotationHandlers = require_once ROOT_PATH . '/core/annotations/AnnotationHandlers.php';
        $scanDir = self::getEnv('scan_dir', ROOT_PATH . '/app');
        $scanRootNamespace = self::getEnv('scan_root_namespace', 'App\\');
        $files = glob($scanDir . '/*.php');
        $reader = new AnnotationReader();
        foreach ($files as $file) {
            require_once "$file";
        }
        AnnotationRegistry::registerAutoloadNamespace('Core\annotations');
        foreach (get_declared_classes() as $declaredClass) {
            if (strpos($declaredClass, $scanRootNamespace) === 0) {
                $reflection = new \ReflectionClass($declaredClass);//目标类的反射对象
                $classAnnotations = $reader->getClassAnnotations($reflection);//获取所有类注解
                foreach ($classAnnotations as $classAnnotation) {//$classAnnotation就是Bean已经实例化后的对象
                    $handler = $annotationHandlers[get_class($classAnnotation)];
                    $handler($reflection->getName(), self::$container, $classAnnotation);
                }
            }
        }
    }
}
```



Bean.php

```javascript
<?php declare(strict_types=1);

namespace Core\annotations;

use Doctrine\Common\Annotations\Annotation\Target;

/**
 * @Annotation
 * @Target({"CLASS"})
 */
class Bean
{
    public $name = '';

}
```



AnnotationHandlers.php



```javascript
<?php

namespace Core\annotations;

return [
    Bean::class => function ($class, $container, $beanObject) {
        $vars = get_object_vars($beanObject);
        if ($vars['name'] ?? '') {
            $name = $vars['name'];
        } else {
            $namespaceArr = explode('\\', $class);
            $name = end($namespaceArr);
        }
        $container->set($name, $container->get($class));

    },
    Value::class => function () {

    }
];
```

