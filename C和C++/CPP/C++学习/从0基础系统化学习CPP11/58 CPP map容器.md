58 CPP map容器

map容器封装了红黑树（平衡二叉排序树），用于查找。

包含头文件 #include <map>

map容器的元素是pair键值对

map类模板的声明：

template<class K,class V,class P=less<K>,class Allocator<pair<const K,V>>>

class map:public_Tree<_Tmap_traits<K,V,P,_Alloc,false>>

{

.....

}

第一个模板参数K：key的数据类型（pair.first）

第二个模板参数V：value的数据类型（pair.second）

第三个模板参数P：排序方法，缺省按key升序。

第四个模板参数_Alloc:分配器，缺省用new和delete.

map提供了双向迭代器。

## **元素操作**

V &operator[](K key);             // 用给定的key访问元素。

const V &operator[](K key) const;  // 用给定的key访问元素，只读。

V &at(K key);                     // 用给定的key访问元素。

const V &at(K key) const;         // 用给定的key访问元素，只读。

注意：

1）[ ]运算符：如果指定键不存在，会向容器中添加新的键值对；如果指定键不存在，则读取或修改容器中指定键的值。

2）at()成员函数：如果指定键不存在，不会向容器中添加新的键值对，而是直接抛出out_of_range 异常。