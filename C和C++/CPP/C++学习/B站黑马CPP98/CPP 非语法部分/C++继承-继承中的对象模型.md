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
class Son : public Base1 {
public:
    int d;
};

void test();

int main() {
    test();
    return 0;
}

void test() {
    //size=16，
    //父类中所有非静态成员属性都会被子类继承下去
    //父类中私有成员属性 是被编译器给隐藏了 因此是访问不到的 但是确实被继承下去了
    cout << "size of Son=" << sizeof(Son) << endl;
}
```

