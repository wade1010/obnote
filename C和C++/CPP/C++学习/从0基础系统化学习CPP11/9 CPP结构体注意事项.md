注意：

1 结构名是标识符

2 结构体的成员可以是任意数据类型

3 定义结构体描述的代码可以放在程序的人和地方，一般放在main函数的上面或头文件中。

4 结构体成员可以用C++的类（如string），但是不提倡。

5 在C++中，结构体重可以有函数，但是不提倡。

6 在C++11中，定义结构体的时候一指定初始值。

struct perople{

string name;//不提倡

}

建议

struct perople{

char name[21];

}

C++11可以有初始值

struct perople{

char name[21]="测试";

}

![](https://gitee.com/hxc8/images2/raw/master/img/202407172224685.jpg)

初学阶段，第一种初始化方法用的比较多，但是实际开发中，第二种方法多。

3）使用结构体

在C++程序中，用成员运算符(.)来访问结构体的每个成员。结构体中的每个成员具备普通变量的全部特征。

语法：结构体变量名.结构体成员名;

4)内存占用的大小

用sizeof运算符可以得到结构体占用内存的大小。

注意：结构体占用内存的大小不一定等于全部成员占用内存之和。

内存对齐： #pragma pack(字节数)   默认是8 可以用1

![](https://gitee.com/hxc8/images2/raw/master/img/202407172224443.jpg)

```
#include "iostream"

//#pragma pack(1)//打开这行注释 结构体大小就是35个字节了
using namespace std;
struct people {
    char name[21];//21
    int age;//4
    double weight;//8
    char sex;//1
    bool isVIP;//1
};

void test() {
    struct people p = {0};
    cout << "结构体大小:" << sizeof(p) << endl;//默认的，不是全部成员占用的35个字节而是48个字节
//    struct people {
//        char name[21];//默认占3个8字节 24
//        int age;//默认占1个8字节  8
//        double weight;//默认占用1个8字节  8
//        char sex;//默认占用1个8字节 8
//        bool isVIP;//默认占用1个8字节,但是前面的sex没占满8字节，且字节和sex的和也不足8字节，所以和sex共同占用8字节即可
//    };

//但是如果结构体顺序如下
//    struct people {
//        char sex;
//        char name[21];
//        bool isVIP;
//        int age;
//        double weight;
//    };
    //默认情况下就是占用40个字节 sex name isVIP占用3个8字节也就是24字节
}

int main() {
    test();
    return 0;
}
```

5)清空结构体

创建的结构体变量如果没有初始化，成员中有垃圾值。

用memset函数可以把结构体中全部成员清零，（只适用于C++基本数据类型）bzero()函数也可以。

```
#include "iostream"

using namespace std;
struct people {
    char name[21];
    int age;
    double weight;
    char sex;
    bool isVIP;
};

void test() {
    struct people p;
    memset(&p, 0, sizeof(p));
    bzero(&p, sizeof(people));
    cout << p.age << endl;
}

int main() {
    test();
    return 0;
}
```

6)结构体的赋值

用memcpy()函数把结构体中全部元素赋值到另一个想同类型的结构体（只适用于C++基本数据类型）。

也可以直接用等号（只适用C++基本数据类型）

```
#include "iostream"

using namespace std;
struct people {
    char name[21];
    int age;
    double weight;
    char sex;
    bool isVIP;
};

void test() {
    struct people p;
    memset(&p, 0, sizeof(p));
    bzero(&p, sizeof(people));
    cout << p.age << endl;
    p.age = 11;
    struct people p2;
    memcpy(&p2, &p, sizeof(p));
    cout << p2.age << endl;
}

int main() {
    test();
    return 0;
}
0
11
```

```
#include "iostream"
#include "cstring"
//结构体数组
using namespace std;
struct people {
    char name[21];
    int age;
    double weight;
    char sex;
    bool isVIP;
};

void test() {
    people ps[3];
    memset(ps, 0, sizeof(ps));//清空整个数组
    cout << "数组表示法" << endl;
    //数组表示法
    strcpy(ps[0].name, "hello");
    ps[0].age = 22;
    ps[0].weight = 11.2;
    ps[0].sex = 'X';
    ps[0].isVIP = true;

    ps[1] = {"hulu", 3, 44.3, 'Y', true};//C++11标准的语法
    ps[2] = {"haha", 4, 21.3, 'X', false};//C++11标准的语法
    for (int i = 0; i < 3; i++) {
        cout << "姓名：" << ps[i].name << endl;
    }
    cout << "指针表示法：不常用" << endl;
    //指针表示法：不常用
    strcpy((ps + 0)->name, "ptr_huhu");
    *(ps + 1) = {"ptr_gaga", 13, 424.3, 'Y', true};//C++11标准的语法
    *(ps + 2) = {"ptr_haha", 41, 31.3, 'X', false};//C++11标准的语法
    for (int i = 0; i < 3; i++) {
        cout << "姓名：" << (*(ps + i)).name << endl;
    }
}

int main() {
    test();
    return 0;
}
数组表示法
姓名：hello
姓名：hulu
姓名：haha
指针表示法：不常用
姓名：ptr_huhu
姓名：ptr_gaga
姓名：ptr_haha

```

结构体中的指针

如果结构体重的指针指向的是动态分配的内存地址：

1 对结构体用sizeof运算可能没有意义。

2 对结构体用memset()函数可能会造成内存泄漏

3 C++的字符串string中有一个指向的是动态分配的内存地址指针。

```
#include "iostream"
#include <cstring>

using namespace std;
//结构体中的指针
struct People {
    int a;
    int *p;
};

void test() {
    People people;
    people.p = new int[100];
    cout << "sizeof(people)=" << sizeof(people) << endl;//16个字节 ，肯定是有问题

    //结构体清零
    cout << "调用前：people.a:" << people.a << ",people.p:" << people.p << endl;
    memset(&people, 0, sizeof(people));
    cout << "调用后：people.a:" << people.a << ",people.p:" << people.p << endl;

    //上面的清零是有问题的  输出结果如下，这个指针都给清零了，导致p之前所指向的内存就不能被操作了，内存泄漏
//    sizeof(people) = 16
//    调用前：people.a:0, people.p:0x7fb1e6405910
//    调用后：people.a:0, people.p:0x0

//应该这么做
    People people2;
    people2.a = 3;
    people2.p = new int[100];
    cout << endl;
    cout << "调用前：people2.a:" << people2.a << ",people2.p:" << people2.p << endl;
    people2.a = 0;//清零成员a
    memset(people2.p, 0, 100 * sizeof(people2.p));//清空p指向的内存中的内容
    cout << "调用后：people2.a:" << people2.a << ",people2.p:" << people2.p << endl;


    delete[] people.p;//释放动态分配的内存
}

struct stringTest {
    string name;
};

void test2() {

    stringTest st;
    st.name = "葫芦娃";
    cout << "name=" << st.name << endl;
    //我这里演示是没有问题的，但是视频里面是有问题的 视频里面是VS
    memset(&st, 0, sizeof(st));

    st.name = "舅爷爷";
    cout << "name=" << st.name << endl;
}

int main() {
    cout << "执行test()" << endl;
    test();
    cout << "执行test2()" << endl;
    test2();
    return 0;
}

执行test()
sizeof(people)=16
调用前：people.a:197835680,people.p:0x7fd12d405910
调用后：people.a:0,people.p:0x0

调用前：people2.a:3,people2.p:0x7fd12d405aa0
调用后：people2.a:0,people2.p:0x7fd12d405aa0
执行test2()
name=葫芦娃
name=舅爷爷
```