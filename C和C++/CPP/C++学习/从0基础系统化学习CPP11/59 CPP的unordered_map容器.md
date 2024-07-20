```
#include <iostream>
#include <unordered_map>
using namespace std;

template <class K, class V>
using umap = unordered_map<K, V>;

void test()
{
    umap<int, string> m;
    cout << m.bucket_count() << endl;
    size_t itmp = m.bucket_count();
    for (int i = 0; i < 2000000; i++)
    {
        char name[50];
        sprintf(name, "user%d", i);
        m.emplace(i, name);
        if (itmp != m.bucket_count())
        {
            cout << m.bucket_count() << endl;
            itmp = m.bucket_count();
        }
    }
}
int main()
{
    test();
    return 0;
}

1
3
7
17
37
79
167
337
709
1493
3209
6427
12983
26267
53201
107897
218971
444487
902483
1832561
3721303
```

```
#include <iostream>
#include <unordered_map>
using namespace std;

void test()
{
    unordered_map<int, string> m;
    m.insert({{1, "a"}, {2, "b"}, {3, "c"}, {4, "d"}});
    m.insert({{6, "e"}, {7, "f"}, {8, "g"}, {9, "h"}});

    for (int i = 0; i < m.bucket_count(); i++)
    {
        cout << "桶" << i << ":";
        for (auto it = m.begin(i); it != m.end(i); it++)
            cout << it->first << "," << it->second << " ";
        cout << endl;
    }
    m.insert({{15, "a"}, {16, "b"}, {17, "c"}, {18, "d"}});
    m.insert({{25, "e"}, {26, "f"}, {27, "g"}, {28, "h"}});
    cout << endl;
    for (int i = 0; i < m.bucket_count(); i++)
    {
        cout << "桶" << i << ":";
        for (auto it = m.begin(i); it != m.end(i); it++)
            cout << it->first << "," << it->second << " ";
        cout << endl;
    }
}
int main()
{
    test();
    return 0;
}
/* 桶0:
桶1:1,a
桶2:2,b
桶3:3,c
桶4:4,d
桶5:
桶6:6,e
桶7:7,f
桶8:8,g
桶9:9,h
桶10:
桶11:
桶12:
桶13:
桶14:
桶15:
桶16:

桶0:17,c
桶1:18,d 1,a
桶2:2,b
桶3:3,c
桶4:4,d
桶5:
桶6:6,e
桶7:7,f
桶8:25,e 8,g
桶9:26,f 9,h
桶10:27,g
桶11:28,h
桶12:
桶13:
桶14:
桶15:15,a
桶16:16,b */
```

从输出的内容可以看出，扩容前后完全不一样，在哈希表中，桶是数组，桶中的元素是链表，哈希表要扩容，必须重新分配内存，并且，因为哈希表的表长不一样了，那么，哈希函数也肯定不一样。所有的元素要重新哈希，要重新散列，

```
#include <iostream>
#include <unordered_map>
#include <ctime>
using namespace std;

void test()
{
    unordered_map<int, string> m(10000000);
    int start = time(0);
    cout << "开始创建" << start << endl;
    for (int i = 0; i < 10000000; i++)
    {
        m.insert({i, "美女"});
        // m.emplace(i, "美女"); //更快
    }
    cout << "创建完成:" << (time(0) - start) << "秒" << endl;
}
int main()
{
    test();
    return 0;
}

```

如果数据量过意，数据元素占用的内存空间更多，创建哈希表的时间会比较长

程序运行的过程中，如果中途要重新哈希，在新的哈希表准备好之前，是不能提供服务的，也就是说，业务要暂停，对很多重要的业务系统来说，业务暂停较长时间就是巨大损失，不可接受。