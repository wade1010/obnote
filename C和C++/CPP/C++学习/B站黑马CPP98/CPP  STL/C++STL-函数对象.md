

![](https://gitee.com/hxc8/images3/raw/master/img/202407172231321.jpg)



```javascript
#include <iostream>
#include <map>

using namespace std;

/*
 *函数对象（仿函数）
 */
class MyAdd {
public:
    int operator()(int a, int b) {
        return a + b;
    }
};

//1 函数对象在使用时，可以像普通函数那样调用，可以有参数，可以有返回值
void test() {
    MyAdd ma;
    int i = ma(1, 2);
    cout << i << endl;
}

//2 函数对象超出普通函数的概念，函数对象可以有自己的状态
class MyPrint {
public:
    MyPrint() {
        count = 0;
    }

    void operator()(string test) {
        cout << test << endl;
        count++;
    }

    int count;
};

void test2() {
    MyPrint mp;
    mp("hello world");
    mp("hello world");
    mp("hello world");
    mp("hello world");
    mp("hello world");
    cout << "myPrint调用次数" << mp.count << endl;
}

//3 函数对象可以作为参数传递
void doPrint(MyPrint &mp, string test) {
    mp(test);
}

void test3() {
    MyPrint mp;
    doPrint(mp, "hello");
}

int main() {
    test();
    test2();
    test3();
    return 0;
}
```



```javascript
3
hello world
hello world
hello world
hello world
hello world
myPrint调用次数5
hello
```

