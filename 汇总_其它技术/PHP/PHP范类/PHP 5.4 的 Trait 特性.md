Trait 是 PHP5.4 中的新特性，是 PHP 多重继承的一种解决方案。例如，需要同时继承两个 Abstract Class， 这将会是件很麻烦的事情，Trait 就是为了解决这个问题。

简单使用

首先，当然是声明个 Trait，PHP5.4 增加了 trait 关键字

trait first_trait {    function first_method() { /* Code Here */ }    function second_method() { /* Code Here */ }}

同时，如果要在 Class 中使用该 Trait，那么使用 use 关键字

class first_class {    // 注意这行，声明使用 first_trait    use first_trait;}$obj = new first_class();// Executing the method from trait$obj->first_method(); // valid$obj->second_method(); // valid

使用多个 Trait

在同个 Class 中可以使用多个 Trait

trait first_trait{    function first_method() { echo "method1"; }}trait second_trait {    function second_method() { echo "method2"; }}class first_class {    // now using more than one trait    use first_trait, second_trait;}$obj= new first_class();// Valid$obj->first_method(); // Print : method1// Valid$obj->second_method(); // Print : method2

Trait 之间的嵌套

同时，Trait 之间也可以相互的嵌套，例如

trait first_trait {    function first_method() { echo "method1"; }}trait second_trait {    use first_trait;    function second_method() { echo "method2"; }}class first_class {    // now using     use second_trait;}$obj= new first_class();// Valid$obj->first_method(); // Print : method1// Valid$obj->second_method(); // Print : method2

Trait 的抽象方法（Abstract Method）

我们可以在 Trait 中声明需要实现的抽象方法，这样能使使用它的 Class 必须实现它

trait first_trait {    function first_method() { echo "method1"; }    // 这里可以加入修饰符，说明调用类必须实现它    abstract public function second_method();}class first_method {    use first_trait;    function second_method() {        /* Code Here */    }}

Trait 冲突

多个 Trait 之间同时使用难免会冲突，这需要我们去解决。PHP5.4 从语法方面带入了相关 的关键字语法：insteadof 以及 as ，用法参见

trait first_trait {    function first_function() {         echo "From First Trait";    }}trait second_trait {    // 这里的名称和 first_trait 一样，会有冲突    function first_function() {         echo "From Second Trait";    }}class first_class {    use first_trait, second_trait {        // 在这里声明使用 first_trait 的 first_function 替换        // second_trait 中声明的        first_trait::first_function insteadof second_trait;    }}  $obj = new first_class();// Output: From First Trait$obj->first_function();

需要注意的几点

上面就是些 Trait 比较基本的使用了，更详细的可以参考官方手册。这里总结下注意的几 点：

- Trait 会覆盖调用类继承的父类方法

- Trait 无法如 Class 一样使用 new 实例化

- 单个 Trait 可由多个 Trait 组成

- 在单个 Class 中，可以使用多个 Trait

- Trait 支持修饰词（modifiers），例如 final、static、abstract

- 我们能使用 insteadof 以及 as 操作符解决 Trait 之间的冲突

-- Split --

一些看法

坦白讲，我第一眼看到 Trait 对它并没有任何好感。PHP5 以来带来的新特性已经足够得 多，而且让开发者们有点应接不暇。

同时，Trait 更像是程序员的“语法糖”，然而它提供便利的同时可能会造成巨大的隐患。 例如 Trait 能够调用类中的成员：

trait Hello {    public function sayHelloWorld() {        echo 'Hello'.$this->getWorld();    }    abstract public function getWorld();}class MyHelloWorld {    private $world;    use Hello;    public function getWorld() {        return $this->world;    }    public function setWorld($val) {        $this->world = $val;    }}

同时，针对类中已经实现的方法，Trait 没有效果

trait HelloWorld {    public function sayHello() {        echo 'Hello World!';    }}class TheWorldIsNotEnough {    use HelloWorld;    public function sayHello() {        echo 'Hello Universe!';    }}$o = new TheWorldIsNotEnough();$o->sayHello(); // echos Hello Universe!

那么 Trait 的出现是为何呢？有哥们的回答比较有意思，但不无道理：

因为php没有javascript作用域链的机制，所以无法把function bind到class里面，曾经以为php 5.3的闭包可以做这个事，最后才发觉作用域的设计不允许这么干

但话说回来，拿 interface 和 Trait 类比，显然 Trait 有更多方便的地方（虽然 两者不能完全相互替代）。

不过很显然 Trait 目前还处于测试阶段，它的未来相比其他 PHP5 新推来的特性还有 更多让人观望的地方，但或许这特性能改变 PHP5 未来继承的方式。

因为，我个人坚信 PHP 的作用链设计迟早会改得“更像 JavaScript”，即便这事情会在遥远的 PHP6 。