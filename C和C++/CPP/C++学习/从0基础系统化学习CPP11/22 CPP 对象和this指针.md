![](https://gitee.com/hxc8/images2/raw/master/img/202407172223997.jpg)

 

![](https://gitee.com/hxc8/images2/raw/master/img/202407172223971.jpg)

```
#include <iostream>

using namespace std;

class Person {
public:
    string m_name;
    int m_age;

    Person() {
    }

    Person(string name, int age) {
        m_name = name;
        m_age = age;
    }

    const Person &pk(const Person &p) {
        return this->m_age > p.m_age ? *this : p;
    }
};

void test() {
    Person p1("a", 1), p2("b", 2), p3("c", 3), p4("d", 4);
    p1.pk(p2).pk(p3);
}

int main() {
    test();
    return 0;
}
```

上面代码报错如下

![](https://gitee.com/hxc8/images2/raw/master/img/202407172223237.jpg)

上面报错其实就是const对象只能调用const修饰的成员函数。

![](https://gitee.com/hxc8/images2/raw/master/img/202407172223304.jpg)

上图不知道为什么截图不清楚

```
void test() {
    Person p1("a", 1), p2("b", 2), p3("c", 3), p4("d", 4);
    p1.pk(p2);//只调用一次不报错，因为开始调用的时候p1不是const,只是第一次调用后返回的是const,导致不能再次调用pk函数了，如下面代码如果开始声明变量就加上const
    const Person cp;
    cp.pk(p2);//就是报上图的错，'this' argument to member function 'pk' has type 'const Person', but function is not marked const
}
```