CPP 核心编程8-模板

C++另一种编程思想称为 泛型编程 ，主要利用的技术就是模板





C++提供两种模板机制:函数模板和类模板

函数模板作用：

建立一个通用函数，其函数返回值类型和形参类型可以不具体制定，用一个**虚拟的类型**来代表。

**语法：**

```
template<typename T>
函数声明或定义

```

**解释：**

template --- 声明创建模板

typename --- 表面其后面的符号是一种数据类型，可以用class代替

T --- 通用的数据类型，名称可以替换，通常为大写字母

```
#include "iostream"

using namespace std;
void mySwap(int &a, int &b)
{
    int temp = a;
    a = b;
    b = temp;
}
void mySwap(double &a, double &b)
{
    double temp = a;
    a = b;
    b = temp;
}
//利用模板提供通用的交换函数
template <typename T>
void myTSwap(T &a, T &b)
{
    T temp = a;
    a = b;
    b = temp;
}
void test()
{
    int a = 10;
    int b = 20;
    mySwap(a, b);
    cout << a << endl;
    cout << b << endl;
    double c = 10.2;
    double d = 20.3;
    mySwap(c, d);
    cout << c << endl;
    cout << d << endl;
}
void test2()
{
    char a = 'a';
    char b = 'b';
    // 1 自动类型推导
    myTSwap(a, b);
    cout << a << " " << b << endl;
    // 2 显示指定类型
    int c = 100;
    int d = 200;
    myTSwap<int>(c, d);
    cout << c << " " << d << endl;
}

int main()
{
    test();
    test2();
    return 0;
}
```

总结：

- 函数模板利用关键字 template

- 使用函数模板有两种方式：自动类型推导、显示指定类型

- 模板的目的是为了提高复用性，将类型参数化

#### 1.2.2 函数模板注意事项

注意事项：

- 自动类型推导，必须推导出一致的数据类型T,才可以使用

- 模板必须要确定出T的数据类型，才可以使用

```
#include "iostream"

using namespace std;
template <typename T>
void mySwap(T &a, T &b)
{
    T temp = a;
    a = b;
    b = temp;
}
// 1、自动类型推导，必须推导出一致的数据类型T,才可以使用
void test()
{
    int a = 10;
    int b = 20;
    char c = 'c';
    mySwap(a, b); //正确,可以推导出一致的T
    // mySwap(a, c); //错误,推导不出一致的T
}

// 2、模板必须要确定出T的数据类型，才可以使用
template <class T>
void func()
{
    cout << "func call" << endl;
}

void test1()
{
    // func();//错误 模板不能独立使用,必须确定出T的类型
    func<int>();    //利用显示置顶烈性的方式,给T一个类型,才可以使用改=该模板
    func<double>(); //也可以
}
int main()
{
    test();
    test1();
    return 0;
}
```

总结：

- 使用模板时必须确定出通用数据类型T，并且能够推导出一致的类型

#### 1.2.3 函数模板案例

案例描述：

- 利用函数模板封装一个排序的函数，可以对**不同数据类型数组**进行排序

- 排序规则从大到小，排序算法为**选择排序**

- 分别利用**char数组**和**int数组**进行测试

```
#include "iostream"

using namespace std;
template <class T>
void mySort(T arr[], int len)
{
    for (int i = 0; i < len; i++)
    {
        int maxIndex = i;
        for (int j = i + 1; j < len; j++)
        {
            if (arr[maxIndex] < arr[j])
            {
                maxIndex = j;
            }
        }
        if (maxIndex != i)
        {
            T temp = arr[maxIndex];
            arr[maxIndex] = arr[i];
            arr[i] = temp;
        }
    }
}
template <class T>
void printArray(T arr[], int len)
{
    for (int i = 0; i < len; i++)
    {
        cout << arr[i] << " ";
    }
    cout << endl;
}
void test1()
{
    int arr[] = {32, 3243, 45, 564, 657, 23, 2};
    int len = sizeof(arr) / sizeof(arr[0]);
    mySort(arr, len);
    printArray(arr, len);
}
void test2()
{
    char arr[] = "qwefrhsb";
    int len = sizeof(arr) / sizeof(arr[0]);
    mySort(arr, len);
    printArray(arr, len);
}
int main()
{
    test1();
    test2();
    return 0;
}
```

