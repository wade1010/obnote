![](https://gitee.com/hxc8/images2/raw/master/img/202407172222670.jpg)

```
#include <iostream>

using namespace std;

//重载前置++ 和  后置++
class Person {
public:
    string m_name;
    int m_ranking;

    Person() {
        m_name = "hello";
        m_ranking = 1;
    }

    void show() const {
        cout << m_name << " " << m_ranking << endl;
    }

    //++前置
    Person &operator++() {
        m_ranking++;
        return *this;
    }

    //后置
    const Person operator++(int) {
        Person tmp = *this;
        m_ranking++;
        return tmp;
    }

};

void test() {
    Person p;
    ++(++p);
    p.show();
    p++;
    p.show();
}

int main() {
    test();
    return 0;
}
```