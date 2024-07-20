![](https://gitee.com/hxc8/images2/raw/master/img/202407172222898.jpg)

![](https://gitee.com/hxc8/images2/raw/master/img/202407172222366.jpg)

注意：

 

![](https://gitee.com/hxc8/images2/raw/master/img/202407172222786.jpg)

、

![](https://gitee.com/hxc8/images2/raw/master/img/202407172222989.jpg)

上图的

CGirl g=8; ///错误，这个只限，只有Cgirl(int bh)；没有其它可以转换的，比如Cgirl(double bh)；  如果有，就转换为double,然后调用构造函数。

![](https://gitee.com/hxc8/images2/raw/master/img/202407172222345.jpg)

![](https://gitee.com/hxc8/images2/raw/master/img/202407172222728.jpg)

![](https://gitee.com/hxc8/images2/raw/master/img/202407172222978.jpg)

string name = "XXX" 相当于把字符指针转化为string类的对象，但是C++中没有提供反方向的转换，不能把string类的对象转换成字符指针，而是提供一个c_str()的成员函数，它可以返回字符指针。