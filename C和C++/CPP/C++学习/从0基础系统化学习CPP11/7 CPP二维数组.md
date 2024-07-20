CPP二维数组

![](https://gitee.com/hxc8/images3/raw/master/img/202407172224877.jpg)

![](https://gitee.com/hxc8/images2/raw/master/img/202407172224328.jpg)

![](https://gitee.com/hxc8/images2/raw/master/img/202407172224258.jpg)

```
#include "iostream"
using namespace std;

void test()
{
    int bh[2][3];
    bh[0][0] = 11;
    bh[0][1] = 12;
    bh[0][2] = 13;
    bh[1][0] = 20;
    bh[1][1] = 21;
    bh[1][2] = 22;

    for (int i = 0; i < 2; i++)
    {
        for (int j = 0; j < 3; j++)
        {
            cout << "&bh[" << i << "][" << j << "]=" << (long long)&bh[i][j] << endl;
        }
    }
    //骚操作
    int *p = (int *)bh;
    for (int i = 0; i < 6; i++)
    {
        cout << "p[" << i << "]=" << (long long)&p[i] << " " << p[i] << endl;
    }
}
int main()
{
    test();
    return 0;
}
&bh[0][0]=6421952
&bh[0][1]=6421956
&bh[0][2]=6421960
&bh[1][0]=6421964
&bh[1][1]=6421968
&bh[1][2]=6421972
p[0]=6421952 11
p[1]=6421956 12
p[2]=6421960 13
p[3]=6421964 20
p[4]=6421968 21
p[5]=6421972 22
```

![](https://gitee.com/hxc8/images2/raw/master/img/202407172224713.jpg)

 

![](https://gitee.com/hxc8/images2/raw/master/img/202407172224513.jpg)

![](https://gitee.com/hxc8/images2/raw/master/img/202407172224771.jpg)

memcpy这个函数的本质是内存拷贝，只要逻辑上讲得通，可以对任意数据类型的内存进行复制，所以，可以把二维数组直接复制到一维数组中，也可以把一维数组直接复制到二维数组中。

windows11下面代码c++11都是可以的

```
#include "iostream"
using namespace std;

void test()
{
    int a = 1;
    int b[a];
    int c[sizeof(a) / 2];
    cout << "ok" << endl;
}
int main()
{
    test();
    return 0;
}
ok
```