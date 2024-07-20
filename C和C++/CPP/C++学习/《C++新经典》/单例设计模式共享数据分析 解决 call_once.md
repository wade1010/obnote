单例设计模式共享数据问题分析、解决

面临的问题：需要我们自己创建的线程中来创建单例，这个线程不止一个。

```
static SingleCls *GetInstance()
    {
        // c++11之后这种写法是安全的,由static保证
        if (m_instance == nullptr)
        {
            m_instance = new SingleCls;
            static GC gc;
        }
        return m_instance;
    }
```

上面代码如果是C++11之前就需要加锁