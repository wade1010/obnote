![](https://gitee.com/hxc8/images3/raw/master/img/202407172248096.jpg)

```
use std::mem::size_of;

static B: [u8; 10] = [99, 97, 114, 114, 121, 116, 111, 119, 101, 108];
static C: [u8; 11] = [116, 104, 97, 110, 107, 115, 102, 105, 115, 104, 0];
fn main() {
    let a: usize = 42;
    let b: Box<[u8]> = Box::new(B);
    let c: &[u8; 11] = &C;

    println!("a (unsigned 整数)");
    println!("  地址：{:p}", &a); //a的地址
    println!("  大小：{:?} bytes", size_of::<usize>()); //8字节
    println!("  值：{:?}\n", a); //42

    println!("B (10 bytes 的数组)");
    println!("  地址：{:p}", &B);
    println!("  大小:{:?} bytes", size_of::<[u8; 10]>()); //10字节
    println!("  值:{:?}\n", B);

    println!("C (11 bytes 的数字)");
    println!("  地址:{:p}", &C); //C的地址
    println!("  大小:{:?} bytes", size_of::<[u8; 11]>()); //11字节
    println!("  值：{:?}", C);

    println!("b (B装在Box 里)");
    println!("  地址：{:p}", &b); //B的地址
    println!("  大小：{:?} bytes:", size_of::<Box<[u8]>>()); //16字节
    println!("  指向的地址：{:p}\n", b); //b指向的地址。b里面存的值就是它指向的地址

    println!("c (C的引用)");
    println!("  地址：{:p}", &c); //C的地址
    println!("  大小：{:?} bytes", size_of::<&[u8; 11]>()); //8字节
    println!("  指向的地址：{:p}\n", c); //c指向的地址
}


a (unsigned 整数)
  地址：0x7ffee1661298
  大小：8 bytes
  值：42

B (10 bytes 的数组)
  地址：0x10e5d9390
  大小:10 bytes
  值:[99, 97, 114, 114, 121, 116, 111, 119, 101, 108]

C (11 bytes 的数字)
  地址:0x10e5d939a
  大小:11 bytes
  值：[116, 104, 97, 110, 107, 115, 102, 105, 115, 104, 0]
b (B装在Box 里)
  地址：0x7ffee16612a0
  大小：16 bytes:
  指向的地址：0x7f8ab7c05bb0

c (C的引用)
  地址：0x7ffee16612c0
  大小：8 bytes
  指向的地址：0x10e5d939a
```

![](https://gitee.com/hxc8/images3/raw/master/img/202407172249418.jpg)

```
use std::borrow::Cow;
use std::ffi::CStr;
use std::os::raw::c_char;

static B: [u8; 10] = [99, 97, 114, 114, 121, 116, 111, 119, 101, 108];
static C: [u8; 11] = [116, 104, 97, 110, 107, 115, 102, 105, 115, 104, 0];
fn main() {
    let a = 42;
    let b: String;
    let c: Cow<str>;
    unsafe {
        let b_ptr = &B as *const u8 as *mut u8;
        b = String::from_raw_parts(b_ptr, 10, 10);

        let c_ptr = &C as *const u8 as *const c_char;
        c = CStr::from_ptr(c_ptr).to_string_lossy();
    }
    println!("a:{}\nb:{}\nc:{}", a, b, c);
}


a:42
b:carrytowel
c:thanksfish
pointer_demo1(13893,0x10d2e8dc0) malloc: *** error for object 0x10093b3c0: pointer being freed was not allocated
pointer_demo1(13893,0x10d2e8dc0) malloc: *** set a breakpoint in malloc_error_break to debug

后面的报错先不用管
```

![](https://gitee.com/hxc8/images3/raw/master/img/202407172249230.jpg)

![](https://gitee.com/hxc8/images3/raw/master/img/202407172249826.jpg)

```
fn main() {
    let a: i64 = 42;
    let a_ptr = &a as *const i64;
    println!("a: {} ({:p})", a, a_ptr);
}

a: 42 (0x7ffee02ed760)
```

![](https://gitee.com/hxc8/images3/raw/master/img/202407172249419.jpg)

```
fn main() {
    // let a: i64 = 42;
    // let a_ptr = &a as *const i64;
    // println!("a: {} ({:p})", a, a_ptr);

    let a: i64 = 42;
    let a_ptr = &a as *const i64;
    let a_addr: usize = unsafe { std::mem::transmute(a_ptr) };
    println!(
        "a: {} ({:p}...0x{:x}...0x{:x})",
        a,
        a_ptr,
        a_addr,
        a_addr + 7
    );
}

a: 42 (0x7ffee9f7b730...0x7ffee9f7b730...0x7ffee9f7b737)
```

![](https://gitee.com/hxc8/images3/raw/master/img/202407172249016.jpg)

![](https://gitee.com/hxc8/images3/raw/master/img/202407172249368.jpg)

![](https://gitee.com/hxc8/images3/raw/master/img/202407172249892.jpg)

![](https://gitee.com/hxc8/images3/raw/master/img/202407172249299.jpg)

![](https://gitee.com/hxc8/images3/raw/master/img/202407172249822.jpg)