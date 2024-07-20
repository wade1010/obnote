(1)new内存分配细探秘

```
void func(){
    char * p =new char[10];
    memset(p,0,10);
    delete [] p;
}
```

我们注意到：一块内存的回收，影响范围很广，远远不是一个10字节，而是一大片

new delete(malloc free) 内存没有看上去那么简单，它们的工作内部是很复杂的。

比如分配4个字节，需要在这4个字节周围，编译器做了很多处理，比如记录分配出去的字节数等等

![](https://gitee.com/hxc8/images2/raw/master/img/202407172212637.jpg)

分配内存时，为了记录和管理分配出去的内存，额外多分配了不少内存，造成了浪费：尤其是频繁的申请小块内存时，造成的浪费更明显、更严重

二、重载类中operator new和operator delete操作符

```
class A{
    public:
        static void * operator new(size_t size);
        static void operator delete(void * phead);
};
void * A::operator new(size_t size){
    //code
    A * p = (A*)malloc(size);
    return p;
}
void A::operator delete(void *phead){
    free(phead);
}
```

三、重载类中operator new[]和operator delete[]操作符

A *pa = new A[3]();  构造函数和析构函数被调用3次，但是operator new[]和operator delete[]仅仅被调用一次

假设A对象占用1字节，这new A[3]()应该是需要3个字节，

但是在new[]里面发现size是7，额外需要4个字节保存数组大小，这个4个字节是用户需要的内存前面分配的，不是后面

![](https://gitee.com/hxc8/images2/raw/master/img/202407172212692.jpg)

编译器在后面干了很多我们看不见的事，

如上图，我们申请3个对象的内存，编译器返回给我们的是红色的地址，这个地址向低地址偏移4个字节，记录数组的大小。

调用析构或者构造的时候，编译器以红色地址为基础，向低地址偏移4个字节，拿到数组大小，比如这里是3，构造的时候，就调用3次构造函数，析构的时候，就调用3次析构函数，