总结：模板可以提高代码复用，需要熟练掌握

#### 1.2.4 普通函数与函数模板的区别

**普通函数与函数模板区别：**

- 普通函数调用时可以发生自动类型转换（隐式类型转换）

- 函数模板调用时，如果利用自动类型推导，不会发生隐式类型转换

- 如果利用显示指定类型的方式，可以发生隐式类型转换

```
#include "iostream"

using namespace std;
//普通函数
int myAdd01(int a, int b)
{
    return a + b;
}
//函数模板
template <typename T>
T myAdd02(T a, T b)
{
    return a + b;
}
//使用函数模板时,如果用自动类型推导,不会发生自动类型转换,即隐式类型转换
void test1()
{
    int a = 10;
    int b = 20;
    char c = 'c';
    cout << myAdd01(a, c) << endl; //正确,将char类型的隐式转换为int类型
    // cout << myAdd02(a, c) << endl; //错误,使用自动类型推导时,不会发生隐式类型转换
    cout << myAdd02<int>(a, c) << endl; //正确，如果用显示指定类型，可以发生隐式类型转换
}
int main()
{
    test1();
    return 0;
}
```

总结：建议使用显示指定类型的方式，调用函数模板，因为可以自己确定通用类型T

#### 1.2.5 普通函数与函数模板的调用规则

调用规则如下：

1. 如果函数模板和普通函数都可以实现，优先调用普通函数

1. 可以通过空模板参数列表来强制调用函数模板

1. 函数模板也可以发生重载

1. 如果函数模板可以产生更好的匹配,优先调用函数模板

```
#include "iostream"

using namespace std;
//普通函数于模板函数调用规则
void myPrint(int a, int b)
{
    cout << "1调用的普通函数" << endl;
}
template <typename T>
void myPrint(T a, T b)
{
    cout << "2调用的模板函数" << endl;
}
template <typename T>
void myPrint(T a, T b, T c)
{
    cout << "3调用的模板函数" << endl;
}
void test1()
{
    // 1、如果函数模板和普通函数都可以实现，优先调用普通函数
    //  注意 如果告诉编译器  普通函数是有的，但只是声明没有实现，或者不在当前文件内实现，就会报错找不到
    int a = 10;
    int b = 20;
    myPrint(a, b); //调用普通函数
    // 2 可以通过空模板参数列表来强制调用模板函数
    myPrint<>(a, b);
    // 3、函数模板也可以发生重载
    int c = 30;
    myPrint(a, b, c); //调用重载的函数模板
    // 4 如果函数模板可以产生更好的匹配,有限调用函数模板
    char ch1 = 'a';
    char ch2 = 'b';
    myPrint(ch1, ch2); //调用函数模板 因为调用普通的,要发生隐式转换,所以编译器就优先调用函数模板
}
int main()
{
    test1();
    return 0;
}
```

#### 1.2.6 模板的局限性

**局限性：**

- 模板的通用性并不是万能的

**例如：**

```
	template<class T>
	void f(T a, T b)
	{ 
    	a = b;
    }

```

在上述代码中提供的赋值操作，如果传入的a和b是一个数组，就无法实现了

再例如：

```
	template<class T>
	void f(T a, T b)
	{ 
    	if(a > b) { ... }
    }

```

在上述代码中，如果T的数据类型传入的是像Person这样的自定义数据类型，也无法正常运行

因此C++为了解决这种问题，提供模板的重载，可以为这些**特定的类型**提供**具体化的模板**

