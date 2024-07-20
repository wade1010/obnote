

![](https://gitee.com/hxc8/images3/raw/master/img/202407172226818.jpg)



![](https://gitee.com/hxc8/images3/raw/master/img/202407172226183.jpg)



```javascript
#include "iostream"
#include "string"

using namespace std;

//继承中同名静态成员处理

class Base {
public:
    static void func() {
        cout << "Base static func" << endl;
    }

    static void func(int a) {
        cout << "Son static func(int a)" << endl;
    }

    static int a;
};

//类内声明，类外初始化
int Base::a = 1;

class Son : public Base {
public:
    static void func() {
        cout << "Son static func" << endl;
    }

    static int a;
};

int Son::a = 2;

void test() {
    //1 通过对象访问
    Son s;
    //直接访问子类
    cout << s.a << endl;
    //访问父类的
    cout << s.Base::a << endl;

    //2 通过类名访问
    cout << Son::a << endl;
    cout << Son::Base::a << endl;
}

void test2() {
    //1 通过对象访问
    Son s;
    s.func();
    s.Base::func();
    //2 通过类名访问
    Son::func();
    Son::Base::func();
    //子类出现和父类同名静态成员函数，也会隐藏父类中所有同名成员函数
    //如果想访问父类中被隐藏的同名成员，需要添加作用域
    Son::Base::func(100);
}

int main() {
    test();
    test2();
    return 0;
}
```

