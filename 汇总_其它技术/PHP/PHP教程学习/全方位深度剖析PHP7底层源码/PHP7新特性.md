前言

本文是一篇讲座听后＋后续研究的总结。 

话说当年追时髦，php7一出就给电脑立马装上了，php5和php7共存，也是立马写了个超级耗时间的循环脚本测了一番，确实php7给力很多，然后也是注意了一些新增的特性与一些丢弃掉的用法。 

由于php升级乃头等大事，公司近期才打算升级，所以之前一直只能私下欣赏php7带来的快感，负责升级的小伙伴搞了个分享，还挺全的，此处mark一下，当作笔记。

主要研究问题： 

1.PHP7带来的好处 

2.PHP7带来的新东西 

3.PHP7带来的废弃 

4.PHP7带来的变更 

5.如何充分发挥PHP7的性能 

6。如何更好的写代码来迎接PHP7? 

7.如何升级当前项目代码来兼容PHP7?

PHP7带来的好处

是的，性能上的大幅度提升，可以省机器，可以省钱。 

![](https://gitee.com/hxc8/images8/raw/master/img/202407191105135.jpg)

PHP7带来的新东西

1.类型的声明。

可以使用字符串(string), 整数 (int), 浮点数 (float), 以及布尔值 (bool)，来声明函数的参数类型与函数返回值。

1. declare(strict_types=1);

1. function add(int $a, int $b): int {

1.     return $a+$b;

1. }

1.  

1. echo add(1, 2);

1. echo add(1.5, 2.6);

php5是无法执行上面代码的，php7执行的时候会先输出一个3和一个报错( Argument 1 passed to add() must be of the type integer, float given);

标量类型声明 有两种模式: 强制 (默认) 和 严格模式。 

declare(strict_types=1),必须放在文件的第一行执行代码，当前文件有效！

2.set_exception_handler() 不再保证收到的一定是 Exception 对象

在 PHP 7 中，很多致命错误以及可恢复的致命错误，都被转换为异常来处理了。 这些异常继承自 Error 类，此类实现了 Throwable 接口 （所有异常都实现了这个基础接口）。

PHP7进一步方便开发者处理, 让开发者对程序的掌控能力更强. 因为在默认情况下, Error会直接导致程序中断, 而PHP7则提供捕获并且处理的能力, 让程序继续执行下去, 为程序员提供更灵活的选择。

3.新增操作符“<=>”

语法：$c = $a <=> $b

如果$a > $b, $c 的值为1

如果$a == $b, $c 的值为0

如果$a < $b, $c 的值为-1

4.新增操作符“??”

如果变量存在且值不为NULL， 它就会返回自身的值，否则返回它的第二个操作数。

1. //原写法

1. $username = isset($_GET['user]) ? $_GET['user] : 'nobody';

1.  

1. //现在

1. $username = $_GET['user'] ?? 'nobody';

5.define() 定义常量数组

1. define('ARR',['a','b']);

1. echo ARR[1];// a

6.AST: Abstract Syntax Tree, 抽象语法树

AST在PHP编译过程作为一个中间件的角色, 替换原来直接从解释器吐出opcode的方式, 让解释器(parser)和编译器(compliler)解耦, 可以减少一些Hack代码, 同时, 让实现更容易理解和可维护.

PHP5 : PHP代码 -> Parser语法解析 -> OPCODE -> 执行 

PHP7 : PHP代码 -> Parser语法解析 -> AST -> OPCODE -> 执行

参考: https://wiki.php.net/rfc/abstract_syntax_tree

7.匿名函数

1. $anonymous_func = function(){return 'function';};

1. echo $anonymous_func(); // 输出function

8.Unicode字符格式支持(echo “\u{9999}”)

9.Unserialize 提供过滤特性

防止非法数据进行代码注入,提供了更安全的反序列化数据。

10.命名空间引用优化

1. // PHP7以前语法的写法 

1. use FooLibrary\Bar\Baz\ClassA; 

1. use FooLibrary\Bar\Baz\ClassB; 

1. // PHP7新语法写法 

1. use FooLibrary\Bar\Baz\{ ClassA, ClassB};

PHP7带来的废弃

1.废弃扩展

Ereg 正则表达式 

mssql 

mysql 

sybase_ct

2.废弃的特性

不能使用同名的构造函数 

实例方法不能用静态方法的方式调用

3.废弃的函数

方法调用 

call_user_method() 

call_user_method_array()

应该采用call_user_func() 和 call_user_func_array()

加密相关函数

mcrypt_generic_end() 

mcrypt_ecb() 

mcrypt_cbc() 

mcrypt_cfb() 

mcrypt_ofb()

注意: PHP7.1 以后mcrypt_*序列函数都将被移除。推荐使用：openssl 序列函数

杂项

set_magic_quotes_runtime 

set_socket_blocking 

Split 

imagepsbbox() 

imagepsencodefont() 

imagepsextendfont() 

imagepsfreefont() 

imagepsloadfont() 

imagepsslantfont() 

imagepstext()

4.废弃的用法

$HTTP_RAW_POST_DATA 变量被移除, 使用php://input来代

ini文件里面不再支持#开头的注释, 使用”;”

移除了ASP格式的支持和脚本语法的支持: <% 和 < script language=php >

PHP7带来的变更

1.字符串处理机制修改

含有十六进制字符的字符串不再视为数字, 也不再区别对待.

1. var_dump("0x123" == "291"); // false

1. var_dump(is_numeric("0x123")); // false

1. var_dump("0xe" + "0x1"); // 0

1. var_dump(substr("f00", "0x1")) // foo

2.整型处理机制修改

Int64支持, 统一不同平台下的整型长度, 字符串和文件上传都支持大于2GB. 64位PHP7字符串长度可以超过2^31次方字节.

1. // 无效的八进制数字(包含大于7的数字)会报编译错误

1. $i = 0681; // 老版本php会把无效数字忽略。

1.  

1. // 位移负的位置会产生异常

1. var_dump(1 >> -1);

1.  

1. // 左位移超出位数则返回0

1. var_dump(1 << 64);// 0 

1.  

1. // 右位移超出会返回0或者-1

1. var_dump(100 >> 32);// 0 

1. var_dump(-100 >> 32);// -1 

3.参数处理机制修改

不支持重复参数命名

function func(b, c) {} ;hui报错

func_get_arg()和func_get_args()这两个方法返回参数当前的值, 而不是传入时的值, 当前的值有可能会被修改

所以需要注意，在函数第一行最好就给记录下来，否则后续有修改的话，再读取就不是传进来的初始值了。

1. function foo($x) {

1.     $x++;

1.     echo func_get_arg(0);

1. }

1. foo(1); //返回2

4.foreach修改

foreach()循环对数组内部指针不再起作用

1. $arr = [1,2,3];

1. foreach ($arr as &$val) {

1.     echo current($arr);// php7 全返回0

1. }

按照值进行循环的时候, foreach是对该数组的拷贝操作

1. $arr = [1,2,3];

1. foreach ($arr as $val) {

1.     unset($arr[1]);

1. }

1. var_dump($arr);

最新的php7依旧会打印出[1,2,3]。(ps：7.0.0不行) 

老的会打印出[1,3]

按照引用进行循环的时候, 对数组的修改会影响循环

1. $arr = [1];

1. foreach ($arr as $val) {

1.     var_dump($val);

1.     $arr[1]=2;

1. }

最新的php7依旧会追加新增元素的循环。(ps：7.0.0不行)

5. list修改

不再按照相反的顺序赋值

1. //$arr将会是[1,2,3]而不是之前的[3,2,1]

1. list($arr[], $arr[], $arr[]) = [1,2,3];

不再支持字符串拆分功能

1. // $x = null 并且 $y = null

1. $str = 'xy';

1. list($x, $y) = $str;

空的list()赋值不再允许

list() = [123];

list()现在也适用于数组对象

list($a, $b) = (object)new ArrayObject([0, 1]);

6.变量处理机制修改

对变量、属性和方法的间接调用现在将严格遵循从左到右的顺序来解析，而不是之前的混杂着几个特殊案例的情况。 下面这张表说明了这个解析顺序的变化。

![](https://gitee.com/hxc8/images8/raw/master/img/202407191105666.jpg)

引用赋值时自动创建的数组元素或者对象属性顺序和以前不同了

1. $arr = [];

1. $arr['a'] = &$arr['b'];

1. $arr['b'] = 1;

1. // php7: ['a' => 1, 'b' => 1]

1. // php5: ['b' => 1, 'a' => 1]

7.杂项

1.debug_zval_dump() 现在打印 “int” 替代 “long”, 打印 “float” 替代 “double”

2.dirname() 增加了可选的第二个参数, depth, 获取当前目录向上 depth 级父目录的名称。

3.getrusage() 现在支持 Windows.mktime() and gmmktime() 函数不再接受 is_dst 参数。

4.preg_replace() 函数不再支持 “\e” (PREG_REPLACE_EVAL). 应当使用 preg_replace_callback() 替代。

5.setlocale() 函数不再接受 category 传入字符串。 应当使用 LC_* 常量。

6.exec(), system() and passthru() 函数对 NULL 增加了保护.

7.shmop_open() 现在返回一个资源而非一个int， 这个资源可以传给shmop_size(), shmop_write(), shmop_read(), shmop_close() 和 shmop_delete().

8.为了避免内存泄露，xml_set_object() 现在在执行结束时需要手动清除 $parse。

9.curl_setopt 设置项CURLOPT_SAFE_UPLOAD变更

TRUE 禁用 @ 前缀在 CURLOPT_POSTFIELDS 中发送文件。 意味着 @ 可以在字段中安全得使用了。 可使用 CURLFile作为上传的代替。 

PHP 5.5.0 中添加，默认值 FALSE。 PHP 5.6.0 改默认值为 TRUE。. PHP 7 删除了此选项， 必须使用 CURLFile interface 来上传文件。

如何充分发挥PHP7的性能

1.开启Opcache

zend_extension=opcache.so 

opcache.enable=1 

opcache.enable_cli=1

2.使用GCC 4.8以上进行编译

只有GCC 4.8以上PHP才会开启Global Register for opline and execute_data支持, 这个会带来5%左右的性能提升(Wordpres的QPS角度衡量)

3.开启HugePage （根据系统内存决定）

![](https://gitee.com/hxc8/images8/raw/master/img/202407191105281.jpg)

4.PGO (Profile Guided Optimization）

第一次编译成功后，用项目代码去训练PHP，会产生一些profile信息，最后根据这些信息第二次gcc编译PHP就可以得到量身定做的PHP7

需要选择在你要优化的场景中: 访问量最大的, 耗时最多的, 资源消耗最重的一个页面.

参考: http://www.laruence.com/2015/06/19/3063.html 

参考: http://www.laruence.com/2015/12/04/3086.html

如何更好的写代码来迎接PHP7?

1. 不使用php7废弃的方法，扩展

1. 使用2个版本都兼容的语法特性【 list ,foreach, func_get_arg 等】

如何升级当前项目代码来兼容PHP7?

逐步剔除php7不支持的代码

检测工具：https://github.com/sstalle/php7cc

![](https://gitee.com/hxc8/images8/raw/master/img/202407191105758.jpg)

更多资料

官方5.6.x移植7.0.x 文档

Laruence 让PHP7达到最高性能的tips

博客-PHP7特征

Zval的变更