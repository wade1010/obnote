

```javascript
#include "iostream"
#include "string"

void test();

using namespace std;


//重载 递增运算符
class MyInteger {
    friend ostream &operator<<(ostream &cout, MyInteger myInteger);

public :
    MyInteger() {
        num = 0;
    }

    //重载前置++运算符  这里需要返回 引用，不能返回 值
    MyInteger operator++() {//这里故意测试 返回 值
        num++;
        return *this;
    }
    //重载后置++运算符

private:
    int num;
};

//先重载左移运算符
ostream &operator<<(ostream &cout, MyInteger myInteger) {
    cout << myInteger.num << endl;
    return cout;
}

void test() {
    MyInteger myInteger;
//    cout << ++myInteger << endl;
    cout << ++(++myInteger) << endl;
    cout << myInteger << endl;//需要返回 引用，不能返回 值,否则不是操作一个数
}

int main() {
    test();
    return 0;
}


```

输出

```javascript
2

1
```







改成返回引用



```javascript
#include "iostream"
#include "string"

void test();

using namespace std;


//重载 递增运算符
class MyInteger {
    friend ostream &operator<<(ostream &cout, MyInteger myInteger);

public :
    MyInteger() {
        num = 0;
    }

    //重载前置++运算符  这里需要返回 引用，不能返回 值
    MyInteger &operator++() {
        num++;
        return *this;
    }
    //重载后置++运算符

private:
    int num;
};

//先重载左移运算符
ostream &operator<<(ostream &cout, MyInteger myInteger) {
    cout << myInteger.num << endl;
    return cout;
}

void test() {
    MyInteger myInteger;
//    cout << ++myInteger << endl;
    cout << ++(++myInteger) << endl;
    cout << myInteger << endl;//需要返回 引用，不能返回 值,否则不是操作一个数
}

int main() {
    test();
    return 0;
}


```

输出就对了

```javascript
2

2
```



