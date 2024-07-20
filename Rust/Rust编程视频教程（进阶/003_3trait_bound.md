```
//1、trait_bound语法
//2、指定多个trait bound
//3、返回 trait的类型
//fn print_information(item: impl GetInformation) { //直接作为参数的写法

//fn print_information<T: GetInformation>(item: T){ //使用trait bound的写法
//    println!("name = {}", item.get_name());
//    println!("age = {}", item.get_age());
//}

use std::fmt::Debug;

trait GetName {
    fn get_name(&self) -> &String;
}

trait GetAge {
    fn get_age(&self) -> u32;
}
//写法一
fn print_information<T: GetName + GetAge>(item: T) {
    println!("name={}", item.get_name());
    println!("age={}", item.get_age());
}
//写法二
fn pring_information2<T>(item: T)
where
    T: GetAge + GetName,
{
    println!("name={}", item.get_name());
    println!("age={}", item.get_age());
}
#[derive(Debug)]
struct Student {
    name: String,
    age: u32,
}
impl GetName for Student {
    fn get_name(&self) -> &String {
        &self.name
    }
}
impl GetAge for Student {
    fn get_age(&self) -> u32 {
        self.age
    }
}

//要求返回值实现了GetAge这个特征,这里Debug是为了打印输出
fn produce_item_with_age() -> impl GetAge + Debug {
    Student {
        name: "haha".to_string(),
        age: 22,
    }
}

fn main() {
    let s = Student {
        name: "力王".to_string(),
        age: 33,
    };
    println!("nage={},age={}", s.get_name(), s.get_age());

    print_information(s);

    let s2 = Student {
        name: "力王2".to_string(),
        age: 22,
    };
    pring_information2(s2);

    let s3 = produce_item_with_age();
    println!("age={:#?}", s3);
}

```