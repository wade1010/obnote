

![](https://gitee.com/hxc8/images3/raw/master/img/202407172231203.jpg)





![](https://gitee.com/hxc8/images3/raw/master/img/202407172231762.jpg)



```javascript
#include <iostream>
#include <vector>
#include "algorithm"

using namespace std;

/*
 *仿函数  返回值类型是bool数据类型，称为谓词
 * 一元谓词
 */

class GreaterFive {
public:
    bool operator()(int val) {
        return val > 5;
    }

};

class MyCompare {
public:
    bool operator()(int a, int b) {
        return a > b;
    }
};

void test() {
    vector<int> v;
    v.push_back(1);
    v.push_back(2);
    v.push_back(3);
    v.push_back(4);
    v.push_back(10);
    v.push_back(101);
    //查找容器中有没有>5的数字
    //GreaterFive() 匿名函数对象
    vector<int>::iterator it = find_if(v.begin(), v.end(), GreaterFive());
    if (it == v.end()) {
        cout << "未找到" << endl;
    } else {
        for (vector<int>::iterator sit = it; sit != v.end(); sit++) {
            cout << "找到：" << *sit << endl;
        }
    }
}

void test2() {
    vector<int> v;
    v.push_back(1);
    v.push_back(2);
    v.push_back(3);
    v.push_back(4);
    v.push_back(10);
    v.push_back(101);
    sort(v.begin(), v.end());
    //默认是升序排序
    for (vector<int>::iterator it = v.begin(); it != v.end(); it++) {
        cout << *it << " ";
    }
    cout << endl;

    //使用函数对象，改变算法策略，变为降序排序
    sort(v.begin(), v.end(), MyCompare());
    for (vector<int>::iterator it = v.begin(); it != v.end(); it++) {
        cout << *it << " ";
    }
    cout << endl;
}

int main() {
    test();
    cout << endl;
    test2();
    return 0;
}
```



```javascript
找到：10
找到：101

1 2 3 4 10 101 
101 10 4 3 2 1 
```

