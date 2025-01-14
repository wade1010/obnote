```
//1、trait用于定义与其它类型共享的功能，类似于其它语言中的接口。
//（1）可以通过trait以抽象的方式定义共享的行为。
//（2）可以使用trait bounds指定泛型是任何拥有特定行为的类型。
// (3) trait可以有默认是想，其他语言只有函数声明
//2、定义trait
pub trait GetInformation {
    fn get_name(&self) -> &String;
    fn get_age(&self) -> u32;
}
//3、实现trait
pub struct Student {
    pub name: String,
    pub age: u32,
}

impl GetInformation for Student {
    fn get_name(&self) -> &String {
        &self.name
    }

    fn get_age(&self) -> u32 {
        self.age
    }
}

pub struct Teacher {
    pub name: String,
    pub age: u32,
    pub subject: String,
}
impl GetInformation for Teacher {
    fn get_name(&self) -> &String {
        &self.name
    }

    fn get_age(&self) -> u32 {
        self.age
    }
}
//因为有默认实现，不需要去实现
impl SchoolName for Student {}
impl SchoolName for Teacher {
    fn get_schoole_name(&self) -> String {
        String::from("光明小学")
    }
}

impl Teacher {
    fn get_subject(&self) -> &str {
        self.subject.as_str()
    }
}

//4、默认实现：可以在定义trait的时候提供默认的行为，trait的类型可以使用默认的行为。
trait SchoolName {
    fn get_schoole_name(&self) -> String {
        String::from("希望小学")
    }
}

//5、trait作为参数
fn print_information(item: impl GetInformation) {
    println!("name={}", item.get_name());
    println!("age={}", item.get_age());
}

fn main() {
    let s = Student {
        name: "小明".to_string(),
        age: 20,
    };
    let t = Teacher {
        name: "李老师".to_string(),
        age: 34,
        subject: "物理".to_string(),
    };
    println!(
        "student,name:{},age:{},school:{}",
        s.get_name(),
        s.get_age(),
        s.get_schoole_name()
    );
    println!(
        "teacher,name:{},age:{},subject:{},school:{}",
        t.get_name(),
        t.get_age(),
        t.get_subject(),
        t.get_schoole_name()
    );
    print_information(s);
    print_information(t);
}

```