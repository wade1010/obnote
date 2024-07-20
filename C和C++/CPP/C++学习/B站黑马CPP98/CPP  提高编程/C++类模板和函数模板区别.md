

![](https://gitee.com/hxc8/images3/raw/master/img/202407172235277.jpg)



```javascript
#include "iostream"
#include "string"
#include "fstream"

using namespace std;

/*
 *类模板和函数模板区别
 */

//template<class NameType, class AgeType>
template<class NameType, class AgeType = int>//默认参数类型
class Person {
public:
    Person(NameType n, AgeType a) {
        name = n;
        age = a;
    }

    void showPerson() {
        cout << this->name << " " << this->age << endl;
    }

    NameType name;
    AgeType age;
};

//1 类模板没有自动函数类型推到使用方式

void test1() {
//    Person p("哈哈", 1000);//错误的，无法用自动类型推导
    Person<string, int> p("哈哈", 1000);
    p.showPerson();
}

//2 类模板在模板参数列表中可以有默认参数

void test2() {
    Person<string> p("猪八戒", 20);
    p.showPerson();
}

int main() {
//    test1();
    test2();
    return 0;
}

```

