shared_ptr共享它指向的对象，多个shared_ptr可以指向（关联）相同的对象，在内部采用计数机制来实现。

当新的shared_ptr与对象关联时，引用计数增加1。

当shared_ptr超出作用域时，引用计数减1。当引用计数变为0时，则表示没有任何shared_ptr与对象关联，则释放该对象。

一、基本用法

shared_ptr的构造函数也是explicit，但是没有删除拷贝构造函数和赋值函数

初始化方法，前3中和unique_ptr一样不同的是，C++11标准就提供了make_shared()函数，而make_unique()函数是C++14标准才提供的。

推荐使用make_shared()

1）初始化

方法一：

shared_ptr<AA> p0(new AA("西施"));     // 分配内存并初始化。

方法二：

shared_ptr<AA> p0 = make_shared<AA>("a");  // C++11标准，效率更高。

shared_ptr<int> pp1=make_shared<int>();         // 数据类型为int。

shared_ptr<AA> pp2 = make_shared<AA>();       // 数据类型为AA，默认构造函数。

shared_ptr<AA> pp3 = make_shared<AA>("a");  // 数据类型为AA，一个参数的构造函数。

shared_ptr<AA> pp4 = make_shared<AA>("a",8); // 数据类型为AA，两个参数的构造函数。

方法三：

AA* p = new AA("a");

shared_ptr<AA> p0(p);                  // 用已存在的地址初始化。

方法四：

shared_ptr<AA> p0(new AA("西施"));

shared_ptr<AA> p1(p0);                 // 用已存在的shared_ptr初始化，计数加1。

shared_ptr<AA> p1=p0;                 // 用已存在的shared_ptr初始化，计数加1。

方法四是和unique_ptr不同的，因为shared_ptr没有禁用拷贝构造和赋值

2）使用方法

- 智能指针重载了*和->操作符，可以像使用指针一样使用shared_ptr。

- use_count()方法返回引用计数器的值。

- unique()方法，如果use_count()为1，返回true，否则返回false。

- shared_ptr支持赋值，左值的shared_ptr的计数器将减1，右值shared_ptr的计算器将加1。

- get()方法返回裸指针。

- 不要用同一个裸指针初始化多个shared_ptr。

- 不要用shared_ptr管理不是new分配的内存。

3）用于函数的参数

与unique_ptr的原理相同。

4）不支持指针的运算（+、-、++、--）

二、更多细节

1 跟unique_ptr不同，没有禁用赋值和拷贝构造

2 用nullptr给shared_ptr赋值将把计数减1，如果计数为0，将释放对象，空的shared_ptr==nullptr

3 跟unique_ptr不同，没有release()函数，因为不需要，共享的，不能由某个shared_ptr决定。

4 std:move()可以转移对原始指针的控制权，还可以将unique_ptr转移成shared_ptr。（反过来不行）

5 reset()改变与资源的关联关系

pp.reset();        // 解除与资源的关系，资源的引用计数减1。

pp. reset(new AA("bbb"));  // 解除与资源的关系，资源的引用计数减1。关联新资源。

6 swap()交换两个控制权

7shared_ptr也可以像普通指针那样，当指向一个类继承体系的基类对象时，也具有多态薪资，如同使用了裸指针管理基类和派生类对象那样。

8 shared_ptr不是绝对安全的，如果程序中调用exit()退出，全局的share_ptr可以自动释放，但局部的shared_ptr无法释放。

9 shared_ptr提供了支持数组的具体化版本。

数组版本的shared_ptr，重载了操作符[]，操作符[]返回的是引用，可以作为左值使用。

10 shared_ptr的线程安全性：

- shared_ptr的引用计数本身是线程安全的（引用计数是原子操作）。

- 多个线程同时读一个shared_ptr对象时线程安全的。

- 如果是多个线程对同一个shared_ptr对象进行对和写，则需要加锁。

- 多线程读写shared_ptr所指向的同一个对象，不管是相同的shared_ptr对象，还是不同的shared_ptr对象，也需要加锁保护。

11 如果unique_ptr能解决问题，就不要用shared_ptr.unique_ptr的效率更高，占用的资源更少。