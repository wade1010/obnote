如果没有声明，函数需要在main函数之前



```javascript
#include "iostream"

using namespace std;

void test1() {
    cout << "test1" << endl;
}

int main() {
    test1();
    return 0;
}
```



如果你放到main后面就报错

```javascript
#include "iostream"

using namespace std;

int main() {
    test1();
    return 0;
}

void test1() {
    cout << "test1" << endl;
}
```





如果用函数声明



```javascript
#include "iostream"

using namespace std;
void test1();

int main() {
    test1();
    return 0;
}

void test1() {
    cout << "test1" << endl;
}

```





```javascript
#include "iostream"

using namespace std;

void test1();//函数声明可以多次
void test1();//函数声明可以多次，函数定义只能一次

int main() {
    test1();
    return 0;
}

void test1() {//函数定义只能一次
    cout << "test1" << endl;
}
```

