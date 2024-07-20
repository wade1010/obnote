

![](https://gitee.com/hxc8/images3/raw/master/img/202407172228968.jpg)



![](https://gitee.com/hxc8/images3/raw/master/img/202407172228632.jpg)



![](https://gitee.com/hxc8/images3/raw/master/img/202407172228950.jpg)



---



![](images/F4F09BCE96FD48D2B54B37FCDCF59260image.png)



```javascript
#include <iostream>
#include <vector>
#include "algorithm"
#include "functional"

using namespace std;

/*
 * 常用遍历算法
 */
void print1(int v) {
    cout << v << " ";
}

class Print2 {
public:
    void operator()(int v) {
        cout << v << " ";
    }
};

void test() {
    vector<int> v;
    for (int i = 0; i < 10; i++) {
        v.push_back(i);
    }
    for_each(v.begin(), v.end(), print1);
    cout << endl;

    for_each(v.begin(), v.end(), Print2());
    cout << endl;

}

class MyTransform {
public:
    int operator()(int v) {
        return v;
    }

};

class MyTransform2 {
public:
    int operator()(int v) {
        return v * 2;
    }

};

//transform
void test2() {
    vector<int> v;
    for (int i = 0; i < 10; i++) {
        v.push_back(i);
    }
    vector<int> v2;
    v2.resize(v.size());//目标容器必须要提前开辟空间
    transform(v.begin(), v.end(), v2.begin(), MyTransform());
    for_each(v2.begin(), v2.end(), Print2());
    cout << endl;
    vector<int> v3;
    v3.resize(v.size());//目标容器必须要提前开辟空间
    transform(v.begin(), v.end(), v3.begin(), MyTransform2());
    for_each(v3.begin(), v3.end(), Print2());
}

int main() {
    test();
    cout << endl;
    test2();
    return 0;
}
```



```javascript
0 1 2 3 4 5 6 7 8 9 
0 1 2 3 4 5 6 7 8 9 

0 1 2 3 4 5 6 7 8 9 
0 2 4 6 8 10 12 14 16 18
```



![](https://gitee.com/hxc8/images3/raw/master/img/202407172229128.jpg)



---



![](https://gitee.com/hxc8/images3/raw/master/img/202407172229720.jpg)



![](https://gitee.com/hxc8/images3/raw/master/img/202407172229179.jpg)



![](https://gitee.com/hxc8/images3/raw/master/img/202407172229736.jpg)



![](https://gitee.com/hxc8/images3/raw/master/img/202407172229213.jpg)



![](https://gitee.com/hxc8/images3/raw/master/img/202407172229001.jpg)



![](https://gitee.com/hxc8/images3/raw/master/img/202407172229523.jpg)



---



![](https://gitee.com/hxc8/images3/raw/master/img/202407172229830.jpg)



![](https://gitee.com/hxc8/images3/raw/master/img/202407172229359.jpg)



![](https://gitee.com/hxc8/images3/raw/master/img/202407172229902.jpg)



![](https://gitee.com/hxc8/images3/raw/master/img/202407172229419.jpg)



![](https://gitee.com/hxc8/images3/raw/master/img/202407172229905.jpg)



---



![](https://gitee.com/hxc8/images3/raw/master/img/202407172229352.jpg)



![](https://gitee.com/hxc8/images3/raw/master/img/202407172229910.jpg)



![](https://gitee.com/hxc8/images3/raw/master/img/202407172229597.jpg)



---



![](https://gitee.com/hxc8/images3/raw/master/img/202407172229828.jpg)



![](https://gitee.com/hxc8/images3/raw/master/img/202407172229109.jpg)



![](https://gitee.com/hxc8/images3/raw/master/img/202407172229637.jpg)



---



![](https://gitee.com/hxc8/images3/raw/master/img/202407172229062.jpg)





![](https://gitee.com/hxc8/images3/raw/master/img/202407172229599.jpg)



![](https://gitee.com/hxc8/images3/raw/master/img/202407172229707.jpg)



![](https://gitee.com/hxc8/images3/raw/master/img/202407172229097.jpg)



![](https://gitee.com/hxc8/images3/raw/master/img/202407172229510.jpg)



---



![](https://gitee.com/hxc8/images3/raw/master/img/202407172229988.jpg)



---



![](https://gitee.com/hxc8/images3/raw/master/img/202407172229399.jpg)



![](images/EE4AF66A1EBE4E5CA84C63772B1A21DEimage.png)



---



![](https://gitee.com/hxc8/images3/raw/master/img/202407172229617.jpg)



---



![](https://gitee.com/hxc8/images3/raw/master/img/202407172229163.jpg)



![](https://gitee.com/hxc8/images3/raw/master/img/202407172229846.jpg)



![](images/556EE54D5F9E47EE8F5237EEDCB70499image.png)



```javascript
#include <iostream>
#include <vector>
#include "algorithm"
#include "functional"

using namespace std;

/*
 * 常用遍历算法
 */

class Print {
public:
    void operator()(int v) {
        cout << v << " ";
    }
};

void test() {
    vector<int> v;
    for (int i = 0; i < 10; i++) {
        v.push_back(i);
    }
    vector<int> v2;
    for (int i = 10; i > 0; i--) {
        v2.push_back(i);
    }
    vector<int> v3;
    v3.resize(v.size() + v2.size());
    merge(v.begin(), v.end(), v2.begin(), v2.end(), v3.begin());
    for_each(v3.begin(), v3.end(), Print());
}

int main() {
    test();
    return 0;
}
```



```javascript
0 1 2 3 4 5 6 7 8 9 10 9 8 7 6 5 4 3 2 1 
```



---



![](https://gitee.com/hxc8/images3/raw/master/img/202407172230175.jpg)



---



![](https://gitee.com/hxc8/images3/raw/master/img/202407172230703.jpg)



![](https://gitee.com/hxc8/images3/raw/master/img/202407172230165.jpg)



![](https://gitee.com/hxc8/images3/raw/master/img/202407172230621.jpg)



![](https://gitee.com/hxc8/images3/raw/master/img/202407172230851.jpg)



---



![](https://gitee.com/hxc8/images3/raw/master/img/202407172230236.jpg)



![](https://gitee.com/hxc8/images3/raw/master/img/202407172230838.jpg)



![](https://gitee.com/hxc8/images3/raw/master/img/202407172230987.jpg)



---



![](https://gitee.com/hxc8/images3/raw/master/img/202407172230121.jpg)



![](https://gitee.com/hxc8/images3/raw/master/img/202407172230741.jpg)



---



![](https://gitee.com/hxc8/images3/raw/master/img/202407172230190.jpg)



![](https://gitee.com/hxc8/images3/raw/master/img/202407172230935.jpg)



---



![](https://gitee.com/hxc8/images3/raw/master/img/202407172230372.jpg)



---



![](https://gitee.com/hxc8/images3/raw/master/img/202407172230825.jpg)



![](https://gitee.com/hxc8/images3/raw/master/img/202407172230293.jpg)



![](https://gitee.com/hxc8/images3/raw/master/img/202407172230735.jpg)

