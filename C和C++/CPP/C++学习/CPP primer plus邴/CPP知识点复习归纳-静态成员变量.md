静态类成员特点：

无论创建多少个对象，程序都只创建一个静态类变量的副本。也就是说，类的所有对象共享同一个静态成员。

静态成员变量其类型可以是当前类的类型，而其它普通成员变量是可不可以的，除非是指针。

```
#include <iostream>
using namespace std;
class Person
{
public:
    string name;
    static Person p1;
    // Person p2; //报错
    Person *ptr;
};
void test()
{
    Person p;
    cout << sizeof(Person) << endl;
}
int main()
{
    test();
    return 0;
}
```

类定义一个对象的时候，才真正给这个类分配内存空间，

分析1，只有

 string name;

static Person p1;

真正占用内存空间就是string类型的name，还有一个就是已经确定大小的Person p1，因为p1在编译之前已经确定了大小，静态成员已经给他分配好内存空间。

定义Person p的时候，所占内存空间等于 string name所占空间 加上 Person p1所占的空间，

p1占多大空间呢？要看Person占多少空间，这里就是string name所占空间大小。

所以定义Person p，就可以确定她的大小，它的本身普通成员变量的大小，再加上一个不在类范围内的静态成员变量所占的内存空间。

Person p; name + p1 -->固定大小 + 固定大小

分析2,只有

    string name;


    Person p2;

等于 name所占空间加上p2所占空间，p2占多大空间？p2不是静态的，它并没有提前开辟出来内存空间，p2占多大要看Person占多大，Person占多大，又返回来name占的空间加上p2所占的空间，如此循环，

Person p:固定大小的name + 不确定大小的内存

所以会编译报错

分析3，只有

    string name;


   Person *ptr;

因为指针所占用的内存空间是固定的，

Person p :固定大小name + 固定大小指针