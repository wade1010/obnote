

![](https://gitee.com/hxc8/images3/raw/master/img/202407172227395.jpg)



![](https://gitee.com/hxc8/images3/raw/master/img/202407172227859.jpg)





![](images/0337D5E9E44A462FB01C3F2D53560C88image.png)



```javascript
#include "iostream"

using namespace std;

class Person {
public:
    static void test() {
        ma = 100;//静态成员函数 可以 访问静态成员变量
//        mb = 10;//静态成员函数 不可以 访问静态成员变量
        cout << "static void test" << endl;
    }

    static int ma;//静态成员变量
    int mb;//非静态成员变量
};

int Person::ma = 10;//必须  静态变量需要在类外初始化

void test() {
    Person p;
    p.test();
    //或者
    Person::test();
}

int main() {
    test();
    return 0;
}
```

