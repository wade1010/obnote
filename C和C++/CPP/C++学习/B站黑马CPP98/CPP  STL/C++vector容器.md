

![](https://gitee.com/hxc8/images3/raw/master/img/202407172234342.jpg)



![](https://gitee.com/hxc8/images3/raw/master/img/202407172234821.jpg)



![](https://gitee.com/hxc8/images3/raw/master/img/202407172234451.jpg)



```javascript
#include "string"
#include "iostream"
#include "vector"
#include "algorithm"

using namespace std;

/*
 *vector容器
 *
 */
void printVector(vector<int> &v) {
    for (vector<int>::iterator it = v.begin(); it != v.end(); it++) {
        cout << *it << " ";
    }
    cout << endl;
}

void test() {
    vector<int> v1;//默认构造
    for (int i = 0; i < 5; i++) {
        v1.push_back(i);
    }
    printVector(v1);

    //通过区间方式进行构造
    vector<int> v2(v1.begin(), v1.end());
    printVector(v2);

    //n个element方式构造   10个100
    vector<int> v3(10, 100);
    printVector(v3);


    //拷贝构造
    vector<int> v4(v3);
    printVector(v4);

}

int main() {
    test();
    return 0;
}
```



```javascript
0 1 2 3 4 
0 1 2 3 4 
100 100 100 100 100 100 100 100 100 100 
100 100 100 100 100 100 100 100 100 100 
```



![](https://gitee.com/hxc8/images3/raw/master/img/202407172234898.jpg)



---



![](https://gitee.com/hxc8/images3/raw/master/img/202407172234928.jpg)



```javascript
#include "string"
#include "iostream"
#include "vector"
#include "algorithm"

using namespace std;

/*
 *vector的赋值
 *
 */
void printVector(vector<int> &v) {
    for (vector<int>::iterator it = v.begin(); it != v.end(); it++) {
        cout << *it << " ";
    }
    cout << endl;
}

void test() {
    vector<int> v1;//默认构造
    for (int i = 0; i < 5; i++) {
        v1.push_back(i);
    }
    printVector(v1);
//赋值
    vector<int> v2;
    v2 = v1;
    printVector(v2);

    //assign
    vector<int> v3;
    v3.assign(v1.begin(), v1.end());
    printVector(v3);

    //n个elem 方式赋值
    vector<int> v4;
    v4.assign(10,100);
    printVector(v4);
}

int main() {
    test();
    return 0;
}
```



```javascript
0 1 2 3 4 
0 1 2 3 4 
0 1 2 3 4 
100 100 100 100 100 100 100 100 100 100 
```



![](https://gitee.com/hxc8/images3/raw/master/img/202407172234052.jpg)



---



![](https://gitee.com/hxc8/images3/raw/master/img/202407172234450.jpg)



![](https://gitee.com/hxc8/images3/raw/master/img/202407172234651.jpg)



```javascript
#include "string"
#include "iostream"
#include "vector"
#include "algorithm"

using namespace std;

/*
 *vector容器的容量和大小操作
 *
 */
void printVector(vector<int> &v) {
    for (vector<int>::iterator it = v.begin(); it != v.end(); it++) {
        cout << *it << " ";
    }
    cout << endl;
}

void test() {
    vector<int> v1;//默认构造
    for (int i = 0; i < 10; i++) {
        v1.push_back(i);
    }
    printVector(v1);
//判断是否为空
    if (v1.empty()) {
        cout << "为空" << endl;
    } else {
        cout << "不为空" << endl;
        cout << "容量：" << v1.capacity() << endl;
        cout << "大小：" << v1.size() << endl;
    }
    //重新指定大小
    v1.resize(15);
    printVector(v1);

    v1.resize(20,100);
    printVector(v1);

    v1.resize(5);//比原来短，超出部分会删除
    printVector(v1);
}

int main() {
    test();
    return 0;
}
```



```javascript
0 1 2 3 4 5 6 7 8 9 
不为空
容量：16
大小：10
0 1 2 3 4 5 6 7 8 9 0 0 0 0 0 
0 1 2 3 4 5 6 7 8 9 0 0 0 0 0 100 100 100 100 100 
0 1 2 3 4
```



---



![](https://gitee.com/hxc8/images3/raw/master/img/202407172234041.jpg)



![](https://gitee.com/hxc8/images3/raw/master/img/202407172234643.jpg)



```javascript
#include "string"
#include "iostream"
#include "vector"
#include "algorithm"

using namespace std;

/*
 *vector插入和删除
 *
 */
void printVector(vector<int> &v) {
    for (vector<int>::iterator it = v.begin(); it != v.end(); it++) {
        cout << *it << " ";
    }
    cout << endl;
}

void test() {
    vector<int> v1;//默认构造

//    尾插
    v1.push_back(10);
    v1.push_back(20);
    printVector(v1);
//    尾删
    v1.pop_back();
    printVector(v1);
//    插入
    v1.insert(v1.begin(), 100);
    printVector(v1);

    v1.insert(v1.begin(), 2, 1000);
    printVector(v1);

//    删除
    v1.erase(v1.begin());
    printVector(v1);
//清空
//    v1.erase(v1.begin(), v1.end());//相当于清空
//    printVector(v1);
    v1.clear();
    printVector(v1);

}

int main() {
    test();
    return 0;
}
```



```javascript
10 20 
10 
100 10 
1000 1000 100 10 
1000 100 10 
```



---



![](https://gitee.com/hxc8/images3/raw/master/img/202407172234629.jpg)



![](https://gitee.com/hxc8/images3/raw/master/img/202407172234673.jpg)



```javascript
#include "string"
#include "iostream"
#include "vector"
#include "algorithm"

using namespace std;

/*
 *vector数据存取
 *
 */
void printVector(vector<int> &v) {
    for (vector<int>::iterator it = v.begin(); it != v.end(); it++) {
        cout << *it << " ";
    }
    cout << endl;
}

void test() {
    vector<int> v1;//默认构造
//    尾插
    for (int i = 0; i < 2; i++) {
        v1.push_back(i);
    }
    cout << "" << endl;
    //利用[]访问数组中元素
    for (int j = 0; j < v1.size(); j++) {
        cout << v1[j] << endl;
    }
    cout << "" << endl;
    //利用at访问数组中元素
    for (int j = 0; j < v1.size(); j++) {
        cout << v1.at(j) << endl;
    }
    cout << endl;
//    获取第一个元素
    cout << v1.front() << endl;
    cout << endl;
    //获取最后一个元素
    cout << v1.back() << endl;
}

int main() {
    test();
    return 0;
}
```



```javascript

0
1

0
1

0
1
```



---



![](https://gitee.com/hxc8/images3/raw/master/img/202407172234115.jpg)



```javascript
#include "string"
#include "iostream"
#include "vector"
#include "algorithm"

using namespace std;

/*
 *vector互换
 *
 */
void printVector(vector<int> &v) {
    for (vector<int>::iterator it = v.begin(); it != v.end(); it++) {
        cout << *it << " ";
    }
    cout << endl;
}

//1 基本使用
void test() {
    vector<int> v1;
    for (int i = 0; i < 10; i++) {
        v1.push_back(i);
    }

    printVector(v1);

    vector<int> v2;
    for (int i = 10; i > 0; i--) {
        v2.push_back(i);
    }
    printVector(v2);

    cout << "交换后" << endl;
    v1.swap(v2);
    printVector(v1);
    printVector(v2);
}

//2 实际用途
void test2() {
    vector<int> v;
    for (int i = 0; i < 10000; i++) {
        v.push_back(i);
    }
    cout << "v的容量：" << v.capacity() << endl;
    cout << "v的大小：" << v.size() << endl;
    v.resize(3);
    cout << "v的容量：" << v.capacity() << endl;
    cout << "v的大小：" << v.size() << endl;
    //巧用swap收缩内存
    vector<int>(v).swap(v);
    cout << "v的容量：" << v.capacity() << endl;
    cout << "v的大小：" << v.size() << endl;
}

int main() {
    test();
    cout << endl;
    cout << endl;
    test2();
    return 0;
}
```



```javascript
0 1 2 3 4 5 6 7 8 9 
10 9 8 7 6 5 4 3 2 1 
交换后
10 9 8 7 6 5 4 3 2 1 
0 1 2 3 4 5 6 7 8 9 


v的容量：16384
v的大小：10000
v的容量：16384
v的大小：3
v的容量：3
v的大小：3
```



![](https://gitee.com/hxc8/images3/raw/master/img/202407172234499.jpg)



![](https://gitee.com/hxc8/images3/raw/master/img/202407172234520.jpg)



---



![](https://gitee.com/hxc8/images3/raw/master/img/202407172234445.jpg)





```javascript
#include "string"
#include "iostream"
#include "vector"
#include "algorithm"

using namespace std;

/*
 *vector预留空间
 *
 */
void printVector(vector<int> &v) {
    for (vector<int>::iterator it = v.begin(); it != v.end(); it++) {
        cout << *it << " ";
    }
    cout << endl;
}

void test() {
    vector<int> v;
    int num = 0;
    int *p = NULL;
    for (int i = 0; i < 100000; i++) {
        v.push_back(i);
        if (p != &v[0]) {
            p = &v[0];
            num++;
        }
    }
    cout << "不reserve 分配内存次数：" << num << endl;

}

void test2() {
    vector<int> v;
    v.reserve(100000);
    int num = 0;
    int *p = NULL;
    for (int i = 0; i < 100000; i++) {
        v.push_back(i);
        if (p != &v[0]) {
            p = &v[0];
            num++;//重新分配内存
        }
    }
    cout << "reserve后 分配内存次数：" << num << endl;

}

int main() {
    test();
    test2();
    return 0;
}
```



```javascript
不reserve 分配内存次数：18
reserve后 分配内存次数：1
```



![](https://gitee.com/hxc8/images3/raw/master/img/202407172234940.jpg)

