

![](https://gitee.com/hxc8/images3/raw/master/img/202407172227971.jpg)



```javascript
#include "iostream"

using namespace std;

//成员变量和成员函数是分开存储的
class Person {

};


void test() {
    Person p;
    cout << "sizeof p=" << sizeof(p) << endl;
}

int main() {
    test();
    return 0;
}
```



![](https://gitee.com/hxc8/images3/raw/master/img/202407172227531.jpg)

```javascript
输出
sizeof p=1
```





```javascript
#include "iostream"

using namespace std;

//成员变量和成员函数是分开存储的
class Person {
public:
    int mb;//非静态成员变量  属于类的对象上
    static int ma;//静态成员变量  不属于类对象上
    void test() {}//非静态成员函数 不属于类对象上
    static void test2() {}//静态成员函数 不属于类对象上
};

int Person::ma = 10;//必须  静态变量需要在类外初始化

void test() {
    Person p;
    cout << "sizeof p=" << sizeof(p) << endl;
}

int main() {
    test();
    return 0;
}
```



输出

```javascript
sizeof p=4//其实就是 int mb;的空间
```





![](https://gitee.com/hxc8/images3/raw/master/img/202407172227200.jpg)



```javascript
#include "iostream"

using namespace std;

//1 解决名称冲突

class Person {
public:
    Person(int age) {
        //age = age;
        //this 指针指向 被调用的成员函数 所属的对象
        this->age = age;
    }

    Person *addAge(int age) {
        this->age += age;
        return this;
    }

    int age;
};

void test() {
    Person p(18);
    cout << "age=" << p.age << endl;
}

int main() {
//    test();
    Person p(10);
    p.addAge(10)->addAge(10);
    cout << p.age << endl;
    return 0;
}
```

输出

```javascript
30
```







重要：

```javascript
#include "iostream"

using namespace std;

//1 解决名称冲突

class Person {
public:
    Person(int age) {
        //age = age;
        //this 指针指向 被调用的成员函数 所属的对象
        this->age = age;
    }

    Person *addAge(int age) {
        this->age += age;
        return this;
    }

    Person &addAge2(int age) {
        this->age += age;
        return *this;
    }

    Person addAge3(int age) {
        this->age += age;
        return *this;
    }

    int age;
};

void test() {
    Person p(18);
    cout << "age=" << p.age << endl;
}

int main() {
//    test();
    Person p(10);
    p.addAge(10)->addAge(10);
    cout << p.age << endl;

    Person p2(10);
    p2.addAge2(10).addAge2(10);
    cout << p2.age << endl;

    Person p3(10);
    //调用第一次后返回的是个p3的拷贝，所以第一次调用后，再调用都不是本体操作，所以p3.age=20
    p3.addAge3(10).addAge3(10).addAge3(10);
    cout << p3.age << endl;
    return 0;
}
```



```javascript
输出
30
30
20
```





