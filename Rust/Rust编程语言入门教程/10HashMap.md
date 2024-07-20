![](https://gitee.com/hxc8/images4/raw/master/img/202407172313090.jpg)

![](https://gitee.com/hxc8/images4/raw/master/img/202407172313419.jpg)

![](https://gitee.com/hxc8/images4/raw/master/img/202407172313609.jpg)

```
use std::collections::HashMap;

fn main() {
    let teams = vec![String::from("Blue"), String::from("Yellow")];
    let initial_score = vec![10, 20];
    let scores: HashMap<_, _> = teams.iter().zip(initial_score.iter()).collect();
    println!("{:?}", scores);
} 
```

![](https://gitee.com/hxc8/images4/raw/master/img/202407172313769.jpg)

```
use std::collections::HashMap;

fn main() {
    let f_name = String::from("a");
    let f_value = String::from("b");
    let mut map = HashMap::new();
    map.insert(f_name, f_value);
    // println!("{} {}", f_name, f_value);//打开执行会报错
}

error[E0382]: borrow of moved value: `f_name`
 --> src/main.rs:8:23
  |
4 |     let f_name = String::from("a");
  |         ------ move occurs because `f_name` has type `String`, which does not implement the `Copy` trait
...
7 |     map.insert(f_name, f_value);
  |                ------ value moved here
8 |     println!("{} {}", f_name, f_value);
  |                       ^^^^^^ value borrowed here after move
  |

```

而对于实现了Copy Trait的使用如下，就不会报错

```
use std::collections::HashMap;

fn main() {
    let f_name = "a";
    let f_value = "b";
    let mut map = HashMap::new();
    map.insert(f_name, f_value);
    println!("{} {}", f_name, f_value);//打开执行会报错
}


```

![](https://gitee.com/hxc8/images4/raw/master/img/202407172313841.jpg)

![](https://gitee.com/hxc8/images4/raw/master/img/202407172313083.jpg)

```
use std::collections::HashMap;

fn main() {
    let f_name = String::from("a");
    let f_value = String::from("b");
    let mut map = HashMap::new();
    map.insert(&f_name, &f_value);
    println!("{} {}", f_name, f_value);//打开执行不会报错
}
OK
```

![](https://gitee.com/hxc8/images4/raw/master/img/202407172313372.jpg)

![](https://gitee.com/hxc8/images4/raw/master/img/202407172313444.jpg)

![](https://gitee.com/hxc8/images4/raw/master/img/202407172313732.jpg)

![](https://gitee.com/hxc8/images4/raw/master/img/202407172313892.jpg)

![](https://gitee.com/hxc8/images4/raw/master/img/202407172313182.jpg)

![](https://gitee.com/hxc8/images4/raw/master/img/202407172313553.jpg)

![](https://gitee.com/hxc8/images4/raw/master/img/202407172314315.jpg)

![](https://gitee.com/hxc8/images4/raw/master/img/202407172314774.jpg)

![](https://gitee.com/hxc8/images4/raw/master/img/202407172314978.jpg)

![](https://gitee.com/hxc8/images4/raw/master/img/202407172314260.jpg)

![](https://gitee.com/hxc8/images4/raw/master/img/202407172314529.jpg)