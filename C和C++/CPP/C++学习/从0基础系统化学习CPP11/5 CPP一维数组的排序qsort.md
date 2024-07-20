CPP一维数组的排序qsort

```
#include "iostream"
using namespace std;

// 1 如果函数的返回值<0 那么a所指向的元素会排在8所指向元素的前面
// 2 如果函数的返回值==0 那么a所指向的元素与8所指向元素的顺序不确定
// 3 如果函数的返回值>0 那么a所指向的元素会排在8所指向元素的后面
int myCompareAsc(const void *a, const void *b)
{
    // int aa = *((int *)a);
    // int bb = *((int *)b);
    // if (aa < bb)
    // {
    //     return -1;
    // }
    // else if (aa == bb)
    // {
    //     return 0;
    // }
    // return 1;
    return *((int *)a) - *((int *)b);
}
int myCompareDesc(const void *a, const void *b)
{
    return *((int *)b) - *((int *)a);
}
void test()
{
    int a[8] = {2, 12, 4, 542, 1, 5, 65, 3};
    //函数的原型
    // qsort(void *_Base,size_t _NumOfElements,size_t _SizeOfElements,int (__cdecl *_PtFuncCompare)(const void *,const void *));
    qsort(a, sizeof(a) / sizeof(a[0]), sizeof(a[0]), myCompareAsc);
    for (int i = 0; i < 8; i++)
    {
        cout << a[i] << " ";
    }
    cout << endl;

    qsort(a, sizeof(a) / sizeof(a[0]), sizeof(a[0]), myCompareDesc);
    for (int i = 0; i < 8; i++)
    {
        cout << a[i] << " ";
    }
    cout << endl;
}
int main()
{
    test();
    return 0;
}
1 2 3 4 5 12 65 542 
542 65 12 5 4 3 2 1
```

注意:

1 形参中的地址用void是为了支持任意数据类型，在回调函数中必须具体化；

2 size_t是C标准库中定义的，在64位操作系统中是8字节无符号(unsigned long long)。

typedef unsigned long long size_t

为什么需要第三个形参，因为qsort函数不知道数组的数据类型，在函数内部，操作数据的时候不是按数据类型来操作的，而是按内存块来操作的，如果要交换数组中两个元素的位置，不是用赋值语句，而是用memcpy()函数。

3 排序的需求除了升序和降序，还有很多不可预知的情况，只能用回调函数。 

二分查找（折半查找）

```
#include "iostream"
using namespace std;
//在arr中查找key,成功返回key在arr中的数组下标,失败返回-1
int search(int arr[], int len, int key)
{
    int low = 0, high = len - 1, mid;
    while (low <= high)
    {
        mid = (low + high) / 2;
        cout << arr[low] << " " << arr[mid] << " " << arr[high] << endl;
        if (arr[mid] == key)
            return mid;
        else if (arr[mid] > key)
            high = mid - 1;
        else
            low = mid + 1;
    }
    return -1;
}
int myCompareAsc(const void *a, const void *b)
{
    return *((int *)a) - *((int *)b);
}
void test()
{
    int a[10] = {321, 43, 54, 65, 723, 212, 32, 54, 65, 765};
    //必须得排好序
    qsort(a, 10, sizeof(int), myCompareAsc);

    if (search(a, 10, 32) >= 0)
    {
        cout << "在数组中查找32成功" << endl;
    }
    else
    {
        cout << "查找失败" << endl;
    }
}
int main()
{
    test();
    return 0;
}
32 65 765
32 43 54
32 32 32
在数组中查找32成功
```