19 CPP拷贝构造函数

![](https://gitee.com/hxc8/images2/raw/master/img/202407172223778.jpg)

![](https://gitee.com/hxc8/images2/raw/master/img/202407172224424.jpg)

函数以值的方式返回对象时，可能会调用拷贝构造函数（VS会调用，Linux不会，g++编译器做了优化） 代码说明

```
#include <iostream>

using namespace std;

//函数以值的方式返回对象时，可能会调用拷贝构造函数（VS会调用，Linux不会，g++编译器做了优化）
class Person {
public:
    Person() {
        m_Name.clear();
        m_Age = 0;
        cout << "调用构造函数Person()" << endl;
    }

    Person(const Person &p) {
        m_Name = p.m_Name;
        m_Age = p.m_Age;
        cout << "调用了拷贝构造函数" << endl;
    }

    void show() {
        cout << "姓名：" << m_Name << "，年龄：" << m_Age << endl;
    }

    ~Person() {
        cout << "调用了析构函数" << endl;
    }

    string m_Name;
    int m_Age;
};

Person func() {
    Person p;
    p.m_Name = "hello";
    p.m_Age = 19;
    cout << &p << endl;
    return p;
}

void test() {
    Person p = func();
    p.show();
    cout << &p << endl;
}

int main() {
    test();
    return 0;
}
/*
调用构造函数Person()
0x7ffee9534780
姓名：hello，年龄：19
0x7ffee9534780
调用了析构函数
 */
```

拷贝构造函数可以重载，可以有默认参数。

类名(.....,const 类名& 对象名,.....){......}

但是重载的时候，形参中一定要有类本身的常引用，如果形参中没有类本身的常引用，那就成了普通构造函数。

如果类中重载了拷贝构造函数却没有定义默认的拷贝构造函数，编译器会提供默认的拷贝构造函数，这一点和普通构造函数不一样。