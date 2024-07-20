CPP之C风格字符串

c++中的string类，封装了C风格的字符串。 使用方便，能自动扩展，不用担心内存问题

在某些场景中，C风格字符串更方便，更高效

C标准库、Linux系统和开源库，大部分开源库一定有C语言版本，但不一定有C++版本，还有数据库接口函数，例如MySQL，只有C语言版本，没有C++版本。

C语言约定：如果字符串(char)数组的末尾包含了空字符\0（也就是0），那么该数组中的内容就是一个字符串。

这是一个数组，不是字符串。

![](https://gitee.com/hxc8/images3/raw/master/img/202407172224937.jpg)

这是一个字符串数组，也是一个字符串

![](https://gitee.com/hxc8/images2/raw/master/img/202407172224277.jpg)

c++也遵守这个约定，string类中的字符串，最后也有一个空字符，只是没有显示出来而已。

```
#include "iostream"
using namespace std;

void test()
{
    string str = "abc";
    cout << "str[0]" << str[0] << endl;
    cout << "str[1]" << str[1] << endl;
    cout << "str[2]" << str[2] << endl;
    cout << "str[3]" << str[3] << endl;
}
int main()
{
    test();
    return 0;
}
str[0]a
str[1]b
str[2]c
str[3]
```

```
#include "iostream"
using namespace std;

void test()
{
    string str = "abc";
    cout << "str[0]" << (int)str[0] << endl;
    cout << "str[1]" << (int)str[1] << endl;
    cout << "str[2]" << (int)str[2] << endl;
    cout << "str[3]" << (int)str[3] << endl;
}
int main()
{
    test();
    return 0;
}
str[0]97
str[1]98
str[2]99
str[3]0
```

因为字符串需要用0结尾，所以在声明字符串数组的时候，要预留一个字节用来存放0.

 例如：

char name[21]; //声明一个最多存放20个英文字符或10个中文的字符串(中文占两个字节)

1）初始化方法

```
#include "iostream"
using namespace std;

void test()
{
    char name1[11]; //可以存放1个字符，没有初始化，里面是垃圾值

    for (int i = 0; i < 11; i++)
    {
        cout << (int)name1[i] << " ";
    }
    cout << endl;
}
int main()
{
    test();
    return 0;
}
0 0 0 0 0 0 0 0 0 0 0
只打印name1的时候，输出全部是11个0，看上去是没问题的，但是后面有例子(还有别的打印)就可以看出不初始化不全部都是0.
```

    char name1[11];             //可以存放1个字符，没有初始化，里面是垃圾值,但不影响使用，如果单独打印的时候都是0


    char name2[11] = "hello";   //初始化内容为 hello


    char name3[] = {"hello"};   //初始化内容为 hello,系统会自动添加\0,数组长度是6.


    char name4[11] = {"hello"}; //初始化内容为 hello


    char name5[11]{"hello"};    //初始化内容为 hello


    char name6[11] = {0};       //把全部的元素初始化为\0    这种方法用的最多

```
#include "iostream"
using namespace std;

void test()
{
//可以存放1个字符，没有初始化，里面是垃圾值，如果单独打印的时候都是0，
//但是在这个例子中，有别的打印就可以看出有问题了
    char name1[11];
    char name2[11] = "hello";   //初始化内容为 hello
    char name3[] = {"hello"};   //初始化内容为 hello,系统会自动添加\0,数组长度是6.
    char name4[11] = {"hello"}; //初始化内容为 hello
    char name5[11]{"hello"};    //初始化内容为 hello
    char name6[11] = {0};       //把全部的元素初始化为\0
    for (int i = 0; i < 11; i++)
    {
        cout << (int)name1[i] << " ";
    }
    cout << endl;
    for (int i = 0; i < 11; i++)
    {
        cout << (int)name2[i] << " ";
    }
    cout << endl;
    for (int i = 0; i < 11; i++)
    {
        cout << (int)name3[i] << " ";
    }
    cout << endl;
    for (int i = 0; i < 11; i++)
    {
        cout << (int)name4[i] << " ";
    }
    cout << endl;
    for (int i = 0; i < 11; i++)
    {
        cout << (int)name5[i] << " ";
    }
    cout << endl;
    for (int i = 0; i < 11; i++)
    {
        cout << (int)name6[i] << " ";
    }
    cout << endl;
}
int main()
{
    test();
    return 0;
}
0 0 0 96 24 64 0 0 0 0 0 
104 101 108 108 111 0 0 0 0 0 0 
104 101 108 108 111 0 104 101 108 108 111 
104 101 108 108 111 0 0 0 0 0 0 
104 101 108 108 111 0 0 0 0 0 0 
0 0 0 0 0 0 0 0 0 0 0

可以看出这个时候name1就有问题了
```

2） 清空字符串

```
#include "iostream"
#include <cstring>
using namespace std;

void test()
{
    char name[11] = {"hello"};

    //清空字符串

    name[0] = 0; //不规范 有隐患 不推荐
    for (int i = 0; i < 11; i++)
    {
        cout << (int)name[i] << " ";
    }

    memset(name, 0, sizeof(name)); //把全部元素置为0
}
int main()
{
    test();
    return 0;
}
0 101 108 108 111 0 0 0 0 0 0 
0 0 0 0 0 0 0 0 0 0 0 

```

3）字符串赋值或赋值 strcpy()

char *strcpy(char*dest,const char*src);

C++风格的字符串可以用等号赋值，很方便，但是C风格的不能用，只能用strcpy()

功能：将参数src字符串拷贝至参数dest所指的地址。

返回值：返回参数dest的字符串起始地址。

复制完字符串后，会在dest后追加0.

**如果参数dest所指向的内存空间不够大，会导致数组的越界。**

```
#include "iostream"
#include <cstring>
using namespace std;

void test()
{
    char name[11] = {"hello"};

    cout << endl;
    strcpy(name, "world");
    for (int i = 0; i < 11; i++)
    {
        cout << (int)name[i] << " ";
    }
    cout << endl;
    strcpy(name, "worldworldworld");
    for (int i = 0; i < 11; i++)
    {
        cout << (int)name[i] << " ";
    }
    cout << endl;
}
int main()
{
    test();
    return 0;
}
main.cpp: In function 'void test()':
main.cpp:16:11: warning: 'void* __builtin_memcpy(void*, const void*, long long unsigned int)' writing 16 bytes into a region of size 11 overflows the destination [-Wstringop-overflow=]
     strcpy(name, "worldworldworld");
     ~~~~~~^~~~~~~~~~~~~~~~~~~~~~~~~
 
119 111 114 108 100 0 0 0 0 0 0 
119 111 114 108 100 119 111 114 108 100 119 
```

strncpy()

char* strncpy(char* dest,const char* src,const size_t n);

功能，把src前n个字符的内容赋值到dest中

返回值：dest字符串起始地址

如果src字符串长度小于n，则拷贝完字符串后，在dest后追加0，直到n个。

如果src的长度大于等于n，就截取src的前n个字符，不会在dest后追加0。

如果参数dest所指的内存空间不够大，会导致数组的越界

```
#include "iostream"
#include <cstring>
using namespace std;

void test()
{
    char name[11];

    memset(name, 0, sizeof(name));

    // strncpy(name, "hello",8);//会自动补0
    strncpy(name, "hello", 3); //不会自动补0,可以用memset(name,0,sizeof(name))先清空name;
    for (int i = 0; i < 11; i++)
    {
        cout << (int)name[i] << " ";
    }
    cout << endl;
}
int main()
{
    test();
    return 0;
}
这里有个坑，就是src小于n的时候，不会自动补0，可以先用memset清空
```

```
#include "iostream"
#include <cstring>
using namespace std;

void test()
{
    char name[11];

    memset(name, 0, sizeof(name)); //打开注释,name打印,除了hel则3个字符,后面就全部是0了

    // strncpy(name, "hello",8);//会自动补0
    strncpy(name, "hello", 3); //不会自动补0,可以用memset(name,0,sizeof(name))先清空name;
    for (int i = 0; i < 11; i++)
    {
        cout << (int)name[i] << " ";
    }
    cout << endl;
    char name2[11] = "hello";   //初始化内容为 hello
    char name3[] = {"hello"};   //初始化内容为 hello,系统会自动添加\0,数组长度是6.
    char name4[11] = {"hello"}; //初始化内容为 hello
    char name5[11]{"hello"};    //初始化内容为 hello
    char name6[11] = {0};       //把全部的元素初始化为\0    这种方法用的最多
    for (int i = 0; i < 11; i++)
    {
        cout << (int)name2[i] << " ";
    }
    cout << endl;
    for (int i = 0; i < 11; i++)
    {
        cout << (int)name3[i] << " ";
    }
    cout << endl;
    for (int i = 0; i < 11; i++)
    {
        cout << (int)name4[i] << " ";
    }
    cout << endl;
    for (int i = 0; i < 11; i++)
    {
        cout << (int)name5[i] << " ";
    }
    cout << endl;
    for (int i = 0; i < 11; i++)
    {
        cout << (int)name6[i] << " ";
    }
    cout << endl;
}
int main()
{
    test();
    return 0;
}
104 101 108 0 0 0 0 0 0 0 0 
104 101 108 108 111 0 0 0 0 0 0 
104 101 108 108 111 0 104 101 108 108 111 
104 101 108 108 111 0 0 0 0 0 0 
104 101 108 108 111 0 0 0 0 0 0 
0 0 0 0 0 0 0 0 0 0 0 
```

5）获取字符串长度 strlen();

![](https://gitee.com/hxc8/images2/raw/master/img/202407172224748.jpg)

```
#include "iostream"
#include <cstring>
using namespace std;

void test()
{
    char name[11] = {"hello\0f"};
    cout << strlen(name) << endl;
    char name1[11] = {"hello"};
    name1[1] = 0;
    cout << strlen(name1) << endl;
}
int main()
{
    test();
    return 0;
}
5
1
```

6)字符串拼接 strcat()

![](https://gitee.com/hxc8/images3/raw/master/img/202407172225808.jpg)

![](https://gitee.com/hxc8/images3/raw/master/img/202407172225061.jpg)

![](https://gitee.com/hxc8/images3/raw/master/img/202407172225163.jpg)

 

11)

可以把C风格的字符串用于包含了string类型的赋值拼接等表达式中。

注意事项：

1 字符串的结尾标志是0，按照约定，在处理字符串的时候，会从起始位置开始搜索0，一直找下去，找到为止（不会判断数组是否越界）

2 结尾标志0后面的都是垃圾内容

3 字符串在每次使用前都要初始化，减少入坑的可能，是每次，不是第一次。

4 不要在子函数中对字符指针用sizeof运算符，所以，不能在子函数中对传入的字符串进行初始化，除非字符串的长度也作为参数传入到子函数中。