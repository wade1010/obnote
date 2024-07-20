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
    //int 代表占位符，可以用于区分前置和后置 ,只能用int
    MyInteger operator++(int) {
        MyInteger tmp = *this;
        num++;
        return tmp;
    }

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
//    cout << (myInteger++)++ << endl;//因为返回的是 值  不是引用，链式操作就不是操作桶给一个对象
    cout << myInteger++ << endl;
    cout << myInteger << endl;//需要返回 引用，不能返回 值,否则不是操作一个数
}

int main() {
    test();
    return 0;
}
```

