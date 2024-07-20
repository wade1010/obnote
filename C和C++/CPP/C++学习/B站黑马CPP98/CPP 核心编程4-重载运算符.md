CPP 核心编程4-重载运算符

![](https://gitee.com/hxc8/images3/raw/master/img/202407172237890.jpg)

![](https://gitee.com/hxc8/images3/raw/master/img/202407172237331.jpg)

成员函数重载+

```
#include "iostream"

using namespace std;

//加好运算符重载
//1 成员函数重载+号
//2 全局函数重载+号

class Person {
public:
    int m_A;
    int m_B;

    //1 成员函数重载+号
    Person operator+(Person &p) const {
        Person temp{};
        temp.m_A = this->m_A + p.m_A;
        temp.m_B = this->m_B + p.m_B;
        return temp;
    }

};

void test1() {
    Person p1{10, 10};
    Person p2{20, 20};
//    Person p3 = p1 + p2;//没重载运算符之前报错 Invalid operands to binary expression ('Person' and 'Person')
    Person p3 = p1 + p2;//本质是Person p3 = p1.operator+(p2);
    cout << p3.m_A << " " << p3.m_B << endl;
}

int main() {
    test1();
    return 0;
}
30 30

```

全局函数重载+

```
#include "iostream"

using namespace std;

//加好运算符重载
//1 成员函数重载+号
//2 全局函数重载+号

class Person {
public:
    int m_A;
    int m_B;

    //1 成员函数重载+号
//    Person operator+(Person &p) const {
//        Person temp{};
//        temp.m_A = this->m_A + p.m_A;
//        temp.m_B = this->m_B + p.m_B;
//        return temp;
//    }

};

Person operator+(Person &p1, Person &p2) {
    return {p1.m_A + p2.m_A, p1.m_B + p2.m_B};
    //等同于下面
//    Person temp{p1.m_A + p2.m_A, p1.m_B + p2.m_B};
//    return temp;
}

//成员函数重载+号
void test1() {
    Person p1{10, 10};
    Person p2{20, 20};
//    Person p3 = p1 + p2;//没重载运算符之前报错 Invalid operands to binary expression ('Person' and 'Person')
    Person p3 = p1 + p2;//本质是Person p3 = p1.o perator+(p2);
    cout << p3.m_A << " " << p3.m_B << endl;
}

//全局函数重载+号
void test2() {
    Person p1{10, 10};
    Person p2{20, 20};
    Person p3 = p1 + p2;//本质是Person p3 = operator+(p1, p2);
    cout << p3.m_A << " " << p3.m_B << endl;
}

int main() {
    //test1();
    test2();
    return 0;
}
```

运算符重载，也可以发生函数重载

```
#include "iostream"

using namespace std;

//加好运算符重载
//1 成员函数重载+号
//2 全局函数重载+号  
//可以同时存在

class Person {
public:
    int m_A;
    int m_B;

    //1 成员函数重载+号
//    Person operator+(Person &p) const {
//        Person temp{};
//        temp.m_A = this->m_A + p.m_A;
//        temp.m_B = this->m_B + p.m_B;
//        return temp;
//    }

    Person operator+(int a) const {
        return {this->m_A + a, this->m_B + a};
    }
};

Person operator+(Person &p1, Person &p2) {
    return {p1.m_A + p2.m_A, p1.m_B + p2.m_B};
    //等同于下面
//    Person temp{p1.m_A + p2.m_A, p1.m_B + p2.m_B};
//    return temp;
}

//成员函数重载+号
void test1() {
    Person p1{10, 10};
    Person p2{20, 20};
//    Person p3 = p1 + p2;//没重载运算符之前报错 Invalid operands to binary expression ('Person' and 'Person')
    Person p3 = p1 + p2;//本质是Person p3 = p1.o perator+(p2);
    cout << p3.m_A << " " << p3.m_B << endl;
}

//全局函数重载+号
void test2() {
    Person p1{10, 10};
    Person p2{20, 20};
    Person p3 = p1 + p2;//本质是Person p3 = operator+(p1, p2);
    cout << p3.m_A << " " << p3.m_B << endl;
}

Person operator+(Person &p, int a) {
    return {p.m_A + a, p.m_B + 10};
}

//运算符重载发生函数重载
void test3() {
    Person p1{10, 10};
    Person p2 = p1 + 10;//
    cout << p2.m_A << " " << p2.m_B << endl;
}

int main() {
    //test1();
//    test2();
    test3();
    return 0;
}
```

总结：

对于内置的数据类型的表达式的运算符是不可能改变的

不要滥用运算符重载

![](https://gitee.com/hxc8/images3/raw/master/img/202407172237418.jpg)

```
#include "iostream"

using namespace std;

//左移运算符重载
class Person {
public:
    //利用成员函数重载 左移运算符

    //p.operator<<(cout)  简化 p<<cout
    //因此通常不会利用成员函数重载左移运算符，因为无法实现cout在左侧
//    void operator<<(Person &p) {
//
//    }

    int m_A;
    int m_B;
};

//所以只能使用全局函数重载左移运算符
//返回 ostream& 是用来做链式调用
ostream &operator<<(ostream &cout, Person &p) {//本质 operator<<(cout,p)
    cout << "m_A=" << p.m_A << " m_B=" << p.m_B;
    return cout;
}

void test() {
    Person p{1, 2};
    Person p2{3, 4};
//    cout << p;//无重载时，Invalid operands to binary expression ('std::__1::ostream' (aka 'basic_ostream<char>') and 'Person')
    cout << p << "\n" << p2 << "\n" << "hello world" << endl;
}

int main() {
    test();
    return 0;
}
m_A=1 m_B=2
m_A=3 m_B=4
hello world

```

```
#include "iostream"

using namespace std;

//友元+重载
class Person {
    friend ostream &operator<<(ostream &cout, Person &p);

public:
    Person(int a, int b) {
        m_A = a;
        m_B = b;
    }

private:
    int m_A;
    int m_B;
};

ostream &operator<<(ostream &cout, Person &p) {
    cout << "m_A=" << p.m_A << " m_B=" << p.m_B;
    return cout;
}

void test() {
    Person p{1, 2};
    Person p2{3, 4};
    cout << p << "\n" << p2 << "\n" << "hello world" << endl;
}

int main() {
    test();
    return 0;
}
m_A=1 m_B=2
m_A=3 m_B=4
hello world

```

重载左移运算符配合友元

赋值运算符重载

![](https://gitee.com/hxc8/images3/raw/master/img/202407172237745.jpg)

有问题代码如下

```
#include "iostream"

using namespace std;

//赋值运算符重载
class Person {
public:
    Person(int age) {
        m_Age = new int(age);
    }

    ~Person() {
        if (m_Age != nullptr) {
            delete m_Age;
            m_Age = nullptr;
        }
    }

    int *m_Age;
};

void test() {
    Person p{18};
    Person p2{20};
    cout << "p年龄为：" << *p.m_Age << endl;
    cout << "p2年龄为：" << *p2.m_Age << endl;
    p2 = p;//堆区内存重复释放
    cout << "p年龄为：" << *p.m_Age << endl;
    cout << "p2年龄为：" << *p2.m_Age << endl;
}

int main() {
    test();
    return 0;
}
p年龄为：18
p2年龄为：20
p年龄为：18
p2年龄为：18
main(42841,0x116209dc0) malloc: *** error for object 0x7fcd4f405910: pointer being freed was not allocated
main(42841,0x116209dc0) malloc: *** set a breakpoint in malloc_error_break to debug

```

```
#include "iostream"

using namespace std;

//赋值运算符重载
class Person {
public:
    Person() {

    }

    Person(int age) {
        m_Age = new int(age);
    }

    ~Person() {
        if (m_Age != nullptr) {
            delete m_Age;
            m_Age = nullptr;
        }
    }

    void operator=(const Person &p) {
        //编译器的 =赋值 提供的是浅拷贝  会发生内存重复释放的问题
//        m_Age = p.m_Age;
//        *m_Age = *p.m_Age;//如果m_Age没有初始化就会报错，比如提供了默认构造函数 如下面注释的代码
        /*
           Person p{10};
           Person p2{};
           p2 = p;
           这样地址直接复制就会报错
        */

        //应该先判断是否有属性在堆区，如果有先释放干净，然后再深拷贝
        /*
         * Person p{};
            Person p2{10};
            p2 = p;
            下面代码也会报错
        if (m_Age != nullptr) {
            delete m_Age;
            m_Age = nullptr;
        }
        m_Age = new int(*p.m_Age);
        */
        // 最好 改成如下
        if (p.m_Age != nullptr) {
            if (m_Age != nullptr) {
                delete m_Age;
                m_Age = nullptr;
            }
            m_Age = new int(*p.m_Age);
        }

    }

    int *m_Age;
};

void test() {
    //提供无参构造，这样m_Age 可能为空
    Person p{};
    Person p2{10};
//    cout << "p年龄为：" << *p.m_Age << endl;
//    cout << "p2年龄为：" << *p2.m_Age << endl;
    p2 = p;//堆区内存重复释放
//    cout << "p年龄为：" << *p.m_Age << endl;
//    cout << "p2年龄为：" << *p2.m_Age << endl;
}

int main() {
    test();
    return 0;
}
```

后来发现，上面代码也有瑕疵，就是不能自己赋值给自己

p1=p1;这样会报错，下下方有修复代码

下面代码虽然运行不报错，但是不支持连等操作

如：

```
int a = 10;
int b = 20;
int c = 30;
c = b = a;
cout << a << b << c << endl;  //a =10; b=10; c=10;
```

```
#include "iostream"

using namespace std;

//赋值运算符重载
class Person {
public:
    Person(int age) {
        m_Age = new int(age);
    }

    ~Person() {
        if (m_Age != nullptr) {
            delete m_Age;
            m_Age = nullptr;
        }
    }

    void operator=(const Person &p) {
        *m_Age = *p.m_Age;
    }

    int *m_Age;
};

void test() {
    //如果使用有参构造函数，确保m_Age不为空
    Person p{20};
    Person p2{10};
    cout << "p年龄为：" << *p.m_Age << endl;
    cout << "p2年龄为：" << *p2.m_Age << endl;
    p2 = p;//堆区内存重复释放
    cout << "p年龄为：" << *p.m_Age << endl;
    cout << "p2年龄为：" << *p2.m_Age << endl;
}

int main() {
    test();
    return 0;
}
p年龄为：20
p2年龄为：10
p年龄为：20
p2年龄为：20
```

支持连等操作

```
#include "iostream"

using namespace std;

//赋值运算符重载
class Person {
public:
    Person(int age) {
        m_Age = new int(age);
    }

    ~Person() {
        if (m_Age != nullptr) {
            delete m_Age;
            m_Age = nullptr;
        }
    }

    const Person &operator=(const Person &p) {
        *m_Age = *p.m_Age;
        return *this;
    }

    int *m_Age;
};

ostream &operator<<(ostream &cout, Person &p) {
    cout << *p.m_Age;
    return cout;
}

void test() {
    //如果使用有参构造函数，确保m_Age不为空
    Person p1{10};
    Person p2{20};
    Person p3{30};
    p3 = p2 = p1;
    cout << p1 << " " << p2 << " " << p3 << endl;
}

int main() {
    test();
//    int a = 10;
//    int b = 20;
//    int c = 30;
//    c = b = a;
//    cout << a << b << c << endl;  //a =10; b=10; c=10;
    return 0;
}
```

修改成支持自己赋值给自己的代码

```
#include "iostream"

using namespace std;

//赋值运算符重载
class Person {
public:
    Person() {

    }

    Person(int age) {
        m_Age = new int(age);
    }

    ~Person() {
        if (m_Age != nullptr) {
            delete m_Age;
            m_Age = nullptr;
        }
    }

    void operator=(const Person &p) {
        //编译器的 =赋值 提供的是浅拷贝  会发生内存重复释放的问题
//        m_Age = p.m_Age;
//        *m_Age = *p.m_Age;//如果m_Age没有初始化就会报错，比如提供了默认构造函数 如下面注释的代码
        /*
           Person p{10};
           Person p2{};
           p2 = p;
           这样地址直接复制就会报错
        */

        //应该先判断是否有属性在堆区，如果有先释放干净，然后再深拷贝
        /*
         * Person p{};
            Person p2{10};
            p2 = p;
            下面代码也会报错

        if (m_Age != nullptr) {
            delete m_Age;
            m_Age = nullptr;
        }
        m_Age = new int(*p.m_Age);
        */

        // 最好 改成如下  后来发现下面代码也有问题就是 p1=p1;自己复制给自己。所以再修改
//        if (p.m_Age != nullptr) {
//            if (m_Age != nullptr) {
//                delete m_Age;
//                m_Age = nullptr;
//            }
//            m_Age = new int(*p.m_Age);
//        }

        if (p.m_Age != nullptr && p.m_Age != m_Age) {
            if (m_Age != nullptr) {
                delete m_Age;
                m_Age = nullptr;
            }
            m_Age = new int(*p.m_Age);
        }

    }

    int *m_Age;
};

void test() {
    //提供无参构造，这样m_Age 可能为空
    Person p{};
    Person p2{10};
//    cout << "p年龄为：" << *p.m_Age << endl;
//    cout << "p2年龄为：" << *p2.m_Age << endl;
    p2 = p2;//堆区内存重复释放
//    cout << "p年龄为：" << *p.m_Age << endl;
    cout << "p2年龄为：" << *p2.m_Age << endl;
}

int main() {
    test();
    return 0;
}
```

![](https://gitee.com/hxc8/images3/raw/master/img/202407172237196.jpg)

```
#include "iostream"

using namespace std;

//重载关系运算符
class Person {
public:
    Person(string name, int age) {
        m_age = age;
        m_name = name;
    }

    bool operator==(const Person &p) const {
        return m_name == p.m_name && m_age == p.m_age;
    }

    bool operator!=(const Person &p) const {
        return !(*this == p);
    }

    string m_name;
    int m_age;
};

void test() {
    Person p1{"zs", 20};
    Person p2{"zs2", 20};
    if (p1 == p2) {
        cout << "相等" << endl;
    } else {
        cout << "不相等" << endl;
    }

    if (p1 != p2) {
        cout << "不相等" << endl;
    } else {
        cout << "相等" << endl;
    }

}

int main() {
    test();
    return 0;
}
不相等
不相等
```