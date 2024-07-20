026_5newtype模式

```
/*5 newtype 模式用以在外部类型上实现外部trait
孤儿规则（orphan rule）：只要 trait 或者类型对于当前crate 是本地的话就可以在此类型上实现该trait。
一个绕开这个限制的方法就是使用newtype模式（newtype pattern）
 */
use std::fmt;
use std::fmt::Formatter;

//在这里Vec是外部类型，Display是外部特征，都不是本地定义的。如果直接为Vec使用Display这个特征，根据孤儿规则肯定会报错。
//这里就用Wrapper这个结构体包装了一层，作为Wrapper的成员，就绕开了整个规则，这样wrapper就是本地了，这样就能实现Display这个特征了
struct Wrapper(Vec<String>);

impl fmt::Display for Wrapper {
    fn fmt(&self, f: &mut Formatter<'_>) -> fmt::Result {
        write!(f, "({})", self.0.join(","))
    }
}
fn main() {
    let w = Wrapper(vec![String::from("hello"), String::from("world")]);
    println!("w:{}", w);
}

```

w:(hello,world)