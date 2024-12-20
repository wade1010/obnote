1- 编写模块化代码 

良好的PHP代码应该是模块化代码。PHP的面向对象的编程功能是一些特别强大的工 具，可以把你的应用程序分解成函数或方法。你应该尽可能多的从你的应用程序的服务器端分开前端的HTML/CSS/JavaScript代码。你也可以在 任何PHP框架上遵循MVC（模型-视图-控制器）模式。 

2- 代码编写规范

良好的PHP代码应该有一套完整的代码编写规范。通过对变量和函数的命名，统一的方法访问数据库和对错误的处理，以及同样的代码缩进方式等来达到编程规范，这样可以使你的代码更具可读性。  



3- 编写可移植代码

良好的PHP代码应该是可移植的。你可以使用php的现有功能，如魔术引号和短标签。试着了解你的需求，然后通过适应PHP特性来编写代码让代码独立、可移植。  



4- 编写安全代码

良 好的PHP代码应该是安全的。PHP5提供了出色的性能和灵活性。但是安全问题完全在于开发人员。对于一个专业的PHP开发人员来说，深入理解重大安全漏 洞是至关重要的，如：跨站点脚本(XSS)、跨站请求伪造(CSRF)、代码注入漏洞、字符编码漏洞。通过使用PHP的特殊功能和函数， 如：mysql_real_escape_string等等，你可以编写出安全的代码。  



5- 代码注释

代码注释是代码的重要组成部分。通过代码注释可以知道该变量或函数是做什么的，这将在今后的代码维护中十分有用。  



6- 避免短标签

把所有用到短标签的替换成完整的PHP标签。  



7- 使用单引号代替双引号

字符串始终使用单引号代替双引号，以避免PHP搜索字符串内的变量导致的性能下降。 用单引号代替双引号来包含字符串，这样做会更快一些。因为PHP会在双引号包围的字符串中搜寻变量，单引号则不会 



8- 转义字符串输出

