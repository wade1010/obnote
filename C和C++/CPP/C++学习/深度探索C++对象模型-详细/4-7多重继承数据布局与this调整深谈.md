（1）单一继承数据成员布局this指针知识补充

父类没有虚函数，子类有虚函数，父类this地址是子类首地址+1（偏移一个虚函数指针的大小）这个地址偏移调整是编译器内部帮我们做好了

父类有虚函数，子类有没有都可以，父类this地址就是虚函数指针的地址

![](https://gitee.com/hxc8/images2/raw/master/img/202407172212983.jpg)

（2）多重继承且父类都带虚函数的数据成员布局

![](https://gitee.com/hxc8/images2/raw/master/img/202407172212487.jpg)

1 通过this指针打印，我们看到访问Base1成员不用跳，访问Base2成员要this指针偏移（跳过）8字节

2 我们看到偏移值，m_bi和m_b2i偏移都是4（相对他们自己的this）

3 this指针，加上偏移值，就能访问对应的成员变量，比如m_b2i = this指针 + 偏移值

结论：

我们要访问一个类对象的成员，成员的定位是通过this指针（编译器会自动调整）以及该成员的偏移值，这两个因素来定位，

这种this指针偏移的调整，都需要编译器介入来处理完成。

下面比较有趣的探索：

myobj是子类对象

Base1 *pbase1 = &myobj;// pbase1和myobj地址相同

Base2 *pbase2 = &myobj; //this指针调整导致pbase2实际是向前走8个字节的内存位置的

 //myobj = 0x0093fad0,经过本语句后，pbase2 = 0x0093fad8

//站在编译器视角，上面语句就是  Base2 *pbase2 = (Base2 *) (((char *)&myobj)+sizeof(Base1));

再测试：

Base2 *pbase2 = new MYCLS(); //父类指针new子类对象，这里new出来的是24字节,但是返回给pbase2的指针是指向new出来的对象首地址偏移8个字节后的地址

MYCLS *psubobj = (MYCLS *)pbase2;//转回去的时候，又把pbase2的地址往回偏移了8个字节，也就是开始new出来对象的首地址

更有意思的是

delete pbase2;  //报异常，所以我们认为pbase2里边返回的地址不是分配的首地址，而是偏移后地址，而真正分配的首地址应该在psubobj里面的这个地址，则下面delete是在很缺的。

delete psubobj;