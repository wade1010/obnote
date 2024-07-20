## &操作符

&操作符用于变量名前，表示创建此变量的引用，用于类型名前，表示此类型的借用类型。

```
let x = vec![1, 2, 3]; // vec不具备复制特性。赋值时不会自动复制。
let y = &x; // &x创建对x的引用，再绑定到y，y的类型是&Vec<i32>。
let z: &Vec<i32> = &x; //等同上一行

fn some_fun(arg: &T) {} // &T是一个类型，表示arg是对T类型的引用。

```

当&出现在=左侧时，可以用于模式匹配，但通常这种写法都是错的，仅在具备复制特性的类型时才能通过编译。

### 具备复制特性的类型

```
// 具备复制特性的类型，如i32
let x = 100;
let &y = &x; // &x创建对x的引用（&i32类型），再通过模式匹配，与y进行绑定。
// 用&y匹配&i32类型，同时将=右侧解构，得到&i32类型，相当于将右侧的i32复制到左侧的i32。
// 因为i32可以复制，所以可以成功绑定。

```

### 不具备复制特性的类型

```
// 不具备复制特性的类型，如Vec<i32>
let x = vec![1, 2, 3];
let &y = &x; // 出错，&x创建对x的引用（&Vec<i32>类型），再通过模式匹配，与y进行绑定。
// 绑定时&y匹配&Vec<i32>类型，所以y就是Vec<i32>类型，=右侧也对&x的类型进行解构，也得到&Vec<i32>，
// 于是这上绑定就相当于将右侧的Vec<i32>赋给左侧的Vec<i32>，
// 因为Vec<i32>不能复制，所以这里是移动语义，相当于把x移动到y。
// 但因为&x本身创建了对x的引用（临时的引用），不能移动x。出错信息：
// error[E0507]: cannot move out of a shared reference
//   --> src/bin/test1.rs:11:14
//    |
// 11 |     let &u = &x;
//    |         --   ^^
//    |         ||
//    |         |data moved here
//    |         |move occurs because `u` has type `Vec<i32>`, which does not implement the `Copy` trait
//    |         help: consider removing the `&`: `u`
```

上述出错的代码相当于：

```
let x = vec![1, 2, 3];
let y = &x;
let &z = y; // 对y进行解构，得到&Vec<i32>，于是尝试将y所指向的x移动到z，但因为y的存在不能成功。
```

## ref关键字

某些情况下，使用ref与&是等效的，但ref关键字一般用于要绑定的标识符前，通常这些地方都无法使用&。

```
let x = vec![1, 2, 3];
let ref y = x; // ref表示以引用的方式绑定y和x，所以y的类型是&Vec<i32>。
let y = &x; // 与上一句等价。
```

### ref用于match的分支匹配中

match在匹配时，会拿走被匹配变量，所以match之后，原变量无法再使用。

```
let a = Some(vec![1, 2, 3]);
match a {
        None => (),
        Some(value) => println!("{:?}", value), // value绑定是移动语义，所以a被match占据。
}
println!("{:?}", a); // 不能再使用a

```

要避免这种情况，可以使用ref，

```
let a = Some(vec![1, 2, 3]);
match a {
        None => (),
        Some(ref value) => println!("{:?}", value), // value以引用方式绑定。
}
println!("{:?}", a); // a仍然在

// 也可以写成

let a = Some(vec![1, 2, 3]);
match &a { // 创建a的引用，match只使用了a引用，且match结束时，引用离开作用域也会销毁
        None => (),
        Some(value) => println!("{:?}", value), // 编译器会识别出，value也是引用。
}
println!("{:?}", a); // 可以继续使用a，如果a可变，也可以修改a

```

### ref用于解构时引用结构体某些字段

不考虑复制特性，结构体进行匹配时，默认将使用移动语义，结构体字段所有权从结构体移动到被绑定的变量上，原变量失去所有权。例如

```
#[derive(Debug)]
struct Person {
    name: String,
}

fn main() {
    let mut p = Person {
        name: "薛海舟".to_string(),
    };

    let Person { name: a } = p; // a是被绑定的标识符，这里将p中name的所有权转移给a变量。
    // p.name.push_str("舒珊靖"); // 失败，因为p已经被移动。
    println!("{:?}", a); // a中内容是"薛海舟"
}

```

使用ref就表示，要以引用的形式来绑定。例如

```
#[derive(Debug)]
struct Person {
    name: String,
}

// ref只能用于要被绑定的标识符前
fn main() {
    let mut p = Person {
        name: "薛海舟".to_string(),
    };

    let Person { name: ref mut a } = p; // a是是对p中name字段的可变借用
    *a = "舒珊靖".to_string(); // 对a赋值，就相当于对p中name操作
    println!("{:?}", p); // p中name变为舒珊靖
}

```