```
#include "iostream"

using namespace std;
class Person
{
public:
    Person(string name, int age)
    {
        this->m_Name = name;
        this->m_Age = age;
    }
    string m_Name;
    int m_Age;
};
//普通函数模板
template <class T>
bool myCompare(T &a, T &b)
{
    return a == b;
}
//具体化,显示具体化的原型和以template<>开头,并通过名称来指出类型
//具体优化于常规模板
template <>
bool myCompare(Person &p1, Person &p2)
{
    return p1.m_Age == p2.m_Age && p1.m_Name == p2.m_Name;
}

void test1()
{
    int a = 10;
    int b = 20;
    //内置数据类型可以直接使用通用的函数模板
    bool ret = myCompare(a, b);
    if (ret)
    {
        cout << "a==b" << endl;
    }
    else
    {
        cout << "a!=b" << endl;
    }
}

void test2()
{
    Person p1("Tom", 10);
    Person p2("Tom", 10);
    bool ret = myCompare(p1, p2);
    if (ret)
    {
        cout << "p1==p2" << endl;
    }
    else
    {
        cout << "p1!=p2" << endl;
    }
}
int main()
{
    test1();
    test2();
    return 0;
}
```

总结：

- 利用具体化的模板，可以解决自定义类型的通用化

- 学习模板并不是为了写模板，而是在STL能够运用系统提供的模板

### 1.3 类模板

#### 1.3.1 类模板语法

类模板作用：

- 建立一个通用类，类中的成员 数据类型可以不具体制定，用一个**虚拟的类型**来代表。

**语法：**

```
template<typename T>
类

```

**解释：**

template --- 声明创建模板

typename --- 表面其后面的符号是一种数据类型，可以用class代替

T --- 通用的数据类型，名称可以替换，通常为大写字母

```
#include "iostream"

using namespace std;
template <class NameType, class AgeType>
class Person
{
public:
    Person(NameType name, AgeType age)
    {
        m_Name = name;
        m_Age = age;
    }
    void showPerson()
    {
        cout << "name: " << this->m_Name << " age: " << this->m_Age << endl;
    }
    NameType m_Name;
    AgeType m_Age;
};
void test1()
{
    //置顶NameType 为string类型 AgeType 为 int 类型
    Person<string, int> p1("孙悟空", 1000);
    p1.showPerson();
}

void test2()
{
}
int main()
{
    test1();
    test2();
    return 0;
}
```

总结：类模板和函数模板语法相似，在声明模板template后面加类，此类称为类模板

#### 1.3.2 类模板与函数模板区别

类模板与函数模板区别主要有两点：

1. 类模板没有自动类型推导的使用方式

1. 类模板在模板参数列表中可以有默认参数

```
#include "iostream"

using namespace std;
//类模板
template <class NameType, class AgeType = int>
class Person
{
public:
    Person(NameType name, AgeType age)
    {
        this->name = name;
        this->age = age;
    }
    void showPerson()
    {
        cout << " age: " << this->age << " name: " << this->name << endl;
    }
    NameType name;
    AgeType age;
};
// 1 类模板没有自动类型推导的使用方式
void test1()
{
    // Person p("孙悟空", 1000);错误 类模板使用时候 不可以用自动类型推导
    Person<string, int> p("孙悟空", 1000);
    p.showPerson();
}
// 2 类模板在模板参数列表中可以有默认参数
void test2()
{
    Person<string> p("猪八戒", 999); //类模板中的模板参数列表 可以指定默认参数
    p.showPerson();
}
int main()
{
    test1();
    test2();
    return 0;
}
```

总结：

- 类模板使用只能用显示指定类型方式

- 类模板中的模板参数列表可以有默认参数

#### 1.3.3 类模板中成员函数创建时机

类模板中成员函数和普通类中成员函数创建时机是有区别的：

- 普通类中的成员函数一开始就可以创建

- 类模板中的成员函数在调用时才创建

- 

