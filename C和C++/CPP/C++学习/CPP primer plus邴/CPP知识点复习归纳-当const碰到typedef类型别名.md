已知：

typedef char *pstring;

则：

const pstring cstr = nullptr;

问cstr是什么类型？

typedef char *pstring;---》pstring是char *的别名

const限定的指针有两种

1）常量指针 char * const ptr;

指针只能指向特定的对象，不能指向其它的对象，但是可以通过常量指针修改指向的对象

char * const cstr = nullptr;

2）指向常量的指针（不能通过该指针修改指向的对象，但是该指针可以指向其它对象）

const char * cstr = nullptr; ->cstr 指向常量的指针

 

```
#include <iostream>
using namespace std;
typedef char *pstring;
void test()
{
    char c = 'C';
    const pstring cstr = &c;
    char b = 'B';

    //测试1 修改指向的值
    *cstr = 'B';

    //测试2 修改指向
    // cstr = &b; //报错如下
    /* main.cpp:9:13: error: assignment of read-only variable 'cstr'
     cstr = &b; */

    //  通过测试可以得出,const pstring是常量指针
}
int main()
{
    test();
    return 0;
}
```

 const pstring cstr

这里const限定的是pstring，pstring是指针。那么const限定的是pstring，这是一个常量指针

const char * cstr = nullpt

这里const修饰的是char 

const靠近谁限定谁