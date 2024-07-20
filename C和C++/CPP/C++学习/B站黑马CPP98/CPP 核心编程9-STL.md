CPP 核心编程9-STL

## STL初识

### 2.1 STL的诞生

- 长久以来，软件界一直希望建立一种可重复利用的东西

- C++的**面向对象**和**泛型编程**思想，目的就是**复用性的提升**

- 大多情况下，数据结构和算法都未能有一套标准,导致被迫从事大量重复工作

- 为了建立数据结构和算法的一套标准,诞生了**STL**

​

### 2.2 STL基本概念

- STL(Standard Template Library,**标准模板库**)

- STL 从广义上分为: **容器(container) 算法(algorithm) 迭代器(iterator)**

- 容器和**算法**之间通过**迭代器**进行无缝连接。

- STL 几乎所有的代码都采用了模板类或者模板函数

### 2.3 STL六大组件

STL大体分为六大组件，分别是:**容器、算法、迭代器、仿函数、适配器（配接器）、空间配置器**

1. 容器：各种数据结构，如vector、list、deque、set、map等,用来存放数据。

1. 算法：各种常用的算法，如sort、find、copy、for_each等

1. 迭代器：扮演了容器与算法之间的胶合剂。

1. 仿函数：行为类似函数，可作为算法的某种策略。

1. 适配器：一种用来修饰容器或者仿函数或迭代器接口的东西。

1. 空间配置器：负责空间的配置与管理。

### 2.4 STL中容器、算法、迭代器

**容器：**置物之所也

STL**容器**就是将运用**最广泛的一些数据结构**实现出来

常用的数据结构：数组, 链表,树, 栈, 队列, 集合, 映射表 等

这些容器分为**序列式容器**和**关联式容器**两种:

​ **序列式容器**:强调值的排序，序列式容器中的每个元素均有固定的位置。 **关联式容器**:二叉树结构，各元素之间没有严格的物理上的顺序关系

**算法：**问题之解法也

有限的步骤，解决逻辑或数学上的问题，这一门学科我们叫做算法(Algorithms)

算法分为:**质变算法**和**非质变算法**。

质变算法：是指运算过程中会更改区间内的元素的内容。例如拷贝，替换，删除等等

非质变算法：是指运算过程中不会更改区间内的元素内容，例如查找、计数、遍历、寻找极值等等

**迭代器：**容器和算法之间粘合剂

提供一种方法，使之能够依序寻访某个容器所含的各个元素，而又无需暴露该容器的内部表示方式。

每个容器都有自己专属的迭代器

迭代器使用非常类似于指针，初学阶段我们可以先理解迭代器为指针

迭代器种类：

| 种类 | 功能 | 支持运算 | 
| -- | -- | -- |
| 输入迭代器 | 对数据的只读访问 | 只读，支持++、==、！= | 
| 输出迭代器 | 对数据的只写访问 | 只写，支持++ | 
| 前向迭代器 | 读写操作，并能向前推进迭代器 | 读写，支持++、==、！= | 
| 双向迭代器 | 读写操作，并能向前和向后操作 | 读写，支持++、--， | 
| 随机访问迭代器 | 读写操作，可以以跳跃的方式访问任意数据，功能最强的迭代器 | 读写，支持++、--、[n]、-n、<、<=、>、>= | 


常用的容器中迭代器种类为双向迭代器，和随机访问迭代器

### 2.5 容器算法迭代器初识

了解STL中容器、算法、迭代器概念之后，我们利用代码感受STL的魅力

STL中最常用的容器为Vector，可以理解为数组，下面我们将学习如何向这个容器中插入数据、并遍历这个容器

#### 2.5.1 vector存放内置数据类型

容器： vector

算法： for_each

迭代器： vector<int>::iterator

