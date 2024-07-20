![](https://gitee.com/hxc8/images2/raw/master/img/202407172223496.jpg)

 

1)友元全局函数

在友元全局函数中，可以访问另外一个类的所有成员。

```
#include <iostream>

using namespace std;

class Person {
    friend int main();

    friend void test();

public:
    string m_name;

    Person() {
        m_name = "hello";
        m_age = 100;
    }

private:
    int m_age;

    void showAge() {
        cout << m_age << endl;
    }
};

void test() {
    Person p;
    p.showAge();
}

int main() {
    Person p;
    p.showAge();
    test();
    return 0;
}
```

2）友元类

在友元类所有成员函数中，都可以访问另一个类的所有成员。

友元类注意事项：

1 友元关系不能被继承

2 友元关系是单向的，不具备交换性

若类B是类A的友元，类A不一定是类B的友元。B是类A的友元，类C似B的友元，类C不一定是类A的友元，要看类中是否有相应的声明。

```
#include <iostream>

using namespace std;

//友元 全局函数和友元类
class Person {
    friend int main();//友元全局函数

    friend void test();//友元全局函数

    friend class Student;//友元类

public:
    string m_name;

    Person() {
        m_name = "hello";
        m_age = 100;
    }

private:
    int m_age;

    void showAge() {
        cout << m_age << endl;
    }
};

class Student {
public:
    void callPersonFunc(const Person &p) {
        cout << p.m_age << endl;
    }
};

void test() {
    Person p;
    p.showAge();
}

int main() {
    Person p;
    p.showAge();
    test();

    Student s;
    s.callPersonFunc(p);

    return 0;
}
```

3）友元成员函数

在友元成员函数中，可以访问另一个类的所有成员。

如果把Student类的某个成员函数声明为People类的友元，声明和定义顺序如下：

class People; //前置声明。

class Student {....};

class People {....};

```
#include <iostream>

using namespace std;

//步骤1 Person类前置
class Person;

//步骤2 把Student类放在Person前
class Student {
public:
//    void callPersonFunc1(const Person &p) {
//        cout << "callPersonFunc1" << p.m_age << endl;
//    }
//
//    void callPersonFunc2(const Person &p) {
//        cout << "callPersonFunc2" << p.m_age << endl;
//    }
//改成声明，类外实现
    void callPersonFunc1(const Person &p);

    void callPersonFunc2(const Person &p);
};

//步骤3 把Student类的成员函数的函数体从Student类中拿出来 ,放在定义Person类的代码后面
//往下看 因为如果不放在定义Person类代码后面，就会报错，member access into incomplete type 'const Person'

//步骤4 把Student类的成员函数声明为Person类的友元函数

class Person {
    friend void Student::callPersonFunc1(const Person &p);

    friend void Student::callPersonFunc2(const Person &p);

public:
    string m_name;

    Person() {
        m_name = "hello";
        m_age = 100;
    }

private:
    int m_age;

    void showAge() {
        cout << m_age << endl;
    }
};

void Student::callPersonFunc1(const Person &p) {
    cout << "callPersonFunc1   " << p.m_age << endl;
}

void Student::callPersonFunc2(const Person &p) {
    cout << "callPersonFunc2   " << p.m_age << endl;
}


void test() {
    Person p;
    Student stu;
    stu.callPersonFunc1(p);
    stu.callPersonFunc2(p);
}

int main() {
    test();
    return 0;
}
```