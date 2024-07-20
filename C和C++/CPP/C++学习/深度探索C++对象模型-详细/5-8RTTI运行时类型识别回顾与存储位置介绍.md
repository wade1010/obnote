（1）RTTI（运行时类型识别）简单回顾

C++运行时类型识别RTTI，要求父类中必须至少有一个虚函数，如果父类中没有虚函数，那么得到RTTI就不准确；

RTTI就可以在执行期间查询一个多态指针，或者多态引用的信息；

RTTI能力靠typeid和dynamic_cast运算符来体现。

typeid(*pb).name()

typeid(ra).name()

Derive * pderive = dynamic_cast<Derive *>(pb);

if( pdrive != nullptr ){

//是一个Derive类型

pderive->func();//可以调用自己的专属函数

}

（2）RTTI实现原理

typeid返回的是一个常来那个对象的引用，这个常量对象的类型一般是type_info （是一个类）：

const std::type_info & tp = typeid(*pb);

系统中是有一个地方存放信息的，上面用tp引用查看

存储位置：

![](https://gitee.com/hxc8/images2/raw/master/img/202407172212336.jpg)

```
printf("tp2地址为：%p\n",&tp2);
long *pvptr = (long *)pb2;
long *vptr = (long *)(*pvptr);
printf("虚函数表首地址为:%p\n",vptr);
printf("虚函数表首地址之前一个地址为:%p\n",vptr-1);//实际往上走了4字节(32位系统)
long *prrtiinfo = (long *)(*(vptr-1));
prrtiinfo+=3;//就是跳了12字节
long *ptypeinfoaddr = (long *)(*prrtiinfo);
const std::type_info *ptypeinfoaddrreal = (const std::type_info *)ptypeinfoaddr;
printf("ptypeinfoaddrreal地址为：%p\n",ptypeinfoaddrreal);//这里地址和第一行的tp2地址一样就是对的
count<< ptypeinfoaddrreal->name() <<endl;
```