```
#include "iostream"
#include "vector"
#include "algorithm"
using namespace std;

void myPrint(int val)
{
    cout << val << endl;
}
void test()
{
    //创建vector容器对象 并且通过模板参数指定容器中存放的数据类型
    vector<int> v;
    //向容器中放数据
    v.push_back(1);
    v.push_back(2);
    v.push_back(3);
    v.push_back(4);
    v.push_back(5);
    //每一个容器都有自己的迭代器，迭代器是用来遍历容器中的元素
    // v.begin()返回迭代器，这个迭代器指向容器中第一个数据
    // v.end()返回迭代器，这个迭代器指向容器元素的最后一个元素的下一个位置
    // vector<int>::iterator 拿到vector<int>这种容器的迭代器类型
    vector<int>::iterator iterBegin = v.begin();
    vector<int>::iterator iterEnd = v.end();
    //第一种遍历
    while (iterBegin != iterEnd)
    {
        cout << *iterBegin << endl;
        iterBegin++;
    }
    cout << endl;
    //第二种遍历
    for (vector<int>::iterator it = v.begin(); it != v.end(); it++)
    {
        cout << *it << endl;
    }
    cout << endl;
    //第三种遍历
    //使用STL提供的遍历算法 头文件 algorithm
    for_each(v.begin(), v.end(), myPrint);
}
int main()
{
    test();
    return 0;
}
```

互换容器

```
#include "iostream"
#include "vector"

using namespace std;

//vector容器互换
void printVector(vector<int> v) {
    for (vector<int>::iterator it = v.begin(); it != v.end(); it++) {
        cout << *it << " ";
    }
    cout << endl;
}

//1 基本使用
void test1() {
    vector<int> v1;

    for (int i = 0; i < 10; i++) {
        v1.push_back(i);
    }
    cout << "交换前：" << endl;
    printVector(v1);

    vector<int> v2;
    for (int j = 10; j > 0; j--) {
        v2.push_back(j);
    }
    printVector(v2);

    cout << "交换后：" << endl;
    v1.swap(v2);

    printVector(v1);
    printVector(v2);
}
//2 实际用途：巧用swap可以收缩内存空间

void test2() {
    vector<int> v;
    for (int i = 0; i < 100000; i++) {
        v.push_back(i);
    }
    cout << "v的容量为：" << v.capacity() << endl;
    cout << "v的大小为：" << v.size() << endl;
    v.resize(3);
    cout << "resize后 v的容量为：" << v.capacity() << endl;
    cout << "resize后 v的大小为：" << v.size() << endl;

    //巧用swap收缩内存空间  后面有图片解释
    vector<int>(v).swap(v);
    cout << "swap后 v的容量为：" << v.capacity() << endl;
    cout << "swap后 v的大小为：" << v.size() << endl;
}

int main() {
    test1();
    test2();
    return 0;
}
交换前：
0 1 2 3 4 5 6 7 8 9 
10 9 8 7 6 5 4 3 2 1 
交换后：
10 9 8 7 6 5 4 3 2 1 
0 1 2 3 4 5 6 7 8 9 
v的容量为：131072
v的大小为：100000
resize后 v的容量为：131072
resize后 v的大小为：3
swap后 v的容量为：3
swap后 v的大小为：3
```

总结：swap可以使两个容器互换，可以达到实用的收缩内存效果

