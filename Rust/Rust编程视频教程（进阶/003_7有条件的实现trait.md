```
//对任何实现了特定trait的类型有条件的实现trait

trait GetName {
    fn get_name(&self) -> &String;
}
trait PrintName {
    fn print_name(&self);
}

impl<T: GetName> PrintName for T {
    fn print_name(&self) {
        println!("name={}", self.get_name());
    }
}

struct Student {
    name: String,
}
//这里Student实现了GetName这个trait，就会实现上面的PrintName这个trait。这就是blanket impl
impl GetName for Student {
    fn get_name(&self) -> &String {
        &self.name
    }
}

fn main() {
    let s = Student {
        name: "haha".to_string(),
    };
    s.print_name();
    println!("Hello, world!");
}

```

name=haha

Hello, world!