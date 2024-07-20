![](https://gitee.com/hxc8/images2/raw/master/img/202407172223273.jpg)

![](https://gitee.com/hxc8/images2/raw/master/img/202407172223105.jpg)

![](https://gitee.com/hxc8/images2/raw/master/img/202407172223824.jpg)

3 初始化列表与赋值有本质的区别，如果成员是类，使用初始化列表调用的拷贝构造函数，而赋值是想创建对象（调用普通构造函数），然后再赋值。

传值stu

```
#include <iostream>

using namespace std;

class Student {
public:
    string m_name;

    Student() {
        m_name.clear();
        cout << "调用Student()构造函数" << endl;
    }

    Student(string name) {
        m_name = name;
        cout << "调用Student(string name)构造函数" << endl;
    }

    Student(const Student &stu) {
        m_name = stu.m_name;
        cout << "调用Student(const Student &stu)拷贝构造函数" << endl;
    }
};

class Person {
public:
    string m_name;
    int m_age;
    Student m_stu;

    Person() {
        cout << "调用Person()构造函数" << endl;
    }

    Person(string name, int age, Student stu) : m_name(name), m_age(age) {
        m_stu.m_name = stu.m_name;
        cout << "调用Person(string name, int age, Student stu)构造函数" << endl;
    }

    void show() {
        cout << m_name << " " << m_age << " " << m_stu.m_name << endl;
    }
};

void test() {
    Student stu("kobe");
    Person p2("world", 20, stu);
    p2.show();
}

int main() {
    test();
    return 0;
}
/*
 调用Student(string name)构造函数
调用Student(const Student &stu)拷贝构造函数
调用Student()构造函数
调用Person(string name, int age, Student stu)构造函数
world 20 kobe*/
```

传引用stu

```
#include <iostream>

using namespace std;

class Student {
public:
    string m_name;

    Student() {
        m_name.clear();
        cout << "调用Student()构造函数" << endl;
    }

    Student(string name) {
        m_name = name;
        cout << "调用Student(string name)构造函数" << endl;
    }

    Student(const Student &stu) {
        m_name = stu.m_name;
        cout << "调用Student(const Student &stu)拷贝构造函数" << endl;
    }
};

class Person {
public:
    string m_name;
    int m_age;
    Student m_stu;

    Person() {
        cout << "调用Person()构造函数" << endl;
    }

    Person(string name, int age, Student &stu) : m_name(name), m_age(age) {
        m_stu.m_name = stu.m_name;
        cout << "调用Person(string name, int age, Student stu)构造函数" << endl;
    }

    void show() {
        cout << m_name << " " << m_age << " " << m_stu.m_name << endl;
    }
};

void test() {
    Student stu("kobe");
    Person p2("world", 20, stu);
    p2.show();
}

int main() {
    test();
    return 0;
}
/*
调用Student(string name)构造函数
调用Student()构造函数
调用Person(string name, int age, Student stu)构造函数
world 20 kobe*/
```

使用初始化列表stu

```
#include <iostream>

using namespace std;

class Student {
public:
    string m_name;

    Student() {
        m_name.clear();
        cout << "调用Student()构造函数" << endl;
    }

    Student(string name) {
        m_name = name;
        cout << "调用Student(string name)构造函数" << endl;
    }

    Student(const Student &stu) {
        m_name = stu.m_name;
        cout << "调用Student(const Student &stu)拷贝构造函数" << endl;
    }
};

class Person {
public:
    string m_name;
    int m_age;
    Student m_stu;

    Person() {
        cout << "调用Person()构造函数" << endl;
    }

    Person(string name, int age, Student &stu) : m_name(name), m_age(age), m_stu(stu) {
        cout << "调用Person(string name, int age, Student stu)构造函数" << endl;
    }

    void show() {
        cout << m_name << " " << m_age << " " << m_stu.m_name << endl;
    }
};

void test() {
    Student stu("kobe");
    Person p2("world", 20, stu);
    p2.show();
}

int main() {
    test();
    return 0;
}
/*
调用Student(string name)构造函数
调用Student(const Student &stu)拷贝构造函数
调用Person(string name, int age, Student stu)构造函数
world 20 kobe*/
```

people的 m_stu如果不采用初始化列表，调用的是普通构造函数，如果采用了初始化列表，调用的是拷贝构造函数，还有，如果没有初始化列表，对象的初始化和赋值是两步操作，如果采用了初始化列表，对象的初始化和赋值是一步操作。所以采用初始化列表的效率更高。

如果成员是常量或引用，必须使用初始化列表

![](https://gitee.com/hxc8/images2/raw/master/img/202407172223060.jpg)

```
#include <iostream>

using namespace std;

class Student {
public:
    string m_name;

    Student() {
        m_name.clear();
        cout << "调用Student()构造函数" << endl;
    }

    Student(string name) {
        m_name = name;
        cout << "调用Student(string name)构造函数" << endl;
    }

    Student(const Student &stu) {
        m_name = stu.m_name;
        cout << "调用Student(const Student &stu)拷贝构造函数" << endl;
    }
};

class Person {
public:
    string m_name;
    const int m_age;
    Student m_stu;

    Person() {
        cout << "调用Person()构造函数" << endl;
    }

    Person(string name, int age, Student &stu) {
        m_name = name;
        m_age = age;
        m_stu.m_name = stu.m_name;
        cout << "调用Person(string name, int age, Student stu)构造函数" << endl;
    }

    void show() {
        cout << m_name << " " << m_age << " " << m_stu.m_name << endl;
    }
};

void test() {
    Student stu("kobe");
    Person p2("world", 20, stu);
    p2.show();
}

int main() {
    test();
    return 0;
}
/*
编译报错
no matching constructor for initialization of 'Person'
*/
```

使用初始化列表 ，成员常量就可以了

```
#include <iostream>

using namespace std;

class Student {
public:
    string m_name;

    Student() {
        m_name.clear();
        cout << "调用Student()构造函数" << endl;
    }

    Student(string name) {
        m_name = name;
        cout << "调用Student(string name)构造函数" << endl;
    }

    Student(const Student &stu) {
        m_name = stu.m_name;
        cout << "调用Student(const Student &stu)拷贝构造函数" << endl;
    }
};

class Person {
public:
    string m_name;
    const int m_age;
    Student m_stu;

//    Person() {
//        cout << "调用Person()构造函数" << endl;
//    }

//    Person(string name, int age, Student &stu) {
//        m_name = name;
//        m_age = age;
//        m_stu.m_name = stu.m_name;
//        cout << "调用Person(string name, int age, Student stu)构造函数" << endl;
//    }

    Person(string name, int age, Student &stu) : m_name(name), m_age(age), m_stu(stu) {
        cout << "调用Person(string name, int age, Student stu)构造函数" << endl;
    }

    void show() {
        cout << m_name << " " << m_age << " " << m_stu.m_name << endl;
    }
};

void test() {
    Student stu("kobe");
    Person p2("world", 20, stu);
    p2.show();
}

int main() {
    test();
    return 0;
}
/*
调用Student(string name)构造函数
调用Student(const Student &stu)拷贝构造函数
调用Person(string name, int age, Student stu)构造函数
world 20 kobe
*/
```

成员是引用

```
#include <iostream>

using namespace std;

class Student {
public:
    string m_name;

    Student() {
        m_name.clear();
        cout << "调用Student()构造函数" << endl;
    }

    Student(string name) {
        m_name = name;
        cout << "调用Student(string name)构造函数" << endl;
    }

    Student(const Student &stu) {
        m_name = stu.m_name;
        cout << "调用Student(const Student &stu)拷贝构造函数" << endl;
    }
};

class Person {
public:
    string m_name;
    int m_age;
    Student &m_stu;

//    Person() {
//        cout << "调用Person()构造函数" << endl;
//    }

    Person(string name, int age, Student &stu) {
        m_name = name;
        m_age = age;
        m_stu.m_name = stu.m_name;
        cout << "调用Person(string name, int age, Student stu)构造函数" << endl;
    }

//    Person(string name, int age, Student &stu) : m_name(name), m_age(age), m_stu(stu) {
//        cout << "调用Person(string name, int age, Student stu)构造函数" << endl;
//    }

    void show() {
        cout << m_name << " " << m_age << " " << m_stu.m_name << endl;
    }
};

void test() {
    Student stu("kobe");
    Person p2("world", 20, stu);
    p2.show();
}

int main() {
    test();
    return 0;
}
/*
编译报错
constructor for 'Person' must explicitly initialize the reference member 'm_stu'
*/
```

可以使用

```
#include <iostream>

using namespace std;

class Student {
public:
    string m_name;

    Student() {
        m_name.clear();
        cout << "调用Student()构造函数" << endl;
    }

    Student(string name) {
        m_name = name;
        cout << "调用Student(string name)构造函数" << endl;
    }

    Student(const Student &stu) {
        m_name = stu.m_name;
        cout << "调用Student(const Student &stu)拷贝构造函数" << endl;
    }
};

class Person {
public:
    string m_name;
    int m_age;
    Student &m_stu;

//    Person() {
//        cout << "调用Person()构造函数" << endl;
//    }

//    Person(string name, int age, Student &stu) {
//        m_name = name;
//        m_age = age;
//        m_stu.m_name = stu.m_name;
//        cout << "调用Person(string name, int age, Student stu)构造函数" << endl;
//    }

    Person(string name, int age, Student &stu) : m_name(name), m_age(age), m_stu(stu) {
        cout << "调用Person(string name, int age, Student stu)构造函数" << endl;
    }

    void show() {
        cout << m_name << " " << m_age << " " << m_stu.m_name << endl;
    }
};

void test() {
    Student stu("kobe");
    Person p2("world", 20, stu);
    p2.show();
}

int main() {
    test();
    return 0;
}
/*
调用Student(string name)构造函数
调用Person(string name, int age, Student stu)构造函数
world 20 kobe
 */
```