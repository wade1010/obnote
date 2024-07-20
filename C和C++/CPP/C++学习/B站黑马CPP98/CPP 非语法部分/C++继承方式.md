

![](https://gitee.com/hxc8/images3/raw/master/img/202407172226642.jpg)





![](https://gitee.com/hxc8/images3/raw/master/img/202407172226937.jpg)



```javascript
#include "iostream"
#include "string"

using namespace std;

//继承方式

class Base1 {
public:
    int a;
protected:
    int b;
private:
    int c;
};

//公共继承
class Son1 : public Base1 {
public:
    void test() {
        a = 10;//父类中的公共权限成员到子类中依然是公共权限
        b = 20;//父类中的保护权限成员到子类中依然是保护权限
//        c = 30;//父类中的私有权限成员到子类中访问不到
    }
};

//保护继承
class Son2 : protected Base1 {
public:
    void test() {
        a = 10;//父类中的公共权限成员到子类中变为保护权限
        b = 20;//父类中的保护权限成员到子类中依然是保护权限
//        c = 30;//父类中的私有权限成员到子类中访问不到
    }
};

//私有继承
class Son3 : private Base1 {
public:
    void test() {
        a = 10;//父类中的   公共权限成员 到子类中变为   私有权限
        b = 20;//父类中的   保护权限成员 到子类中变为   私有权限
        //c = 30;//父类中的私有权限成员到子类中访问不到
    }
};

class GrandChild : public Son3 {
public:
    void test() {
//        a = 1000;//访问不到，是Son3私有的

    }
};

int main() {
    Son1 s1;
    s1.test();
    s1.a = 10;//公共权限，类外能访问
//    s1.b=20;//保护权限，类外不能访问

    Son2 s2;
    s2.test();
//    s2.a = 10;//保护权限，类外不能访问
//    s2.b = 20;//保护权限，类外不能访问

    Son3 s3;
    s3.test();
//    s3.a = 10;//变为私有成员，类外访问不到
//    s3.b = 10;//变为私有成员，类外访问不到

    return 0;
}
```

