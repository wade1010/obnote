

![](https://gitee.com/hxc8/images3/raw/master/img/202407172235334.jpg)





![](https://gitee.com/hxc8/images3/raw/master/img/202407172235973.jpg)





![](images/278533D4BA3E4F39BB7C271E00E1D7BEimage.png)



![](https://gitee.com/hxc8/images3/raw/master/img/202407172235211.jpg)



普通实现

```javascript
#include "iostream"
#include "string"
#include "fstream"

using namespace std;
//函数模板


//交换两个整型交换函数
void swapInt(int &a, int &b) {
    int temp = a;
    a = b;
    b = temp;
}

//交换两个浮点型函数
void swapDouble(double &a, double &b) {
    double temp = a;
    a = b;
    b = temp;
}

void test() {
    int a = 10;
    int b = 20;
    swapInt(a, b);
    cout << "a=" << a << endl;
    cout << "b=" << b << endl;

    double c = 10.2;
    double d = 20;
    swapDouble(c, d);
    cout << "c=" << c << endl;
    cout << "d=" << d << endl;
}

int main() {
    test();
    return 0;
}
```





模板实现

```javascript
#include "iostream"
#include "string"
#include "fstream"

using namespace std;
//函数模板


//交换两个整型交换函数
void swapInt(int &a, int &b) {
    int temp = a;
    a = b;
    b = temp;
}

//交换两个浮点型函数
void swapDouble(double &a, double &b) {
    double temp = a;
    a = b;
    b = temp;
}

//函数模板
template<typename T>
//声明一个模板，告诉编译器后面代码中紧跟着的T不要报错，T是一个通用数据类型

void mySwap(T &a, T &b) {
    T temp = a;
    a = b;
    b = temp;
}


void test() {
    int a = 10;
    int b = 20;
    swapInt(a, b);
    cout << "a=" << a << endl;
    cout << "b=" << b << endl;

    double c = 10.2;
    double d = 20;
    swapDouble(c, d);
    cout << "c=" << c << endl;
    cout << "d=" << d << endl;
}

void test2() {
    //使用函数模板有两种方式
    int a = 10;
    int b = 20;
    //1 自动类型推到
    mySwap(a, b);
    cout << "a=" << a << endl;
    cout << "b=" << b << endl;

    //2 显示指定类型
    double c = 10.2;
    double d = 20.3;
    mySwap<double>(c, d);
    cout << "c=" << c << endl;
    cout << "d=" << d << endl;

}

int main() {
//    test();
    test2();
    return 0;
}
```



![](https://gitee.com/hxc8/images3/raw/master/img/202407172235765.jpg)





函数模板注意事项如下



```javascript
#include "iostream"
#include "string"
#include "fstream"

using namespace std;
//函数模板注意是想
/*
 * 1 自动类型推到  必须推导出一致的数据类型T才可以使用
 *
 * 2 模板必须要确定出T的数据类型，才可以使用
 *
 */

//typename 可以替换成class
template<class T>

void mySwap(T &a, T &b) {
    T temp = a;
    a = b;
    b = temp;
}

//1 自动类型推到  必须推导出一致的数据类型T才可以使用
void test() {
    int a = 10;
    int b = 20;
    mySwap(a, b);//正确

    char c = 'a';
//    mySwap(a, c);//错误；必须推导出一致的数据类型T才可以使用
}

//template<typename T>//如果是注释的，test2是可以调用func();的
template<typename T>//如果是没注释的，test2是不可以调用func();的，因为模板必须没有确定出T的数据类型，需要显示指定类型调用  func<int>();
void func() {
    cout << "func 调用" << endl;
}

void test2() {
//    func();
    func<int>();
}
int main() {
//    test();
    test2();
    return 0;
}
```





函数模板案例-数组排序



```javascript
#include "iostream"
#include "string"
#include "fstream"

using namespace std;

/*
 *实现通用 对数组进行排序的函数
 * 规则 从大到小
 * 算法 选择排序
 * 测试 char 数组、int 数组
 */
//交换函数模板
template<typename T>
void mySwap(T &a, T &b) {
    int temp = a;
    a = b;
    b = temp;
}

//排序算法
template<typename T>
void mySort(T arr[], int len) {
    for (int i = 0; i < len; i++) {
        int max = i;
        for (int j = i + 1; j < len; j++) {
            if (arr[max] < arr[j]) {
                max = j;

            }
        }
        if (max != i) {
            mySwap(arr[max], arr[i]);
        }
    }
}

//提供打印数组的模板
template<typename T>
void printArray(T arr[], int len) {
    for (int i = 0; i < len; i++) {
        cout << arr[i] << " ";
    }
    cout << endl;
}

void test() {
    //测试char数组
    char charArr[] = "badcfe";
    int num = sizeof(charArr) / sizeof(char);
    mySort(charArr, num);
    printArray(charArr, num);
}

void test2() {
    int arr[] = {32, 321, 3, 4324, 2, 543, 5, 46, 1};
    int num = sizeof(arr) / sizeof(int);
    mySort(arr,num);
    printArray(arr, num);
}

int main() {
    test();
    test2();
    return 0;
}
```





![](https://gitee.com/hxc8/images3/raw/master/img/202407172235995.jpg)



```javascript
#include "iostream"
#include "string"
#include "fstream"

using namespace std;

/*
 *学习普通函数与函数模板的区别
 * 1 普通函数调用可以发生隐式类型转换
 * 2 函数模板 用自动类型推导，不可以发生隐式类型转换
 * 3 函数模板 用显示指定类型，可以发生隐式类型转换
 */

//普通函数

int myAdd(int a, int b) {
    return a + b;
}

//函数模板
template<typename T>
T myAdd2(T a, T b) {
    return a + b;
}

void test() {
    int a = 10;
    int b = 20;
    cout << myAdd(a, b) << endl;
    char c = 'c';
    cout << myAdd(a, c) << endl;//发生隐式转换
    double d = 10.2;
    cout << myAdd(a, d) << endl;//发生隐式转换

    //自动类型推导
//    myAdd2(a, c);//报错，自动推导不会发生隐式类型转换
    myAdd2<int>(a, c);//不报错，显示指定类型会发生隐式类型转换

}

int main() {
    test();
    return 0;
}
```



![](https://gitee.com/hxc8/images3/raw/master/img/202407172235457.jpg)







![](https://gitee.com/hxc8/images3/raw/master/img/202407172235460.jpg)





调用普通函数

```javascript
#include "iostream"
#include "string"
#include "fstream"

using namespace std;

/*
 *普通函数与函数模板掉好用规则
 * 1  如果函数模板和普通函数可以调用，优先调用普通函数
 * 2 可以通过空模板参数列表，强制调用 函数模板
 * 3 函数模板可以发生函数重载
 * 4 如果函数模板可以产生更好的匹配 优先调用函数模板
 */

void myPrint(int a, int b) {
    cout << "调用普通函数" << endl;
}

template<class T>
void myPrint(T a, T b) {
    cout << "调用函数模板" << endl;
}

void test() {
    int a = 10;
    int b = 20;
    myPrint(a, b);
}

int main() {
    test();
    return 0;
}
```





报错 ，普通函数只有声明没有实现

```javascript
#include "iostream"
#include "string"
#include "fstream"

using namespace std;

/*
 *普通函数与函数模板掉好用规则
 * 1  如果函数模板和普通函数可以调用，优先调用普通函数
 * 2 可以通过空模板参数列表，强制调用 函数模板
 * 3 函数模板可以发生函数重载
 * 4 如果函数模板可以产生更好的匹配 优先调用函数模板
 */

void myPrint(int a, int b);

template<class T>
void myPrint(T a, T b) {
    cout << "调用函数模板" << endl;
}

void test() {
    int a = 10;
    int b = 20;
    myPrint(a, b);
}

int main() {
    test();
    return 0;
}
```



```javascript
Undefined symbols for architecture x86_64:
  "myPrint(int, int)", referenced from:
      test() in main.cpp.o
ld: symbol(s) not found for architecture x86_64
clang: error: linker command failed with exit code 1 (use -v to see invocation)
make[3]: *** [first_demo] Error 1
make[2]: *** [CMakeFiles/first_demo.dir/all] Error 2
make[1]: *** [CMakeFiles/first_demo.dir/rule] Error 2
make: *** [first_demo] Error 2
```





通过空模板参数列表，强制调用函数模板

```javascript
#include "iostream"
#include "string"
#include "fstream"

using namespace std;

/*
 *普通函数与函数模板掉好用规则
 * 1  如果函数模板和普通函数可以调用，优先调用普通函数
 * 2 可以通过空模板参数列表，强制调用 函数模板
 * 3 函数模板可以发生函数重载
 * 4 如果函数模板可以产生更好的匹配 优先调用函数模板
 */

void myPrint(int a, int b);

template<class T>
void myPrint(T a, T b) {
    cout << "调用函数模板" << endl;
}

void test() {
    int a = 10;
    int b = 20;
//    myPrint(a, b);

    //通过空模板参数列表，强制调用函数模板
    myPrint<>(a, b);
}

int main() {
    test();
    return 0;
}
```





函数模板可以发生函数重载

```javascript
#include "iostream"
#include "string"
#include "fstream"

using namespace std;

/*
 *普通函数与函数模板掉好用规则
 * 1  如果函数模板和普通函数可以调用，优先调用普通函数
 * 2 可以通过空模板参数列表，强制调用 函数模板
 * 3 函数模板可以发生函数重载
 * 4 如果函数模板可以产生更好的匹配 优先调用函数模板
 */

void myPrint(int a, int b);

template<class T>
void myPrint(T a, T b) {
    cout << "2个参数 调用函数模板" << endl;
}

template<class T>
void myPrint(T a, T b, T c) {
    cout << "3个参数 调用函数模板" << endl;
}

void test() {
    int a = 10;
    int b = 20;
//    myPrint(a, b);

    //通过空模板参数列表，强制调用函数模板
    myPrint<>(a, b);
    myPrint<>(a, b, 30);
}

int main() {
    test();
    return 0;
}
```





如果函数模板可以产生更好的匹配 优先调用函数模板





```javascript
#include "iostream"
#include "string"
#include "fstream"

using namespace std;

/*
 *普通函数与函数模板掉好用规则
 * 1  如果函数模板和普通函数可以调用，优先调用普通函数
 * 2 可以通过空模板参数列表，强制调用 函数模板
 * 3 函数模板可以发生函数重载
 * 4 如果函数模板可以产生更好的匹配 优先调用函数模板
 */

void myPrint(int a, int b) {
    cout << "调用普通函数" << endl;
}

template<class T>
void myPrint(T a, T b) {
    cout << "2个参数 调用函数模板" << endl;
}

template<class T>
void myPrint(T a, T b, T c) {
    cout << "3个参数 调用函数模板" << endl;
}

void test() {
    int a = 10;
    int b = 20;
//    myPrint(a, b);

    //通过空模板参数列表，强制调用函数模板
//    myPrint<>(a, b);
//    myPrint<>(a, b, 30);

    //如果函数模板可以产生更好的匹配 优先调用函数模板,这里编译器觉得发生隐式转换还不如自动类型推导   
    char c1 = 'a';
    char c2 = 'b';
    myPrint(c1, c2);
}

int main() {
    test();
    return 0;
}
```



![](https://gitee.com/hxc8/images3/raw/master/img/202407172235915.jpg)











模板的局限性



1、正常实现，没问题

```javascript
#include "iostream"
#include "string"
#include "fstream"

using namespace std;

/*
 * 模板的局限性
 *  模板并不是万能的，有些特定数据类型，徐亚用具体化方式做特殊实现
 *
 */

template<typename T>
bool myCompare(T &a, T &b) {
    return a == b;
}

void test() {
    int i = 0;
    int j = 1;
    bool b = myCompare(i, j);
    if (b) {
        cout << "相等" << endl;
    } else {
        cout << "不相等" << endl;
    }
}

int main() {
    test();
    return 0;
}
```



2、 自定义实现，有问题



```javascript
#include "iostream"
#include "string"
#include "fstream"

using namespace std;

/*
 * 模板的局限性
 *  模板并不是万能的，有些特定数据类型，徐亚用具体化方式做特殊实现
 *
 */

class Person {
public:
    Person(int a, string n) {
        age = a;
        name = n;
    }

    int age;
    string name;


};

template<typename T>
bool myCompare(T &a, T &b) {
    return a == b;
}

void test() {
    int i = 0;
    int j = 1;
    bool b = myCompare(i, j);
    if (b) {
        cout << "相等" << endl;
    } else {
        cout << "不相等" << endl;
    }
}

void test2() {
    Person p1(1, "Tom");
    Person p2(1, "Tom");
    bool b = myCompare(p1, p2);
    if (b) {
        cout << "相等" << endl;
    } else {
        cout << "不相等" << endl;
    }
}

int main() {
//    test();
    test2();
    return 0;
}
```



报错了

```javascript
bool myCompare(T &a, T &b) {
    return a == b;//Invalid operands to binary expression ('Person' and 'Person')
}
```







解决办法

1  重载==运算符，不过太麻烦了，要是有>或者<，都需要重载



2 利用具体化的Person的版本实现代码，具体优化优先调用



```javascript
#include "iostream"
#include "string"
#include "fstream"

using namespace std;

/*
 * 模板的局限性
 *  模板并不是万能的，有些特定数据类型，徐亚用具体化方式做特殊实现
 *
 */

class Person {
public:
    Person(int a, string n) {
        age = a;
        name = n;
    }

    int age;
    string name;


};

template<typename T>
bool myCompare(T &a, T &b) {
    return a == b;
}

//利用具体化的Person的版本实现代码，具体优化优先调用  (格式化后就换行了)
template<>
bool myCompare(Person &p1, Person &p2) {
    if (p1.age == p2.age && p1.name == p2.name) {
        return true;
    }
    return false;
}

void test() {
    int i = 0;
    int j = 1;
    bool b = myCompare(i, j);
    if (b) {
        cout << "相等" << endl;
    } else {
        cout << "不相等" << endl;
    }
}

void test2() {
    Person p1(1, "Tom");
    Person p2(1, "Tom");
    bool b = myCompare(p1, p2);
    if (b) {
        cout << "相等" << endl;
    } else {
        cout << "不相等" << endl;
    }
}

int main() {
//    test();
    test2();
    return 0;
}
```





![](images/AF4219F3A8634E8FBFAB1E5CF1628300image.png)

