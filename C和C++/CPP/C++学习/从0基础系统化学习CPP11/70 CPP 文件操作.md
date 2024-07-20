![](https://gitee.com/hxc8/images2/raw/master/img/202407172219234.jpg)

### 写入文件：

文本文件一般以行的形式组织数据。

包含头文件：#include <fstream>

类：ofstream（output file stream）

ofstream打开文件的模式（方式）：

对于ofstream，不管用哪种模式打开文件，如果文件不存在，都会创建文件。

ios::out     		缺省值：会截断文件内容。

ios::trunc  		截断文件内容。（truncate）

ios::app   		不截断文件内容，只在文件未尾追加文件。（append）

```
#include <iostream>
#include <fstream>
using namespace std;

void test()
{
    // 文件名一般用全路径，书写的方法如下：
    //  1）"D:\data\txt\test.txt"       // 错误。
    //  2）R"(D:\data\txt\test.txt)"   // 原始字面量，C++11标准。
    //  3）"D:\\data\\txt\\test.txt"   // 转义字符。
    //  4）"D:/tata/txt/test.txt"        // 把斜线反着写。
    //  5）"/data/txt/test.txt"          //  Linux系统采用的方法。
    string filename = R"(W:\workspace\test.txt)";
    ofstream ofs(filename);
    //如果目录不存在,则会打开失败,如果目录存在,但是文件不存在,会自动创建文件
    if (!ofs.is_open())
    {
        cout << "打开失败" << endl;
        return;
    }

    ofs << "1,2,3\n";
    ofs << "4,5,6\n";

    ofs.close();
    cout << "ok" << endl;
}
int main()
{
    test();
    return 0;
}
```

### 读取文件

包含头文件：#include <fstream>

类：ifstream

ifstream打开文件的模式（方式）：

对于ifstream，如果文件不存在，则打开文件失败。

ios::in     		缺省值。

```
#include <iostream>
#include <fstream>
using namespace std;

void test()
{
    ifstream ifs("test.txt");
    if (!ifs.is_open())
    {
        cout << "打开失败" << endl;
        return;
    }
    //第一种方式
    // string buffer;
    // while (getline(ifs, buffer))
    // {
    //     cout << buffer << endl;
    // }
    // ifs.close();

    //第二种方式
    //注意一定要保证缓冲区足够大,否则超过缓冲区那一行以及后面的所有行都不会读取出来
    //搞太大浪费空间,所以不建议使用
    // char buffer[16];
    // while (ifs.getline(buffer, 15))
    // {
    //     cout << buffer << endl;
    // }
    // ifs.close();

    //第三种方式
    string buffer;
    while (ifs >> buffer)
    {
        cout << buffer << endl;
    }
    ifs.close();

    //读取文件还有别的方式,但是不怎么推荐
}
int main()
{
    test();
    return 0;
}
```

### 写入二进制文件

先搞清楚文本数据和二进制数据的区别

文本数据可以简单的理解为字符串

 string str="131425";

这是一个字符串，长度是6，需要6个字节的内存。

那么在内存分布是这样的，每个字节存放一个字符，字符在内存中存放的是ASCII码，不是符号，

所以内存中存放的是49,51,49,52,50,53

对计算机来说，全部的数据都是用二进制表示的，所以内存中真正存放的是这些

![](https://gitee.com/hxc8/images2/raw/master/img/202407172219655.jpg)

c++存储整数的时候，需要4字节的内存空间，并且4字节要作为一整体来安排，把下面长长的二进制存进去，如果把每个字节分开来看，分别是（从右往左）97,1,2,0

但是把这块内存分开来看是没有意义的，

![](https://gitee.com/hxc8/images2/raw/master/img/202407172219961.jpg)

文件操作

写文件：把内存中的数据转移到磁盘文件中

读文件：把磁盘文件中的数据转移到内存中

内存和硬盘都是存储设备，本质上没有区别。

文本文件和二进制文件：

- 文本文件：存放的是字符串，以行的方式组织数据，每个字节都是有意义的符号。、

- 文本文件：由可显示的字符组成，方便阅读（解码），占用的空间比较多

- 二进制文件：存放的不一定是字符串，以数据类型组织数据，内容要作为一个整体来考虑，单个字节没有意义。

- 二进制文件：由比特0和1组成，组织数据的格式与文件用途有关，不方便阅读（解码）。为了节省存储空间，还可能采用压缩技术。为了保证数据安全，也可能采用加密技术。

操作文本文件和二进制文件的一些细节：

1）在windows平台下，文本文件的换行标志是"\r\n"。

2）在linux平台下，文本文件的换行标志是"\n"。

3）在windows平台下，如果以文本方式打开文件，写入数据的时候，系统会将"\n"转换成"\r\n"；读取数据的时候，系统会将"\r\n"转换成"\n"。 如果以二进制方式打开文件，写和读都不会进行转换。(微软挺恶心，非得自己搞一套标准)

4）在Linux平台下，以文本或二进制方式打开文件，系统不会做任何转换。

5）以文本方式读取文件的时候，遇到换行符停止，读入的内容中没有换行符；以二制方式读取文件的时候，遇到换行符不会停止，读入的内容中会包含换行符（换行符被视为数据）。

6）在实际开发中，从兼容和语义考虑，一般：

a）以文本模式打开文本文件，用行的方法操作它；

b）以二进制模式打开二进制文件，用数据块的方法操作它；

c）以二进制模式打开文本文件和二进制文件，用数据块的方法操作它，这种情况表示不关心数据的内容。（例如复制文件和传输文件）

d）不要以文本模式打开二进制文件，也不要用行的方法操作二进制文件，可能会破坏二进制数据文件的格式，也没有必要。（因为二进制文件中的某字节的取值可能是换行符，但它的意义并不是换行，可能是整数n个字节中的某个字节）

### 文件操作-随机存取

一、fstream类

fstream类既可以读文本/二进制文件，也可以写文本/二进制文件。

fstream类的缺省模式是ios::in | ios::out，如果文件不存在，则创建文件；但是，不会清空文件原有的内容。（g++测试是清空的，VS没测试）

普遍的做法是：

1）如果只想写入数据，用ofstream；如果只想读取数据，用ifstream；如果想写和读数据，用fstream，这种情况不多见。不同的类体现不同的语义。

2）在Linux平台下，文件的写和读有严格的权限控制。（需要的权限越少越好）

### 文件的位置指针

对文件进行读/写操作时，文件的位置指针指向当前文件读/写的位置。

很多资料用“文件读指针的位置”和“文件写指针的位置”，容易误导人。不管用哪个类操作文件，文件的位置指针只有一个。

1）获取文件位置指针

ofstream类的成员函数是tellp()；ifstream类的成员函数是tellg()；fstream类两个都有，效果相同。

std::streampos tellp();

std::streampos tellg();

2）移动文件位置指针

ofstream类的函数是seekp()；ifstream类的函数是seekg()；fstream类两个都有，效果相同。

方法一：

std::istream & seekg(std::streampos _Pos);

fin.seekg(128);   // 把文件指针移到第128字节。

fin.seekp(128);   // 把文件指针移到第128字节。

fin.seekg(ios::beg) // 把文件指针移动文件的开始。

fin.seekp(ios::end) // 把文件指针移动文件的结尾。

方法二：

std::istream & seekg(std::streamoff _Off,std::ios::seekdir _Way);

在ios中定义的枚举类型：

enum seek_dir {beg, cur, end};  // beg-文件的起始位置；cur-文件的当前位置；end-文件的结尾位置。

fin.seekg(30, ios::beg);    // 从文件开始的位置往后移30字节。

fin.seekg(-5, ios::cur);     // 从当前位置往前移5字节。

fin.seekg( 8, ios::cur);     // 从当前位置往后移8字节。

fin.seekg(-10, ios::end);   // 从文件结尾的位置往前移10字节。

![](https://gitee.com/hxc8/images2/raw/master/img/202407172219247.jpg)

在第一行文件中重位置0开始写入abc,结果是第三行，而不是第二行。

### 文件操作-缓冲区及流状态

一、文件缓冲区

文件缓冲区（缓存）是系统预留的内存空间，用于存放输入或输出的数据。

根据输出和输入流，分为输出缓冲区和输入缓冲区。

注意，在C++中，每打开一个文件，系统就会为它分配缓冲区。不同的流，缓冲区是独立的。

程序员不用关心输入缓冲区，只关心输出缓冲区就行了。

在缺省模式下，输出缓冲区中的数据满了才把数据写入磁盘，但是，这种模式不一定能满足业务的需求。

输出缓冲区的操作：

1）flush()成员函数

刷新缓冲区，把缓冲区中的内容写入磁盘文件。

2）endl

换行，然后刷新缓冲区。

3）unitbuf

fout << unitbuf;

设置fout输出流，在每次操作之后自动刷新缓冲区。

4）nounitbuf

fout << nounitbuf;

设置fout输出流，让fout回到缺省的缓冲方式。

二、流状态

流状态有三个：eofbit、badbit和failbit，取值：1-设置；或0-清除。

当三个流状成都为0时，表示一切顺利，good()成员函数返回true。

1）eofbit

当输入流操作到达文件未尾时，将设置eofbit。

eof()成员函数检查流是否设置了eofbit。

2）badbit

无法诊断的失败破坏流时，将设置badbit。（例如：对输入流进行写入；磁盘没有剩余空间）。

bad()成员函数检查流是否设置了badbit。

3）failbit

当输入流操作未能读取预期的字符时，将设置failbit（非致命错误，可挽回，一般是软件错误，例如：想读取一个整数，但内容是一个字符串；文件到了未尾）I/O失败也可能设置failbit。

fail()成员函数检查流是否设置了failbit。

4）clear()成员函数清理流状态。

5）setstate()成员函数重置流状态。