```
#include "iostream"

using namespace std;
class Person1
{
public:
    void showPerson1()
    {
        cout << "Person1 show" << endl;
    }
};
class Person2
{
public:
    void showPerson2()
    {
        cout << "Person2 show" << endl;
    }
};
template <class T>
class MyClass
{
public:
    T obj;
    //类模板中的成员函数,并不是一开始就创建的,而是在模板调用时再生成
    void fun1()
    {
        obj.showPerson1();
    }
    void fun2()
    {
        obj.showPerson2();
    }
};
void test1()
{
    MyClass<Person1> m;
    m.fun1();
    // m.fun2(); //编译会出错，说明函数调用才会去创建成员函数
}
int main()
{
    test1();

    return 0;
}
```

总结：类模板中的成员函数并不是一开始就创建的，在调用时才去创建

#### 1.3.4 类模板对象做函数参数

学习目标：

- 类模板实例化出的对象，向函数传参的方式

一共有三种传入方式：

1. 指定传入的类型 --- 直接显示对象的数据类型

1. 参数模板化 --- 将对象中的参数变为模板进行传递

1. 整个类模板化 --- 将这个对象类型 模板化进行传递

```
#include "iostream"

using namespace std;
//类模板
template <class NameType, class AgeType = int>
class Person
{
public:
    Person(NameType name, AgeType age)
    {
        this->m_Age = age;
        this->m_Name = name;
    }
    void showPerson()
    {
        cout << "age" << this->m_Age << " name:" << this->m_Name << endl;
    }

    NameType m_Name;
    AgeType m_Age;
};
// 1 指定传入的类型
void printPerson1(Person<string, int> &p)
{
    p.showPerson();
}
void test1()
{
    Person<string, int> p("孙悟空", 1000);
    printPerson1(p);
}
// 2 参数模板化
template <typename T1, typename T2>
void printPerson2(Person<T1, T2> &p)
{
    p.showPerson();
    cout << "T1的类型为： " << typeid(T1).name() << endl;
    cout << "T2的类型为： " << typeid(T2).name() << endl;
}
void test2()
{
    Person<string, int> p("孙悟空", 1000);
    printPerson2(p);
}

// 3 整个类模板化
template <class T>
void printPerson3(T &p)
{
    p.showPerson();
    cout << "T的类型为： " << typeid(T).name() << endl;
}

void test3()
{
    Person<string, int> p("a", 100);
    printPerson3(p);
}
int main()
{
    test1();
    test2();
    test3();

    return 0;
}
```

总结：

- 通过类模板创建的对象，可以有三种方式向函数中进行传参

- 使用比较广泛是第一种：指定传入的类型

#### 1.3.5 类模板与继承

当类模板碰到继承时，需要注意一下几点：

- 当子类继承的父类是一个类模板时，子类在声明的时候，要指定出父类中T的类型

- 如果不指定，编译器无法给子类分配内存

- 如果想灵活指定出父类中T的类型，子类也需变为类模板

```
#include "iostream"

using namespace std;
template <class T>
class Base
{
    T m;
};
// class Son:public Base //错误 c++编译器需要给子类分配内存,必须知道父类中T的类型才可以向下继承
class Son : public Base<int>
{
};
void test()
{
    Son s;
}
//类模板继承类模板,可以用T2指定父类中的T类型
template <class T1, class T2>
class Son2 : public Base<T2>
{
public:
    Son2()
    {
        cout << typeid(T1).name() << endl;
        cout << typeid(T2).name() << endl;
    }
};
void test2()
{
    Son2<int, char> s;
}
int main()
{
    test();
    test2();
    return 0;
}
i
c
```

总结：如果父类是类模板，子类需要指定出父类中T的数据类型

#### 1.3.6 类模板成员函数类外实现

学习目标：能够掌握类模板中的成员函数类外实现

```
#include "iostream"
using namespace std;
//类模板中成员函数类外实现
template <class T1, class T2>
class Person
{
public:
    //成员函数类内声明
    Person(T1 name, T2 age);
    void showPerson();
    T1 m_Name;
    T2 m_Age;
};
//构造函数 类外实现
template <class T1, class T2>
Person<T1, T2>::Person(T1 name, T2 age)
{
    m_Name = name;
    m_Age = age;
}

//成员函数 类外实现
template <class T1, class T2>
void Person<T1, T2>::showPerson()
{
    cout << "show person" << endl;
}

void test()
{
    Person<string, int> p("孙武", 100);
    p.showPerson();
}
int main()
{
    test();
    return 0;
}
```

