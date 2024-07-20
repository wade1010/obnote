021_1trait对象

├── Cargo.lock

├── Cargo.toml

├── gui

│   ├── Cargo.toml

│   └── src

│       └── lib.rs

├── main

│   ├── Cargo.toml

│   └── src

│       └── main.rs

├── src

│   └── main.rs

Cargo.toml

```
[workspace]
members=[
"gui","main"
]
```

gui/src/lib.rs

```
pub trait Draw {
    fn draw(&self);
}

pub struct Screen {
    pub components: Vec<Box<dyn Draw>>,//trait对象，使用dyn关键字
}

impl Screen {
    pub fn run(&self) {
        //会报错,`self.components` moved due to this implicit call to `.into_iter()`  move occurs because
        // `self.components` has type `Vec<Box<dyn Draw>>`, which does not implement the `Copy` trait
        // for component in self.components {
        //OK 获取不可变借用
        // for component in self.components.iter() {
        //OK 也是获取不可变借用
        for component in &self.components {
            component.draw();
        }
    }
}

pub struct Button {
    pub width: u32,
    pub height: u32,
    pub label: String,
}

impl Draw for Button {
    fn draw(&self) {
        println!("button draw,width={},height={},label={}", self.width, self.height, self.label);
    }
}

pub struct SelectBox {
    pub width: u32,
    pub height: u32,
    pub option: Vec<String>,
}

impl Draw for SelectBox {
    fn draw(&self) {
        println!("draw selectBox,width = {} ,height={},option={:?}",
                 self.width, self.height, self.option);
    }
}

pub fn add(left: usize, right: usize) -> usize {
    left + right
}

#[cfg(test)]
mod tests {
    use super::*;

    #[test]
    fn it_works() {
        let result = add(2, 2);
        assert_eq!(result, 4);
    }
}

```

main/Cargo.toml

```
[package]
name = "main"
version = "0.1.0"
edition = "2021"

# See more keys and their definitions at 

[dependencies]
gui = { path = "../gui" }
```

main/src/main.rs

```
use gui::{Screen, SelectBox, Button};

fn main() {
    let mut s = Screen { components: vec![] };
    let btn = Button {
        width: 10,
        height: 20,
        label: "i am button".to_string(),
    };
    let sb = SelectBox {
        width: 22,
        height: 11,
        option: vec![
            String::from("a"),
            String::from("b"),
            String::from("c"),
        ],
    };
    s.components.push(Box::new(btn));
    s.components.push(Box::new(sb));
    s.run();
}

```

button draw,width=10,height=20,label=i am button

draw selectBox,width = 22 ,height=11,option=["a", "b", "c"]