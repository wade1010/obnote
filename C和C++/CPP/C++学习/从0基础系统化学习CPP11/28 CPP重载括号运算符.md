28 CPP重载括号运算符

![](https://gitee.com/hxc8/images2/raw/master/img/202407172223980.jpg)

![](https://gitee.com/hxc8/images2/raw/master/img/202407172223276.jpg)

![](images/WEBRESOURCE9e825117280ba9e06692dc4c7be1cb33截图.png)

用重载了括号运算符的类创建的对象也叫函数对象或者仿函数

```
#include <iostream>

using namespace std;

void show(string str) {
    cout << "普通函数：" << str << endl;
}

class Person {
public:
    void operator()(string str) {
        cout << "重载函数：" << str << endl;
    }

};

void test() {
    /* show("hello");
     Person show;
     show("hello");*/
    /*上面输出
    普通函数：hello
    重载函数：hello*/

    Person show;
    ::show("hello");//这一行想要调用全局函数，在方法面前加上::
    show("hello");
    /*上面输出
    普通函数：hello
    重载函数：hello*/
}

int main() {
    test();
    return 0;
}
```