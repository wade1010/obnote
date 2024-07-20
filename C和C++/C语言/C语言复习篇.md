A->B->C->C++

![](images/84170634DBB34A3E8EE208D779DDC2CAimage.png)



大胡子就是牛



C语言自增(++)和自减(--)

```javascript
#include "stdio.h"
int main() {
    int a = 12, b = 1;
    int c = a - (b--);
    printf("b=%d\n",b);
    int d = (++a) - (--b);
    printf("a=%d, b=%d, c=%d, d=%d\n",a,b, c, d);
    return 0;
}


b=0
a=13, b=-1, c=11, d=14
```





数据类型自动转换



![](https://gitee.com/hxc8/images3/raw/master/img/202407172242411.jpg)



```javascript
#include "stdio.h"

int max(int num1, int num2) {
    int result;
    if (num1 > num2) {
        result = num1;
    } else {
        result = num2;
    }
    return result;
}

int main() {
    for (int i = 0; i < 10; ++i) {
        printf("id=%d\n", i);
    }
    int max1 = max(11, 12);
    printf("%d", max1);
}
```





传值方式调用函数

```javascript
#include "stdio.h"

void swap(int num1, int num2) {
    int temp;
    temp = num1;
    num1 = num2;
    num2 = temp;
    return;
}

int main() {
    int a = 100;
    int b = 200;
    printf("交换前，a 的值： %d\n", a);
    printf("交换前，b 的值： %d\n", b);
    swap(a, b);
    printf("交换后，a 的值： %d\n", a);
    printf("交换后，b 的值： %d\n", b);
    return 0;
}
```



引用方式调用函数

```javascript
#include "stdio.h"

void swap(int *num1, int *num2) {
    int temp;
    temp = *num1;
    *num1 = *num2;
    *num2 = temp;
    return;
}

int main() {
    int a = 100;
    int b = 200;
    printf("交换前，a 的值： %d\n", a);
    printf("交换前，b 的值： %d\n", b);
    swap(&a, &b);
    printf("交换后，a 的值： %d\n", a);
    printf("交换后，b 的值： %d\n", b);
    return 0;
}
```



C语言strcat

```javascript
#include <stdio.h>  
#include <string.h>  
  
int main(void)  {
    char str1[6] = "hello";  
    char str2[6] = "world";  
    strcat(str1,str2);
 
     printf("str1 = %s\n",str1);  
    printf("str2 = %s\n",str2);
 
    int len = strlen(str1);
    printf("len的长度:%d\n",len);
    
    return 0;  
}
报错
[1]    45162 illegal hardware instruction  ./a.out


将 str1[6]改成 str1[11]即可：解释在下方

int main(void)  {
    char str1[11] = "hello";
    char str2[6] = "world";
    strcat(str1,str2);

    printf("str1 = %s\n",str1);
    printf("str2 = %s\n",str2);

    int len = strlen(str1);
    printf("len的长度:%d\n",len);

    return 0;
}
输出:
str1 = helloworld
str2 = world
len的长度:10


因为str1这个数组的长度本来就是6 [hello+\0], strcat函数的追加，最后str1的长度变成11 [hello+world+\0],所以使用strcat()函数净量给予足够大的内存空间


#include <stdio.h>
#include <string.h>

int main(void)
{
    char *dest = NULL;
    char *src = "World";

    strcat(dest, src);
    printf("dest=[%s]", dest);

    return 0;
}

输出
[1]    45475 segmentation fault  ./a.out
strcat函数在将src的内容拷贝到dest中是没问题的，但是dest没有足够的空间来存储src中的内容

int main(void) {
    char *dest = NULL;
    dest = (char *) malloc(1024);
    char *src = "World";

    strcat(dest, src);
    printf("dest:[%s]", dest);

    return 0;
}
输出:
dest=[World]


#include <stdio.h>
#include <string.h>

int main(void)
{
    char dest[6] = "Hello";
    char *src = "World";

    strcat(dest, src);
    printf("dest=[%s]\n", dest);

    return 0;
}

输出:
    [1]    45815 illegal hardware instruction  ./a.out


6改成11

#include <stdio.h>
#include <string.h>

int main(void)
{
    char dest[6] = "Hello";
    char *src = "World";

    strcat(dest, src);
    printf("dest=[%s]\n", dest);

    return 0;
}

输出内容
dest=[HelloWorld]




```





![](https://gitee.com/hxc8/images3/raw/master/img/202407172242807.jpg)





C 中的 NULL 指针

在变量声明的时候，如果没有确切的地址可以赋值，为指针变量赋一个 NULL 值是一个良好的编程习惯。赋为 NULL 值的指针被称为空指针。



NULL 指针是一个定义在标准库中的值为零的常量。请看下面的程序：



实例

#include <stdio.h>

 

int main ()

{

   int  *ptr = NULL;

 

   printf("ptr 的地址是 %p\n", ptr  );

 

   return 0;

}

当上面的代码被编译和执行时，它会产生下列结果：



ptr 的地址是 0x0

在大多数的操作系统上，程序不允许访问地址为 0 的内存，因为该内存是操作系统保留的。然而，内存地址 0 有特别重要的意义，它表明该指针不指向一个可访问的内存位置。但按照惯例，如果指针包含空值（零值），则假定它不指向任何东西。



如需检查一个空指针，您可以使用 if 语句，如下所示：



if(ptr)     /* 如果 p 非空，则完成 */

if(!ptr)    /* 如果 p 为空，则完成 */





```javascript
#include <stdio.h>

int main(void) {
    char a = 'F';
    int f = 123;
    char *pa = &a;
    int *pb = &f;
    printf("a= %c\n", *pa);//这里用%c
    printf("f= %d\n", *pb);//这里用%d
    *pa = 'C';
    *pb += 1;
    printf("new a= %c\n", *pa);
    printf("new f= %d\n", *pb);
    
    printf("sizeof pa  = %lu\n", sizeof(pa));
    printf("sizeof pb  = %lu\n", sizeof(pb));
    
    printf("pa addr = %p\n", pa);
    printf("pb addr = %p\n", pb);
    return 0;
}
输出:
a= F
f= 123
new a= C
new f= 124
sizeof pa  = 8
sizeof pb  = 8
pa addr = 0x7ffee82127fb
pb addr = 0x7ffee82127f4


```





避免访问未初始化的指针。









数组名其实是数组第一个元素的地址

```javascript
#include <stdio.h>

int main(void) {
    char str[128];
    printf("请输入字符串\n");
    scanf("%s", str); //同scanf("%s", &str[0]);
    printf("%s\n", str);
    printf("str的地址是:%p\n", str);
    printf("str的地址是:%p\n", &str[0]);
}

请输入字符串
123
123
str的地址是:0x7ffeed1a4770
str的地址是:0x7ffeed1a4770

```







```javascript
char a[] = "oct";
int b[5] = {1, 2, 3, 4, 5};
float c[5] = {1.1, 2.2, 3.3, 4.5, 5.6};
double d[5] = {1.1, 2.2, 3.3, 4.5, 5.6};
printf("a[0] -> %p,a[1] -> %p,a[2] -> %p\n", &a[0], &a[1], &a[2]);
printf("b[0] -> %p,b[1] -> %p,b[2] -> %p\n", &b[0], &b[1], &b[2]);
printf("c[0] -> %p,c[1] -> %p,c[2] -> %p\n", &c[0], &c[1], &c[2]);
printf("d[0] -> %p,d[1] -> %p,d[2] -> %p\n", &d[0], &d[1], &d[2]);


a[0] -> 0x7ffee3f9d78c,a[1] -> 0x7ffee3f9d78d,a[2] -> 0x7ffee3f9d78e
b[0] -> 0x7ffee3f9d7e0,b[1] -> 0x7ffee3f9d7e4,b[2] -> 0x7ffee3f9d7e8
c[0] -> 0x7ffee3f9d7c0,c[1] -> 0x7ffee3f9d7c4,c[2] -> 0x7ffee3f9d7c8
d[0] -> 0x7ffee3f9d790,d[1] -> 0x7ffee3f9d798,d[2] -> 0x7ffee3f9d7a0



```





指针的运算

```javascript
#include <stdio.h>

int main(void) {
    char a[] = "oct";
    int b[5] = {1, 2, 3, 4, 5};
    float c[5] = {1.100000, 2.200000, 3.300000, 4.500000, 5.600000};
    double d[5] = {1.1, 2.2, 3.3, 4.5, 5.6};

//    printf("a[0] -> %p,a[1] -> %p,a[2] -> %p\n", &a[0], &a[1], &a[2]);
//    printf("b[0] -> %p,b[1] -> %p,b[2] -> %p\n", &b[0], &b[1], &b[2]);
//    printf("c[0] -> %p,c[1] -> %p,c[2] -> %p\n", &c[0], &c[1], &c[2]);
//    printf("d[0] -> %p,d[1] -> %p,d[2] -> %p\n", &d[0], &d[1], &d[2]);
    char *p = a;
    printf("*p = %c,*(p+1) = %c,*(p+2) = %c\n", *p, *(p + 1), *(p + 2));

    int *pb = b;
    printf("*pb = %d,*(pb+1) = %d,*(pb+2) = %d\n", *pb, *(pb + 1), *(pb + 2));

    //直接作用于数组
    printf("*c = %f,*(c+1) = %f,*(c+2) =%f\n", *c, *(c + 1), *(c + 2));
}


输出
*p = o,*(p+1) = c,*(p+2) = t
*pb = 1,*(pb+1) = 2,*(pb+2) = 3
*c = 1.100000,*(c+1) = 2.200000,*(c+2) =3.300000


```







```javascript
#include <stdio.h>
#include "string.h"

int main(void) {
    char *str = "I love oct!";
    int i, length;
    length = strlen(str);
    printf("%d\n", length);
    for (int i = 0; i < length; ++i) {
        printf("%c", str[i]);
    }
    printf("\n");
    return 0;
}

输出
11
I love oct!

```





手动实现strlen

```javascript
#include <stdio.h>

int main(void) {
    char str[] = "i love oct";
    char *p = str;
    int count = 0;
    while (*p++ != '\0') {
        count++;
    }
    printf("总共有%d个字符!\n", count);
    return 0;
}
```





指针数组和数组指针

![](https://gitee.com/hxc8/images3/raw/master/img/202407172242231.jpg)



![](https://gitee.com/hxc8/images3/raw/master/img/202407172242544.jpg)



![](https://gitee.com/hxc8/images3/raw/master/img/202407172242983.jpg)



```javascript
#include <stdio.h>

int main(void) {
    int a = 1;
    int b = 2;
    int c = 3;
    int d = 4;
    int e = 5;
    int *p[5] = {&a, &b, &c, &d, &e};
    for (int i = 0; i < 5; ++i) {
        printf("%d\n", *p[i]);
    }
    return 0;
}
输出内容:
1
2
3
4
5



#include <stdio.h>

int main(void) {
    char *p[5] = {
            "1222222222",
            "2333333333",
            "3444444444",
            "4555555555",
            "5666666666"
    };
    for (int i = 0; i < 5; ++i) {
        printf("%s\n",p[i]);//字符串的地址
        printf("%c\n",*p[i]);//取字符
    }
    return 0;
}
输出:
1222222222
1
2333333333
2
3444444444
3
4555555555
4
5666666666
5




```





数组指针是一个指针，它指向的是一个数组



![](https://gitee.com/hxc8/images3/raw/master/img/202407172242989.jpg)



![](https://gitee.com/hxc8/images3/raw/master/img/202407172242404.jpg)



```javascript
#include <stdio.h>

int main(void) {
    int (*p)[5] = {1, 2, 3, 4, 5};
    for (int i = 0; i < 5; ++i) {
        printf("%d\n", *(p + i));
    }
    return 0;
}
输出:
main.c:4:20: warning: incompatible integer to pointer conversion initializing 'int (*)[5]' with an expression of type 'int' [-Wint-conversion]
    int (*p)[5] = {1, 2, 3, 4, 5};
                   ^
main.c:4:23: warning: excess elements in scalar initializer
    int (*p)[5] = {1, 2, 3, 4, 5};
                      ^
main.c:6:24: warning: format specifies type 'int' but the argument has type 'int *' [-Wformat]
        printf("%d\n", *(p + i));
                ~~     ^~~~~~~~
3 warnings generated.
1
21
41
61
81


#include <stdio.h>

int main(void) {
    int temp[5] = {1, 2, 3, 4, 5};
    int (*p)[5] = temp;
    for (int i = 0; i < 5; ++i) {
        printf("%d\n", *(p + i));
    }
    return 0;
}
输出内容:

main.c:5:11: warning: incompatible pointer types initializing 'int (*)[5]' with an expression of type 'int [5]'; take the address with & [-Wincompatible-pointer-types]
    int (*p)[5] = temp;
          ^       ~~~~
                  &
main.c:7:24: warning: format specifies type 'int' but the argument has type 'int *' [-Wformat]
        printf("%d\n", *(p + i));
                ~~     ^~~~~~~~
2 warnings generated.
-427755552
-427755532
-427755512
-427755492
-427755472


改正后
#include <stdio.h>

main(void) {
    int t[5] = {1, 2, 3, 4, 5};
    int (*p)[5] = &t;
    for (int i = 0; i < 5; ++i) {
        printf("%d\n", *(*p + i));
    }
}
输出:
1
2
3
4
5

解释：
p为数组(或数组第一个元素)的地址的地址
*p为数组(或数组第一个元素)的地址
*(*p)为数组第一个元素的值,*(*p+i)为数组第i+1个元素的值 
```





指针和二维数组

```javascript
#include <stdio.h>

int main(void) {
    int array[4][5] = {0};
    printf("sizeof int:%lu\n", sizeof(int));
    printf("array %p\n", array);
    printf("array+1 %p\n", array + 1);

    return 0;
}

sizeof int:4
array 0x7ffeeef0d7a0
array+1 0x7ffeeef0d7b4


0x7ffeeef0d7b4-0x7ffeeef0d7a0=14
16进制的14等于10进制的20
每个整型4个字节，20/4=5，所以array是指向包含5个元素的数组的指针
```





解引用 

*(array+1)表示的指向第二行的首地址，相当于 *(array_+1)==array[1]   (语法糖)



```javascript
#include <stdio.h>

int main(void) {
    int array[4][5] = {0};
    for (int i, k = 0; i < 4; ++i) {
        for (int j = 0; j < 5; ++j) {
            array[i][j] = k++;
        }
    }
    printf("*(array+1):%p\n", *(array + 1));
    printf("array[1]:%p\n", array[1]);
    printf("&array[1][0]:%p\n", &array[1][0]);
    printf("**(array+1):%d\n", **(array + 1));
    return 0;
}
输出:
*(array+1):0x7ffee3ea87b4
array[1]:0x7ffee3ea87b4
&array[1][0]:0x7ffee3ea87b4
**(array+1):5

```





*(array+1)+3==&array[1][3]



*(*(array+1)+3)=array[1][3]



结论:

*(array+i)==array[i]

*(*(array+i)+j)==array[i][j]

*(*(*(array+i)+j)+k)==array[i][j][k]

..........................................







数组指针和二维数组

初始化二维数组的时候是可以偷懒的：



int array[2][3] = {

    {1, 2, 3},

    {4, 5, 6}

};

可以写成

int array[][3] = {

    {1, 2, 3},

    {4, 5, 6}

};



定义一个数组指针是这样的：



int(*p)[3];



那么下面是什么意思呢？



int(*p)[3] = array;



通过刚刚的说明，我们可以知道，array是指向一个3个元素的数组的[指针]，所以这里完全可以将array的值赋值给p。



```javascript
#include <stdio.h>

int main(void) {
    int array[2][3] = {
            {0, 1, 2},
            {3, 4, 5}
    };
    int(*p)[3] = array;
    printf("**(p+1): %d\n", **(p + 1));
    printf("**(array+1): %d\n", **(array + 1));
    printf("array[1][0]: %d\n", array[1][0]);
    printf("*(*(p+1)+2): %d\n", *(*(p + 1) + 2));
    printf("array[1][2]: %d\n", array[1][2]);
    return 0;
}

**(p+1): 3
**(array+1): 3
array[1][0]: 3
*(*(p+1)+2): 5
array[1][2]: 5
```





void指针

```javascript
#include <stdio.h>

int main(void) {
    int num = 110;
    int *pn = &num;
    char *pc = "oct";
    void *pv;

    pv = pn;
    printf("pn:%p,pv:%p\n", pn, pv);
//    printf("*pv:%d\n", *pv);//编译不通过 Argument type 'void' is incomplete
    printf("*pv:%d\n", pv);// warning: format specifies type 'int' but the argument has type 'void *' [-Wformat]

    pv = pc;
    printf("pc:%p,pv:%p\n", pc, pv);
    printf("*pv:%s\n", pv);//%s 直接给地址，不用解引用


    return 0;
}
输出:
pn:0x7ffee45947f8,pv:0x7ffee45947f8
pc:0x10b66ef8e,pv:0x10b66ef8e
*pv:oct

```



上面的代码不规范，应该使用强制类型转换。



```javascript
#include <stdio.h>

int main(void) {
    int num = 110;
    int *pn = &num;
    char *pc = "oct";
    void *pv;

    pv = pn;
    printf("pn:%p,pv:%p\n", pn, pv);
    printf("*pv:%d\n", *(int *) pv);

    pv = pc;
    printf("pc:%p,pv:%p\n", pc, pv);
    printf("*pv:%s\n", (char *) pv);//%s 直接给地址，不用解引用


    return 0;
}
```





NULL指针

其实是个宏定义

#define NULL ((void *)0) 





指向指针的指针:

```javascript
#include <stdio.h>

int main() {
    int num = 520;
    int *p = &num;
    int **pp = &p;
    printf("num:%d\n", num);
    printf("*p:%d\n", *p);
    printf("**pp:%d\n", **pp);
    printf("&p:%p, pp:%p\n", &p, pp);
    printf("&num:%p ,p:%p,  *pp:%p\n", &num, p, *pp);
    return 0;
}
输出
num:520
*p:520
**pp:520
&p:0x7ffee12ea7f0, pp:0x7ffee12ea7f0
&num:0x7ffee12ea7f8 ,p:0x7ffee12ea7f8,  *pp:0x7ffee12ea7f8

```



C语言内存布局

```javascript
#include <stdio.h>

int main() {
    char a = 0, b = 0;
    int *p = (int *) &b;
    *p = 258;
    printf("%d  %d\n", a, b);
    return 0;
}

输出
1  2

原因:
258对应的二进制  100000010 高位补0,char 一个字节,int4个字节,内容从高位向低位覆盖,所以a之前的内存空间覆盖后就是00000001，所以输出1，
b就是00000010,也就是输出2
```





结构体

```javascript
#include <stdio.h>

struct Book {
    char title[128];
    char author[40];
    float price;
    unsigned int date;
    char publisher[49];
};

int main(void) {
    struct Book book;
    printf("请输入书名:");
    scanf("%s", book.title);
    printf("请输入作者:");
    scanf("%s", book.author);
    printf("请输入售价:");
    scanf("%f", &book.price);
    printf("请输入出版日期:");
    scanf("%d", &book.date);
    printf("请输入出版社:");
    scanf("%s", book.publisher);
    printf("\n=======数据录入完毕=======\n");
    printf("书名: %s\n", book.title);
    printf("作者: %s\n", book.author);
    printf("售价: %.2f\n", book.price);
    printf("出版日期: %d\n", book.date);
    printf("出版社: %s\n", book.publisher);

    return 0;
}
```



结构体内存对齐

```javascript
#include <stdio.h>


int main(void) {
    struct A {
        char a;
        int b;
        char c;
    } t = {'x', 100, 'o'};
    printf("%lu", sizeof(t));
    return 0;
}
输出12

```

对齐前:

![](https://gitee.com/hxc8/images3/raw/master/img/202407172242846.jpg)

对齐后:

![](https://gitee.com/hxc8/images3/raw/master/img/202407172242138.jpg)





修改下上面的代码:

```javascript
#include <stdio.h>


int main(void) {
    struct A {
        char a;
        char c;
        int b;
    } t = {'x', 'o', 100};
    printf("%lu", sizeof(t));
    return 0;
}
输出8
```

对齐前:

![](https://gitee.com/hxc8/images3/raw/master/img/202407172242991.jpg)



对齐后:

![](https://gitee.com/hxc8/images3/raw/master/img/202407172242424.jpg)





所以结构体内部字段顺序设计也很重要。





结构体指针

通过结构体指针访问结构体成员的两种方法:

(*结构体指针).成员名

结构体指针->成员名





结构体赋值

```javascript
#include <stdio.h>

int main(void) {
    struct Test {
        int x;
        int y;
    } t1, t2;
    t1.x = 1;
    t1.y = 2;

    t2 = t1;
    printf("t2.x =%d,t2.y = %d\n", t2.x, t2.y);
    return 0;
}
输出
t2.x =1,t2.y = 2
```



typedef



共用体

union{

int i;

char ch;

float f;

}



union data a={1};//初始化第一个成员

union data b=a;//直接用一个共同体初始化

union data c={.ch = 'a'};//指定初始化成员





枚举类型







位域

```javascript
#include <stdio.h>

int main(void) {
    typedef struct T {
        unsigned int a: 1;
        unsigned int b: 1;
        unsigned int c: 2;
    } Test;
    Test t;
    t.a = 0;
    t.b = 1;
    t.c = 2;
    printf("a=%u,b=%u,c=%u\n", t.a, t.b, t.c);
    printf("size of t = %lu\n", sizeof(t));
    return 0;
}

输出
a=0,b=1,c=2
size of t = 4

```





逻辑位运算

![](https://gitee.com/hxc8/images3/raw/master/img/202407172242971.jpg)





IO缓冲区

标准IO提供的三种类型的缓冲模式:

- 按块缓存

- 按行缓存

- 不缓存