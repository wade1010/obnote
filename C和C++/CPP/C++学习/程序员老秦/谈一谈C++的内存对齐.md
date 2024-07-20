# 内存对齐的原则

三条：

1:数据成员对齐规则：结构/类(struct/class)的数据成员，第一个数据成员放在offset为0的地

方，以后每个数据成员存储的起始位置要从当前成员大小或者当前成员的子成员大小（只要该成员

有子成员，比如说是数组，结构体等)的整数倍开始（比如int在32位机为4字节，则要从4

的整数倍地址开始存储)。

2:结构体作为成员：如果一个结构A嵌套了其他结构体B,要从B最大元素整数倍地址开始存

值(stuct a果存有struct b,b里有char,int,double等远素那b应该从8的整数倍开始存储)

3:收尾工作：结构体的总大小，也就是sizeof的结果.必须是其内部最大成员的整数倍.不足的

要补齐。

4:如程序中有#pragma pack(n)预编译指令，则所有成员对齐以n字节为准(即偏移量是n的整数倍)，不再考虑当前类型以及最大结构体内类型

# 内存对齐作用：

1、 平台原因(移植原因)：不是所有的硬件平台都能访问任意地址上的任意数据的；某些硬件平台只能在某些地址处取某些特定类型的数据，否则抛出硬件异常。

2、 性能原因：经过内存对齐后，CPU的内存访问速度大大提升。

```
#include <iostream>
using namespace std;
/*
内存对齐的原则
三条：
1:数据成员对齐规则：结构/类(struct/class)的数据成员，第一个数据成员放在offset为0的地
方，以后每个数据成员存储的起始位置要从当前成员大小或者当前成员的子成员大小（只要该成员
有子成员，比如说是数组，结构体等)的整数倍开始（比如int在32位机为4字节，则要从4
的整数倍地址开始存储)。
2:结构体作为成员：如果一个结构A嵌套了其他结构体B,要从B最大元素整数倍地址开始存
值(stuct a果存有struct b,b里有char,int,double等远素那b应该从8的整数倍开始存储)
3:收尾工作：结构体的总大小，也就是sizeof的结果.必须是其内部最大成员的整数倍.不足的
要补齐。
4:如程序中有#pragma pack(n)预编译指令，则所有成员对齐以n字节为准(即偏移量是n的整数倍)，不再考虑当前类型以及最大结构体内类型
5:函数也不占sizeof的内容,static 变量属于类成员,不属于特定对象
6:带虚函数的类,有一个虚函数表指针,且属于对象首部,(且必须要和最大元素的大小对齐?没理解)
*/
void test()
{
    struct a1
    {
        double a1;  // 8
        char a2;    // 1+3
        int a4;     // 4
        char a5[2]; // 2+6
    };
    cout << sizeof(a1) << endl; // 24

    struct a2
    {
        int a;    // 4+4
        double d; // 8
        int b;    // 4+4
        double c; // 8
    };
    cout << sizeof(a2) << endl; // 32

    struct a3
    {
        int a;
        virtual void func(){};
        int b;
        double c;
    };
    cout << sizeof(a3) << endl; // 24  virtual也是占8个字节,但是为什么不跟a2一样占32个字节呢?就是因为virtual属于对象首部,也就是相当于下面的结构体
    // struct a3
    // {
    //     virtual void func(){};
    //     int a;
    //     int b;
    //     double c;
    // };
}
class animal
{
protected:
    int age;

public:
    virtual void print_age(void) = 0;
};
class dog : public animal
{
    int as;

public:
    dog() { this->age = 2; }
    ~dog() {}
    virtual void print_age(void)
    {
        cout << "wang. my age=" << this->age << endl;
    }
};
class cat : public animal
{
public:
    cat() { this->age = 1; }
    ~cat() {}
    virtual void print_age(void)
    {
        cout << "Miao,my age= " << this->age << endl;
    }
};
void test2()
{
    cat kitty; // 16
    dog jd;    // 16
    animal *pa;
    int *p = (int *)(&kitty);
    int *q = (int *)(&jd);
    p[1] = q[1];
    pa = &kitty;
    pa->print_age();
}
int main()
{
    // test();
    test2();
    return 0;
}

/*
64位系统下:
指针占8字节,虚函数是首部对象,所以0-8是虚函数指针,8-12是age,12-16是内存补齐部分
所以p[1]=q[1]没有修改age的值
打印内容:
Miao,my age= 1

32位系统下:
指针占4字节,虚函数是首部对象,所以0-4是虚函数指针,4-8是age
所以p[1]=q[1],修改age的值
打印内容:
Miao,my age= 2
 */
```