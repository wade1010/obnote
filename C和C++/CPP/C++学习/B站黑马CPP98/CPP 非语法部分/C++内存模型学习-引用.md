

![](https://gitee.com/hxc8/images3/raw/master/img/202407172228694.jpg)



![](https://gitee.com/hxc8/images3/raw/master/img/202407172228123.jpg)



![](https://gitee.com/hxc8/images3/raw/master/img/202407172228806.jpg)



![](https://gitee.com/hxc8/images3/raw/master/img/202407172228486.jpg)

```javascript
#include "iostream"

using namespace std;

//交换函数
//1 值传递
void swap1(int a, int b) {
    int temp = a;
    a = b;
    b = temp;
}
//2 地址传递

void swap2(int *a, int *b) {
    int temp = *a;
    *a = *b;
    *b = temp;
}

//3 引用传递

void swap3(int &a, int &b) {
    int temp = a;
    a = b;
    b = temp;
}

int main() {
    int a = 10;
    int b = 20;
//    swap1(a, b);
//    swap2(&a, &b);
    int &aa = a;
    int &bb = b;
    swap3(aa, bb);

    //或者直接传，接受那里会变成引用
    swap3(a, b);

    cout << a << endl;
    cout << b << endl;

    return 0;
}
```



![](https://gitee.com/hxc8/images3/raw/master/img/202407172228060.jpg)



```javascript
#include "iostream"

using namespace std;

//1引用做函数的返回值
int &test1() {
    int a = 10;//局部变量存放在四区中的 栈区
    return a;
}

//2 函数的调用作为左值(等号的左边 )
int &test2() {
    static int a = 10;
    return a;
}

int main() {

    int &ref = test1();
    cout << "ref=" << ref << endl;//第一次打印结果正确，因为编译器做了一次保留
    cout << "ref=" << ref << endl;//第二次结果错误，a的内存已经释放


//    2

    int &ref2 = test2();
    cout << ref2 << endl;//结果为10
    cout << ref2 << endl;//结果为10

    //作为左值
    test2() = 1000;//如果函数的返回值是引用，这个函数调用可以作为左值

    cout << ref << endl;//结果为1000
    cout << ref << endl;//结果为1000

    return 0;
}
```



![](https://gitee.com/hxc8/images3/raw/master/img/202407172228552.jpg)

