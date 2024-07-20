

![](images/CBACEF3198CD4FA286B4BC94EBC378C6image.png)



![](https://gitee.com/hxc8/images3/raw/master/img/202407172227462.jpg)



```javascript
#include "iostream"

using namespace std;

//1构造函数
class Student {
    /*
     * 1构造函数 得需要在public作用域中，struct就不需要写
     * 没有返回值，不写void
     * 函数名与类名相同
     * 构造函数可以有参数，可以发生重载
     * 创建对象的时候，构造函数会自动调用，而且只调用一次
     */
public:
    Student() {
        cout << "Student构造函数调用" << endl;
    }

};

struct Student2 {
    /*
     * 1构造函数 struct模式是public
     * 没有返回值，不写void
     * 函数名与类名相同
     * 构造函数可以有参数，可以发生重载
     * 创建对象的时候，构造函数会自动调用，而且只调用一次
     */
    Student2() {
        cout << "Student2构造函数调用" << endl;
    }

};


//2析构函数
 

class Car {
public:
    Car() {
        cout << "Car构造函数" << endl;
    }

    ~Car() {
        cout << "Car析构函数" << endl;
    }
};

int main() {
    Student stu;
    Student2 stu2;
    Car car;
    return 0;
}
```





![](https://gitee.com/hxc8/images3/raw/master/img/202407172227777.jpg)



![](images/30D01D7060354CEBAFB4C58B226A742Cimage.png)



![](images/94E4186453D64930B414E99E7DDE3753image.png)

