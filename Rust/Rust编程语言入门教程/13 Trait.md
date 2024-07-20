![](https://gitee.com/hxc8/images4/raw/master/img/202407172311550.jpg)

![](https://gitee.com/hxc8/images4/raw/master/img/202407172311612.jpg)

![](https://gitee.com/hxc8/images4/raw/master/img/202407172311132.jpg)

```
pub trait Summary {
    fn summarize(&self) -> String;
}

pub struct NewsArticle {
    pub headline: String,
    pub location: String,
    pub author: String,
    pub content: String,
}

impl Summary for NewsArticle {
    fn summarize(&self) -> String {
        format!("{},by {} ({})", self.headline, self.author, self.location)
    }
}

pub struct Tweet {
    pub username: String,
    pub content: String,
    pub reply: bool,
    pub retweet: bool,
}

impl Summary for Tweet {
    fn summarize(&self) -> String {
        format!("{}:{}", self.username, self.content)
    }
}
```

```
extern crate demo;

use demo::{Tweet, Summary};

fn main() {
    let tweet = Tweet {
        username: "horse_ebooks".to_string(),
        content: "hello world".to_string(),
        reply: false,
        retweet: false,
    };
    println!("1 new tweet: {}", tweet.summarize());
}
```

![](https://gitee.com/hxc8/images4/raw/master/img/202407172311041.jpg)

![](https://gitee.com/hxc8/images4/raw/master/img/202407172311227.jpg)

![](https://gitee.com/hxc8/images4/raw/master/img/202407172311449.jpg)

![](https://gitee.com/hxc8/images4/raw/master/img/202407172311950.jpg)

![](https://gitee.com/hxc8/images4/raw/master/img/202407172311313.jpg)

![](https://gitee.com/hxc8/images4/raw/master/img/202407172311566.jpg)

![](https://gitee.com/hxc8/images4/raw/master/img/202407172311579.jpg)

![](https://gitee.com/hxc8/images4/raw/master/img/202407172311598.jpg)

![](https://gitee.com/hxc8/images4/raw/master/img/202407172311660.jpg)

![](https://gitee.com/hxc8/images4/raw/master/img/202407172311716.jpg)

![](https://gitee.com/hxc8/images4/raw/master/img/202407172311878.jpg)

但是多了之后函数签名就不美观，可以用where

![](https://gitee.com/hxc8/images4/raw/master/img/202407172311476.jpg)

![](https://gitee.com/hxc8/images4/raw/master/img/202407172311363.jpg)

![](https://gitee.com/hxc8/images4/raw/master/img/202407172311406.jpg)

![](https://gitee.com/hxc8/images4/raw/master/img/202407172311655.jpg)

![](https://gitee.com/hxc8/images4/raw/master/img/202407172311780.jpg)

![](https://gitee.com/hxc8/images4/raw/master/img/202407172311919.jpg)

报错如下

![](https://gitee.com/hxc8/images4/raw/master/img/202407172311064.jpg)

![](https://gitee.com/hxc8/images4/raw/master/img/202407172311230.jpg)

```
fn main() {
    let number_list = [34, 1, 3, 6];
    let result = largest(&number_list);
    println!("{}", result);
}

fn largest<T>(list: &[T]) -> T {
    let mut largest = list[0];
    for &item in list {//item不加&就是对&i32  在item前面价格&，&item类型就是i32 相当于发生了解构
        if item > largest {
            largest = item
        }
    }
    largest
}
这个代码会报错

error[E0369]: binary operation `>` cannot be applied to type `T`
  --> src/main.rs:10:17
   |
10 |         if item > largest {
   |            ---- ^ ------- T
   |            |
   |            T
   |
help: consider restricting type parameter `T`
   |
7  | fn largest<T: std::cmp::PartialOrd>(list: &[T]) -> T {
   |             ++++++++++++++++++++++

```

```
fn main() {
    let number_list = [34, 1, 3, 6];
    let result = largest(&number_list);
    println!("{}", result);
}

fn largest<T: PartialOrd>(list: &[T]) -> T {
    let mut largest = list[0];
    for &item in list {//item不加&就是对&i32  在item前面价格&，&item类型就是i32 相当于发生了解构
        if item > largest {
            largest = item
        }
    }
    largest
}

加了trait之后还报错

error[E0508]: cannot move out of type `[T]`, a non-copy slice
 --> src/main.rs:8:23
  |
8 |     let mut largest = list[0];
  |                       ^^^^^^^
  |                       |
  |                       cannot move out of here
  |                       move occurs because `list[_]` has type `T`, which does not implement the `Copy` trait
  |                       help: consider borrowing here: `&list[0]`

error[E0507]: cannot move out of a shared reference
 --> src/main.rs:9:18
  |
9 |     for &item in list {//item不加&就是对&i32  在item前面价格&，&item类型就是i32 相当于发生了解构
  |         -----    ^^^^
  |         ||
  |         |data moved here
  |         |move occurs because `item` has type `T`, which does not implement the `Copy` trait
  |         help: consider removing the `&`: `item`

Some errors have detailed explanations: E0507, E0508.
For more information about an error, try `rustc --explain E0507`.
error: could not compile `demo` due to 2 previous errors

```

给加上Copy这个trait 代码就可以了。但是仅限于存在栈上的类型，标量

```
fn main() {
    let number_list = [34, 1, 3, 6];
    let result = largest(&number_list);
    println!("{}", result);

    let char_list = ['1', 'a', 'c', 'b'];
    let result = largest(&char_list);
    println!("{}", result);
}

fn largest<T: PartialOrd + Copy>(list: &[T]) -> T {
    let mut largest = list[0];
    for &item in list {//item不加&就是对&i32  在item前面价格&，&item类型就是i32 相当于发生了解构
        if item > largest {
            largest = item
        }
    }
    largest
}
```

![](https://gitee.com/hxc8/images4/raw/master/img/202407172311399.jpg)

但是如果改成String类型呢，就报错

```
fn main() {
    /*let number_list = [34, 1, 3, 6];
    let result = largest(&number_list);
    println!("{}", result);

    let char_list = ['1', 'a', 'c', 'b'];
    let result = largest(&char_list);
    println!("{}", result);*/

    let string_list = [String::from("hello"), String::from("world")];
    let result = largest(&string_list);
    println!("{}", result);
}

fn largest<T: PartialOrd + Copy>(list: &[T]) -> T {
    let mut largest = list[0];
    for &item in list {//item不加&就是对&i32  在item前面价格&，&item类型就是i32 相当于发生了解构
        if item > largest {
            largest = item
        }
    }
    largest
}


error[E0277]: the trait bound `String: Copy` is not satisfied
  --> src/main.rs:11:26
   |
11 |     let result = largest(&string_list);
   |                  ------- ^^^^^^^^^^^^ the trait `Copy` is not implemented for `String`
   |                  |
   |                  required by a bound introduced by this call
   |
note: required by a bound in `largest`
  --> src/main.rs:15:28
   |
15 | fn largest<T: PartialOrd + Copy>(list: &[T]) -> T {
   |                            ^^^^ required by this bound in `largest`

```

```
fn main() {
    let string_list = [String::from("hello"), String::from("world")];
    let result = largest(&string_list);
    println!("{}", result);
}

fn largest<T: PartialOrd + Clone>(list: &[T]) -> T {
    let mut largest = list[0].clone();
    for item in list {
        if item > &largest {
            largest = item.clone();
        }
    }
    largest
}
```

![](https://gitee.com/hxc8/images4/raw/master/img/202407172311292.jpg)

![](https://gitee.com/hxc8/images4/raw/master/img/202407172311808.jpg)

```
use std::fmt::Display;

fn main() {
    let p = Pair::new(String::from("hello"), String::from("world"));
    p.cmp_display();

    // let p = Pair::new(vec!["hello"], vec!["world"]);
    // p.cmp_display();//报错 ^^^^^^^^^^^ method cannot be called on `Pair<Vec<&str>>` due to unsatisfied trait bounds
}

struct Pair<T> {
    y: T,
    x: T,
}

impl<T> Pair<T> {
    fn new(y: T, x: T) -> Self {
        return Self { y, x };
    }
}

impl<T: Display + PartialOrd> Pair<T> {
    fn cmp_display(&self) {
        if self.x >= self.y {
            println!("the largest number is {}", self.x);
        } else {
            println!("the largest number is {}", self.y);
        }
    }
}
```

![](https://gitee.com/hxc8/images4/raw/master/img/202407172311119.jpg)

**解释上面的意思，示例如下，对所有实现了Display的类型都实现ToString这个trait**

![](images/WEBRESOURCE0ce28f4aeb066061cbac7fa02a6bc35a截图.png)