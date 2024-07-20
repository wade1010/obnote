61 CPP其它容器

array（静态数组）


1）物理结构


在栈上分配内存，创建数组的时候，数组长度必须是常量，创建后的数组大小不可变。


template<class T, size_t size>


class array{


private:


	T elems_[size]; 


	……


};


2）迭代器


随机访问迭代器。


3）特点


部分场景中，比常规数组更方便（能用于模板），可以代替常规数组。

```
#include <iostream>
#include <array>
using namespace std;
// array容器
void test()
{
    int aa[10] = {1, 2, 3, 4, 5, 6, 7, 8, 9, 10};
    array<int, 10> ab = {1, 2, 3, 4, 5, 6, 7, 8, 9, 10};

    // array迭代有4种方法
    for (int i = 0; i < 10; i++)
    {
        cout << ab[i] << " ";
    }
    cout << endl;

    for (int i = 0; i < ab.size(); i++)
    {
        cout << ab[i] << " ";
    }
    cout << endl;

    for (auto it = ab.begin(); it != ab.end(); it++)
    {
        cout << *it << " ";
    }
    cout << endl;

    for (auto val : ab)
    {
        cout << val << " ";
    }
    cout << endl;
}
int main()
{
    test();
    return 0;
}
```

deque（双端队列）


	


1）物理结构


deque容器存储数据的空间是多段等长的连续空间构成，各段空间之间并不一定是连续的。


为了管理这些连续空间的分段，deque容器用一个数组存放着各分段的首地址。


	


通过建立数组，deque容器的分段的连续空间能实现整体连续的效果。


当deque容器在头部或尾部增加元素时，会申请一段新的连续空间，同时在数组中添加指向该空间的指针。


2）迭代器


随机访问迭代器。


3）特点


提高了在两端插入和删除元素的效率，扩展空间的时候，不需要拷贝以前的元素。


在中间插入和删除元素的效率比vector更糟糕。


随机访问的效率比vector容器略低。


4）各种操作


与vector容器相同。

## **forward_list（单链表）**

**1）物理结构**

单链表。

**2）迭代器**

正向迭代器。

**3）特点**

比双链表少了一个指针，可节省一丢丢内存，减少了两次对指针的赋值操作。

如果单链表能满足业务需求，建议使用单链表而不是双链表。

**4）各种操作**

与list容器相同。