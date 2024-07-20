012_1drop trait清理代码

```
//1 Drop trait 类似于其他语言的析构函数，当值离开作用域的时候执行此代码

struct Dog {
    name: String,
}
impl Drop for Dog {
    fn drop(&mut self) {
        println!("Dog {} leave", self.name);
    }
}

fn main() {
    let a = Dog {
        name: "wangcai".to_string(),
    };
    {
        let b = Dog {
            name: "dahuang".to_string(),
        };
        println!("0----------------------------");
    }
    println!("1----------------------------");
}

```

0----------------------------

Dog dahuang leave

1----------------------------

Dog wangcai leave