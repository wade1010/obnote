队列的特点只有一个，先进先出

没有插队，也没有离队的说法，

queue容器的逻辑结构是队列，物理结构可以是数组或者链表。

主要用于多线程之间的数据共享。

// template<class T,class _Container=deque<T>>

// class queue{

//  ......

// }

//第一个模板参数T:元素的数据类型.

// 第二个模板参数_Container:底层容器的类型,缺省的是std::deque,可以是数组（vector不支持），也可以用链表，还可以用自定义的类模板

list和vector容器都是线性表，可以在头部插入和删除元素，也可以在尾部插入和删除元素，如果你创建一个list或者vector，只在尾部插入元素，头部删除元素，就是把它当做队列来使用。

queue容器并没有像vector和list那样自己分配内存，而是把其它的容器做了二次封装，创建队列的时候，如果把第二个模板参数指定为vector，那么在队列中就有一个vector容器的对象，如果把第二个模板参数指定为list,那么在队列中就有一个list容器的对象，还有可以不用list和vector容器，自己再写一个容器也可以，只要自己写的代码适应queue类模板就行。

和其它容易不一样，queue容器不支持迭代器，因为它不需要迭代器。

queue容器不存在对它赋初始值的用法，所以构造函数也很简单。

```
#include <iostream>
#include <queue>
#include <deque>
#include <list>
using namespace std;
class girl
{
public:
    int m_bh;
    string m_name;
    girl(const int &bh, const string &name) : m_bh(bh), m_name(name) {}
};
void test()
{
    // template<class T,class _Container=deque<T>>
    // class queue{
    //  ......
    // }
    //第一个模板参数T:元素的数据类型.
    // 第二个模板参数_Container:底层容器的类型,可以 是std:deque,可以用std::list还可以用自定义的类模板.

    queue<girl, list<girl>> q;    //物理结构为链表
    queue<girl, deque<girl>> q2;  //物理结构为数组.
    queue<girl> q3;               //物理结构为数组
    queue<girl, vector<girl>> q4; //物理结构为数组,!!!!!!！!!!！!！!！!!!！!！!!这个不行
                                  /*    q4.push(girl(1, "a"));
                                    q4.push(girl(2, "b"));
                                    q4.push(girl(3, "c"));
                                    while (!q4.empty())
                                    {
                                        cout << q4.front().m_bh << "  " << q4.front().m_name << endl;
                                        q4.pop();
                                    }
                                    error: 'class std::vector<girl>' has no member named 'pop_front'; did you mean 'front'?
                                   c.pop_front();
                                   ~~^~~~~~~~~
                                   front */

    q3.push(girl(1, "a"));
    q3.push(girl(2, "b"));
    q3.push(girl(3, "c"));
    while (!q3.empty())
    {
        cout << q3.front().m_bh << "  " << q3.front().m_name << endl;
        q3.pop();
    }
}
int main()
{
    test();
    return 0;
}
1  a
2  b
3  c
```