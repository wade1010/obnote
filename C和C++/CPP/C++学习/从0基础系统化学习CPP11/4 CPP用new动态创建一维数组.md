CPP用new动态创建一维数组

普通数组在栈上分配内存，栈很小，如果需要存放更多的元素，必须在堆上分配内存。

动态创建一维数组的语法 ：  数组类型 *指针=new 数据类型[数组长度];

释放一维数组的语法  delete[] 指针;

注意：

1 动态创建的数组没有数组名，不能用sizeof运算符；

2 可以用数组表示法和指针表示法两种方式使用动态创建的数组；

3 必须使用delete[] 来释放动态数组的内存（不能只用delete）;

4 不要用delete[]来释放不是new[]分配的内存，（C语言的malloc()函数也可以动态分配内存，该方法分配的内存，需要用free()来释放，不能用delete。还有就是如果指针指向的地址是栈上的变量，也就是普通变量的地址，也不能用delete来）

5 不要用delete[]释放同一内存块两次（第一次delete释放内存后，不会把指针置空，第二次等同于操作野指针）；

6 对空指针用delete[]是安全的（释放内存后，应该把指针置为nullptr，防止误操作）;

7 声明普通数组的时候，数组长度可以用变量，相当于在栈上动态创建数组，且不需要释放。在实际开发中数组很小，在栈上分配也是可以的，很安全 很方便。

8 如果内存不足，调用new会产生异常，导致程序终止；如果在new关键字后面加上(std::notthrow)选项，则返回nullptr，不会产生异常。

9 为什么用delete[]释放数组的时候，不需要指定数组的大小？因为系统会自动跟踪已分配数组的内存。

就上面第8点有下面2个测试。

```
#include "iostream"
using namespace std;

void test()
{
    int a[1000001];
    a[100000] = 10;
}
int main()
{
    test();
    return 0;
}
```

上面代码执行后，异常退出

![](https://gitee.com/hxc8/images3/raw/master/img/202407172225196.jpg)

```
#include "iostream"
using namespace std;

void test()
{
    int *p = new int[10000022222220001];
    p[10000022222220000] = 10;
    cout << p[10000022222220000] << endl;
    cout << "111" << endl;
}
int main()
{
    test();
    return 0;
}
terminate called after throwing an instance of 'std::bad_alloc'
  what():  std::bad_alloc
```

上面代码 内存不足时，申请分配不到内存，程序就崩溃掉了，实际开发中，我们希望，如果没有申请成功，返回失败就可以了，不要崩溃。

修改后

```
#include "iostream"
using namespace std;

void test()
{
    int *p = new (std::nothrow) int[10000022222220001];
    if (p == nullptr)
    {
        cout << "申请失败" << endl;
    }
    else
    {
        p[10000022222220000] = 10;
        cout << p[10000022222220000] << endl;
    }

    cout << "111" << endl;
}
int main()
{
    test();
    return 0;
}
申请失败
111
```