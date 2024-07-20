

![](https://gitee.com/hxc8/images3/raw/master/img/202407172231189.jpg)



![](https://gitee.com/hxc8/images3/raw/master/img/202407172231783.jpg)



```javascript
#include <iostream>
#include <vector>
#include "algorithm"
#include "functional"

using namespace std;

/*
 * 内建函数对象 算数仿函数
 * negate  一元仿函数  取反仿函数
 *
 * plus 二元仿函数  加法
 */
void test() {
    negate<int> n;
    cout << n(10) << endl;
    cout << n(1) << endl;

    cout << endl;
    plus<int> p;
    cout << p(10, 20) << endl;
}

int main() {
    test();
    return 0;
}
```



---



![](https://gitee.com/hxc8/images3/raw/master/img/202407172231377.jpg)



```javascript
#include <iostream>
#include <vector>
#include "algorithm"
#include "functional"

using namespace std;

/*
 * 内建函数对象  关系仿函数
 */

void test() {
    vector<int> v;
    v.push_back(1);
    v.push_back(3);
    v.push_back(2);
    v.push_back(5);
    for (vector<int>::iterator i = v.begin(); i != v.end(); i++) {
        cout << *i << " ";
    }
    cout << endl;
    //降序
    sort(v.begin(),v.end(),greater<int>());
//    sort(v.begin(),v.end(),less<int>());
    for (vector<int>::iterator i = v.begin(); i != v.end(); i++) {
        cout << *i << " ";
    }
    cout << endl;
}

int main() {
    test();
    return 0;
}
```



![](https://gitee.com/hxc8/images3/raw/master/img/202407172231801.jpg)



---



![](https://gitee.com/hxc8/images3/raw/master/img/202407172231107.jpg)



![](images/C2EAA776D289499BA869E7A573A71131image.png)



![](https://gitee.com/hxc8/images3/raw/master/img/202407172231499.jpg)

