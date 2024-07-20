main.cpp

```javascript
#include "iostream"
#include "swap.h"

using namespace std;

//1、创建.h后缀名的头文件
//2、创建.cpp后缀名的源文件
//3、在头文件中写函数声明
//4、在源文件中写函数的定义
int main() {
    swap(2, 3);
    return 0;
}
```



swap.h

```javascript

#ifndef FIRST_DEMO_SWAP_H
#define FIRST_DEMO_SWAP_H

#endif //FIRST_DEMO_SWAP_H

#include "iostream"

using namespace std;

void swap(int a, int b);
```



swap.cpp

```javascript
#include "iostream"
#include "swap.h"

using namespace std;

//1、创建.h后缀名的头文件
//2、创建.cpp后缀名的源文件
//3、在头文件中写函数声明
//4、在源文件中写函数的定义
int main() {
    swap(2, 3);
    return 0;
}
```

