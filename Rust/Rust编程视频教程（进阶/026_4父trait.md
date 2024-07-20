026_4父trait

```
//4 父 trait 用于在另外一个 trait 中使用某trait 的 功能
//有时我们可能会需要某个trait 使用另外一个trait的功能。
//在这种情况下，需要能够依赖相关的trait也被实现
//这个所需的trait 是我们实现的trait的父(超)trait(super trait)
use std::fmt;
use std::fmt::Formatter;

//要求实现Display trait
trait OutPrint: fmt::Display {
    fn out_print(&self) {
        let output = self.to_string();
        println!("output:{}", output);
    }
}
struct Point {
    x: i32,
    y: i32,
}

impl OutPrint for Point {}

impl fmt::Display for Point {
    fn fmt(&self, f: &mut Formatter<'_>) -> fmt::Result {
        write!(f, "(x{},y{})", self.x, self.y)
    }
}

fn main() {}

```