

```javascript
#include "iostream"
#include "string"

using namespace std;

//继承方式

class Base {
public:
    Base() {
        cout << "Base构造函数" << endl;
    }

    ~Base() {
        cout << "Base析构函数" << endl;
    };
};

//公共继承
class Son : public Base {
public:
    Son() {
        cout << "Son构造函数" << endl;
    }

    ~Son() {
        cout << "Son析构函数" << endl;
    };
};

void test();

int main() {
    test();
    return 0;
}

void test() {
    Son s;
}
```



```javascript
Base构造函数
Son构造函数
Son析构函数
Base析构函数
```



![](https://gitee.com/hxc8/images3/raw/master/img/202407172226569.jpg)

