![](https://gitee.com/hxc8/images4/raw/master/img/202407172318050.jpg)

![](https://gitee.com/hxc8/images4/raw/master/img/202407172318992.jpg)

![](https://gitee.com/hxc8/images4/raw/master/img/202407172318783.jpg)

![](https://gitee.com/hxc8/images4/raw/master/img/202407172318075.jpg)

![](https://gitee.com/hxc8/images4/raw/master/img/202407172318400.jpg)

![](https://gitee.com/hxc8/images4/raw/master/img/202407172318563.jpg)

![](https://gitee.com/hxc8/images4/raw/master/img/202407172319083.jpg)

![](https://gitee.com/hxc8/images4/raw/master/img/202407172319427.jpg)

![](https://gitee.com/hxc8/images4/raw/master/img/202407172319657.jpg)

![](https://gitee.com/hxc8/images4/raw/master/img/202407172319056.jpg)

![](https://gitee.com/hxc8/images4/raw/master/img/202407172319508.jpg)

![](https://gitee.com/hxc8/images4/raw/master/img/202407172319882.jpg)

```
fn main() {
    let w = 30;
    let l = 50;
    println!("{}", area(w, l));
}

fn area(w: u32, l: u32) -> u32 {
    w * l
}
```

```
fn main() {
    let rect = (30, 50);
    println!("{}", area(rect));
}

fn area(r: (u32, u32)) -> u32 {
    r.0 * r.1
}
```

```
fn main() {
    let rect = Rectangle {
        width: 30,
        length: 50,
    };
    println!("{}", area(&rect));//借用，main还保留rect的所有权，所以洗唛那一行打印不会报错
    println!("{:?}", rect);
}

#[derive(Debug)]
struct Rectangle {
    width: u32,
    length: u32,
}

fn area(r: &Rectangle) -> u32 {
    r.width * r.length
}
```

![](https://gitee.com/hxc8/images4/raw/master/img/202407172319403.jpg)

```
fn main() {
    let rect = Rectangle {
        width: 30,
        length: 50,
    };
    println!("{}", rect.area());//借用，main还保留rect的所有权，所以洗唛那一行打印不会报错
    println!("{:?}", rect);
}

#[derive(Debug)]
struct Rectangle {
    width: u32,
    length: u32,
}

impl Rectangle {
    fn area(self) -> u32 { //注意这里没有&
        self.width * self.length
    }
}

error[E0382]: borrow of moved value: `rect`
  --> src/main.rs:7:22
   |
2  |     let rect = Rectangle {
   |         ---- move occurs because `rect` has type `Rectangle`, which does not implement the `Copy` trait
...
6  |     println!("{}", rect.area());//借用，main还保留rect的所有权，所以洗唛那一行打印不会报错
   |                         ------ `rect` moved due to this method call
7  |     println!("{:?}", rect);
   |                      ^^^^ value borrowed here after move

```

```
fn main() {
    let rect = Rectangle {
        width: 30,
        length: 50,
    };
    println!("{}", rect.area());//借用，main还保留rect的所有权，所以洗唛那一行打印不会报错
    println!("{:?}", rect);
}

#[derive(Debug)]
struct Rectangle {
    width: u32,
    length: u32,
}

impl Rectangle {
    fn area(&self) -> u32 {//注意这里有&
        self.width * self.length
    }
}

1500
Rectangle { width: 30, length: 50 }
```

![](https://gitee.com/hxc8/images4/raw/master/img/202407172319663.jpg)

![](https://gitee.com/hxc8/images4/raw/master/img/202407172319649.jpg)

![](https://gitee.com/hxc8/images4/raw/master/img/202407172319067.jpg)

```
fn main() {
    let rect = Rectangle {
        width: 30,
        length: 50,
    };
    println!("{}", rect.area());//借用，main还保留rect的所有权，所以洗唛那一行打印不会报错
    println!("{:?}", rect);

    let rect2 = Rectangle {
        width: 10,
        length: 20,
    };
    let rect3 = Rectangle {
        width: 20,
        length: 70,
    };
    println!("rect can hold rect2:{}", rect.can_hold(&rect2));
    println!("rect can hold rect3:{}", rect.can_hold(&rect3));
}

#[derive(Debug)]
struct Rectangle {
    width: u32,
    length: u32,
}

impl Rectangle {
    fn area(&self) -> u32 {
        self.width * self.length
    }
    fn can_hold(&self, other: &Rectangle) -> bool {
        self.width > other.width && self.length > other.length
    }
}


```

![](https://gitee.com/hxc8/images4/raw/master/img/202407172319396.jpg)

```
fn main() {
    let rect = Rectangle {
        width: 30,
        length: 50,
    };
    println!("{}", rect.area());//借用，main还保留rect的所有权，所以洗唛那一行打印不会报错
    println!("{:?}", rect);

    let rect2 = Rectangle {
        width: 10,
        length: 20,
    };
    let rect3 = Rectangle {
        width: 20,
        length: 70,
    };
    println!("rect can hold rect2:{}", rect.can_hold(&rect2));
    println!("rect can hold rect3:{}", rect.can_hold(&rect3));

    let s = Rectangle::square(10);
    println!("{:?}", s);
}

#[derive(Debug)]
struct Rectangle {
    width: u32,
    length: u32,
}

impl Rectangle {
    fn area(&self) -> u32 {
        self.width * self.length
    }
    fn can_hold(&self, other: &Rectangle) -> bool {
        self.width > other.width && self.length > other.length
    }
    fn square(size: u32) -> Rectangle {
        Rectangle { width: size, length: size }
    }
}


```

![](https://gitee.com/hxc8/images4/raw/master/img/202407172319784.jpg)

![](https://gitee.com/hxc8/images4/raw/master/img/202407172319460.jpg)