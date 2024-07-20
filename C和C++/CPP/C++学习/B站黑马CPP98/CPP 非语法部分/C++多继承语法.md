

![](https://gitee.com/hxc8/images3/raw/master/img/202407172226877.jpg)



![](https://gitee.com/hxc8/images3/raw/master/img/202407172226135.jpg)

```javascript
#include "iostream"
#include "string"

using namespace std;

//多继承

class Base1 {
public:
    Base1() {
        a = 100;
    }

    int a;
};

class Base2 {
public:
    Base2() {
        b = 200;
    }

    int b;
};

//子类 集成Base1和Base2
//语法  class 子类 :public 父类1,public 父类2...
class Son : public Base1, public Base2 {
public:
    Son() {
        c = 300;
    }

    int c;
};

void test() {
    Son s;
    cout << sizeof(s) << endl;
}

int main() {
    test();
    return 0;
}
```



```javascript
12
```







```javascript
#include "iostream"
#include "string"

using namespace std;

//多继承

class Base1 {
public:
    Base1() {
        a = 100;
    }

    int a;
};

class Base2 {
public:
    Base2() {
        a = 200;
        b = 200;
    }

    int a;
    int b;
};

//子类 集成Base1和Base2
//语法  class 子类 :public 父类1,public 父类2...
class Son : public Base1, public Base2 {
public:
    Son() {
        c = 300;
    }

    int c;
};

void test() {
    Son s;
    cout << sizeof(s) << endl;
    //当父类出现同名成员，需要加作用域区分
    cout << "base1 a=" << s.Base1::a << endl;
    cout << "base2 a=" << s.Base2::a << endl;
}

int main() {
    test();
    return 0;
}
```

