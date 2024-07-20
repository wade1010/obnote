26 CPP运算符重载

![](https://gitee.com/hxc8/images2/raw/master/img/202407172218797.jpg)

![](images/WEBRESOURCE87a6b313c2f907ed9821b1c52132dcfd截图.png)

```
#include <iostream>

using namespace std;

//非成员函数重载+ -
class Person {
public:
    Person() {
        m_score = 0;
        m_name.clear();
    }

    void show() {
        cout << m_name << " " << m_score << endl;
    }

    int m_score;
    string m_name;
};

//全局重载+号
void operator+(Person &p, int score) {
    p.m_score += score;
}

//全局重载-号
Person &operator-(Person &p, int score) {
    p.m_score -= score;
    return p;
}

void test() {
    Person p;
    p.m_score = 1;
    p + 10;
    p.show();
    p = p - 1 - 2;//同下
//    p = operator-(operator-(p, 1), 2);
    p.show();
}

int main() {
    test();
    return 0;
}
 11
 8
```

```
#include <iostream>

using namespace std;

//非成员函数重载+ -
class Person {
public:
    Person() {
        m_score = 0;
        m_name.clear();
    }

    void show() {
        cout << m_name << " " << m_score << endl;
    }

    void operator+(int score) {
        this->m_score += score;
    }

    Person &operator-(int score) {
        this->m_score -= score;
        return *this;
    }

    int m_score;
    string m_name;
};


void test() {
    Person p;
    p.m_score = 1;
    p + 10;
    p.show();
    p = p - 1 - 2;//同下
//    p = operator-(operator-(p, 1), 2);
    p.show();
}

int main() {
    test();
    return 0;
}
```

注意事项：

1 返回自定义数据类型的引用可以让多个运算符表达式串联起来。（不要返回局部变量的引用）

2 重载函数参数列表中顺序决定了操作数的位置（后面有代码说明）

3 重载函数的参数列表中至少有一个是用户自定义的类型，防止程序员为内置数据类型重载运算符

4 如果运算符重载既可以是成员函数也可以是全局函数，应该优先考虑成员函数，这样更符合运算符重载的初衷。

5 重载函数不能违背运算符原来的含义和优先级。（语法上没有限制不能这么做，但是这么做很垃圾）

6 不能创建新的运算符

7 以下运算符不可以重载

![](https://gitee.com/hxc8/images2/raw/master/img/202407172219727.jpg)

![](https://gitee.com/hxc8/images2/raw/master/img/202407172219068.jpg)

8 以下运算符只能通过成员函数进行重载

![](https://gitee.com/hxc8/images2/raw/master/img/202407172219160.jpg)

![](https://gitee.com/hxc8/images2/raw/master/img/202407172219284.jpg)

重载函数参数列表中顺序决定了操作数的位置 代码说明

```
#include <iostream>

using namespace std;

//重载函数参数列表中顺序决定了操作数的位置
class Person {
public:
    Person() {
        m_score = 0;
        m_name.clear();
    }

    void show() {
        cout << m_name << " " << m_score << endl;
    }

    int m_score;
    string m_name;
};

Person &operator+(Person &p, int score) {
    p.m_score += score;
    return p;
}

Person &operator+(int score, Person &p) {
    p.m_score += score;
    return p;
}

Person &operator+(Person &p1, Person &p2) {
    p1.m_score += p2.m_score;
    return p1;
}


void test() {
    Person p;
    p.m_score = 1;
    p + 10;
    p.show();
    //如上，重载的时候 Person &p, int score 顺序是这样，那么下面代码将报错
    p + 10 + p;//Invalid operands to binary expression ('void' and 'Person'
    //所以需要再写两个重载，顺序是int score,Person &p和Person &p1, Person &p2
    p.show();
}

int main() {
    test();
    return 0;
}
```

![](https://gitee.com/hxc8/images2/raw/master/img/202407172219321.jpg)

上图 关系运算符和左移运算符重载比较简单，其它几个运算符重载都有一些不一样的地方，C++编译器需要特别的处理。

重载左移运算符

```
#include <iostream>

using namespace std;

//左移运算符 cout
class People {
    friend ostream &operator<<(ostream &cout, const People &p);

    int m_age;
    int m_score;
public:
    People() {
        m_age = 18;
        m_score = 0;
    }

    ostream &operator<<(ostream &cout) {
        cout << m_age << " " << m_score << endl;
        return cout;
    }
};

ostream &operator<<(ostream &cout, const People &p) {
    cout << p.m_age << " " << p.m_score << endl;
    return cout;
}

void test() {
    People p;
    cout << p << endl;
    p << cout << endl;//如果是成员函数的重载，位置就不对了。
}

int main() {
    test();
    return 0;
}
```

重载下标运算符

![](https://gitee.com/hxc8/images2/raw/master/img/202407172219837.jpg)

![](https://gitee.com/hxc8/images2/raw/master/img/202407172219062.jpg)

![](https://gitee.com/hxc8/images2/raw/master/img/202407172219277.jpg)

![](https://gitee.com/hxc8/images2/raw/master/img/202407172219728.jpg)

![](https://gitee.com/hxc8/images2/raw/master/img/202407172219917.jpg)

注意：

1 编译器提供的默认赋值函数，是浅拷贝

2 如果对象不存在堆区内存空间，默认赋值函数可以满足需求，否则需要深拷贝。

3 赋值运算和拷贝构造不同：拷贝构造是指原来的对象不存在，用已存在的对象进行构造；赋值运算是指已经存在了两个对象，把其中一个对象的成员变量的值赋给另外一个对象的成员变量。

```
#include <iostream>

using namespace std;

//重载赋值运算符
class People {

public:
    int m_age;
    int m_score;
    int *m_ptr;

    People() {
        m_age = 18;
        m_score = 0;
        m_ptr = nullptr;
    }

    ~People() {
        if (m_ptr == nullptr) {
            delete m_ptr;
        }
    }

    void show() {
        cout << m_age << " " << m_score << endl;
        if (m_ptr != nullptr) {
            cout << *m_ptr << endl;
        }
    }

    People &operator=(const People &p) {
        //如果是自己给自己赋值
        if (this == &p) {
            return *this;
        }
        if (p.m_ptr == nullptr) {//如果源对象的指针为空，则清空目标对象的内存和指针
            if (m_ptr != nullptr) {
                delete m_ptr;
                m_ptr = nullptr;
            }
        } else {//源对象指针不为空
            //如果目标对象的指针为空，先分配内存
            if (m_ptr == nullptr) {
                m_ptr = new int;
            }
            //然后把源对象内存中的数据复制到目标对象的内存中
            memcpy(m_ptr, p.m_ptr, sizeof(int));
        }
        m_score = p.m_score;
        m_age = p.m_age;
        cout << "调用了重载赋值函数\n" << endl;
        return *this;
    }
};

void test() {
    People p1, p2;
    p1.m_score = 10;
    p1.m_age = 10;
    p1.m_ptr = new int(2);
    p1.show();
    p2.show();
    p2 = p1;
    p2.show();
}

int main() {
    test();
    return 0;
}
10 10
2
18 0
调用了重载赋值函数

10 10
2
```

重载new&delete运算符

![](https://gitee.com/hxc8/images2/raw/master/img/202407172219162.jpg)

![](https://gitee.com/hxc8/images2/raw/master/img/202407172219494.jpg)

![](https://gitee.com/hxc8/images2/raw/master/img/202407172219781.jpg)

![](https://gitee.com/hxc8/images2/raw/master/img/202407172219963.jpg)

![](https://gitee.com/hxc8/images2/raw/master/img/202407172219004.jpg)

new[]和detele[]与 new和detele基本一致，在实际开发中，类用数组表示的 情况不多见，需要自定义分配内存的情况基本上没有，所以这两

为一个类重在new和delete时，尽管不必显示第使用static，但实际上仍在创建static成员函数  说明：

也就是说，声明这两个成员函数的时候，不管有没有写static关键字，它们都是静态成员函数，所以在这两个函数中，不能访问类的非静态成员。

```
#include <iostream>

using namespace std;

//为整型动态分配内存
void *operator new(size_t size) {
    cout << "调用了重载的new：" << size << "字节" << endl;
    void *p = malloc(size);
    cout << "申请到的内存地址是：" << p << endl;
    return p;
}

void operator delete(void *ptr) {
    cout << "调用了重载的delete" << endl;
//    if (ptr == 0) {//这是模拟C++的封装，所以C++中delete空指针也是安全的
    if (ptr == nullptr) {//这是模拟C++的封装，所以C++中delete空指针也是安全的
        return;
    }
    free(ptr);
}

void test() {
    //对于我们来说，C++的new和delete缺省屏蔽了全部细节，如果我们重载了new和delete运算符，就可以
    //用自己的代码申请和释放内存了。
    int *p = new int(1);
    cout << "p=" << p << ",*p=" << *p << endl;
    cout << "p=" << (void *) p << ",*p=" << *p << endl;
    delete p;


}

int main() {
    test();
    return 0;
}
/*
调用了重载的new：4字节
申请到的内存地址是：0x7fb2fc405910
p=0x7fb2fc405910,*p=1
p=0x7fb2fc405910,*p=1
调用了重载的delete
*/
```

```
#include <iostream>

using namespace std;

//为类动态分配内存  全局
void *operator new(size_t size) {
    cout << "调用了全局重载的new：" << size << "字节" << endl;
    void *p = malloc(size);
    cout << "申请到的内存地址是：" << p << endl;
    return p;
}

void operator delete(void *ptr) {
    cout << "调用了全局重载的delete" << endl;
//    if (ptr == 0) {//这是模拟C++的封装，所以C++中delete空指针也是安全的
    if (ptr == nullptr) {//这是模拟C++的封装，所以C++中delete空指针也是安全的
        return;
    }
    free(ptr);
}

class Person {
public:
    int m_age;
    int m_score;

    Person(int age, int score) {
        m_age = age;
        m_score = score;
        cout << "调用了构造函数Person(int age, int score)" << endl;
    }

    ~Person() {
        cout << "调用了析构函数~Person()" << endl;
    }

    void *operator new(size_t size) {
        cout << "调用了类重载的new：" << size << "字节" << endl;
        void *p = malloc(size);
        cout << "申请到的内存地址是：" << p << endl;
        return p;
    }

    void operator delete(void *ptr) {
        cout << "调用了类重载的delete" << endl;
//    if (ptr == 0) {//这是模拟C++的封装，所以C++中delete空指针也是安全的
        if (ptr == nullptr) {//这是模拟C++的封装，所以C++中delete空指针也是安全的
            return;
        }
        free(ptr);
    }
};

void test() {
    //对于我们来说，C++的new和delete缺省屏蔽了全部细节，如果我们重载了new和delete运算符，就可以
    //用自己的代码申请和释放内存了。
    int *p = new int(1);
    cout << "p=" << p << ",*p=" << *p << endl;
    cout << "p=" << (void *) p << ",*p=" << *p << endl;
    delete p;
    cout << endl;
    Person *p2 = new Person(1, 2);
    cout << "p2的地址是" << p2 << " " << p2->m_age << " " << p2->m_score << endl;
    delete p2;


}

int main() {
    test();
    return 0;
}
调用了全局重载的new：4字节
申请到的内存地址是：0x7ff198c05910
p=0x7ff198c05910,*p=1
p=0x7ff198c05910,*p=1
调用了全局重载的delete

调用了类重载的new：8字节
申请到的内存地址是：0x7ff198c05910
调用了构造函数Person(int age, int score)
p2的地址是0x7ff198c05910 1 2
调用了析构函数~Person()
调用了类重载的delete
```