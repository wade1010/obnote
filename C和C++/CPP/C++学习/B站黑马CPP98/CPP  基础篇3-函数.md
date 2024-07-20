CPP  基础篇3-函数

![](https://gitee.com/hxc8/images3/raw/master/img/202407172238950.jpg)

![](https://gitee.com/hxc8/images3/raw/master/img/202407172238668.jpg)

```
#include "iostream"

using namespace std;

//函数的定语
//语法:
//返回值类型 函数名(参数列表){函数体语句 return表达式}

//加法函数,实现两个整型的相加,并返回相加结果

int add(int num1, int num2)
{
    return num1 + num2;
}

int main()
{
    //实现一个加法函数,功能是,传入两个整型数据,计算数据相加的结果,并返回
    // 1 返回值类型 int
    // 2 函数名    add
    // 3 参数列表   (int num1,int num2)
    // 4 函数体语句   int sum = num1+num2;
    // 5 return 表达式  return sum;

    int sum = add(1, 2);
    cout << sum << endl;
    return 0;
}
```

 

![](https://gitee.com/hxc8/images3/raw/master/img/202407172238909.jpg)

```
#include "iostream"

using namespace std;

//值传递
//定义函数,实现两个数字进行交换函数

//如果函数不需要返回值,声明的时候可以写void

void swap(int num1, int num2)
{
    cout << "交换前:" << endl;
    cout << "num1 = " << num1 << endl;
    cout << "num2 = " << num2 << endl;

    int temp = num1;
    num1 = num2;
    num2 = temp;

    cout << "交换后:" << endl;
    cout << "num1 = " << num1 << endl;
    cout << "num2 = " << num2 << endl;
    // return;返回值void的时候可以不写return
}
int main(int argc, char const *argv[])
{
    int a = 10;
    int b = 20;
    cout << "交换前:" << endl;
    cout << "a = " << a << endl;
    cout << "b = " << b << endl;
    swap(a, b);
    cout << "交换后:" << endl;
    cout << "a = " << a << endl;
    cout << "b = " << b << endl;
    return 0;
}
交换前:
a = 10
b = 20
交换前:
num1 = 10
num2 = 20
交换后:
num1 = 20
num2 = 10
交换后:
a = 10
b = 20
```

![](https://gitee.com/hxc8/images3/raw/master/img/202407172238136.jpg)

```
#include "iostream"

using namespace std;

//函数的声明
//比较函数,实现两个整型数字进行标胶,返回较大的值

int main(int argc, char const *argv[])
{
    int a = 10;
    int b = 20;
    cout << get_max(a, b) << endl;
    return 0;
}
//定义  放在后面  报错   代码是一行一行往下执行的，执行到12行的时候，就会找不到get_max
int get_max(int a, int b)
{
    return a > b ? a : b;
}


报错内容：
main.cpp: In function 'int main(int, const char**)':
main.cpp:12:13: error: 'get_max' was not declared in this scope
     cout << get_max(a, b) << endl;
             ^~~~~~~
main.cpp:12:13: note: suggested alternative: 'get_s'
     cout << get_max(a, b) << endl;
             ^~~~~~~
             get_s
```

增加声明后就没问题了

```
#include "iostream"

using namespace std;

//函数的声明
//比较函数,实现两个整型数字进行标胶,返回较大的值

//提前告诉编译器函数的存在,可以利用函数的声明
int get_max(int a, int b);
//声明可以写多次,但是定义只能有一次
int get_max(int a, int b);
int get_max(int a, int b);

int main(int argc, char const *argv[])
{
    int a = 10;
    int b = 20;
    cout << get_max(a, b) << endl;
    return 0;
}

//定义
int get_max(int a, int b)
{
    return a > b ? a : b;
}
```

![](https://gitee.com/hxc8/images3/raw/master/img/202407172238466.jpg)