总结：类模板中成员函数类外实现时，需要加上模板参数列表

#### 1.3.7 类模板分文件编写

学习目标：

- 掌握类模板成员函数分文件编写产生的问题以及解决方式

问题：

- 类模板中成员函数创建时机是在调用阶段，导致分文件编写时链接不到

解决：

- 解决方式1：直接包含.cpp源文件

- 解决方式2：将声明和实现写到同一个文件中，并更改后缀名为.hpp，hpp是约定的名称，并不是强制          

person.hpp

```
#pragma once
#include "iostream"
using namespace std;

template <class T1, class T2>
class Person
{
public:
    Person(T1 name, T2 age);
    void showPerson();

    int m_Age;
    string m_Name;
};

//构造函数 类外实现
template <class T1, class T2>
Person<T1, T2>::Person(T1 name, T2 age)
{
    this->m_Age = age;
    this->m_Name = name;
}

//成员函数 类外实现
template <class T1, class T2>
void Person<T1, T2>::showPerson()
{
    cout << "name:" << this->m_Name << " age " << this->m_Age << endl;
}

```

mian.cpp

```


#include "person.hpp"

void test()
{
    Person<string, int> p("bob", 100);
    p.showPerson();
}
int main()
{
    test();
    return 0;
}
```

总结：主流的解决方式是第二种，将类模板成员函数写到一起，并将后缀名改为.hpp

#### 1.3.8 类模板与友元

学习目标：

- 掌握类模板配合友元函数的类内和类外实现

全局函数类内实现 - 直接在类内声明友元即可

全局函数类外实现 - 需要提前让编译器知道全局函数的存在

```
#include "iostream"
using namespace std;

// 1 全局函数配合友元 类内实现
template <class T1, class T2>
class Person
{
    friend void showPerson(const Person<T1, T2> &p)
    {
        cout << "age " << p.mage << " name" << p.mname << endl;
    }

public:
    Person(T1 name, T2 age)
    {
        this->mage = age;
        this->mname = name;
    }

private:
    int mage;
    string mname;
};
void test()
{
    Person<string, int> p("bob", 22);
    showPerson(p);
}
int main()
{
    test();
    return 0;
}
```

```
#include "iostream"
using namespace std;

template <class T1, class T2>
class Person;

//如果声明了函数模板，可以将实现写到后面，否则需要将实现体写到类的前面让编译器提前看到
// template <class T1, class T2>
// void showPerson2(const Person<T1, T2> &p);

//这里放到Person类的前面,就不需要上面注释的 声明函数模板
template <class T1, class T2>
void showPerson2(const Person<T1, T2> &p)
{
    cout << "age " << p.mage << " name" << p.mname << endl;
}

template <class T1, class T2>
class Person
{
    // 1 全局函数配合友元 类内实现
    friend void showPerson(const Person<T1, T2> &p)
    {
        cout << "age " << p.mage << " name" << p.mname << endl;
    }
    //全局函数配合友元  类外实现
    // friend void showPerson2(const Person<T1, T2> &p); 这是普通全局函数的友元 得加<>
    friend void showPerson2<>(const Person<T1, T2> &p);

public:
    Person(T1 name, T2 age)
    {
        this->mage = age;
        this->mname = name;
    }

private:
    int mage;
    string mname;
};

void test1()
{
    Person<string, int> p("bob", 22);
    showPerson(p);
}
void test2()
{
    Person<string, int> p("wade", 33);
    showPerson2(p);
}

//放到Person类的后面,就需要提前在类前面声明函数模板,让编译器知道
// template <class T1, class T2>
// void showPerson2(const Person<T1, T2> &p)
// {
//     cout << "age " << p.mage << " name" << p.mname << endl;
// }

int main()
{
    test1();
    test2();
    return 0;
}
```

总结：建议全局函数做类内实现，用法简单，而且编译器可以直接识别