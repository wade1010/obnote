做过PHP开发的程序员应该清楚，PHP中有很多内置的功能，掌握了它们，可以帮助你在做PHP开发时更加得心应手，本文将分享8个开发必备的PHP功能，个个都非常实用，希望各位PHP开发者能够掌握。 

![](https://gitee.com/hxc8/images8/raw/master/img/202407191110011.jpg)





1、传递任意数量的函数参数 

我们在.NET或者JAVA编程中，一般函数参数个数都是固定的，但是PHP允许你使用任意个数的参数。下面这个示例向你展示了PHP函数的默认参数： 

view source print ?

01. // 两个默认参数的函数 

02. functionfoo($arg1= ”, $arg2= ”) { 

03. echo“arg1: $arg1\n”; 

04. echo“arg2: $arg2\n”; 

05. } 

06. foo(‘hello’,'world’); 

07. /* 输出:

08. arg1: hello

09. arg2: world

10. */

11. foo(); 

12. /* 输出:

13. arg1:

14. arg2:

15. */

16. 下面这个示例是PHP的不定参数用法，其使用到了 func_get_args()方法： 

17. // 是的，形参列表为空 

18. functionfoo() { 

19. // 取得所有的传入参数的数组 

20. $args= func_get_args(); 

21. foreach($argsas$k=> $v) { 

22. echo“arg”.($k+1).”: $v\n”; 

23. } 

24. } 

25. foo(); 

26. /* 什么也不会输出 */

27. foo(‘hello’); 

28. /* 输出

29. arg1: hello

30. */

31. foo(‘hello’, ‘world’, ‘again’); 

32. /* 输出

33. arg1: hello

34. arg2: world

35. arg3: again

36. */

2、使用glob()查找文件 

大部分PHP函数的函数名从字面上都可以理解其用途，但是当你看到 glob() 的时候，你也许并不知道这是用来做什么的，其实glob()和scandir() 一样，可以用来查找文件，请看下面的用法： 

view source print ?

01. // 取得所有的后缀为PHP的文件 

02. $files= glob(‘*.php’); 

03. print_r($files); 

04. /* 输出:

05. Array

06. (

07. [0] => phptest.php

08. [1] => pi.php

09. [2] => post_output.php

10. [3] => test.php

11. )

12. */

你还可以查找多种后缀名：



view source print ?

01. // 取PHP文件和TXT文件 

02. $files= glob(‘*.{php,txt}’, GLOB_BRACE); 

03. print_r($files); 

04. /* 输出:

05. Array

06. (

07. [0] => phptest.php

08. [1] => pi.php

09. [2] => post_output.php

10. [3] => test.php

11. [4] => log.txt

12. [5] => test.txt

13. )

14. */

你还可以加上路径： 



view source

print ?

01. $files= glob(‘../images/a*.jpg’); 

02. print_r($files); 

03. /* 输出:

04. Array

05. (

06. [0] => ../images/apple.jpg

07. [1] => ../images/art.jpg

08. )

09. */

如果你想得到绝对路径，你可以调用 realpath() 函数： 



view source print ?

01. $files= glob(‘../images/a*.jpg’); 

02. // applies the function to each array element 

03. $files= array_map(‘realpath’,$files); 

04. print_r($files); 

05. /* output looks like:

06. Array

07. (

08. [0] => C:\wamp\www\images\apple.jpg

09. [1] => C:\wamp\www\images\art.jpg

10. )

11. */

3、获取内存使用情况信息 

PHP的内存回收机制已经非常强大，你也可以使用PHP脚本获取当前内存的使用情况，调用memory_get_usage() 函数获取当期内存使用情况，调用memory_get_peak_usage() 函数获取内存使用的峰值。参考代码如下： 

view source print ?

01. echo“Initial: “.memory_get_usage().” bytes \n”; 

02. /* 输出

03. Initial: 361400 bytes

04. */

05. // 使用内存 

06. for($i= 0; $i< 100000; $i++) { 

07. $array[]= md5($i); 

08. } 

09. // 删除一半的内存 

10. for($i= 0; $i< 100000; $i++) { 

11. unset($array[$i]); 

12. } 

13. echo“Final: “.memory_get_usage().” bytes \n”; 

14. /* prints

15. Final: 885912 bytes

16. */

17. echo“Peak: “.memory_get_peak_usage().” bytes \n”; 

18. /* 输出峰值

19. Peak: 13687072 bytes

20. */

4、获取CPU使用情况信息 

获取了内存使用情况，也可以使用PHP的 getrusage()获取CPU使用情况，该方法在windows下不可用。 

view source print ?

01. print_r(getrusage()); 

02. /* 输出

03. Array

04. (

05. [ru_oublock] => 0

06. [ru_inblock] => 0

07. [ru_msgsnd] => 2

08. [ru_msgrcv] => 3

09. [ru_maxrss] => 12692

10. [ru_ixrss] => 764

11. [ru_idrss] => 3864

12. [ru_minflt] => 94

13. [ru_majflt] => 0

14. [ru_nsignals] => 1

15. [ru_nvcsw] => 67

16. [ru_nivcsw] => 4

17. [ru_nswap] => 0

18. [ru_utime.tv_usec] => 0

19. [ru_utime.tv_sec] => 0

20. [ru_stime.tv_usec] => 6269

21. [ru_stime.tv_sec] => 0

22. )

23. */

这个结构看上出很晦涩，除非你对CPU很了解。下面一些解释： 

- ru_oublock: 块输出操作

- ru_inblock: 块输入操作

- ru_msgsnd: 发送的message

- ru_msgrcv: 收到的message

- ru_maxrss: 最大驻留集大小

- ru_ixrss: 全部共享内存大小

- ru_idrss:全部非共享内存大小

- ru_minflt: 页回收

- ru_majflt: 页失效

- ru_nsignals: 收到的信号

- ru_nvcsw: 主动上下文切换

- ru_nivcsw: 被动上下文切换

- ru_nswap: 交换区

- ru_utime.tv_usec: 用户态时间 (microseconds)

- ru_utime.tv_sec: 用户态时间(seconds)

- ru_stime.tv_usec: 系统内核时间 (microseconds)

- ru_stime.tv_sec: 系统内核时间?(seconds)

要看到你的脚本消耗了多少CPU，我们需要看看“用户态的时间”和“系统内核时间”的值。秒和微秒部分是分别提供的，您可以把微秒值除以100万，并把它添加到秒的值后，可以得到有小数部分的秒数。 



view source

print ?

01. // sleep for 3 seconds (non-busy) 

02. sleep(3); 

03. $data= getrusage(); 

04. echo“User time: “. 

05. ($data['ru_utime.tv_sec'] + 

06. $data['ru_utime.tv_usec'] / 1000000); 

07. echo“System time: “. 

08. ($data['ru_stime.tv_sec'] + 

09. $data['ru_stime.tv_usec'] / 1000000); 

10. /* 输出

11. User time: 0.011552

12. System time: 0

13. */

sleep是不占用系统时间的，我们可以来看下面的一个例子： 

view source print ?

01. // loop 10 million times (busy) 

02. for($i=0;$i<10000000;$i++) { 

03. } 

04. $data= getrusage(); 

05. echo“User time: “. 

06. ($data['ru_utime.tv_sec'] + 

07. $data['ru_utime.tv_usec'] / 1000000); 

08. echo“System time: “. 

09. ($data['ru_stime.tv_sec'] + 

10. $data['ru_stime.tv_usec'] / 1000000); 

11. /* 输出

12. User time: 1.424592

13. System time: 0.004204

14. */

这花了大约14秒的CPU时间，几乎所有的都是用户的时间，因为没有系统调用。 

系统时间是CPU花费在系统调用上的上执行内核指令的时间。下面是一个例子： 

view source print ?

01. $start= microtime(true); 

02. // keep calling microtime for about 3 seconds 

03. while(microtime(true) – $start< 3) { 

04. } 

05. $data= getrusage(); 

06. echo“User time: “. 

07. ($data['ru_utime.tv_sec'] + 

08. $data['ru_utime.tv_usec'] / 1000000); 

09. echo“System time: “. 

10. ($data['ru_stime.tv_sec'] + 

11. $data['ru_stime.tv_usec'] / 1000000); 

12. /* prints

13. User time: 1.088171

14. System time: 1.675315

15. */

我们可以看到上面这个例子更耗CPU。 

5、获取系统常量 

PHP 提供非常有用的系统常量 可以让你得到当前的行号 (__LINE__)，文件 (__FILE__)，目录 (__DIR__)，函数名 (__FUNCTION__)，类名(__CLASS__)，方法名(__METHOD__) 和名字空间 (__NAMESPACE__)，很像C语言。 

我们可以以为这些东西主要是用于调试，当也不一定，比如我们可以在include其它文件的时候使用?__FILE__ (当然，你也可以在 PHP 5.3以后使用 __DIR__ )，下面是一个例子。 

view source print ?

1. // this is relative to the loaded script’s path 

2. // it may cause problems when running scripts from different directories 

3. require_once(‘config/database.php’); 

4. // this is always relative to this file’s path 

5. // no matter where it was included from 

6. require_once(dirname(__FILE__) . ‘/config/database.php’);

下面是使用 __LINE__ 来输出一些debug的信息，这样有助于你调试程序： 

view source

print ?

01. // some code 

02. // … 

03. my_debug(“some debug message”, __LINE__); 

04. /* 输出

05. Line 4: some debug message

06. */

07. // some more code 

08. // … 

09. my_debug(“another debug message”, __LINE__); 

10. /* 输出

11. Line 11: another debug message

12. */

13. functionmy_debug($msg, $line) { 

14. echo“Line $line: $msg\n”; 

15. }

6、生成唯一的id 

很多朋友都利用md5()来生成唯一的编号，但是md5()有几个缺点：1、无序，导致数据库中排序性能下降。2、太长，需要更多的存储空间。其实PHP中自带一个函数来生成唯一的id，这个函数就是uniqid()。下面是用法： 

view source print ?

01. // generate unique string 

02. echouniqid(); 

03. /* 输出

04. 4bd67c947233e

05. */

06. // generate another unique string 

07. echouniqid(); 

08. /* 输出

09. 4bd67c9472340

10. */

该算法是根据CPU时间戳来生成的，所以在相近的时间段内，id前几位是一样的，这也方便id的排序，如果你想更好的避免重复，可以在id前加上前缀，如： 

view source print ?

01. // 前缀 

02. echouniqid(‘foo_’); 

03. /* 输出

04. foo_4bd67d6cd8b8f

05. */

06. // 有更多的熵 

07. echouniqid(”,true); 

08. /* 输出

09. 4bd67d6cd8b926.12135106

10. */

11. // 都有 

12. echouniqid(‘bar_’,true); 

13. /* 输出

14. bar_4bd67da367b650.43684647

15. */

7、序列化 

PHP序列化功能大家可能用的比较多，也比较常见，当你需要把数据存到数据库或者文件中是，你可以利用PHP中的serialize() 和 unserialize()方法来实现序列化和反序列化，代码如下： 



view source print ?

01. // 一个复杂的数组 

02. $myvar= array( 

03. ‘hello’, 

04. 42, 

05. array(1,’two’), 

06. ‘apple’ 

07. ); 

08. // 序列化 

09. $string= serialize($myvar); 

10. echo$string; 

11. /* 输出

12. a:4:{i:0;s:5:”hello”;i:1;i:42;i:2;a:2:{i:0;i:1;i:1;s:3:”two”;}i:3;s:5:”apple”;}

13. */

14. // 反序例化 

15. $newvar= unserialize($string); 

16. print_r($newvar); 

17. /* 输出

18. Array

19. (

20. [0] => hello

21. [1] => 42

22. [2] => Array

23. (

24. [0] => 1

25. [1] => two

26. )

27. [3] => apple

28. )

29. */

如何序列化成json格式呢，放心，php也已经为你做好了，使用php 5.2以上版本的用户可以使用json_encode() 和 json_decode() 函数来实现json格式的序列化，代码如下： 



view source

print ?

01. // a complex array 

02. $myvar= array( 

03. ‘hello’, 

04. 42, 

05. array(1,’two’), 

06. ‘apple’ 

07. ); 

08. // convert to a string 

09. $string= json_encode($myvar); 

10. echo$string; 

11. /* prints

12. ["hello",42,[1,"two"],”apple”]

13. */

14. // you can reproduce the original variable 

15. $newvar= json_decode($string); 

16. print_r($newvar); 

17. /* prints

18. Array

19. (

20. [0] => hello

21. [1] => 42

22. [2] => Array

23. (

24. [0] => 1

25. [1] => two

26. )

27. [3] => apple

28. )

29. */

8、字符串压缩 

当我们说到压缩，我们可能会想到文件压缩，其实，字符串也是可以压缩的。PHP提供了 gzcompress() 和gzuncompress() 函数： 

view source print ?

01. $string= 

02. “Lorem ipsum dolor sit amet, consectetur 

03. adipiscing elit. Nunc ut elit id mi ultricies 

04. adipiscing. Nulla facilisi. Praesent pulvinar, 

05. sapien vel feugiat vestibulum, nulla dui pretium orci, 

06. non ultricies elit lacus quis ante. Lorem ipsum dolor 

07. sit amet, consectetur adipiscing elit. Aliquam 

08. pretium ullamcorper urna quis iaculis. Etiam ac massa 

09. sed turpis tempor luctus. Curabitur sed nibh eu elit 

10. mollis congue. Praesent ipsum diam, consectetur vitae 

11. ornare a, aliquam a nunc. In id magna pellentesque 

12. tellus posuere adipiscing. Sed non mi metus, at lacinia 

13. augue. Sed magna nisi, ornare in mollis in, mollis 

14. sed nunc. Etiam at justo in leo congue mollis. 

15. Nullam in neque eget metus hendrerit scelerisque 

16. eu non enim. Ut malesuada lacus eu nulla bibendum 

17. id euismod urna sodales. “; 

18. $compressed= gzcompress($string); 

19. echo“Original size: “. strlen($string).”\n”; 

20. /* 输出原始大小

21. Original size: 800

22. */

23. echo“Compressed size: “. strlen($compressed).”\n”; 

24. /* 输出压缩后的大小

25. Compressed size: 418

26. */

27. // 解压缩 

28. $original= gzuncompress($compressed);

几乎有50% 压缩比率。同时，你还可以使用 gzencode() 和 gzdecode() 函数来压缩，只不用其用了不同的压缩算法。 

以上就是8个开发必备的PHP功能，是不是都很实用呢？

