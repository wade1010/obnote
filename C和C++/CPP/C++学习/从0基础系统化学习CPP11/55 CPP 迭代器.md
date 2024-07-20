55 CPP 迭代器

迭代器是访问容器中元素的通用方法。

如果使用迭代器，不同的容器，访问元素的方法是相同的。

迭代器支持的基本操作：赋值（=），解引用（*）、比较（==和!=）、从左向右遍历(++)

一般情况下，迭代器是指针和移动指针的方法。在某些容器中，迭代器是指针，在某些容器中，迭代器是类，封装了指针和移动指针的方法的类。

**迭代器有五种分类：**

**1）正向迭代器**

只能使用++运算符从左向右遍历容器，每次沿容器向右移动一个元素。

容器名<元素类型>::iterator 迭代器名;        // 正向迭代器。

容器名<元素类型>::const_iterator 迭代器名;  // 常正向迭代器。

相关的成员函数：

iterator begin();

const_iterator begin();

const_iterator cbegin();  // 配合auto使用。

iterator end();

const_iterator end();

const_iterator cend();

**2）双向迭代器**

具备正向迭代器的功能，还可以反向（从右到左）遍历容器（也是用++），不管是正向还是反向遍历，都可以用--让迭代器后退一个元素。

容器名<元素类型>:: reverse_iterator 迭代器名;        // 反向迭代器。

容器名<元素类型>:: const_reverse_iterator 迭代器名;  // 常反向迭代器。

相关的成员函数：

reverse_iterator rbegin();

const_reverse_iterator crbegin();

reverse_iterator rend();

const_reverse_iterator crend();

典型的就是双向链表

**3）随机访问迭代器**

具备双向迭代器的功能，还支持以下操作：

l 用于比较两个迭代器相对位置的关系运算（<、<=、>、>=）。

l 迭代器和一个整数值的加减法运算（+、+=、-、-=）。

l 支持下标运算（iter[n]）。（这一点，说白了，只有物理存储结构是数组的容器才有这种迭代器，在STL中，string,vector和deque的物理存储结构都是数组）

数组的指针是纯天然的随机访问迭代器。

**4）输入和输出迭代器**

这两种迭代器比较特殊，它们不是把容器当做操作对象，而是把输入/输出流作为操作对象。

## **注意事项**

**迭代器失效的问题**

resize()、reserve()、assign()、push_back()、pop_back()、insert()、erase()等函数会引起vector容器的动态数组发生变化，可能导致vector迭代器失效。