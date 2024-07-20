（1）重载全局operator new 和operator delete操作符

重载全局operatornew[]和operator delete[]操作符

```
A *pa = new A();
  operator new();
    malloc();
  A::A();
----------------------
delete pa;
  A::~A();
  operator delete();
    free();

------------------------
A * pa = new A[3]();
  operator new[];
    malloc();
  A::A();
  A::A();
  A::A();
  
-----------------------
delete []  pa;
  A::~A();
  A::~A();
  A::~A();
  operator delete[];
    free();
```

```
void *operator new(size_t size){
    return malloc(size);
}

void *operator new [](seze_t size){
    return malloc(size);
}
void operator delete(void * phead){
    free(phead);
}
void operator delete[](void * phead){
    free(phead);
}
```

二、定位new (placement new)

有placement new,但是没有对应的placement delete

功能：在已经分配的原始内存中初始化一个对象；

a)已经分配，定位new并不分配内存，你需要提前将这个定位new要使用的内存分配出来

b)初始化一个对象（初始化一个对象的内存），我们就理解成调用这个对象的构造函数；

说白了，定位new就是能够在一个预先分配好的内存地址中构造一个对象；

格式：

new(地址) 类类型(参数...)

```
class A{
    public:
        int m_a;
        A():m_a(0){
            int test;
            test =1;        
        }
        A(int tv):m_a(tv){
             int test;
             test = 1;       
        }
        ~A(){}
};

void func(){
    void * p = (void *) new char[sizeof(A)];//内存必须事先分配出来
    //使用定位new
    A *pa = new(p) A();//调用午餐构造函数，这里并不会额外分配内存

    void *p2 = (void *)new char[sizeof(A)];
    A * pa2 = new(p2) A(19);//调用带一个参数的构造函数，这里并不会额外分配内存
    
    //释放
//直接delete可行
//   delete pa;
//    delete pa2;

//跟new配套的使用
    pa->~A();//手工调用析构函数
    pa2->~A();
    delete[] (void *)pa;
    delete[] (void *)pa2;
}
```

定位new操作符的重载

```
class A{
    public:
        ......
    void * operator new(size_t size,void *phead){
        //code
        return phead;//收到内存开始地址，只需要原样返回即可    
    }
};
```