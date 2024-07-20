```javascript
#include "iostream"

using namespace std;

int *test() {
    //利用new关键字，可以将数据开辟到堆区
    //指针 本质也是局部变量，放在栈上，指针保存的数据时放到堆区
    int *p = new int(10);
    return p;
}

int main() {
    int *p = test();
    cout << *p << endl;
    cout << *p << endl;
    return 0;
}

/*
10
10
返回结果多次输出，结果一致
*/
```



![](https://gitee.com/hxc8/images3/raw/master/img/202407172228274.jpg)



![](https://gitee.com/hxc8/images3/raw/master/img/202407172228562.jpg)



![](https://gitee.com/hxc8/images3/raw/master/img/202407172228114.jpg)



![](https://gitee.com/hxc8/images3/raw/master/img/202407172228578.jpg)

 

![](https://gitee.com/hxc8/images3/raw/master/img/202407172228560.jpg)





![](https://gitee.com/hxc8/images3/raw/master/img/202407172228964.jpg)



```javascript
#include "iostream"

using namespace std;

int *test(int b) {//形参也会放在栈区
    int a = 10;//局部变量，内存放在栈区，栈区的数据在函数执行完成后自动释放
    return &a;//返回局部变量的地址
}

int main() {
    int *p = test(10);
    cout << *p << endl;//第一次可以正常打印结果，是因为编译器做了保留
    cout << *p << endl;//第二次这个数据就不再保留
    return 0;
}

/*
10
32767

 所以不要返回局部变量的地址
*/
```





![](https://gitee.com/hxc8/images3/raw/master/img/202407172228153.jpg)



![](https://gitee.com/hxc8/images3/raw/master/img/202407172228746.jpg)

```javascript
#include "iostream"

using namespace std;

int *test() {
    //利用new关键字，可以将数据开辟到堆区
    //指针 本质也是局部变量，放在栈上，指针保存的数据时放到堆区
    int *p = new int(10);
    return p;
}

int main() {
    int *p = test();
    cout << *p << endl;
    cout << *p << endl;
    return 0;
}

/*
10
10
返回结果多次输出，结果一致
*/
```





![](https://gitee.com/hxc8/images3/raw/master/img/202407172228208.jpg)

上图意思就是栈区指针保存了堆区的地址，值保存在堆区



```javascript
#include "iostream"

using namespace std;
//1 new的 基本语法

int *test() {
    //在堆区创建整型数据
    //new返回的是该数据类型的指针
    int *p = new int(10);
    return p;
}

//2 在堆区利用new开辟数组

int *test2() {
    int *arr = new int[10];
    for (int i = 0; i < 10; ++i) {
        arr[i] = i;
    }
    for (int j = 0; j < 10; ++j) {
        cout << arr[j] << endl;
    }
    //释放堆区数组,需要加[]
    delete[] arr;
}


int main() {
    int *p = test();
    cout << *p << endl;
    cout << *p << endl;
    cout << *p << endl;
    //堆区的数据由程序员自己管理开辟，程序员管理释放
    //如果想释放堆区的数据，利用关键字delete
    delete p;
    //cout << *p << endl;//内存已经释放，再次访问就是非法操作，会报错

    return 0;
}
```

