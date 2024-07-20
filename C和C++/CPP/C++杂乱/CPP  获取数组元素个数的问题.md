C++中数组可分为堆区的数组和栈区的数组，对于两种数组C++都没有函数可以直接获取数组的元素的个数。





一、堆区的数组



堆区的数组是自己申请的，比如用new申请空间：



int* arr = new int[10];

堆区的数组不能计算出包含元素个数。







二、栈区的数组



栈区的数组是系统自动分配的，如：



	int arr[10] = { 1,2,3,4,5,6,7,8,9,0 };



栈区的数组可以通过以下两种方法得出元素的个数：



（1）



	int arr[10] = { 1,2,3,4,5,6,7,8,9,0 };

	auto diff = sizeof(arr)/sizeof(int);



（2）



这种方法需要所用编译器支持C++11,14



	int arr[10] = { 1,2,3,4,5,6,7,8,9,0 };

	int *pbeg = begin(arr);

	int *pend = end(arr);

	auto length = pend - pbeg;//数组元素个数





```javascript
#include<iostream>
using namespace std;

template<class T>

int length(T& arr)
{
    //cout << sizeof(arr[0]) << endl;
    //cout << sizeof(arr) << endl;
    return sizeof(arr) / sizeof(arr[0]);
}

int main()
{
    int arr[] = { 1,5,9,10,9,2 };
    // 方法一
    cout << "数组的长度为：" << length(arr) << endl;
    // 方法二
    //cout << end(arr) << endl;
    //cout << begin(arr) << endl;
    cout << "数组的长度为：" << end(arr)-begin(arr) << endl;
    system("pause");
    return 0;
}
```



```javascript
输出结果为：
数组的长度为：6
数组的长度为：6
```

