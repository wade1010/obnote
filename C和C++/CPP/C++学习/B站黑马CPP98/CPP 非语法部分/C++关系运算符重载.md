

![](https://gitee.com/hxc8/images3/raw/master/img/202407172227579.jpg)



```javascript
#include "iostream"
#include "string"

void test();

using namespace std;

//关系运算符重载
class Person {
public:
    Person(string name, int age) {
        mName = name;
        mAge = age;
    }

    bool operator==(Person &p) {
        return mAge == p.mAge && mName == p.mName;
    }

    bool operator!=(Person &p) {
        return mAge != p.mAge || mName != p.mName;
    }

    string mName;
    int mAge;

};

void test() {
    Person p1("bob", 18);
    Person p2("tom", 18);
    Person p3("tom", 18);
    if (p1 == p2) {
        cout << "相等" << endl;
    } else {
        cout << "不相等" << endl;
    }
    if (p1 != p3) {
        cout << "不相等" << endl;
    }
}

int main() {
    test();
    return 0;
}


```

