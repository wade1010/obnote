   [https://cloud.fynote.com/share/d/2747](https://cloud.fynote.com/share/d/2747)

![](https://gitee.com/hxc8/images2/raw/master/img/202407172212106.jpg)

总结：

![](https://gitee.com/hxc8/images2/raw/master/img/202407172212514.jpg)

因为在insert时，vector可能需要进行扩容，而扩容的本质是new一块新的空间，再将数据迁移过去。而我们知道，迭代器的内部是通过指针访问容器中的元素的，而插入后，若vector扩容，则原有的数据被释放，指向原有数据的迭代器就成了野指针，所以迭代器失效了。

### 10.2.3 deque容器

deque简介：

- deque是“double-ended queue”的缩写，和vector一样都是STL的容器，deque是双端数组，而vector是单端的。

- deque在接口上和vector非常相似，在许多操作的地方可以直接替换。

- deque可以随机存取元素（支持索引值直接存取， 用[]操作符或at()方法，

- deque头部和尾部添加或移除元素都非常快速。但是在中部安插元素或移除元素比较费时。

deque与vector在操作上几乎一样，deque多两个函数：

- deque.push_front(elem); //在容器头部插入一个数据

- deque.pop_front(); //删除容器第一个数据

### 10.2.4 list容器

1、list简介

- list是一个双向链表容器，可高效地进行插入删除元素。

- list不可以随机存取元素，所以不支持at.(pos)函数与[]操作符。It++(ok) it+5(err)

### 10.2.5 stack容器

1、Stack简介

- stack是堆栈容器，是一种“先进后出”的容器。

- stack是简单地装饰deque容器而成为另外的一种容器。

### 10.2.6 queue 容器

1、Queue简介

- queue是队列容器，是一种“先进先出”的容器。

### 10.2.7 Set和multiset容器

1、set/multiset的简介

- set是一个集合容器，其中所包含的**元素是唯一的**，集合中的**元素按一定的顺序排列**。元素插入过程是按排序规则插入，所以不能指定插入位置。

- set采用红黑树变体的数据结构实现，红黑树属于平衡二叉树。在插入操作和删除操作上比vector快。

- set不可以直接存取元素。（不可以使用at.(pos)与[]操作符）。

- multiset与set的区别：set支持唯一键值，每个元素值只能出现一次；而multiset中同一值可以出现多次。

- 不可以直接修改set或multiset容器中的元素值，因为该类容器是自动排序的。如果希望修改一个元素值，必须先删除原有的元素，再插入新的元素。

### 10.2.8 map和multimap容器

1、map/multimap的简介

- map是标准的关联式容器，一个map是一个键值对序列，即(key,value)对。它提供基于key的快速检索能力。

- map中key值是唯一的。集合中的元素按一定的顺序排列。元素插入过程是按排序规则插入，所以不能指定插入位置。

- map的具体实现采用红黑树变体的平衡二叉树的数据结构。在插入操作和删除操作上比vector快。

- map可以直接存取key所对应的value，支持[]操作符，如map[key]=value(将key键所对应的值修改为value)

- multimap与map的区别：map支持唯一键值，每个键只能出现一次；而multimap中相同键可以出现多次。multimap不支持[]操作符。