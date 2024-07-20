

![](https://gitee.com/hxc8/images3/raw/master/img/202407172231289.jpg)



![](https://gitee.com/hxc8/images3/raw/master/img/202407172231041.jpg)



![](https://gitee.com/hxc8/images3/raw/master/img/202407172231841.jpg)



![](https://gitee.com/hxc8/images3/raw/master/img/202407172231356.jpg)



---



![](https://gitee.com/hxc8/images3/raw/master/img/202407172231484.jpg)



![](https://gitee.com/hxc8/images3/raw/master/img/202407172231579.jpg)



![](https://gitee.com/hxc8/images3/raw/master/img/202407172231042.jpg)



---



![](https://gitee.com/hxc8/images3/raw/master/img/202407172231294.jpg)



![](https://gitee.com/hxc8/images3/raw/master/img/202407172231649.jpg)



![](https://gitee.com/hxc8/images3/raw/master/img/202407172231718.jpg)



```javascript
#include <iostream>
#include <map>

using namespace std;

/*
 *map
 */
void printMap(map<int, int> &m) {
    for (map<int, int>::iterator it = m.begin(); it != m.end(); it++) {
        cout << it->first << " " << it->second << endl;
    }
}

void test() {
    map<int, int> m;
    m.insert(make_pair(1, 1));
    cout << m[1] << endl;
    cout << m[2] << endl;
    cout << endl;
    printMap(m);
}

int main() {
    test();
    return 0;
}
```



```javascript
1
0

1 1
2 0
```



---



![](https://gitee.com/hxc8/images3/raw/master/img/202407172231995.jpg)



---



![](https://gitee.com/hxc8/images3/raw/master/img/202407172231640.jpg)

