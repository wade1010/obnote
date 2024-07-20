![](https://gitee.com/hxc8/images4/raw/master/img/202407172314376.jpg)

![](https://gitee.com/hxc8/images4/raw/master/img/202407172314616.jpg)

![](https://gitee.com/hxc8/images4/raw/master/img/202407172314949.jpg)

![](images/WEBRESOURCE69e1a07a61f6fa6ab61d9854f8aea102截图.png)

![](https://gitee.com/hxc8/images4/raw/master/img/202407172314516.jpg)

![](https://gitee.com/hxc8/images4/raw/master/img/202407172314608.jpg)

![](https://gitee.com/hxc8/images4/raw/master/img/202407172314879.jpg)

![](https://gitee.com/hxc8/images4/raw/master/img/202407172314085.jpg)

```
fn main() {
    let data = "hello world";
    let s = data.to_string();
    println!("{} {}", data, s);


    let mut s = String::from("foo");
    let s1 = String::from(" bar");
    s.push_str(&s1);
    println!("{}", s);


    let mut s = String::from("foo");
    s.push_str(" bar");
    println!("{}", s);

    
}

```

![](https://gitee.com/hxc8/images4/raw/master/img/202407172314373.jpg)

```
fn main() {
    let s1 = String::from("hello");
    let s2 = String::from(" world");
    let s3 = s1 + &s2;
    println!("{} {}", s2, s3);
}

OK的
```

![](https://gitee.com/hxc8/images4/raw/master/img/202407172314330.jpg)

```
fn main() {
    let s1 = String::from("hello");
    let s2 = String::from(" world");
    let s3 = s1 + &s2;
    println!("{} {}", s2, s3);
    // println!("{}", s1);//这一行会报错
}
因为+类似使用add(self,s:&str)->String{...}//注意这里self没有&

error[E0382]: borrow of moved value: `s1`
 --> src/main.rs:6:20
  |
2 |     let s1 = String::from("hello");
  |         -- move occurs because `s1` has type `String`, which does not implement the `Copy` trait
3 |     let s2 = String::from(" world");
4 |     let s3 = s1 + &s2;
  |              -- value moved here
5 |     println!("{} {}", s2, s3);
6 |     println!("{}", s1);//这一行会报错
  |                    ^^ value borrowed here after move
  |

```

另外有个问题s2是String类型，&s2是String类型的引用，而我们add方法的第二个参数是字符串切片，不是字符串的引用，为什么这里能编译通过呢?

![](https://gitee.com/hxc8/images4/raw/master/img/202407172314385.jpg)

```
fn main() {
    let s1 = String::from("1");
    let s2 = String::from("2");
    let s3 = String::from("3");

    // let s3 = s1 + "-" + &s2 + "-" + &s3;
    // println!("{}", s3);


    //比较灵活，不会获取任何参数的所有权
    let s = format!("{}-{}-{}", s1, s2, s3);
    println!("{} {} {} {}", s1, s2, s3, s);
}
```

![](https://gitee.com/hxc8/images4/raw/master/img/202407172314522.jpg)

![](https://gitee.com/hxc8/images4/raw/master/img/202407172314485.jpg)

![](https://gitee.com/hxc8/images4/raw/master/img/202407172314640.jpg)

![](https://gitee.com/hxc8/images4/raw/master/img/202407172314149.jpg)

![](https://gitee.com/hxc8/images4/raw/master/img/202407172314187.jpg)

![](https://gitee.com/hxc8/images4/raw/master/img/202407172314504.jpg)

上面是OK的，下面改变下4->3

![](https://gitee.com/hxc8/images4/raw/master/img/202407172314026.jpg)

编译时可以通过的，执行有问题。必须沿着字符边界切割。

![](https://gitee.com/hxc8/images4/raw/master/img/202407172314416.jpg)

![](https://gitee.com/hxc8/images4/raw/master/img/202407172315741.jpg)