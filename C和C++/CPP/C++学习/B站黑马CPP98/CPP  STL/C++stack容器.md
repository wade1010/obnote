

![](https://gitee.com/hxc8/images3/raw/master/img/202407172233007.jpg)



![](https://gitee.com/hxc8/images3/raw/master/img/202407172233609.jpg)



![](https://gitee.com/hxc8/images3/raw/master/img/202407172233675.jpg)









```javascript
using namespace std;

#include <iostream>
#include "stack"

/*
 * stack容器
 */
void test() {
    stack<int> s;
    s.push(1);
    s.push(2);
    s.push(3);
    s.push(4);
    s.push(5);
    cout << s.size() << endl;
    cout << endl;
    while (!s.empty()) {
        cout << s.top() << endl;
        s.pop();
    }
    cout << endl;
    cout << s.size() << endl;
}

int main() {
    test();
    return 0;
}
```



```javascript
5

5
4
3
2
1

0
```





















































