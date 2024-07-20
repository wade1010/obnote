

```javascript
#include "iostream"
#include "string"

void test();

using namespace std;


//左移运算符重载
class Person {
public:
    //利用成员函数重载 左移运算符  调用就是p.operator<<(p);
//    void operator<<(Person &p) {
//
//    }
    //利用成员函数重载 左移运算符  调用就是p.operator<<(count); 这样简化后 p<<count ,也不符合要求
//    void operator<<(count) {
//
//    }

    int ma;
    int mb;
};

//只能利用全局函数重载左移运算符
void operator<<(ostream &count, Person &p) {
    cout << "ma=" << p.ma << ",mb=" << p.mb << endl;
}

int main() {
    test();
    return 0;
}

void test() {
    Person m;
    m.ma = 10;
    m.mb = 20;
    cout << m.ma << " " << m.mb << endl;
    cout << m;//可以输出，但是没有换行
    cout << m << endl;//报错

}
```





## 支持endl

```javascript
#include "iostream"
#include "string"

void test();

using namespace std;


//左移运算符重载
class Person {
public:
    //利用成员函数重载 左移运算符  调用就是p.operator<<(p);
//    void operator<<(Person &p) {
//
//    }
    //利用成员函数重载 左移运算符  调用就是p.operator<<(count); 这样简化后 p<<count ,也不符合要求
//    void operator<<(count) {
//
//    }

    int ma;
    int mb;
};

//只能利用全局函数重载左移运算符 cout << m << endl;//报错,所以需要链式调用
//void operator<<(ostream &count, Person &p) {
//    cout << "ma=" << p.ma << ",mb=" << p.mb << endl;
//}
ostream &operator<<(ostream &count, Person &p) {
    cout << "ma=" << p.ma << ",mb=" << p.mb << endl;
    return cout;
}

int main() {
    test();
    return 0;
}

void test() {
    Person m;
    m.ma = 10;
    m.mb = 20;
    cout << m.ma << " " << m.mb << endl;
//    cout << m;//可以输出，但是没有换行
//    cout << m << endl;//报错,所以需要链式调用
    cout << m << "hello world" << endl;

}
```





支持private



```javascript
#include "iostream"
#include "string"

void test();

using namespace std;


//左移运算符重载
class Person {
    friend ostream &operator<<(ostream &count, Person &p);

public :
    Person(int a, int b) {
        ma = a;
        mb = b;
    }

private:
    int ma;
    int mb;
};

ostream &operator<<(ostream &count, Person &p) {
    cout << "ma=" << p.ma << ",mb=" << p.mb << endl;
    return cout;
}

int main() {
    test();
    return 0;
}

void test() {
    Person m(10, 20);
    cout << m << endl;
}
```

