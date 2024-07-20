

![](https://gitee.com/hxc8/images3/raw/master/img/202407172233942.jpg)



![](https://gitee.com/hxc8/images3/raw/master/img/202407172233065.jpg)



![](https://gitee.com/hxc8/images3/raw/master/img/202407172233282.jpg)



![](https://gitee.com/hxc8/images3/raw/master/img/202407172233760.jpg)



![](https://gitee.com/hxc8/images3/raw/master/img/202407172233614.jpg)



![](https://gitee.com/hxc8/images3/raw/master/img/202407172233003.jpg)



```javascript
#include "string"
#include "iostream"
#include "deque"

using namespace std;

/*
 *deque 构造函数
 *
 */
void printDeque(const deque<int> &d) {
    for (deque<int>::const_iterator i = d.begin(); i != d.end(); i++) {
        cout << *i << " ";
    }
    cout << endl;
}

void test() {
    deque<int> d;
    for (int i = 0; i < 10; i++) {
        d.push_back(i);
    }
    printDeque(d);

    deque<int> d2(d);
    printDeque(d2);

    deque<int> d3(d.begin(), d.end());
    printDeque(d3);

    deque<int> d4(10,100);
    printDeque(d4);
}

int main() {
    test();
    return 0;
}
```



![](https://gitee.com/hxc8/images3/raw/master/img/202407172233401.jpg)



---



![](https://gitee.com/hxc8/images3/raw/master/img/202407172234805.jpg)



```javascript
#include "string"
#include "iostream"
#include "deque"

using namespace std;

/*
 *deque容器赋值操作
 *
 */
void printDeque(const deque<int> &d) {
    for (deque<int>::const_iterator i = d.begin(); i != d.end(); i++) {
        cout << *i << " ";
    }
    cout << endl;
}

void test() {
    deque<int> d;
    for (int i = 0; i < 10; i++) {
        d.push_back(i);
    }
    printDeque(d);

//    等号赋值
    deque<int> d2;
    d2 = d;
    printDeque(d2);
    //assign 赋值
    deque<int> d3;
    d3.assign(d.begin(), d.end());
    printDeque(d3);

    deque<int> d4;
    d4.assign(10, 100);
    printDeque(d4);

}

int main() {
    test();
    return 0;
}
```





















---





![](https://gitee.com/hxc8/images3/raw/master/img/202407172234384.jpg)



![](https://gitee.com/hxc8/images3/raw/master/img/202407172234924.jpg)



![](https://gitee.com/hxc8/images3/raw/master/img/202407172234817.jpg)



---



![](https://gitee.com/hxc8/images3/raw/master/img/202407172234160.jpg)



![](https://gitee.com/hxc8/images3/raw/master/img/202407172234684.jpg)



---



![](https://gitee.com/hxc8/images3/raw/master/img/202407172234721.jpg)



---



![](https://gitee.com/hxc8/images3/raw/master/img/202407172234181.jpg)



```javascript
#include "string"
#include "iostream"
#include "deque"
#include "algorithm"

using namespace std;

/*
 *deque容器排序
 *
 */
void printDeque(const deque<int> &d) {
    for (deque<int>::const_iterator i = d.begin(); i != d.end(); i++) {
        cout << *i << " ";
    }
    cout << endl;
}

void test() {
    deque<int> d;
    d.push_back(10);
    d.push_back(20);
    d.push_back(30);
    d.push_front(100);
    d.push_front(200);
    d.push_front(300);
    printDeque(d);

    //排序  vector 容器也支持排序
    sort(d.begin(), d.end());
    printDeque(d);


}

int main() {
    test();
    return 0;
}
```



```javascript
300 200 100 10 20 30 
10 20 30 100 200 300
```



![](https://gitee.com/hxc8/images3/raw/master/img/202407172234496.jpg)



---































