011_4实现deref trait

```
use std::ops::Deref;
struct MyBox<T>(T);

impl<T> MyBox<T> {
    fn new(x: T) -> MyBox<T> {
        MyBox(x)
    }
}
impl<T> Deref for MyBox<T> {
    type Target = T;
    //为什么返回引用呢？
    //解引用的时候一般不希望获得MyBox内部值的所有全，我们只希望使用它
    fn deref(&self) -> &Self::Target {
        &self.0 //元组第一个就是0
    }
}

fn main() {
    let x = 5;
    let y = MyBox::new(x);
    assert_eq!(5, x);
    assert_eq!(5, *y);
}

```