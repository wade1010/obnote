C++基础篇1

```javascript
# include "iostream"

# define day 7
using namespace std;

int main() {
    cout << "hello world" << endl;
    int a = 1;
    cout << "a=" << a << endl;

    const int constInt = 1;
    cout << constInt << endl;


    cout << day << endl;


    short a1 = 10;
    int a2 = 10;
    long a3 = 10;
    long long a4 = 10;

    cout << endl;
    cout << sizeof(a1) << endl;
    cout << sizeof(a2) << endl;
    cout << sizeof(a3) << endl;
    cout << sizeof(a4) << endl;

    float f1 = 3.1415925;

    cout << f1 << endl;//3.14159  默认情况下，输出一个小数，会显示出6位的有效数字 314159

    double d1 = 3.1415925;

    cout << d1 << endl; //和上面一样默认都是显示3.14159


    //科学计数法
    float f2 = 3e2;
    cout << "f2=" << f2 << endl;

    float f3 = 3e-2;//3*(1/(10e2))      a^-x=1/a^x
    cout << "f3=" << f3 << endl;


    char ch1 = 'a';
    cout << int(ch1) << endl;//打印对应的ascII码  a=97


    cout << "f\tfsadf" << endl;
    cout << "ff\tfsadf" << endl;
    cout << "ffadsfafffff\tfsadf" << endl;


    //C风格字符串型  沿用C风格
    char str1[] = "hello world";
    cout << str1 << endl;

    //C++字符串型
    string str2 = "hello world";
    cout << str2 << endl;

    return 0;
}
```

C++关键字

