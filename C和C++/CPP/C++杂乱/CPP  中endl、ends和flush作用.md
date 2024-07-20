# C++中endl、ends和flush作用

std::endl、std::ends、std::flush的区别

```
头文件：#include <iostream>
更准确的说，其实是位于头文件：#include <ostream>

```

类型 描述

endl——Insert newline and flush (刷新缓存区并插入换行符)

ends——Insert null character (插入空字符)

flush——Flush stream buffer (刷新流缓存区)

可以看出，endl函数与flush的区别在于endl还进行了一步换行操作，而ends是增加了插入空字符；

```
#include <iostream>
using namespace std;
int main()
{
	std::cout << "1" ;
	std::cout << "2" << std::endl;
	std::cout << "3" << std::ends;
	std::cout << "4" << std::flush;
	std::cout << "5";
	system("pause");
	return 0;
}

```

![](https://gitee.com/hxc8/images3/raw/master/img/202407172242214.jpg)

打印 “1” 时，因为没有操作，所以紧接着就开始打印 “2”，但是在 “2”的后面有 std::endl ，所以缓存被刷新，并增加了换行符； “3” 是在第二行输出的，并且在 “3” 的后面紧接着输出了一个空字符 [’\0’] （std::ends控制的），然后才打印 “4”（只是刷新了缓存） 和 “5”。

flush的主要作用：

flush() 是把缓冲区的数据强行输出, 主要用在IO中，即清空缓冲区数据，一般在读写流(stream)的时候，数据是先被读到了内存中，再把数据写到文件中，当你数据读完的时候不代表你的数据已经写完了，因为还有一部分有可能会留在内存这个缓冲区中。这时候如果你调用了close()方法关闭了读写流，那么这部分数据就会丢失，所以应该在关闭读写流之前先flush()。

flush的作用是刷新缓冲区

例如

cout << “hello1”

cout <<“hello2”

cout << flush

调用flush后，可以立即把hello1和hello2输出给cout，而不是保存在缓冲区里面，等待系统定时刷新。

实际开发中，系统定时刷新间隔比较快，所以可能看不出区别。

endl, ends等默认都会刷新缓冲区，不一定非要用flush，例如：

cout << “hello1” << endl