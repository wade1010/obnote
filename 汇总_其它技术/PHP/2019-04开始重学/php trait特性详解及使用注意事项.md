#### 简介
>自 PHP 5.4.0 起，PHP 实现了一种代码复用的方法，称为 trait。

>Trait 是为类似 PHP 的单继承语言而准备的一种代码复用机制。Trait 为了减少单继承语言的限制，使开发人员能够自由地在不同层次结构内独立的类中复用 method。Trait 和 Class 组合的语义定义了一种减少复杂性的方式，避免传统多继承和 Mixin 类相关典型问题。

>Trait 和 Class 相似，但仅仅旨在用细粒度和一致的方式来组合功能。 无法通过 trait 自身来实例化。它为传统继承增加了水平特性的组合；也就是说，应用的几个 Class 之间不需要继承。

>Trait简单的来讲就是将trait里的代码copy一份到类中。（个人理解）

#### 基础使用
```
trait TestTrait {
    public function helloWorld() {
        echo 'hello world';
    }
}

class Demo{
    use TestTrait;
}

(new Demo())->helloWorld(); // hello world
```
#### 优先级
看到这可能会想如何结合extend它的优先级是怎样的？同一个方法谁先执行？我们看代码是怎么运行的。

```
trait TestTrait {
    public function helloWorld() {
        echo 'TestTrait2 - hello world';
    }
}


class Base {
    public function helloWorld() {
        echo 'base - hello world';
    }
}

class Demo extends Base {
    // 没有引入trait
}

class Demo2 extends Base {
    use TestTrait;
}

class Demo3 extends Base {
    use TestTrait;

    public function helloWorld() {
        echo 'Demo3 - hello world';
    }
}

(new Demo())->helloWorld(); // base - hello world
(new Demo2())->helloWorld(); // TestTrait2 - hello world
(new Demo3())->helloWorld(); // Demo3 - hello world
```
结论：内部方法>trait>extend

#### 多个trait 基本使用
通过使用“，”可以在use的适合加载多个trait

```
trait TestTrait_1 {
    public function hello() {
        echo 'hello';
    }
}

trait TestTrait_2 {
    public function world() {
        echo ' world';
    }
}

class Demo {
    use TestTrait_1,TestTrait_2;// 和下面加载方式等价
//  use TestTrait_1;
//  use TestTrait_2;
}
$demo = new Demo();
$demo->hello();
$demo->world(); // echo 'hello world';
```

#### 多个trait 解决冲突，insteadof和as关键字
在加载多个的trait时候插入了相同的方法名会导致方法冲突，例如下面这个情况

```
trait TestTrait_1 {
    public function helloWorld() {
        echo 'Test_1 hello world';
    }
}

trait TestTrait_2 {
    public function helloWorld() {
        echo 'Test_2 hello world';
    }
}

class Demo {
    use TestTrait_1, TestTrait_2;
}

(new Demo())->helloWorld();
// 报错内容：Fatal error: Trait method helloWorld has not been applied, because there are collisions with other trait methods on Demo
```

为了解决这个冲突就需要使用insteadof关键字来指定使用冲突方法中哪一个。我们使用上面的情况来演示

```
trait TestTrait_1 {
    public function helloWorld() {
        echo 'Test_1 hello world';
    }
}

trait TestTrait_2 {
    public function helloWorld() {
        echo 'Test_2 hello world';
    }
}

class Demo {
    use TestTrait_1, TestTrait_2 {
        // 使用TestTrait_2代替TestTrait_1里面的helloWorld。可以简单理解为：TestTrait_2里的helloWorld替换了TestTrait_1里的helloWorld‘
        TestTrait_2::helloWorld insteadof TestTrait_1;
    }
}

(new Demo())->helloWorld(); // echo 'Test_2 hello world';
```
insteadof 是用来解决冲突的，那么as是干嘛的呢？我们来看下例子：

```
class Demo {
    use TestTrait_1, TestTrait_2 {
        TestTrait_2::helloWorld insteadof TestTrait_1;
        // as 即为为这个方法起个别名（第二个名字）
        TestTrait_2::helloWorld as hello;
        // as 同时还可以修改方法的控制权
        TestTrait_2::helloWorld as private;
        // as 可以结合上面同时使用。
        //意思即为：为estTrait_2::helloWorld起个别名为protected_hello方法的属性为protected（这个属性在外面是无法访问的）
        TestTrait_2::helloWorld as protected protected_hello;
    }
}

(new Demo())->hello(); // echo 'Test_2 hello world';
(new Demo())->helloWorld();//报错，private属性不能被访问
(new Demo())->protected_hello(); // 报错，protected属性不能被访问
```

看例子大家应该能看懂了，但是不知道你们有没有发现一个问题，这里as被使用了三次，其中TestTrait_2::helloWorld as private;这行代码已经转移了控制权为什么还能访问hello呢？经过测试可以这么理解，看代码：

```
class Demo {
    use TestTrait_1, TestTrait_2 {
        TestTrait_2::helloWorld insteadof TestTrait_1;
        // 将helloWorld命名为了hello
        TestTrait_2::helloWorld as hello;
        // 将helloWorld控制权从public转为private
        TestTrait_2::helloWorld as private;
        // 将helloWorld命名为了protected_hello并将protected_hello的控制权改为protected
        TestTrait_2::helloWorld as protected protected_hello;
    }

    // 可以这么理解TestTrait_2::helloWorld as hello;
    public function hello() {
        $this->helloWorld();
    }

    // 可以这么理解TestTrait_2::helloWorld as protected protected_hello;
    // 所以在调用(new Demo())->protected_hello()的时候调用失败
    protected function protected_hello() {
        $this->helloWorld();
    }

    // 可以这么理解TestTrait_2::helloWorld as private;
    private function helloWorld() {
        $this->helloWorld();
    }
}
```

相信对as能够区分了，但是这里还有一个问题，上面说的是方法冲突，那么成员属性冲突怎么解决呢？

>在 PHP 7.0 之前，在类里定义和 trait 同名的属性，哪怕是完全兼容的也会抛出 E_STRICT（完全兼容的意思：具有相同的访问可见性、初始默认值）。

也就是说使用7.0之前的版本要注意属性冲突才行。但是有种情况例外：属性是兼容的（同样的访问可见度、初始默认值）。 在 PHP 7.0 之前，属性是兼容的，则会有 E_STRICT 的提醒。

```
trait PropertiesTrait {
    public $same = true;
    public $different = false;
}

class PropertiesExample {
    use PropertiesTrait;
    public $same = true; // PHP 7.0.0 后没问题，之前版本是 E_STRICT 提醒
    public $different = true; // 致命错误
}
```

#### 总结
- trait在php5.4后才支持
- 是将trait里的代码copy一份到类中（理解这个可以避免很多错误）
- trait优先级：内部方法>trait>extend
- trait解决方法名冲突使用insteadof关键字，修改控制权和起别名使用as关键字
- 使用trait要避免属性名称冲突，起名可以使用前缀，例如：public $demo_name=”;来避免冲突。
- trait在php7.0之前相同的属性和值会报错，7.0之后不会。修改trait的属性值会报错。