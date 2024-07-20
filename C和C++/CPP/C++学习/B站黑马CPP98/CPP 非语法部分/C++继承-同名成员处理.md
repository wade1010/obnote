

![](https://gitee.com/hxc8/images3/raw/master/img/202407172226620.jpg)



![](https://gitee.com/hxc8/images3/raw/master/img/202407172226055.jpg)



```javascript
#include "iostream"
#include "string"

using namespace std;

//继承中同名成员处理

class Base {
public:
    Base() {
        a = 100;
    }

    void func() {
        cout << "Base func 函数调用" << endl;
    }

    void func(int a) {
        cout << "Base func(int a) 函数调用" << endl;
    }

    int a;
};

//公共继承
class Son : public Base {
public:
    Son() {
        a = 200;
    }

    void func() {
        cout << "Son func 函数调用" << endl;
    }

    int a;
};

void test();

void test2();

int main() {
    test();
    test2();
    return 0;
}

void test() {
    Son s;
    cout << s.a << endl;
    //如果通过子类对象 访问父类中同名成员 需要添加作用域
    cout << s.Base::a << endl;
}

void test2() {
    Son s;
    //直接调用到父类中同名成员函数
    s.func();
    //调用父类中的同名成员函数
    s.Base::func();
    //如果子类中出现和父类同名的成员函数，之类的同名成员会隐藏父类中所有的同名成员函数调用
    //如果想访问到父类被隐藏的同名成员函数，需要添加作用域
//    s.func(100);//这个会报错，解释如上
    s.Base::func(100);
}
```

