

![](https://gitee.com/hxc8/images3/raw/master/img/202407172227232.jpg)



```javascript
 #include "iostream"
#include "string"

void test();

using namespace std;

//赋值运算符重载
class Person {
public:
    Person(int age) {
        mAge = new int(age);
    }

    int *mAge;

};

void test() {
    Person p(18);
    cout << *p.mAge << endl;
    Person p2(20);
    cout << *p2.mAge << endl;
    p2 = p;
    cout << *p2.mAge << endl;
}

int main() {
    test();
    return 0;
}

上面代码运行时没有问题的，但是堆区内存没有释放
```





那就添加析构函数，结果却报错。

```javascript
#include "iostream"
#include "string"

void test();

using namespace std;

//赋值运算符重载
class Person {
public:
    Person(int age) {
        mAge = new int(age);
    }

    ~Person() {
        if (mAge != NULL) {
            delete mAge;
            mAge = NULL;
        }
    }

    int *mAge;

};

void test() {
    Person p(18);
    cout << *p.mAge << endl;
    Person p2(20);
    cout << *p2.mAge << endl;
    p2 = p;
    cout << *p2.mAge << endl;
}

int main() {
    test();
    return 0;
}


```



```javascript
18
20
18
first_demo(23741,0x110134dc0) malloc: *** error for object 0x7fc8d9c05890: pointer being freed was not allocated
first_demo(23741,0x110134dc0) malloc: *** set a breakpoint in malloc_error_break to debug

```





![](https://gitee.com/hxc8/images3/raw/master/img/202407172227577.jpg)

浅拷贝

![](https://gitee.com/hxc8/images3/raw/master/img/202407172227815.jpg)



这样就重复释放同一内存，就报错



重载赋值运算，深拷贝解决

![](https://gitee.com/hxc8/images3/raw/master/img/202407172227080.jpg)



重载赋值运算符

```javascript
#include "iostream"
#include "string"

void test();

using namespace std;

//赋值运算符重载
class Person {
public:
    Person(int age) {
        mAge = new int(age);
    }

    ~Person() {
        if (mAge != NULL) {
            delete mAge;
            mAge = NULL;
        }
    }

    void operator=(Person &p) {
        //编译器提供的是浅拷贝，就是mAge=p.mAge;
        //应该先判断是否有属性在堆区，如果有先释放干净。
        if (mAge != NULL) {
            delete mAge;
            mAge = NULL;
        }
        mAge = new int(*p.mAge);
    }

    int *mAge;

};

void test() {
    Person p(18);
    cout << *p.mAge << endl;
    Person p2(20);
    cout << *p2.mAge << endl;
    p2 = p;
    cout << *p2.mAge << endl;
}

int main() {
    test();
    return 0;
}


```

正常运行，但是不能连等操作，如   p3 = p2 = p;//No viable overloaded '='

```javascript
#include "iostream"
#include "string"

void test();

using namespace std;

//赋值运算符重载
class Person {
public:
    Person(int age) {
        mAge = new int(age);
    }

    ~Person() {
        if (mAge != NULL) {
            delete mAge;
            mAge = NULL;
        }
    }

    Person &operator=(const Person &p) {
        //编译器提供的是浅拷贝，就是mAge=p.mAge;
        //应该先判断是否有属性在堆区，如果有先释放干净。
        if (mAge != NULL) {
            delete mAge;
            mAge = NULL;
        }
        mAge = new int(*p.mAge);
        return *this;
    }

    int *mAge;

};

void test() {
    Person p(18);
    cout << *p.mAge << endl;
    Person p2(20);
    cout << *p2.mAge << endl;
    Person p3(30);
    p3 = p2 = p;
    cout << *p2.mAge << endl;
    cout << *p3.mAge << endl;
}

int main() {
    test();
    return 0;
}


```



```javascript
18
20
18
18
```



![](https://gitee.com/hxc8/images3/raw/master/img/202407172227298.jpg)

