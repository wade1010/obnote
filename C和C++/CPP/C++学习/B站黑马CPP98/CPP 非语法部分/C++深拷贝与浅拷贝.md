

![](https://gitee.com/hxc8/images3/raw/master/img/202407172227454.jpg)



```javascript
#include "iostream"

using namespace std;
//深拷贝与浅拷贝

class Person {
public:
    Person() {
        cout << "Person()" << endl;
    }

    Person(int age, int height) {
//        age = age;//同名的话，不能这么插座
        this->age = age;
        this->mHeights = new int(height);//手动申请，也需要手动释放
        cout << "Person(int age)" << endl;
    }

    ~Person() {
        //析构函数,将堆区开辟的数据做释放
        if (mHeights != NULL) {
            delete mHeights;
            mHeights = NULL;
        } 
        cout << "~Person()" << endl;
    }

    int age;

    int *mHeights;
};

void test01() {
    Person p(18, 160);
    cout << "p的年龄" << p.age << "身高为：" << *p.mHeights << endl;
    Person p2(p);
    cout << "p2的年龄" << p2.age << "身高为：" << *p2.mHeights << endl;
}

int main() {
    test01();
    return 0;
}
```



运行结果



```javascript
Person(int age)
p的年龄18身高为：160
p2的年龄18身高为：160
~Person()
first_demo(30240,0x10cde9dc0) malloc: *** error for object 0x7fc666c058b0: pointer being freed was not allocated
first_demo(30240,0x10cde9dc0) malloc: *** set a breakpoint in malloc_error_break to debug

```





![](https://gitee.com/hxc8/images3/raw/master/img/202407172227034.jpg)





![](https://gitee.com/hxc8/images3/raw/master/img/202407172227661.jpg)



改进后：



```javascript
#include "iostream"

using namespace std;
//深拷贝与浅拷贝

class Person {
public:
    Person() {
        cout << "Person()" << endl;
    }

    Person(int age, int height) {
//        age = age;//同名的话，不能这么插座
        this->age = age;
        this->mHeights = new int(height);//手动申请，也需要手动释放
        cout << "Person(int age)" << endl;
    }

    ~Person() {
        //析构函数,将堆区开辟的数据做释放
        if (mHeights != NULL) {
            delete mHeights;
            mHeights = NULL;
        }
        cout << "~Person()" << endl;
    }

    Person(const Person &p) {
        age = p.age;
//        mHeights = p.mHeights;//编译器默认实现就是这行代码 浅拷贝
        mHeights = new int(*p.mHeights);
        cout << "Person的拷贝构造函数" << endl;
    }

    int age;

    int *mHeights;
};

void test01() {
    Person p(18, 160);
    cout << "p的年龄" << p.age << "身高为：" << *p.mHeights << endl;
    Person p2(p);
    cout << "p2的年龄" << p2.age << "身高为：" << *p2.mHeights << endl;
}

int main() {
    test01();
    return 0;
}
```



```javascript
Person(int age)
p的年龄18身高为：160
Person的拷贝构造函数
p2的年龄18身高为：160
~Person()
~Person()
```