使用ENT_QUOTES作参数传递给htmlspecialchars函数，以确保单引号(')也转换成HTML实体，这是一个好习惯。  



9- 使用逗号分隔字符串输出

通过echo语句输出使用逗号(,)分隔的字符串，要比使用字符串连接操作符(.)的性能更好。  



10- 输出前检查传来的值

输出前检查传过来的值$_GET['query']。使用isset或empty函数，可以用来检查变量是否为null值。

11- 其他

- 如果能将类的方法定义成static，就尽量定义成static，它的速度会提升将近4倍。

- $row['id'] 的速度是$row[id]的7倍。

- echo 比 print 快，并且使用echo的多重参数（译注：指用逗号而不是句点）代替字符串连接，比如echo $str1,$str2。

- 在执行for循环之前确定最大循环数，不要每循环一次都计算最大值，最好运用foreach代替。

- 注销那些不用的变量尤其是大数组，以便释放内存。

- 尽量避免使用__get，__set，__autoload。

- require_once()代价昂贵。

- include文件时尽量使用绝对路径，因为它避免了PHP去include_path里查找文件的速度，解析操作系统路径所需的时间会更少。

- 如果你想知道脚本开始执行（译注：即服务器端收到客户端请求）的时刻，使用$_SERVER['REQUEST_TIME']要好于time()。

- 函数代替正则表达式完成相同功能。

- str_replace函数比preg_replace函数快，但strtr函数的效率是str_replace函数的四倍。

- 如果一个字符串替换函数，可接受数组或字符作为参数，并且参数长度不太长，那么可以考虑额外写一段替换代码，使得每次传递参数是一个字符，而不是只写一行代码接受数组作为查询和替换的参数。

- 使用选择分支语句（译注：即switch case）好于使用多个if，else if语句。

- 用@屏蔽错误消息的做法非常低效，极其低效。

- 打开apache的mod_deflate模块，可以提高网页的浏览速度。

- 数据库连接当使用完毕时应关掉，不要用长连接。

- 错误消息代价昂贵。

- 在方法中递增局部变量，速度是最快的。几乎与在函数中调用局部变量的速度相当。

- 递增一个全局变量要比递增一个局部变量慢2倍。

- 递增一个对象属性（如：$this->prop++）要比递增一个局部变量慢3倍。

- 递增一个未预定义的局部变量要比递增一个预定义的局部变量慢9至10倍。

- 仅定义一个局部变量而没在函数中调用它，同样会减慢速度（其程度相当于递增一个局部变量）。PHP大概会检查看是否存在全局变量。

- 方法调用看来与类中定义的方法的数量无关，因为我（在测试方法之前和之后都）添加了10个方法，但性能上没有变化。

- 派生类中的方法运行起来要快于在基类中定义的同样的方法。

- 调用带有一个参数的空函数，其花费的时间相当于执行7至8次的局部变量递增操作。类似的方法调用所花费的时间接近于15次的局部变量递增操作。

- Apache解析一个PHP脚本的时间要比解析一个静态HTML页面慢2至10倍。尽量多用静态HTML页面，少用脚本。

- 除非脚本可以缓存，否则每次调用时都会重新编译一次。引入一套PHP缓存机制通常可以提升25%至100%的性能，以免除编译开销。

- 尽量做缓存，可使用memcached。memcached是一款高性能的内存对象缓存系统，可用来加速动态Web应用程序，减轻数据库负载。对运算码 (OP code)的缓存很有用，使得脚本不必为每个请求做重新编译。

- 当操作字符串并需要检验其长度是否满足某种要求时，你想当然地会使用strlen()函数。此函数执行起来相当快，因为它不做任何计算，只返回在 zval 结构（C的内置数据结构，用于存储PHP变量）中存储的已知字符串长度。但是，由于strlen()是函数，多多少少会有些慢，因为函数调用会经过诸多步 骤，如字母小写化（译注：指函数名小写化，PHP不区分函数名大小写）、哈希查找，会跟随被调用的函数一起执行。在某些情况下，你可以使用isset() 技巧加速执行你的代码。

（举例如下）  if (strlen($foo) < 5   echo Foo is too short  pre>

- （与下面的技巧做比较）  if (!isset($foo[5])) { echo 'Foo is too short'; }    

调用isset()恰巧比strlen()快，因为与后者不同的是，isset()作为一种语言结构，意味着它的执行不需要函数查找和字母小写化。也就是说，实际上在检验字符串长度的顶层代码中你没有花太多开销。

- 当执行变量$i的递增或递减时，$i++会比++$i慢一些。这种差异是PHP特有的，并不适用于其他语言，所以请不要修改你的C或Java代码 并指望它们能立即变快，没用的。++$i更快是因为它只需要3条指令(opcodes)，$i++则需要4条指令。后置递增实际上会产生一个临时变量，这 个临时变量随后被递增。而前置递增直接在原值上递增。这是最优化处理的一种，正如Zend的PHP优化器所作的那样。牢记这个优化处理不失为一个好主意， 因为并不是所有的指令优化器都会做同样的优化处理，并且存在大量没有装配指令优化器的互联网服务提供商（ISPs）和服务器。

- 并不是事必面向对象(OOP)，面向对象往往开销很大，每个方法和对象调用都会消耗很多内存。

- 并非要用类实现所有的数据结构，数组也很有用。

- 不要把方法细分得过多，仔细想想你真正打算重用的是哪些代码？

- 当你需要时，你总能把代码分解成方法。

- 尽量采用大量的PHP内置函数。

- 如果在代码中存在大量耗时的函数，你可以考虑用C扩展的方式实现它们。

- 评估检验(profile)你的代码。检验器会告诉你，代码的哪些部分消耗了多少时间。Xdebug调试器包含了检验程序，评估检验总体上可以显示出代码的瓶颈。

- mod_zip可作为Apache模块，用来即时压缩你的数据，并可让数据传输量降低80%。

- 在可以用file_get_contents替代file、fopen、feof、fgets等系列方法的情况下，尽量用file_get_contents，因为他的效率高得多！但是要注意file_get_contents在打开一个URL文件时候的PHP版本问题；

- 尽量的少进行文件操作，虽然PHP的文件操作效率也不低的；

- 优化Select SQL语句，在可能的情况下尽量少的进行Insert、Update操作(在update上，我被恶批过)；

- 尽可能的使用PHP内部函数（但是我却为了找个PHP里面不存在的函数，浪费了本可以写出一个自定义函数的时间，经验问题啊！）；

- 循环内部不要声明变量，尤其是大变量：对象(这好像不只是PHP里面要注意的问题吧？)；

- 多维数组尽量不要循环嵌套赋值；

- 在可以用PHP内部字符串操作函数的情况下，不要用正则表达式；

- foreach效率更高，尽量用foreach代替while和for循环；

- “用i+=1代替i=i+1。符合c/c++的习惯，效率还高”；

- 对global变量，应该用完就unset()掉；

