C++支持模板的模板，把模板名当做一种特殊的数据类型，实例化的时候，可以用模

```
#include "iostream"
using namespace std;
template <class T1, int len>
class LinkList
{
public:
    T1 *m_head;
    int m_len = len;
    void insert() { cout << "向链表中插入了一条记录" << endl; }
    void deleteItem() { cout << "从链表中删除了一条记录" << endl; }
    void update() { cout << "向链表中更新了一条记录" << endl; }
};

template <class T1, int len>
class Array
{
public:
    T1 *m_data;
    int m_len = len;
    void insert() { cout << "向数组中插入了一条记录" << endl; }
    void deleteItem() { cout << "从数组中删除了一条记录" << endl; }
    void update() { cout << "向数组中更新了一条记录" << endl; }
};

//线性表模板类
// template <class, int> class T1  这里表示T1不是一个普通的参数,而是模板,意思是这个参数要填模板名,不要填int string等普通类型
//填什么样的模板名呢?填有两个参数的类模板名, 且要求类模板的第一个参数时通用类型,第二个是非通用类型
// template <template <typename, int> typename T1, typename T2, int len>//同下
template <template <class, int> class T1, class T2, int len>
class LinearList
{
public:
    T1<T2, len> m_table;
    void insert() { m_table.insert(); }
    void deleteItem() { m_table.deleteItem(); }
    void update() { m_table.update(); }
    void oper()
    {
        cout << "len=" << m_table.m_len << endl;
        m_table.insert();
        m_table.update();
    }
};

//从上面两个模板类代码可以看出来,LinkList和Array的逻辑结构是一样的,那能不能把它们做成一个模板类呢?
void test()
{
    LinearList<LinkList, int, 20> a;
    a.insert();
    a.deleteItem();
    a.update();
    cout << endl;
    LinearList<Array, string, 20> b;
    b.insert();
    b.deleteItem();
    b.update();
}
int main()
{
    test();
    return 0;
}
/* 向链表中插入了一条记录
从链表中删除了一条记录
向链表中更新了一条记录

向数组中插入了一条记录
从数组中删除了一条记录
向数组中更新了一条记录 */
```

板名做参数传给模板。 