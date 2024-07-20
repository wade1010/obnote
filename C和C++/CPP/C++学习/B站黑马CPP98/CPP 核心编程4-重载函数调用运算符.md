CPP 核心编程4-重载函数调用运算符

![](https://gitee.com/hxc8/images3/raw/master/img/202407172237522.jpg)

```
#include "iostream"

using namespace std;

//函数调用运算符重载
//打印输出类
class MyPrint {
public:
    //重载函数调用运算符
    void operator()(string str) {
        cout << "仿函数：" << str << endl;
    }
};

void MyPrint(string str) {
    cout << "全局函数MyPrint：" << str << endl;
}

void mp(string str) {
    cout << "全局函数mp：" << str << endl;
}

class MyAdd {
public:
    int operator()(int a, int b) {
        return a + b;
    }
};

void test1() {
    class MyPrint mp;//这里由于类和全局函数一致，所以这里需要加class
    MyPrint("hello world");//由于使用起来非常类似函数调用，因此称为仿函数
    mp("hello world");//由于使用起来非常类似函数调用，因此称为仿函数
}

void test2() {
    MyAdd myAdd;
    int sum = myAdd(1, 2);
    cout << "sum=" << sum << endl;
    cout << endl;
    //匿名函数对象
    int sum2 = MyAdd()(1, 2);
    cout << "sum2=" << sum2 << endl;
    cout << endl;
}

int main() {
    test1();
    cout << endl;
    test2();
    return 0;
}
全局函数MyPrint：hello world
仿函数：hello world

sum=3

sum2=3

```