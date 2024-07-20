CPP二维数组用于函数的参数-行指针（数组指针）

int *p;//整型指针

int *p[3];/./一维整型指针数组，元素是3个整型指针(p[0],p[1],p[2])

int *p();  //函数p的返回值类型时整型的地址。

int  (*p)(int,int); //p是函数指针，函数的返回值是整型。

行指针（数组指针）

声明行指针的语法：  数据类型 (*行指针名)[行的大小]; //行的大小即数组长度。

int (*p1)[3];  //p1是行指针，用于指向数组长度为3的int型数组。

int (*p2)[5]  // p2是行指针，用于指向数组长度为5的int型数组。

double (*p3)[5]   //p3是行指针，用于指向数组长度为5的double型数组。

一维数组名被解释为数组第0个元素的地址。

对一维数组名取地址得到的是数组的地址，是行地址。 

```
int(*p3)[3] = &arr;
```

对二维数组名取地址得到的是数组的地址，是行地址

```
int(*p4)[2][3] = &arr;
```

对三维数组名取地址得到的是数组的地址，是行地址

```
int(*p5)[2][3][4] = &arr;
```

```
#include "iostream"
using namespace std;

void test()
{
    int a[10]{1, 2, 3, 4, 5, 6, 7, 8, 9, 10};
    cout << "数组a第0个元素的地址:" << a << endl;
    cout << "数组a的地址:" << &a << endl;

    cout << "数组a的第0个元素的地址+1:" << a + 1 << endl;
    cout << "数组a的地址+1:" << &a + 1 << endl;

    int *p1 = a;
    int *p2 = &a;
}
int main()
{
    test();
    return 0;
}
编译报错
main.cpp: In function 'void test()':
main.cpp:14:16: error: cannot convert 'int (*)[10]' to 'int*' in initialization
     int *p2 = &a;
                ^
                
其中int (*)[10]就是行指针

```

```
#include "iostream"
using namespace std;

void test()
{
    int a[10]{1, 2, 3, 4, 5, 6, 7, 8, 9, 10};
    cout << "数组a第0个元素的地址:" << a << endl;
    cout << "数组a的地址:" << &a << endl;

    cout << "数组a的第0个元素的地址+1:" << a + 1 << endl;
    cout << "数组a的地址+1:" << &a + 1 << endl;

    int *p1 = a;
    int(*p2)[10] = &a;

    int arr[2][3] = {{1, 2, 3}, {4, 5, 6}};
    int(*p3)[3] = arr;
}
int main()
{
    test();
    return 0;
}
```

int arr[2][3] = {{1,2,3},{4,5,6}};

arr是二维数组名，该数组有2个元素，每一个元素本身又是一个数组长度为3的整型数组。

arr被解释为数组长度为3的整型数组类型的行地址。

如果存放arr的值，要用数组长度为3的整型数组类型的行指针

int (*p)[3]=arr;

3)把二维数组传递给函数

如果要把arr传给函数，函数的声明如下：

void func(int (*p)[3],int len);

void func(int  p[][3],int len);

