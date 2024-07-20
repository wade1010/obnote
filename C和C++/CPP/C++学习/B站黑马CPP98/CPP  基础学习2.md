数据输入

```javascript
# include "iostream"

using namespace std;

int main() {
    int a = 1;
    cout << "请给赋值:" << endl;
    cin >> a;
    cout << "a=" << a << endl;
    return 0;
}
```





算数运算符

![](https://gitee.com/hxc8/images3/raw/master/img/202407172239537.jpg)



```javascript
# include "iostream"

using namespace std;

int main() {
    int a;
    cout << "请输入分数:" << endl;
    cin >> a;
    if (a == 1) {
        cout << "真" << endl;
    } else {
        cout << "假" << endl;
    }

    if (true) {

    } else if (false) {

    } else {
        
    }
    return 0;
}
```





```javascript
# include "iostream"

using namespace std;

int main() {
    int a = 10;
    int b = 20;
    int c;
    c = a > b ? a : b;
    cout << c << endl;

    switch (c) {
        case 10:
            cout << "hello" << endl;
            break;
        case 20:
            cout << "he he" << endl;
            break;
        default:
            cout << "default" << endl;
            break;
    }


    while (c-- > 1) {
        cout << c << endl;
    }


    do {
        cout << c << endl;
    } while (c++ > 2);

    for (int i; i < 10; ++i) {
        cout << i << endl;
    }


    return 0;
}
```

