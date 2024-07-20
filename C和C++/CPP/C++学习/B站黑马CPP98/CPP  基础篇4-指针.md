![](https://gitee.com/hxc8/images3/raw/master/img/202407172238631.jpg)

```
#include "iostream"

using namespace std;

int main(int argc, char const *argv[]) {
    //1定义一个指针
    int a = 10;
    //指针定义的语法：数据类型 * 指针变量名;
    int *p;
    //让指针记录变量a的地址
    p = &a;
    cout << "a的地址为：" << &a << endl;
    cout << "指针p为：" << p << endl;

    //2 使用指针
    //可以通过解引用的方式来找到指针指向的内存
    //指针前加一个*，代表解引用，找到指针指向的内存中的数据
    *p = 1000;
    cout << "a为：" << a << endl;
    cout << "*p为：" << *p << endl;
    return 0;
}
a的地址为：0x7ffee9c516f4
指针p为：0x7ffee9c516f4
a为：1000
*p为：1000

```

```
#include "iostream"

using namespace std;

int main() {
    int a = 10;
    cout << sizeof(&a) << endl;
    cout << sizeof(int *) << endl;
    cout << sizeof(double *) << endl;
    cout << sizeof(float *) << endl;
    return 0;
}
64位操作系统占8个字节
32位操作系统占4个字节
8
8
8
8

```

空指针

![](https://gitee.com/hxc8/images3/raw/master/img/202407172238886.jpg)

![](https://gitee.com/hxc8/images3/raw/master/img/202407172238934.jpg)

野指针

![](https://gitee.com/hxc8/images3/raw/master/img/202407172238282.jpg)

下面代码是可以编译通过的，但是运行报错

```
#include "iostream"

using namespace std;

int main() {
    //野指针
    int *p = (int *) 0x1100;
    cout << *p << endl;
    return 0;
}
```

总结：空指针 和野指针都不是我们申请的内存空间，因此轻易不要访问它

指针-const修饰指针

![](https://gitee.com/hxc8/images3/raw/master/img/202407172238688.jpg)

常量指针

指针常量

```
#include "iostream"

using namespace std;

int main() {
    //1 const 修饰指针  常量指针
    int a = 10;
    int b = 20;
    const int *p = &a;
    //指针指向的值不可以修改，指针的指向可以改
//    *p = 100; //编译报错 error: assignment of read-only location '* p'
    cout << *p << endl;//10
    p = &b;//正确
    cout << *p << endl;//20


    //2 const修饰常量   指针常量
    //指针的指向不可以改，指针指向的值可以改
    int *const p2 = &a;
    *p2 = 100;//正确
//    *p2 = &b;//错误 指针的指向不可以修改

    //3 const 修饰指针和常量
    const int *const p3 = &a;
//    *p3 = 100;//编译错误 main.cpp:25:9: error: assignment of read-only location '*(const int*)p3'
//    p3 = &b//编译错误 main.cpp:26:8: error: assignment of read-only variable 'p3'
    return 0;
}
```

技巧：看const右侧紧跟着的是指针还是常量，是指针就是常量指针，是常量就是指针常量。

const在*前面  常量指针   （常量的指针，常量在前，指针指向的值不能修改）

*在const前面  指针常量  （指针的常量，指针在前，指针的指向不能修改）

指针和数组

![](https://gitee.com/hxc8/images3/raw/master/img/202407172238703.jpg)

```
#include "iostream"

using namespace std;

int main() {
    //指针和数组
    int arr[10] = {1, 2, 3, 4, 5, 6, 7, 8, 9, 10};
    cout << "第一个元素" << arr[0] << endl;
    int *p = arr;
    cout << arr << endl;//arr就是数组的首地址
    cout << "利用指针访问第一个元素：" << *p << endl;
    cout << "p移动前的地址：" << p << " " << (long) p << endl;
    p++;//p是一个整型的指针，64位系统下占8个字节，p++之后，就会往后移8个字节
    cout << "p移动后的地址：" << p << " " << (long) p << endl;
    cout << "利用指针访问第二个元素：" << *p << endl;

    cout << "利用指针遍历数组" << endl;
    int *p2 = arr;
    for (int i = 0; i < 10; i++) {
//        cout << arr[i] << endl;//以前的访问方式
        cout << *p2 << endl;
        p2++;
    }
    return 0;
}

第一个元素1
0x7ffee902f6c0
利用指针访问第一个元素：1
p移动前的地址：0x7ffee902f6c0 140732807706304
p移动后的地址：0x7ffee902f6c4 140732807706308
利用指针访问第二个元素：2
利用指针遍历数组
1
2
3
4
5
6
7
8
9
10

```

![](https://gitee.com/hxc8/images3/raw/master/img/202407172238531.jpg)

```
#include "iostream"

using namespace std;

void swap1(int a, int b) {
    int temp = a;
    a = b;
    b = temp;
}

void swap2(int *a, int *b) {
    int temp = *a;
    *a = *b;
    *b = temp;
}

int main() {
    //1 指针和函数
    //1 值传递
    int a = 10;
    int b = 20;
    swap1(a, b);//实参不发生改变
    cout << a << endl;
    cout << b << endl;
    cout << "" << endl;
    //2 地址传递
    swap2(&a, &b);//实参发生改变
    cout << a << endl;
    cout << b << endl;
    return 0;
}
10
20

20
10

```

总结：如果不想修改实参，就用值传递，如果想修改实参，就用地址传递

![](https://gitee.com/hxc8/images3/raw/master/img/202407172238479.jpg)

```
#include "iostream"

using namespace std;

void bubbleSort(int *arr, int len) {
    int temp = 0;
    for (int i = 0; i < len - 1; i++) {
        for (int j = i + 1; j < len; j++) {
            if (arr[i] > arr[j]) {
                temp = arr[i];
                arr[i] = arr[j];
                arr[j] = temp;
            }
        }
    }
}

int main() {
    //封装一个函数，利用冒泡排序，实现对整型数组的升序排序
    int arr[10] = {4, 3, 6, 9, 1, 2, 10, 8, 7, 5};
    int len = sizeof(arr) / sizeof(arr[0]);
    cout << "排序前：" << endl;
    for (int i = 0; i < len; i++) {
        cout << arr[i] << " ";
    }
    cout << endl;
    bubbleSort(arr, len);
    cout << "排序后：" << endl;
    for (int i = 0; i < len; i++) {
        cout << arr[i] << " ";
    }
    cout << endl;
    return 0;
}
排序前：
4 3 6 9 1 2 10 8 7 5 
排序后：
1 2 3 4 5 6 7 8 9 10 
```