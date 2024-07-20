```
delete this->m_EmpArr[i];//删掉指针指向的堆区对象 清除指向的内存数据
this->m_EmpArr[i] = nullptr;//清空指针本身
```

![](https://gitee.com/hxc8/images3/raw/master/img/202407172237385.jpg)

![](images/WEBRESOURCEb985915f96e6045b2119a59923c1f4ab截图.png)

![](https://gitee.com/hxc8/images3/raw/master/img/202407172237688.jpg)

![](https://gitee.com/hxc8/images3/raw/master/img/202407172237060.jpg)

![](https://gitee.com/hxc8/images3/raw/master/img/202407172237456.jpg)

![](https://gitee.com/hxc8/images3/raw/master/img/202407172237601.jpg)

![](https://gitee.com/hxc8/images3/raw/master/img/202407172237618.jpg)

```
#include "iostream"

using namespace std;

int *func() {
    //在堆区创建整型数据
    //new返回是 该数据类型的指针
//    return new int(10);
    int *p = new int(10);
    return p;
}

void test1() {
    int *p = func();
    cout << *p << endl;
    //堆区的数据 由程序员管理开辟 程序员管理释放
    //如果想释放堆区的数据，利用关键字delete
    delete p;
//    cout << *p << endl;//内存已经释放，再次访问就是非法操作，报错
}

void test2() {
    //创建10整型数据的数组，在堆区
    int *arr = new int[10];//10代表数组有10个元素
    for (int i = 0; i < 10; i++) {
        arr[i] = i + 100;
    }
    for (int j = 0; j < 10; j++) {
        cout << arr[j] << endl;
    }
    //释放堆区数组
    //释放数组的时候，要加[]才可以
//    delete arr;//不报错，但是有提示 'delete' applied to a pointer that was allocated with 'new[]'; did you mean 'delete[]'? allocated with 'new[]' here
    delete[] arr;
}

int main() {
    //new的基本语法
    test1();
    cout << endl;
    //在堆区利用new开辟数组
    test2();
    return 0;
}
10

100
101
102
103
104
105
106
107
108
109
```

![](https://gitee.com/hxc8/images3/raw/master/img/202407172237043.jpg)

```
#include "iostream"

using namespace std;

int main() {
//引用基本语法
//数据类型 &别名 = 原名
    int a = 10;
//创建引用
    int &b = a;
    cout << "a=" << a << endl;
    cout << "b=" << b << endl;
    b = 100;
    cout << "a=" << a << endl;
    cout << "b=" << b << endl;
    return 0;
}

a=10
b=10
a=100
b=100

```

![](https://gitee.com/hxc8/images3/raw/master/img/202407172237413.jpg)

```
#include "iostream"

using namespace std;

//1 引用必须要初始化
//int &b;//错误的
//2 引用一旦初始化后，就不可以更改引用

int main() {
    int a = 10;

//1 引用必须要初始化
//int &b;//错误的
    int &b = a;
//2 引用一旦初始化后，就不可以更改了
    int c = 20;
    b = c;//赋值操作，而不是更改引用
    cout << a << endl;
    cout << b << endl;
    cout << c << endl;
    return 0;
}

20
20
20

```

![](https://gitee.com/hxc8/images3/raw/master/img/202407172237640.jpg)

引用传递

```
#include "iostream"

using namespace std;

void swap(int &a, int &b) {
    int temp = a;
    a = b;
    b = temp;
}

int main() {
    int a = 10;
    int b = 20;
    cout << a << endl;
    cout << b << endl;
    swap(a, b);
    cout << a << endl;
    cout << b << endl;
    return 0;
}
10
20
20
10

```

总结：通过引用参数 产生的效果同按地址传递是一样的，引用的语法更清楚简单

```
#include "iostream"

using namespace std;

//1 不要返回局部变量的引用
//int &test1() {//编译器有 warning   reference to local variable 'a' returned [-Wreturn-local-addr]
//    int a = 10;//局部变量放在四区中的栈区
//    return a;
//}

//2 函数的调用可以作为左值
int &test2() {
    static int a = 10;
    return a;
}

int main() {
    //引用做函数的返回值
//    int &ref = test1();//报错
//    cout << ref << endl;//报错
    int &ref = test2();
    cout << ref << endl;
    cout << ref << endl;
    cout << ref << endl;

    test2() = 1000;//如果函数的返回值是引用，这个函数调用可以作为左值
    cout << ref << endl;//ref是别名
    cout << ref << endl;
    cout << ref << endl;

    return 0;
}
10
10
10
1000
1000
1000
```

引用本质是指针常量

![](https://gitee.com/hxc8/images3/raw/master/img/202407172237930.jpg)

```
#include "iostream"

using namespace std;

//发现是引用，转换为 int* const ref=&a;
void func(int &ref) {
    ref = 100;//ref是引用，转换为*ref=100
}

int main() {
    int a = 10;
    //自动转换为 int* const ref = &a;指针常量是指针指向不可改，也说明了为什么引用不可以更改
    int &ref = a;
    ref = 20;//内部发现ref是引用，自动帮我们转换为*ref=20
    cout << "a=" << a << endl;
    cout << "ref=" << ref << endl;
    return 0;
}

a=20
ref=20
```

常量引用

![](https://gitee.com/hxc8/images3/raw/master/img/202407172237841.jpg)

```
#include "iostream"

using namespace std;

void printInfo(int &val) {
    val = 101;
    cout << val << endl;
}

void printInfo2(const int &val) {
//    val = 101;//不能修改
    cout << val << endl;
}

int main() {
    //常量引用
    //使用场景：用来修饰形参，防止误操作

    int a = 10;
    int &ref1 = a;//对的
//    int &ref2 = 10;//错误 引用必须引一块合法的内存空间

    //对的，加上const之后，编译器将代码修改 int temp = 10; const int &ref3 =temp;
    const int &ref3 = 10;
    ref1 = 20;//可以修改
//    ref3 = 20;//不可以修改，加入const之后变成只读，不可以修改


    int b = 100;
    printInfo(b);
    cout << b << endl;//变成了101，被修改了
    cout << endl;
    int b2 = 100;
    printInfo2(b2);
    cout << b2 << endl;//不变

    return 0;
}
101
101

100
100
```