![](https://gitee.com/hxc8/images3/raw/master/img/202407172237333.jpg)

![](https://gitee.com/hxc8/images3/raw/master/img/202407172237629.jpg)

上图解释：

 vector<int>(v) 利用拷贝构造函数创建了一个匿名对象，新的对象没有名，x代表这个匿名对象，会按照v当前size来初始化匿名对象，所以匿名对象一开始size=3,capacity=3,只不过这是匿名对象。

上面的匿名对象，系统会帮助我们回收，匿名对象有一个特点，当前行执行完，编译器发现它是匿名对象，马上回收。

```
#include "iostream"
#include "vector"

using namespace std;

//vector 容器 预留空间
void test() {
    vector<int> v;
    int num = 0;//统计开辟次数
    int *p = nullptr;
//    for (int i = 0; i < 100000; i++) {
//        v.push_back(i);
//        if (p != &v[0]) {
//            p = &v[0];
//            num++;
//        }
//    }
    int capacity = 0;
    for (int i = 0; i < 100000; i++) {
        v.push_back(i);
        if (v.capacity() != capacity) {
            capacity = v.capacity();
            num++;
        }
    }
    cout << "不用reserve时，动态扩展次数：" << num << endl;

    int num2 = 0;

    int capacity2 = 0;
    vector<int> v2;
    v2.reserve(100000);
    for (int j = 0; j < 100000; j++) {
        v2.push_back(j);
        if (v2.capacity() != capacity) {
            capacity = v2.capacity();
            num2++;
        }
    }
    cout << "使用reserve时，动态扩展次数：" << num2 << endl;

}

int main() {
    test();
    return 0;
}
不用reserve时，动态扩展次数：18
使用reserve时，动态扩展次数：1

```

```
#include "iostream"
#include "deque"
#include "vector"
#include "algorithm"

using namespace std;

void printDeque(deque<int> &d) {
    for (deque<int>::const_iterator it = d.begin(); it != d.end(); it++) {
        cout << *it << " ";
    }
    cout << endl;
}

//deque容器排序
void test() {
    deque<int> d;
    d.push_back(10);
    d.push_back(20);
    d.push_back(30);
    d.push_front(100);
    d.push_front(200);
    d.push_front(300);

    cout << "排序前：" << endl;
    printDeque(d);

    //默认从小到大 升序
    //对于支持随机访问的迭代器的容器，都可以利用sort算法直接对其进行排序
    //vector容器也可以利用 sort进行排序
    sort(d.begin(), d.end());

    cout << "排序后：" << endl;
    printDeque(d);
}

void test2() {
    vector<int> v;
    v.push_back(134);
    v.push_back(54);
    v.push_back(1432);
    v.push_back(43);
    v.push_back(1);
    sort(v.begin(), v.end());
    cout << "vector 排序后：" << endl;
    for (vector<int>::iterator it = v.begin(); it != v.end(); it++) {
        cout << *it << " ";
    }
    cout << endl;
}

int main() {
    test();
    test2();
    return 0;
}
排序前：
300 200 100 10 20 30 
排序后：
10 20 30 100 200 300 
vector 排序后：
1 43 54 134 1432 

```

总结：sort算法非常实用，使用时包含头文件algorithm

![](https://gitee.com/hxc8/images3/raw/master/img/202407172237529.jpg)

  

```
#include "iostream"
#include "vector"
#include "deque"
#include "algorithm"

using namespace std;

class Person {
public:
    Person(string name, int score) {
        this->m_Name = name;
        this->m_Score = score;
    }

    int m_Score;
    string m_Name;
};

void createPerson(vector<Person> &vp) {
    string nameSeed = "ABCDE";
    for (int i = 0; i < 5; i++) {
        Person p("选手" + to_string(i + 1), 0);
        vp.push_back(p);
    }
}

void printInfo(vector<Person> &p) {
    for (vector<Person>::iterator it = p.begin(); it != p.end(); it++) {
        cout << "姓名：" << (*it).m_Name << " 平均分:" << (*it).m_Score << endl;
    }
}

void setScore(vector<Person> &p) {
    srand((unsigned int) time(NULL));
    for (vector<Person>::iterator it = p.begin(); it != p.end(); it++) {
        deque<int> d;
        cout << "选手：" << it->m_Name << "的打分：" << endl;
        for (int i = 0; i < 10; i++) {
            int score = rand() % 41 + 60;
            d.push_back(score);
            cout << score << " ";
        }
        cout << endl;
        //排序
        sort(d.begin(), d.end());
        //去除最高和最低
        d.pop_back();
        d.pop_front();
        int sum = 0;
        for (deque<int>::const_iterator dit = d.begin(); dit != d.end(); dit++) {
            sum += *dit;
        }
        it->m_Score = sum / d.size();
    }
}

//评委打分
void test() {
//1创建5名选手
    vector<Person> v;
    createPerson(v);
    printInfo(v);
//2 给5名选手打分
    setScore(v);
    printInfo(v);

//3 显示最后得分
}

int main() {
    test();
    return 0;
}
姓名：选手1 平均分:0
姓名：选手2 平均分:0
姓名：选手3 平均分:0
姓名：选手4 平均分:0
姓名：选手5 平均分:0
选手：选手1的打分：
83 96 87 61 93 65 87 77 71 73 
选手：选手2的打分：
83 70 77 97 63 88 79 62 70 70 
选手：选手3的打分：
94 100 64 68 83 96 81 92 85 73 
选手：选手4的打分：
65 78 89 62 73 67 71 96 90 62 
选手：选手5的打分：
63 78 93 99 84 81 83 88 62 91 
姓名：选手1 平均分:79
姓名：选手2 平均分:75
姓名：选手3 平均分:84
姓名：选手4 平均分:74
姓名：选手5 平均分:82
```

```
#include "iostream"
#include "stack"

using namespace std;

//栈
//特点，先进后出
void test() {
    stack<int> s;
    s.push(1);
    s.push(2);
    s.push(3);
    s.push(4);
    cout << "栈的大小：" << s.size() << endl;
    while (!s.empty()) {
        cout << s.top() << endl;
        s.pop();
    }
    cout << "栈的大小：" << s.size() << endl;
}

int main() {
    test();
    return 0;
}
```

```
#include "iostream"
#include "queue"

using namespace std;

//queue容器
void test() {
    queue<int> q;
    q.push(1);
    q.push(2);
    q.push(3);
    q.push(4);
    while (!q.empty()) {
        cout << "front=" << q.front() << " back=" << q.back() << " q.size() =" << q.size() << endl;
        q.pop();
    }
}

int main() {
    test();
    return 0;
}
front=1 back=4 q.size() =4
front=2 back=4 q.size() =3
front=3 back=4 q.size() =2
front=4 back=4 q.size() =1

```

![](https://gitee.com/hxc8/images3/raw/master/img/202407172237777.jpg)

![](https://gitee.com/hxc8/images3/raw/master/img/202407172237545.jpg)

```
#include "iostream"
#include "list"

using namespace std;

void printList(const list<int> &l) {
    for (auto i:l) {
        cout << i << " ";
    }
    cout << endl;
}

//list容器构造函数
void test() {
    list<int> l1;
    l1.push_back(1);
    l1.push_back(2);
    l1.push_back(3);
    l1.push_back(4);
    printList(l1);

    //区间方式构造
    list<int> l2(l1.begin(), l1.end());
    printList(l2);
    //拷贝构造
    list<int> l3(l2);
    printList(l3);
    //n个element
    list<int> l4(10, 1);
    printList(l4);
}

int main() {
    test();
    return 0;
}
1 2 3 4 
1 2 3 4 
1 2 3 4 
1 1 1 1 1 1 1 1 1 1 
```

```
#include "iostream"
#include "list"

using namespace std;

void printList(const list<int> &l) {
    for (list<int>::const_iterator it = l.begin(); it != l.end(); it++) {
        cout << *it << " ";
    }
    cout << endl;
}

//list赋值和交换
void test() {
    list<int> l1;
    l1.push_back(1);
    l1.push_back(2);
    l1.push_back(3);
    l1.push_back(4);
    printList(l1);

    list<int> l2 = l1;
    printList(l2);

    list<int> l3;
    l3.assign(l2.begin(), l2.end());
    printList(l3);

    list<int> l4;
    l4.assign(10, 1);
    printList(l4);


}

//交换
void test2() {
    list<int> l1;
    l1.push_back(1);
    l1.push_back(2);
    l1.push_back(3);
    l1.push_back(4);

    list<int> l2;
    l2.assign(10, 1);
    cout << "交换前:" << endl;
    printList(l1);
    printList(l2);
    l1.swap(l2);
    cout << "交换后:" << endl;
    printList(l1);
    printList(l2);
};

int main() {
    test();
    cout << endl;
    test2();
    return 0;
}
1 2 3 4 
1 2 3 4 
1 2 3 4 
1 1 1 1 1 1 1 1 1 1 

交换前:
1 2 3 4 
1 1 1 1 1 1 1 1 1 1 
交换后:
1 1 1 1 1 1 1 1 1 1 
1 2 3 4 

```

```
#include "iostream"
#include "list"

using namespace std;

void printList(const list<int> &l) {
    for (list<int>::const_iterator it = l.begin(); it != l.end(); it++) {
        cout << *it << " ";
    }
    cout << endl;
}

//list 大小操作
void test() {
    list<int> l1;
    l1.push_back(1);
    l1.push_back(2);
    l1.push_back(3);
    l1.push_back(4);
    printList(l1);
    if (l1.empty()) {
        cout << "l1为空" << endl;
    } else {
        cout << "l1不为空" << endl;
        cout << "l1个数：" << l1.size() << endl;
    }
    //重新指定大小
    l1.resize(2);
    printList(l1);
    l1.resize(4, 100);
    printList(l1);
}

int main() {
    test();
    return 0;
}
1 2 3 4 
l1不为空
l1个数：4
1 2 
1 2 100 100
```

```
#include "iostream"
#include "list"

using namespace std;

void printList(list<int> &l) {
    for (auto i:l) {
        cout << i << " ";
    }
    cout << endl;
}

//list 插入和删除
void test() {
    list<int> l1;
    //尾插
    l1.push_back(1);
    l1.push_back(2);
    l1.push_back(3);
    l1.push_back(4);
    //头插
    l1.push_front(100);
    l1.push_front(200);
    l1.push_front(300);

    printList(l1);

    //尾删
    l1.pop_back();
    //头删
    l1.pop_front();
    printList(l1);
//    200 100 1 2 3
    l1.insert(l1.begin(), 1000);
//    1000 200 100 1 2 3
    printList(l1);
    list<int>::iterator it = l1.begin();
    l1.insert(++it, 2000);
//    1000 2000 200 100 1 2 3
    printList(l1);

    it = l1.begin();

    //删除
    l1.erase(it);
    //   2000 200 100 1 2 3
    printList(l1);

    l1.erase(++it);
    //   200 100 1 2 3
    printList(l1);

    it = l1.begin();
    l1.erase(++it);
    //   200 1 2 3
    printList(l1);

    //移除
    l1.push_back(200);
    l1.push_back(1);
//    200 1 2 3 200 1
    printList(l1);


    l1.remove(1);
//    200 2 3 200
    printList(l1);

    //clear
    l1.clear();
    printList(l1);

}

int main() {
    test();
    return 0;
}
300 200 100 1 2 3 4 
200 100 1 2 3 
1000 200 100 1 2 3 
1000 2000 200 100 1 2 3 
2000 200 100 1 2 3 
200 100 1 2 3 
200 1 2 3 
200 1 2 3 200 1 
200 2 3 200

```

list 反转和排序





**功能描述：**





* 将容器中的元素反转，以及将容器中的数据进行排序











**函数原型：**





* `reverse();`   //反转链表


* `sort();`        //链表排序


```
#include "iostream"
using namespace std;
#include "list"
#include "algorithm"
void printList(const list<int> &l)
{
    for (list<int>::const_iterator it = l.begin(); it != l.end(); it++)
    {
        cout << *it << " ";
    }
    cout << endl;
}
bool myCompare(int a, int b)
{
    return a > b;
}
void test()
{
    list<int> l;
    l.push_back(1);
    l.push_back(2);
    l.push_back(3);
    l.push_back(4);
    printList(l);
    //反转
    l.reverse();
    printList(l);

    //排序
    // sort(l.begin(), l.end()); list不支持随机访问,不能用sort,得用内置的排序算法
    l.sort(); // 1 2 3 4  默认从小到大排序
    printList(l);
    l.sort(myCompare); //指定规则，从大到小
    printList(l);
}
int main()
{
    test();
    return 0;
}
```

总结：





* 反转   --- reverse


* 排序   --- sort （成员函数）

#### 3.7.8 排序案例

案例描述：将Person自定义数据类型进行排序，Person中属性有姓名、年龄、身高

排序规则：按照年龄进行升序，如果年龄相同按照身高进行降序

```
#include "iostream"
#include "list"
using namespace std;
//排序案例
class Person
{
public:
    Person(string name, int age, int height)
    {
        mage = age;
        mname = name;
        mheight = height;
    }
    string mname;
    int mage;
    int mheight;
};

void printList(const list<Person> &l)
{
    for (list<Person>::const_iterator it = l.begin(); it != l.end(); it++)
    {
        cout << it->mname << " " << it->mage << " " << it->mheight << endl;
    }
}
bool myCompare(const Person &p1, const Person &p2)
{
    if (p1.mage == p2.mage)
    {
        return p1.mheight > p2.mheight;
    }
    return p1.mage < p2.mage;
}
void test()
{
    Person p1("a", 33, 178);
    Person p2("b", 45, 172);
    Person p3("c", 22, 183);
    Person p4("d", 22, 156);
    Person p5("e", 22, 188);

    list<Person> l;
    l.push_back(p1);
    l.push_back(p2);
    l.push_back(p3);
    l.push_back(p4);
    l.push_back(p5);

    cout << "before sort:" << endl;
    printList(l);
    l.sort(myCompare);
    cout << "after sort:" << endl;
    printList(l);
}
int main()
{
    test();
    return 0;
}
before sort:
a 33 178
b 45 172
c 22 183
d 22 156
e 22 188
after sort:
e 22 188
c 22 183
d 22 156
a 33 178
b 45 172
```

总结：

- 对于自定义数据类型，必须要指定排序规则，否则编译器不知道如何进行排序

- 高级排序只是在排序规则上再进行一次逻辑规则制定，并不复杂

### 3.8 set/ multiset 容器

#### 3.8.1 set基本概念

**简介：**

- 所有元素都会在插入时自动被排序

**本质：**

- set/multiset属于**关联式容器**，底层结构是用**二叉树**实现。

**set和multiset区别**：

- set不允许容器中有重复的元素

- multiset允许容器中有重复的元素

#### 3.8.2 set构造和赋值

功能描述：创建set容器以及赋值

构造：

- set<T> st; //默认构造函数：

- set(const set &st); //拷贝构造函数

赋值：

- set& operator=(const set &st); //重载等号操作符

```
#include "iostream"
#include "set"
using namespace std;
// set
void printSet(const set<int> s)
{
    for (set<int>::iterator it = s.begin(); it != s.end(); it++)
    {
        cout << *it << " ";
    }
    cout << endl;
}

void test()
{
    set<int> s;
    s.insert(1);
    s.insert(3);
    s.insert(43);
    s.insert(33);
    s.insert(2);
    printSet(s); //排好序的 默认是从小打到

    set<int> s2(s);
    printSet(s2);

    set<int> s3;
    s3 = s2;
    printSet(s3);
}
int main()
{
    test();
    return 0;
}
```

总结：

- set容器插入数据时用insert

- set容器插入数据的数据会自动排序

#### 3.8.3 set大小和交换

**功能描述：**

- 统计set容器大小以及交换set容器

**函数原型：**

- size(); //返回容器中元素的数目

- empty(); //判断容器是否为空

- swap(st); //交换两个集合容器

```
#include "iostream"
#include "set"
using namespace std;
// set
void printSet(const set<int> s)
{
    for (set<int>::iterator it = s.begin(); it != s.end(); it++)
    {
        cout << *it << " ";
    }
    cout << endl;
}

void test()
{
    set<int> s;
    s.insert(1);
    s.insert(3);
    s.insert(43);
    s.insert(33);
    s.insert(2);
    printSet(s); //排好序的 默认是从小打到

    set<int> s2(s);
    printSet(s2);

    set<int> s3;
    s3 = s2;
    printSet(s3);
}
//大小
void test2()
{
    set<int> s1;
    s1.insert(1);
    s1.insert(3);
    s1.insert(2);
    s1.insert(4);
    if (s1.empty())
    {
        cout << "empty" << endl;
    }
    else
    {
        cout << "not empty,size is " << s1.size() << endl;
    }
}
//交换
void test3()
{
    set<int> s1;
    s1.insert(1);
    s1.insert(3);
    s1.insert(2);
    s1.insert(4);
    set<int> s2;
    s2.insert(100);
    s2.insert(300);
    s2.insert(200);
    s2.insert(400);
    cout << "before swap" << endl;
    printSet(s1);
    printSet(s2);
    cout << endl;
    cout << "after swap" << endl;
    s1.swap(s2);
    printSet(s1);
    printSet(s2);
}
int main()
{
    test();
    test2();
    test3();
    return 0;
}
```

总结：

- 统计大小 --- size

- 判断是否为空 --- empty

- 交换容器 --- swap

#### 3.8.4 set插入和删除

**功能描述：**

- set容器进行插入数据和删除数据

**函数原型：**

- insert(elem); //在容器中插入元素。

- clear(); //清除所有元素

- erase(pos); //删除pos迭代器所指的元素，返回下一个元素的迭代器。

- erase(beg, end); //删除区间[beg,end)的所有元素 ，返回下一个元素的迭代器。

- erase(elem); //删除容器中值为elem的元素。

```
#include "iostream"
#include "set"
using namespace std;
// set
void printSet(const set<int> s)
{
    for (set<int>::iterator it = s.begin(); it != s.end(); it++)
    {
        cout << *it << " ";
    }
    cout << endl;
}

void test()
{
    set<int> s1;
    //插入
    s1.insert(10);
    s1.insert(30);
    s1.insert(20);
    s1.insert(40);
    printSet(s1);

    //删除
    s1.erase(s1.begin());
    printSet(s1);

    s1.erase(30);
    printSet(s1);

    //清空
    // s1.erase(s1.begin(), s1.end());//等同下面
    s1.clear();
    printSet(s1);
}
int main()
{
    test();
    return 0;
}
```

总结：

- 插入 --- insert

- 删除 --- erase

- 清空 --- clear

#### 3.8.5 set查找和统计

**功能描述：**

- 对set容器进行查找数据以及统计数据

**函数原型：**

- find(key); //查找key是否存在,若存在，返回该键的元素的迭代器；若不存在，返回set.end();

- count(key); //统计key的元素个数

```
#include "iostream"
using namespace std;
#include "set"
void test()
{
    set<int> s;
    s.insert(1);
    s.insert(2);
    s.insert(3);
    s.insert(4);

    //查找
    set<int>::iterator it = s.find(3);
    if (it != s.end())
    {
        cout << "found" << endl;
    }
    else
    {
        cout << "not found" << endl;
    }

    //统计
    int num = s.count(2);
    cout << "num:" << num << endl;
}
int main()
{
    test();
    return 0;
}
```

总结：

- 查找 --- find （返回的是迭代器）

- 统计 --- count （对于set，结果为0或者1）

#### 3.8.6 set和multiset区别

**学习目标：**

- 掌握set和multiset的区别

**区别：**

- set不可以插入重复数据，而multiset可以

- set插入数据的同时会返回插入结果，表示插入是否成功

- multiset不会检测数据，因此可以插入重复数据

```
#include "iostream"
#include "set"
using namespace std;
// set 和 multiset 区别
void test()
{
    set<int> s;
    pair<set<int>::iterator, bool> ret = s.insert(10);
    if (ret.second)
    {
        cout << "fisrt insert ok" << endl;
    }
    else
    {
        cout << "fisrt insert not ok" << endl;
    }
    ret = s.insert(10);
    if (ret.second)
    {
        cout << "second insert ok" << endl;
    }
    else
    {
        cout << "second insert not ok" << endl;
    }

    // multiset
    multiset<int> ms;
    ms.insert(1);
    ms.insert(2);
    ms.insert(1);
    for (multiset<int>::iterator it = ms.begin(); it != ms.end(); it++)
    {
        cout << *it << " ";
    }
    cout << endl;
}
int main()
{
    test();
    return 0;
}
fisrt insert ok
second insert not ok
1 1 2
```

总结：

- 如果不允许插入重复数据可以利用set

- 如果需要插入重复数据利用multiset

#### 3.8.7 pair对组创建

**功能描述：**

- 成对出现的数据，利用对组可以返回两个数据

**两种创建方式：**

- pair<type, type> p ( value1, value2 );

- pair<type, type> p = make_pair( value1, value2 );

```
#include "iostream"
using namespace std;
//创建对组
void test()
{
    pair<string, int> p("aa", 1);
    cout << p.first << "  " << p.second << endl;

    pair<int, char> p2 = make_pair(1, 'c');
    cout << p2.first << "  " << p2.second << endl;
}
int main()
{
    test();
    return 0;
}
aa  1
1  c
```

总结：

两种方式都可以创建对组，记住一种即可

#### 3.8.8 set容器排序

学习目标：

- set容器默认排序规则为从小到大，掌握如何改变排序规则

主要技术点：

- 利用仿函数，可以改变排序规则

```
#include "iostream"
#include "set"
using namespace std;
class MyCompare
{
public:
    bool operator()(int a, int b)
    {
        return a > b;
    }
};
void test()
{
    set<int> s1;
    s1.insert(10);
    s1.insert(40);
    s1.insert(20);
    s1.insert(30);
    s1.insert(50);

    //默认从小到大
    for (set<int>::iterator it = s1.begin(); it != s1.end(); it++)
    {
        cout << *it << " ";
    }
    cout << endl;

    //指定排序规则
    set<int, MyCompare> s2;
    s2.insert(10);
    s2.insert(40);
    s2.insert(20);
    s2.insert(30);
    s2.insert(50);

    for (set<int, MyCompare>::iterator it = s2.begin(); it != s2.end(); it++)
    {
        cout << *it << " ";
    }
    cout << endl;
}
int main()
{
    test();
    return 0;
}
10 20 30 40 50 
50 40 30 20 10
```

总结：利用仿函数可以指定set容器的排序规则

**示例二** set存放自定义数据类型

```
#include "iostream"
#include "set"
using namespace std;
class Person
{
public:
    Person(string name, int age)
    {
        m_Name = name;
        m_Age = age;
    }
    string m_Name;
    int m_Age;
};

class comparePerson
{
public:
    bool operator()(const Person &p1, const Person &p2)
    {
        return p1.m_Age > p2.m_Age;
    }
};

void test()
{
    set<Person, comparePerson> s;

    Person p1("fad", 23);
    Person p2("sgdf", 27);
    Person p3("hgf", 25);
    Person p4("grwe", 21);

    s.insert(p1);
    s.insert(p2);
    s.insert(p3);
    s.insert(p4);

    for (set<Person, comparePerson>::iterator it = s.begin(); it != s.end(); it++)
    {
        cout << "name: " << it->m_Name << " age: " << it->m_Age << endl;
    }
}
int main()
{
    test();
    return 0;
}
name: sgdf age: 27
name: hgf age: 25
name: fad age: 23
name: grwe age: 21
```

总结：

对于自定义数据类型，set必须指定排序规则才可以插入数据

### 3.9 map/ multimap容器

#### 3.9.1 map基本概念

**简介：**

- map中所有元素都是pair

- pair中第一个元素为key（键值），起到索引作用，第二个元素为value（实值）

- 所有元素都会根据元素的键值自动排序

**本质：**

- map/multimap属于**关联式容器**，底层结构是用二叉树实现。

**优点：**

- 可以根据key值快速找到value值

map和multimap**区别**：

- map不允许容器中有重复key值元素

- multimap允许容器中有重复key值元素

#### 3.9.2 map构造和赋值

**功能描述：**

- 对map容器进行构造和赋值操作

**函数原型：**

**构造：**

- map<T1, T2> mp; //map默认构造函数:

- map(const map &mp); //拷贝构造函数

**赋值：**

- map& operator=(const map &mp); //重载等号操作符