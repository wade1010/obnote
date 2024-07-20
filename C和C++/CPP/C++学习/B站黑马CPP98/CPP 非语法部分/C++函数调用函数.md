

![](https://gitee.com/hxc8/images3/raw/master/img/202407172227862.jpg)



```javascript
#include "iostream"
#include "string"

void test();

void test2();

using namespace std;

//函数调用运算符重载
class MyPrint {
public:
    //重载函数调用运算符
    void operator()(string test) {
        cout << test << endl;
    }
};

void printFunc(string test) {
    cout << test << endl;
}


void test() {
    MyPrint mp;
    mp("hello world");//由于使用起来非常类似于函数调用，因此被称为仿函数
    printFunc("hello world");
}

//仿函数非常灵活，没有固定的写法
//加法类

class MyAdd {
public:
    int operator()(int num1, int num2) {
        return num1 + num2;
    }

};

int main() {
    test();
    test2();
    return 0;
}

void test2() {
    MyAdd add;
    int result = add(10, 20);
    cout << result << endl;
    //匿名函数对象  MyAdd() 就是创建匿名对象，这一样执行完就释放
    cout << MyAdd()(10, 20) << endl;
}


```

