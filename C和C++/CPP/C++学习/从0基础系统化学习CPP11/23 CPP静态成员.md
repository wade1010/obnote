23 CPP静态成员

![](https://gitee.com/hxc8/images2/raw/master/img/202407172223636.jpg)

![](https://gitee.com/hxc8/images2/raw/master/img/202407172223349.jpg)

![](https://gitee.com/hxc8/images2/raw/master/img/202407172223701.jpg)

静态成员的本质：如果把类的成员声明为静态的，就可以把它与类的对象独立开来（静态成员不属于对象）

私有静态成员在类外无法访问（初始化是可以的）

```
#include <iostream>

using namespace std;

class Person {
    static int m_age;
public:
    string m_name;

    Person(const string &name, int age) {
        m_name = name;
        m_age = age;
    }

    void showAge() {
        cout << m_name << endl;
    }

    static void showAge2() {
//        cout << m_name << endl;//Invalid use of member 'm_name' in static member function
        cout << m_age << endl;
    }
};
//私有静态成员在类外无法访问，但是可以初始化
int Person::m_age = 1;

void test() {
    Person p("hello", 22);
    p.showAge();

    //不创建成员也能访问静态函数
    Person::showAge2();
    //私有静态成员在类外无法访问，但是可以初始化
//    Person::m_age;//报错'm_age' is a private member of 'Person'
}

int main() {
    test();
    return 0;
}
```

const 静态成员变量可以在定义类的时候初始化（其实有const就行，不一定要有static）

```
class Person {
    const static int m_age = 10;
public:
    string m_name;

    Person(const string &name) {
        m_name = name;
    }

    void showAge() {
        cout << m_name << endl;
    }

    static void showAge2() {
        cout << m_age << endl;
    }
};
```