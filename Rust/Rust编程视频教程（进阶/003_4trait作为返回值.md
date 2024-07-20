```
//错误的用法
//fn produce_item_with_age2() -> impl GetAge {
//    let is = true;
//
//    if is {
//        Student {
//            name: String::from("xiaoming"),
//            age: 15,
//        }
//    } else {
//        Teacher {
//            name: String::from("xiaohuang"),
//            age: 15,
//        }
//    }
//}
```