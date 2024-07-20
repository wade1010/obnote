

![](https://gitee.com/hxc8/images3/raw/master/img/202407172233325.jpg)



![](https://gitee.com/hxc8/images3/raw/master/img/202407172233833.jpg)



![](https://gitee.com/hxc8/images3/raw/master/img/202407172233482.jpg)



![](images/2C08DE4C063643DE844E472BE8F811ABimage.png)

























```javascript
using namespace std;

#include <iostream>
#include "queue"

/*
 * queue容器
 */
void test() {
    queue<int> s;
    s.push(1);
    s.push(2);
    s.push(3);
    s.push(4);
    s.push(5);
    while (!s.empty()) {
        cout << s.back() << " " << s.front() << endl;
        s.pop();
    }
    cout << "队列大小：" << s.size() << endl;
}

int main() {
    test();
    return 0;
}
```

















































 