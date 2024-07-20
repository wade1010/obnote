![](https://gitee.com/hxc8/images2/raw/master/img/202407172220114.jpg)

它只支持随机访问迭代器。

list容器有sort()成员函数，红黑树和哈希表没有排序的说法，

这个sort排序就是为vector容器准备的，string和deque虽然也有随机访问迭代器，但是这两种容器没有排序的必要。

```
#include <iostream>
#include <vector>
#include <list>
#include <functional>
using namespace std;
//普通函数
template <typename T>
bool compare_asc(const T &left, const T &right)
{
    return left < right;
}

template <typename T>
bool compare_desc(const T &left, const T &right)
{
    return left > right;
}
//仿函数
template <class T>
class _less
{
public:
    bool operator()(const T &left, const T &right)
    {
        return left < right;
    }
};

template <class T>
class _greater
{
public:
    bool operator()(const T &left, const T &right)
    {
        return left > right;
    }
};

template <typename T, typename compare>
void bsort(const T first, const T end, compare comp)
{
    while (true)
    {
        bool bswap = false;
        for (auto it = first;;)
        {
            auto left = it;
            it++;
            auto right = it;
            if (right == end)
            {
                break;
            }
            if (comp(*left, *right))
                continue;
            auto tmp = *right;
            *right = *left;
            *left = tmp;
            bswap = true;
        }
        if (bswap == false)
            break;
    }
}
void test()
{
    // vector<int> bh = {1, 2, 321, 42334, 545623, 1312, 65};
    list<string> bh = {"fad", "fag", "hfg"};
    // bsort(bh.begin(), bh.end(), compare_asc<int>);//普通函数
    // bsort(bh.begin(), bh.end(), compare_desc<int>); //普通函数
    // bsort(bh.begin(), bh.end(), _less<int>());    //仿函数
    // bsort(bh.begin(), bh.end(), _greater<int>()); //仿函数
    // bsort(bh.begin(), bh.end(), _greater<string>()); //仿函数
    // bsort(bh.begin(), bh.end(), greater<string>()); //自带
    bsort(bh.begin(), bh.end(), less<string>()); //自带
    for (auto val : bh)
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