```
#include "iostream"

using namespace std;

//void func(int p[][3], int len);
//void func(int (*p)[3], int len);
void func(int (*p)[3], int len) {
    for (int i = 0; i < len; i++) {
        for (int j = 0; j < 3; j++) {
            cout << "p[" << i << "][" << j << "]=" << p[i][j] << " ";
        }
        cout << endl;
    }
}


void test() {
    int a[10] = {1, 2, 3, 4, 5, 6, 7, 8, 9, 10};//可以理解为10行，1列的数组
    cout << "数组a第0个元素的地址:" << (long long) a << endl;
    cout << "数组a的地址:" << (long long) &a << endl;//和第一行相等

    cout << "数组a的第0个元素的地址+1:" << (long long) (a + 1) << " 相差：" << ((long long) (a + 1)) - ((long long) a)
         << endl;//和第一行相等相差1个int的大小，4字节
    cout << "数组a的地址+1:" << (long long) (&a + 1) << " 相差：" << ((long long) (&a + 1)) - ((long long) a)
         << endl;//和第一行相等40个字节，也就是一个a的大小

    int *p1 = a;
    cout << "对一维数组名取地址得到的是数组的地址，是行地址" << endl;
    int(*p2)[10] = &a;


    cout << endl;
    int arr[2][3] = {
            {1, 2, 3},
            {4, 5, 6}
    };//2行3列
    cout << "数组arr第0个元素的地址:" << (long long) arr << endl;
    cout << "数组arr的地址:" << (long long) &arr << endl;//和第一行相等

    cout << "数组arr的第0个元素的地址+1:" << (long long) (arr + 1) << " 相差：" << ((long long) (arr + 1)) - ((long long) arr)
         << endl;//和第一行相等相差3个int的大小，12字节
    cout << "数组arr的地址+1:" << (long long) (&arr + 1) << " 相差：" << ((long long) (&arr + 1)) - ((long long) arr)
         << endl;//和第一行相等24个字节，也就是一个arr的大小

    int(*p3)[3] = arr;

    cout << endl;
    cout << "对二维数组名取地址得到的是数组的地址，是行地址" << endl;
    int(*p4)[2][3] = &arr;

    cout << "把二维数组传递给函数" << endl;
    func(arr, 2);
}

int main() {
    test();
    return 0;
}
//数组a第0个元素的地址:140732881631088
//数组a的地址:140732881631088
//数组a的第0个元素的地址+1:140732881631092 相差：4
//数组a的地址+1:140732881631128 相差：40
//对一维数组名取地址得到的是数组的地址，是行地址
//
//        数组arr第0个元素的地址:140732881631056
//数组arr的地址:140732881631056
//数组arr的第0个元素的地址+1:140732881631068 相差：12
//数组arr的地址+1:140732881631080 相差：24
//
//对二维数组名取地址得到的是数组的地址，是行地址
//        把二维数组传递给函数
//p[0][0]=1 p[0][1]=2 p[0][2]=3
//p[1][0]=4 p[1][1]=5 p[1][2]=6 
```

多维数组

int arr[4][2][3];

arr是三维数组，该数组有4个元素，每一个元素本身又是一个2行3列的二维数组。

arr被解释为2行3列的二维数组的二维地址。

如果存放arr的值，要用2行3列的二维数组类型的行指针。

int (*p)[2][3]=arr;

```
#include "iostream"

using namespace std;

void func(int (*p)[3][4], int len) {
    for (int i = 0; i < len; i++) {
        for (int j = 0; j < 3; j++) {
            for (int k = 0; k < 4; k++) {
                cout << p[i][j][k] << " ";
            }
            cout << endl;
        }
        cout << endl;
    }
}

//多维数组
void test() {
    int arr[2][3][4];
//初始化
    int ii = 0;
    memset(arr, 0, sizeof(arr));//c++11中需要引入<string>  c97不需要
    for (int i = 0; i < 4; i++) {
        for (int j = 0; j < 2; j++) {
            for (int k = 0; k < 3; k++) {
                arr[i][j][k] = ii++;
            }
        }
    }
    for (int i = 0; i < 4; i++) {
        for (int j = 0; j < 2; j++) {
            for (int k = 0; k < 3; k++) {
                cout << arr[i][j][k] << " ";
            }
            cout << endl;
        }
        cout << endl;
    }
    cout << "多维数组传递给函数" << endl;
    func(arr, 2);

    //对三维数组名取地址得到的是数组的地址，是行地址
    int (*aa)[2][3][4] = &arr;
    cout << ((long long) (aa + 1)) - (long long) aa << endl;//相差96字节
}

int main() {
    test();
    return 0;
}
0 1 2 
3 4 5 

6 7 8 
9 10 11 

12 13 14 
15 16 17 

18 19 20 
21 22 23 

多维数组传递给函数
0 1 2 0 
3 4 5 0 
0 0 0 0 

6 7 8 0 
9 10 11 0 
0 0 0 0 

96
```