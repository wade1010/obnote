![](https://gitee.com/hxc8/images3/raw/master/img/202407172249150.jpg)

![](https://gitee.com/hxc8/images3/raw/master/img/202407172249735.jpg)

![](https://gitee.com/hxc8/images3/raw/master/img/202407172249976.jpg)

内存地址 就是内存中单个直接的一个数

指针，就是指向某种类型的一个内存地址

引用，就是指针，如果是动态大小的类型，就是指针和具有额外保证的一个整数。

![](https://gitee.com/hxc8/images3/raw/master/img/202407172249542.jpg)

对于在内存中没有固定长度的类型，rust会保证它的长度会保存在内部指针的附近，这样的话rust就可以保证这个程序永远不会超出类型在内存中的空间

![](https://gitee.com/hxc8/images3/raw/master/img/202407172249344.jpg)

```
static B: [u8; 10] = [99, 97, 114, 114, 121, 116, 111, 119, 101, 108];
static C: [u8; 11] = [116, 104, 97, 110, 107, 115, 102, 105, 115, 104, 0];
fn main() {
    let a = 42;
    let b = &B;
    let c = &C;
    println!("a:{}\nb:{:p}\nc:{:p}", a, b, c);
}

a:42
b:0x1076994c0
c:0x1076994ca
```

![](https://gitee.com/hxc8/images3/raw/master/img/202407172249502.jpg)

![](https://gitee.com/hxc8/images3/raw/master/img/202407172249989.jpg)