![](https://gitee.com/hxc8/images3/raw/master/img/202407172238669.jpg)

变量命名规则

![](https://gitee.com/hxc8/images3/raw/master/img/202407172238718.jpg)

![](https://gitee.com/hxc8/images3/raw/master/img/202407172238759.jpg)

![](https://gitee.com/hxc8/images3/raw/master/img/202407172238502.jpg)

![](https://gitee.com/hxc8/images3/raw/master/img/202407172238558.jpg)

![](https://gitee.com/hxc8/images3/raw/master/img/202407172238285.jpg)

![](https://gitee.com/hxc8/images3/raw/master/img/202407172238874.jpg)

![](https://gitee.com/hxc8/images3/raw/master/img/202407172238814.jpg)

![](https://gitee.com/hxc8/images3/raw/master/img/202407172238277.jpg)

```
#include <iostream>
int main(int argc, char const *argv[])
{
    // 1 字符型变量创建方式
    char a = 'a';
    std::cout << a << std::endl;
    // 2 字符型变量所占内存大小
    std::cout << sizeof(char) << std::endl;
    // 3 字符串变量常见错误
    // char c1 = "b"; 常见字符型变量时候，要用单引号
    // char c2 = ‘adfafg’; 创建字符型变量时候，单引号
    // 4 字符串变量对应ASCII编码
    std::cout << int(a) << std::endl;
    system("pause"); //windows下有用
    return 0;
}
```

![](https://gitee.com/hxc8/images3/raw/master/img/202407172238118.jpg)

![](https://gitee.com/hxc8/images3/raw/master/img/202407172238716.jpg)

```
#include <iostream>

#include <string>
using namespace std;

int main(int argc, char const *argv[])
{
    // 1 字符型变量创建方式
    char a = 'a';
    std::cout << a << std::endl;

    // 2 字符型变量所占内存大小
    std::cout << sizeof(char) << std::endl;

    // 3 字符串变量常见错误
    // char c1 = "b"; 常见字符型变量时候，要用单引号
    // char c2 = ‘adfafg’; 创建字符型变量时候，单引号

    // 4 字符串变量对应ASCII编码
    std::cout << int(a) << std::endl;

    // 1 C风格的字符串
    //注意事项 char 字符串名 []
    //注意事项2 等号后面用用双引号 包含起字符串
    char str[] = "hello world";
    // 2 C++风格字符串
    //注意事项 包含一个头文件  #include <string> 且string是在std命名空间下，using namespace std;
    string str2 = "hello world";
    std::cout << str2 << std::endl;

    system("pause"); // windows下有用

    return 0;
}

```

![](images/WEBRESOURCE61de84c70289148aa20402440dc7194a截图.png)

```
#include <iostream>

#include <string>
using namespace std;

int main(int argc, char const *argv[])
{
    int a = 0;
    cout << "请输入整型变量:" << endl;
    cin >> a;
    cout << a << endl;

    double d = 0;
    cout << "请输入浮点型变量：" << endl;
    cin >> d;
    cout << d << endl;

    float f = 0.f;
    cout << "请输入浮点型变量f：" << endl;
    cin >> f;
    cout << f << endl;

    //字符型
    char c = 'a';
    cout << "请输入字符" << endl;
    cin >> c;
    cout << "字符串变量ch= " << c << endl;

    //字符串
    string str = "hello";
    cout << "请给字符串 str 赋值：" << endl;
    cin >> str;
    cout << "字符串str=" << str << endl;

    // bool
    bool flag = false;
    cout << "请给布尔类型 flag 赋值" << endl;
    cin >> flag; //bool类型 只要是 非0 的值都代表真
    cout << "布尔类型flag=" << flag << endl;

    return 0;
}

```

![](https://gitee.com/hxc8/images3/raw/master/img/202407172239992.jpg)

![](https://gitee.com/hxc8/images3/raw/master/img/202407172239737.jpg)

 

![](https://gitee.com/hxc8/images3/raw/master/img/202407172239641.jpg)

![](https://gitee.com/hxc8/images3/raw/master/img/202407172239280.jpg)

```
#include <iostream>

#include <string>
using namespace std;

int main(int argc, char const *argv[])
{
    int score = 0;
    cout << "请输入高考考试分数：" << endl;
    cin >> score;
    cout << "输入的分数为：" << score << endl;
    if (score > 600)
    {
        cout << "恭喜您考入一本大学" << endl;
    }
    else if (score > 500)
    {
        cout << "恭喜您考入二本大学" << endl;
    }
    else if (score > 400)
    {
        cout << "恭喜您考入三本大学" << endl;
    }
    else
    {
        cout << "未考上大学" << endl;
    }
    system("pause");
    return 0;
}

```

```
#include <iostream>

#include <string>
using namespace std;

int main(int argc, char const *argv[])
{
    int n1 = 0;
    int n2 = 0;
    int n3 = 0;
    cout << "请输入第一只小猪的体重：" << endl;
    cin >> n1;
    cout << "请输入第二只小猪的体重：" << endl;
    cin >> n2;
    cout << "请输入第三只小猪的体重：" << endl;
    cin >> n3;
    cout << "第一只小猪的体重：" << n1 << "公斤" << endl;
    cout << "第二只小猪的体重：" << n2 << "公斤" << endl;
    cout << "第三只小猪的体重：" << n3 << "公斤" << endl;

    if (n1 > n2)
    {
        if (n1 > n3)
        {
            cout << "第一只小猪最重" << endl;
        }
        else
        {
            cout << "第三只小猪最重" << endl;
        }
    }
    else
    {
        if (n2 > n3)
        {
            cout << "第二只小猪最重" << endl;
        }
        else
        {
            cout << "第三只小猪最重" << endl;
        }
    }

    return 0;
}

```

三目运算符

```
#include <iostream>

#include <string>
using namespace std;

int main(int argc, char const *argv[])
{
    //三目运算符
    int a = 10;
    int b = 29;
    int c = 0;
    c = a > b ? a : b;
    cout << c << endl; // 29
    //在C++中三目运算符的变量，可以继续赋值
    a > b ? a : b = 100;
    cout << "a=" << a << endl; // a=10
    cout << "b=" << b << endl; // b=100

    a = 10;
    b = 29;
    a > b ? b : a = 100;
    cout << "a=" << a << endl; // a=100
    cout << "b=" << b << endl; // b=10
    return 0;
}

```

```
#include <iostream>

#include <string>
using namespace std;

int main(int argc, char const *argv[])
{
    switch (1)
    {
    case true:
        cout << "真" << endl;
        break;
    case false:
        cout << "假" << endl;
        break;
    default:
        cout << "default" << endl;
        break;
    }

    cout << "请给电影进行打分" << endl;
    int score = 0;
    cin >> score;
    cout << "您打分为:" << score << endl;
    switch (score)
    {
    case 10:
        cout << "您认为是经典电影" << endl;
        break;
    case 8:
        cout << "您认为电影还行" << endl;
        break;
    case 6:
        cout << "您认为电影较差" << endl;
        break;
    default:
        cout << "其他";
        break;
    }

    return 0;
}

```

![](https://gitee.com/hxc8/images3/raw/master/img/202407172239555.jpg)

```
#include "iostream"

using namespace std;

int main() {
    int num = 0;
    while (num < 10) {
        cout << num << endl;
        num++;
    }
    return 0;
}
```

```
#include "iostream"

using namespace std;

//猜数字
int main() {
    //系统生成随机数
    srand(time(NULL));//需要随机终止，要不然每次产生的数字都是一样的
    int num = rand() % 100 + 1;//生成0-99之间的随机数
//    cout << num << endl;
    //猜测
    while (true) {
        int guess = 0;
        cout << "请输入猜测的数字:" << endl;
        cin >> guess;
        if (guess > num) {
            cout << "您猜测的数字为:" << guess << ",大了" << endl;
        } else if (guess < num) {
            cout << "您猜测的数字为:" << guess << ",小了" << endl;
        } else {
            cout << "恭喜您猜对了!" << endl;
            break;
        }
    }
    //判断大小
    //对，退出
    //错 提示大小
    return 0;
}
```

![](https://gitee.com/hxc8/images3/raw/master/img/202407172239322.jpg)

```
#include "iostream"

using namespace std;

int main() {
    int num = 0;
    //在屏幕中输出0-9
    do {
        cout << num << endl;
        num++;
    } while (num < 10);
    return 0;
}
```

```
#include "iostream"
#include "math.h"
using namespace std;

int main() {
    //水仙花数
    int num = 100;
    int ret = 0;
    do {
        ret = pow(num / 100, 3) + pow(num / 10 % 10, 3) + pow(num % 10, 3);
        if (ret == num) {
            cout << "水仙花数：" << num << endl;
        }
        num++;
    } while (num < 1000);
    return 0;
}
```

![](https://gitee.com/hxc8/images3/raw/master/img/202407172239909.jpg)

```
#include "iostream"

using namespace std;

int main() {
//    for 循环 从数字0打印到9
    for (int i = 0; i < 10; i++) {
        cout << i << endl;
    }
    //等价于
    cout << "" << endl;
    int j = 0;
    for (;;) {
        if (j >= 10) {
            break;
        }
        cout << j << endl;
        j++;
    }
    return 0;
}
```

```
#include "iostream"

using namespace std;

bool isTapTable(int num) {
    return num % 7 == 0 || num % 10 == 7 || num / 10 == 7;
}

int main() {
    for (int i = 1; i <= 100; i++) {
        if (isTapTable(i)) {
            cout << "敲桌子:" << i << endl;
        }
    }

    return 0;
}
```

![](https://gitee.com/hxc8/images3/raw/master/img/202407172239490.jpg)

![](https://gitee.com/hxc8/images3/raw/master/img/202407172239081.jpg)

 

![](https://gitee.com/hxc8/images3/raw/master/img/202407172239731.jpg)

```
#include "iostream"

using namespace std;

int main() {
    int score[10];

    score[0] = 1;

    //数据类型 数组名[长度] = {值1,值2,.....}
    //如果在初始化数据的时候，没有完全填写完，会用0来填补剩余数据
    int score2[10] = {1, 2, 3, 4};
    cout << score2[0] << *score2 << endl;


    int score3[] = {1, 2, 3};
    for (int i = 0; i < 3; i++) {
        cout << score3[i] << endl;
    }

    return 0;
}
```

![](https://gitee.com/hxc8/images3/raw/master/img/202407172239259.jpg)

![](images/WEBRESOURCE5c9ae5e8cc4de12aa6aa9a88fdddc4ca截图.png)

```
#include "iostream"

using namespace std;

int main() {
    int arr[5] = {300, 350, 200, 400, 250};
    int max = 0;
    for (int i = 0; i < sizeof(arr) / sizeof(arr[0]); i++) {
        if (arr[i] > max) {
            max = arr[i];
        }
    }
    cout << "最大体重为:" << max << endl;
    return 0;
}
```

```
#include "iostream"

using namespace std;

int main() {
    //数组逆置
    int arr[5] = {1, 3, 2, 5, 4};
    int len = sizeof(arr) / sizeof(arr[0]);
    for (int k = 0; k < len; k++) {
        cout << arr[k] << endl;
    }
    for (int i = 0; i < len; i++) {
        for (int j = len - i - 1; j > 0;) {
            if (i < j) {
                int temp = arr[i];
                arr[i] = arr[j];
                arr[j] = temp;
            }
            break;
        }
    }
    cout << "" << endl;
    for (int k = 0; k < len; k++) {
        cout << arr[k] << endl;
    }
    return 0;
}
```

![](https://gitee.com/hxc8/images3/raw/master/img/202407172239270.jpg)

```
#include "iostream"

using namespace std;

int main() {
    //冒泡排序
    int arr[] = {4, 2, 8, 0, 5, 7, 1, 3, 9};
    int len = sizeof(arr) / sizeof(arr[0]);
    cout << "排序前:" << endl;
    for (int l = 0; l < len; l++) {
        cout << arr[l] << " ";
    }
    cout << endl;

    for (int i = 0; i < len - 1; i++) {
        for (int j = i + 1; j < len; j++) {
            if (arr[i] > arr[j]) {
                int temp = arr[i];
                arr[i] = arr[j];
                arr[j] = temp;
            }
        }
    }
    cout << "排序后:" << endl;
    for (int l = 0; l < len; l++) {
        cout << arr[l] << " ";
    }
    cout << endl;
    return 0;
}
排序前:
4 2 8 0 5 7 1 3 9 
排序后:
0 1 2 3 4 5 7 8 9 

```

![](https://gitee.com/hxc8/images3/raw/master/img/202407172239302.jpg)

```
#include "iostream"

using namespace std;

int main() {
    //类型 数组名[行数][列数];
    //类型 数组名[行数][列数]={{1,2},{,3,4}};
    int arr2[2][3] = {
            {1, 2, 3},
            {4, 5, 6},
    };
    for (int i = 0; i < 2; i++) {
        for (int j = 0; j < 3; j++) {
            cout << arr2[i][j] << " ";
        }
        cout << endl;
    }
    cout << endl;

    //类型 数组名[行数][列数]={1,2,3,4};  自动帮你分组
    int arr3[2][3] = {1, 2, 3, 4, 5, 6};
    for (int i = 0; i < 2; i++) {
        for (int j = 0; j < 3; j++) {
            cout << arr3[i][j] << " ";
        }
        cout << endl;
    }
    cout << endl;
    //类型 数组名[][列数]={1,2,3,4};
    /*
    为什么不能省略列数？
    因为在int arr[][3]={{1,2,3},{1,2,3},{1,2,3}};定义并赋值的过程中，如果省略了列数，就不能确定一行有多少个元素，也不能确定数组有多少行。

    哪些可以省略？
    在int arr[M][N] 中，其中M可以省略，省略后必须给出初始化表达式，编译器从初始化结果中推断数组有多少行
     */
    int arr4[][3] = {1, 2, 3, 4, 5, 6};
    for (int i = 0; i < 2; i++) {
        for (int j = 0; j < 3; j++) {
            cout << arr4[i][j] << " ";
        }
        cout << endl;
    }
    return 0;
}
```

![](https://gitee.com/hxc8/images3/raw/master/img/202407172239765.jpg)

```
#include "iostream"

using namespace std;

int main() {
    int arr[2][3] = {
            {1, 2, 3},
            {4, 5, 6},
    };
    //1 查看二维数组所占内存空间
    cout << "二维数组占用内存空间" << sizeof(arr) << endl;
    cout << "二维数组第一行占用内存空间" << sizeof(arr[0]) << endl;
    cout << "二维数组第一元素占用内存空间" << sizeof(arr[0][0]) << endl;
    cout << "二维数组行数为" << sizeof(arr) / sizeof(arr[0]) << endl;
    cout << "二维数组列数为" << sizeof(arr[0]) / sizeof(arr[0][0]) << endl;
    //2 查看二维数组的首地址
    cout << "二维数组首地址为：" << arr << endl;
    cout << "二维数组第一行地址为：" << arr[0] << endl;
    cout << "二维数组第一个元素首地址为：" << &arr[0][0] << endl;

    cout << "二维数组第一行地址long为：" << (long) arr[0] << endl;
    cout << "二维数组第二行地址long为：" << (long) arr[1] << endl;//相差12，也就是一行的大小


    return 0;
}
二维数组占用内存空间24
二维数组第一行占用内存空间12
二维数组第一元素占用内存空间4
二维数组行数为2
二维数组列数为3
二维数组首地址为：0x7ffee116c6e0
二维数组第一行地址为：0x7ffee116c6e0
二维数组第一个元素首地址为：0x7ffee116c6e0
二维数组第一行地址long为：140732674787040
二维数组第二行地址long为：140732674787052

```

![](https://gitee.com/hxc8/images3/raw/master/img/202407172239295.jpg)

```
#include "iostream"
#include "string"

using namespace std;

int main() {
    int scores[3][3] = {
            {100, 100, 100},
            {90,  50,  100},
            {60,  70,  80},
    };

    string names[3] = {"张三", "李四", "王五"};

    for (int i = 0; i < 3; i++) {
        int score = 0;
        for (int j = 0; j < 3; j++) {
            score += scores[i][j];
        }
        cout << names[i] << "总分为：" << score << endl;
    }

    return 0;
}
```

```
#include <iostream>

#include <string>
using namespace std;

int main(int argc, char const *argv[])
{
    switch (1)
    {
    case true:
        cout << "真" << endl;
        break;
    case false:
        cout << "假" << endl;
        break;
    default:
        cout << "default" << endl;
        break;
    }

    cout << "请给电影进行打分" << endl;
    int score = 0;
    cin >> score;
    cout << "您打分为:" << score << endl;
    switch (score)
    {
    case 10:
        cout << "您认为是经典电影" << endl;
        break;
    case 8:
        cout << "您认为电影还行" << endl;
        break;
    case 6:
        cout << "您认为电影较差" << endl;
        break;
    default:
        cout << "其他";
        break;
    }

    return 0;
}

```