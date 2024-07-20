# 一、下载以及使用

[https://github.com/nlohmann/json#integration](https://github.com/nlohmann/json#integration)

[json.hpp](https://github.com/nlohmann/json/blob/develop/single_include/nlohmann/json.hpp) is the single required file in single_include/nlohmann or [released here](https://github.com/nlohmann/json/releases). You need to add

```
#include <nlohmann/json.hpp>

// for convenience
using json = nlohmann::json;
```

to the files you want to process JSON and set the necessary switches to enable C++11 (e.g., -std=c++11 for GCC and Clang).

# 二、使用

![](https://gitee.com/hxc8/images3/raw/master/img/202407172240690.jpg)

c_cpp_properties.json

```
{
    "configurations": [
        {
            "name": "Win32",
            "includePath": [
                "${workspaceFolder}/**",
                "C:\\Users\\Administrator\\Downloads\\include\\single_include"
            ],
            "defines": [
                "_DEBUG",
                "UNICODE",
                "_UNICODE"
            ],
            "windowsSdkVersion": "10.0.19041.0",
            "compilerPath": "W:/env/mingw64/bin/g++.exe",
            "cStandard": "c11",
            "intelliSenseMode": "${default}",
            "cppStandard": "c++11"
        }
    ],
    "version": 4
}
```

## 2.1、由值创建json对象

main.cpp

```
#include <iostream>
#include <iomanip>
#include <nlohmann/json.hpp>

using json = nlohmann::json;

int main()
{
    // 方式一:直接构造
    json j =
        {
            {"pi", 3.141},
            {"happy", true},
            {"name", "Niels"},
            {"nothing", nullptr},
            {"answer", {{"everything", 42}}},
            {"list", {1, 0, 2}},
            {"object", {{"currency", "USD"}, {"value", 42.99}}}};

    // add new values
    j["new"]["key"]["value"] = {"another", "list"};

    // count elements
    auto s = j.size();
    j["size"] = s;

    // pretty print with indent of 4 spaces
    std::cout << std::setw(4) << j << '\n';

    // 方式一:赋值构造
    json j2;
    j2["name"] = "tester";
    j2["age"] = 37;
    j2["score"] = 34231.2;
    j2["is_nb"] = true;                                  // 布尔值
    j2["array"] = {"array_value1", "array_value2"};      // 数组
    j2["school"]["name"] = "c++ school";                 // 对象中元素值
    j2["friends"] = {{"name", "bob"}, {"is_nb", false}}; // 对象

    std::cout << j2 << std::endl;
}
```

编译：

g++ --std=c++11 main.cpp -I C:\Users\Administrator\Downloads\include\single_include

执行：

a.exe

输出结果：

```
(base) W:\workspace\cpp\json相关\nlohmannjson_3rd>a.exe                                                                              {
    "answer": {
        "everything": 42
    },
    "happy": true,
    "list": [
        1,
        0,
        2
    ],
    "name": "Niels",
    "new": {
        "key": {
            "value": [
                "another",
                "list"
            ]
        }
    },
    "nothing": null,
    "object": {
        "currency": "USD",
        "value": 42.99
    },
    "pi": 3.141,
    "size": 8
}
{
    "age": 37,
    "array": [
        "array_value1",
        "array_value2"
    ],
    "friends": {
        "is_nb": false,
        "name": "bob"
    },
    "is_nb": true,
    "name": "tester",
    "school": {
        "name": "c++ school"
    },
    "score": 34231.2
}
```

### 2.2、由json对象得到值

main.cpp

```
#include <iostream>
#include <iomanip>
#include <nlohmann/json.hpp>

using json = nlohmann::json;

int main()
{
    json j =
        {
            {"pi", 3.141},
            {"happy", true},
            {"name", "Niels"},
            {"nothing", nullptr},
            {"answer", {{"everything", 42}}},
            {"list", {1, 0, 2}},
            {"object", {{"currency", "USD"}, {"value", 42.99}}}};

    auto name = j["name"].get<std::string>();
    std::cout << "name=" << name << std::endl;

    std::cout << std::endl;

    auto num1 = j["list"][0].get<int>();
    auto num2 = j["list"].at(1).get<int>();
    std::cout << "num1=" << num1 << std::endl;
    std::cout << "num2=" << num2 << std::endl;

    std::cout << std::endl;

    auto obj0 = j["object"]["currency"].get<std::string>();
    std::cout << "obj0=" << obj0 << std::endl;
    return EXIT_SUCCESS;
}
```

编译：

g++ --std=c++11 main.cpp -I C:\Users\Administrator\Downloads\include\single_include

执行：

a.exe

输出结果：

```
name=Niels

num1=1
num2=0

obj0=USD
```

### 2.3、像STL container一样操作json value

```
#include <iostream>
#include <iomanip>
#include <nlohmann/json.hpp>

using json = nlohmann::json;

int main()
{
    json j = {"a1", "a2"};

    j.push_back("a3");

    j.emplace_back("a4");

    std::cout << "j=" << j << std::endl;

    if (j.is_array() && !j.empty())
    {
        size_t size = j.size();
        std::cout << "j size:" << size << std::endl;

        std::string lastEle = j.at(size - 1).get<std::string>();
        std::cout << "j[size-1]:" << lastEle << std::endl;
        std::cout << std::endl;
    }

    //
    json j2 = {{"name", "bob"}, {"age", 12}};
    j2.push_back({"height", 123.2});
    j2.erase("age"); // 删除键值
    std::cout << "j2=" << j2 << std::endl;

    j2["height"] = 222.33; // 通过key修改value的值

    // 判断是否含有某个键值方式1
    if (j2.contains("height"))
    {
        auto height = j2["height"].get<double>();
        std::cout << "method1:height=" << height << std::endl;
    }

    // 判断是否含有某个键值方式二
    auto size = j2.count("height");
    if (size > 0)
    {
        auto height = j2["height"].get<double>();
        std::cout << "method2:height=" << height << std::endl;
    }

    // 判断是否含有某个键值方式三
    auto iter = j2.find("height");
    if (iter != j2.end())
    {
        auto height = j2["height"].get<double>();
        std::cout << "method3:height=" << height << std::endl;
    }

    // 遍历输出方式1
    std::cout << "遍历输出键值方式1" << std::endl;
    for (auto &item : j2.items())
    {
        std::cout << item.key() << ":" << item.value() << std::endl;
    }

    // 遍历输出方式2
    for (auto iter = j2.begin(); iter != j2.end(); ++iter)
    {
        std::cout << iter.key() << ":" << iter.value() << std::endl;
    }

    return EXIT_SUCCESS;
}
```

输出结果

```
j=["a1","a2","a3","a4"]
j size:4
j[size-1]:a4

j2={"height":123.2,"name":"bob"}
method1:height=222.33
method2:height=222.33
method3:height=222.33
遍历输出键值方式1
height:222.33
name:"bob"
height:222.33
name:"bob"
```

### 2.4、json序列化和反序列化

#### 2.4.1 json字符串序列化和反序列化

```
#include <iostream>
#include <iomanip>
#include <nlohmann/json.hpp>

using json = nlohmann::json;

int main()
{
    // 两种方式,反序列化构建json对象
    // 方式1，通过"_json"实现反序列化
    json j1 = "{\"name\":\"tester1\",\"score\":88,\"money\":100000}"_json;
    // 使用原生字符串关键字R来避免转移字符
    auto temp = R"({"name":"tester2","score":188,"money":200000})";
    // 方式2,通过静态函数parse实现反序列化
    json j2 = json::parse(temp);

    std::cout << "反序列化" << std::endl;
    std::cout << "j1 =" << j1 << std::endl;
    std::cout << "j2 =" << j2 << std::endl;

    std::cout << "序列化" << std::endl;
    // 序列化(Serialization)：dump(number)，number为打印出的空格数
    auto j1_string = j1.dump();
    auto j2_string = j2.dump(4);
    std::cout << "j1_string=" << j1_string << std::endl;
    std::cout << std::endl;
    std::cout << "j2_string=" << j2_string << std::endl;

    return EXIT_SUCCESS;
}
```

输出结果：

```
反序列化
j1 ={"money":100000,"name":"tester1","score":88}
j2 ={"money":200000,"name":"tester2","score":188}
序列化
j1_string={"money":100000,"name":"tester1","score":88}

j2_string={
    "money": 200000,
    "name": "tester2",
    "score": 188
}
```

#### 2.4.2、json对象和文件输入输出转换

文件 student_json.txt

```
{
"name":"bob",
"age":22,
"man":true,
"money":10000.22
}
```

main.cpp

```
#include <iostream>
#include <iomanip>
#include <fstream>
#include <nlohmann/json.hpp>

using json = nlohmann::json;

int main()
{
    // 从json文件中读取内容到json对象中
    std::ifstream in("./student_json.txt");
    if (!in.is_open())
    {
        std::cout << "open failed!" << std::endl;
        return EXIT_FAILURE;
    }
    json j;
    in >> j;
    in.close();
    std::cout << std::setw(4) << j << std::endl;

    // 输出json对象内容到文件中
    std::ofstream out("./new_student_json.txt");
    j["new_name"] = "new name";
    // 输出json对象到文件中，std::setw(4)用于设置增加打印空格
    out << std::setw(4) << j;
    out.close();

    return EXIT_SUCCESS;
}
```

输出结果：

```
{
    "age": 22,
    "man": true,
    "money": 10000.22,
    "name": "bob"
}
```

new_student_json.txt内容如下

```
{
    "age": 22,
    "man": true,
    "money": 10000.22,
    "name": "bob",
    "new_name": "new name"
}
```

#### 2.4.3、json value和自定义对象

在自定义对象命名空间中定义两个函数即可像basic value一样进行反序列化和序列化：

from_json(const json& j,T& value)

to_json(json& j,const T& value)

```
#include <iostream>
#include <iomanip>
#include <fstream>
#include <nlohmann/json.hpp>

using json = nlohmann::json;
class person
{
public:
    person() {}
    person(std::string name, int age, double score) : m_name(name), m_age(age), m_score(score) {}

    void show()
    {
        std::cout << "person name=" << m_name << std::endl;
        std::cout << "person age=" << m_age << std::endl;
        std::cout << "person score=" << m_score << std::endl;
    }

    std::string m_name;
    int m_age;
    double m_score;
};

// 定义from_json(const json& j,T& value)函数，用于序列化
// json对象----->class对象
void from_json(const json &j, person &p) // 形参顺序不能变
{
    p.m_name = j["name"].get<std::string>();
    p.m_age = j["age"].get<int>();
    p.m_score = j["score"].get<double>();
}
// 定义to_json(json& j,const T& value)函数，用于反序列化
// class对象----->json对象
void to_json(json &j, const person &p) // 形参顺序不能变,要不然不能隐式转换,nlohmann json内部做了处理
{
    j["name"] = p.m_name;
    j["age"] = p.m_age;
    j["score"] = p.m_score;
}
// void to_json(json& j, const person& p)
// {
//  j = json{ {"name", p.name}, {"address", p.address}, {"age", p.age} };
// }

// void from_json(const json& j, person& p) {
//  j.at("name").get_to(p.name);
//  j.at("address").get_to(p.address);
//  j.at("age").get_to(p.age);
// }

int main()
{
    person p{"bob", 22, 95.6};
    std::cout << "to json,方式1:json=class隐式转换" << std::endl;
    json j1 = p;
    std::cout << "j1 = " << j1 << std::endl;

    std::cout << "to json,方式2:调用to_json函数" << std::endl;
    json j2;
    to_json(j2, p);
    std::cout << "j2 = " << j2 << std::endl;

    std::cout << "from json,方式1:调用from_json函数" << std::endl;
    j1["name"] = "new_bob";
    std::cout << "new j1 = " << j1 << std::endl;

    person new_p;
    from_json(j1, new_p);
    new_p.show();

    std::cout << "from json,方式2:调用.get()函数" << std::endl;
    // 像basic value一样通过get函数获取值，将其值直接赋值给自定义对象
    person new_p2 = j1.get<person>();
    new_p2.show();

    return EXIT_SUCCESS;
}
```

输出结果：

```
to json,方式1:json=class隐式转换
j1 = {"age":22,"name":"bob","score":95.6}
to json,方式2:调用to_json函数
j2 = {"age":22,"name":"bob","score":95.6}
from json,方式1:调用from_json函数
new j1 = {"age":22,"name":"new_bob","score":95.6}
person name=new_bob
person age=22
person score=95.6
from json,方式2:调用.get()函数
person name=new_bob
person age=22
person score=95.6
```

### 3、NLOHMANN_DEFINE_TYPE_INTRUSIVE宏的使用

#### 3.1、宏的定义

JSON for Modern C++中为了方便序列化和反序列化，定义了两个宏，如下

```
NLOHMANN_DEFINE_TYPE_NON_INTRUSIVE(name, member1, member2, …) 将在要为其创建代码的类/结构的命名空间内定义。
NLOHMANN_DEFINE_TYPE_INTRUSIVE(name, member1, member2, …) 将在要为其创建代码的类/结构中定义。 该宏还可以访问私有成员。
```

INTRUSIVE：侵入性的

查看源码：

```
/*!
@brief macro
@def NLOHMANN_DEFINE_TYPE_INTRUSIVE
@since version 3.9.0
*/
#define NLOHMANN_DEFINE_TYPE_INTRUSIVE(Type, ...)  \
    friend void to_json(nlohmann::json& nlohmann_json_j, const Type& nlohmann_json_t) { NLOHMANN_JSON_EXPAND(NLOHMANN_JSON_PASTE(NLOHMANN_JSON_TO, __VA_ARGS__)) } \
    friend void from_json(const nlohmann::json& nlohmann_json_j, Type& nlohmann_json_t) { NLOHMANN_JSON_EXPAND(NLOHMANN_JSON_PASTE(NLOHMANN_JSON_FROM, __VA_ARGS__)) }

/*!
@brief macro
@def NLOHMANN_DEFINE_TYPE_NON_INTRUSIVE
@since version 3.9.0
*/
#define NLOHMANN_DEFINE_TYPE_NON_INTRUSIVE(Type, ...)  \
    inline void to_json(nlohmann::json& nlohmann_json_j, const Type& nlohmann_json_t) { NLOHMANN_JSON_EXPAND(NLOHMANN_JSON_PASTE(NLOHMANN_JSON_TO, __VA_ARGS__)) } \
    inline void from_json(const nlohmann::json& nlohmann_json_j, Type& nlohmann_json_t) { NLOHMANN_JSON_EXPAND(NLOHMANN_JSON_PASTE(NLOHMANN_JSON_FROM, __VA_ARGS__)) }


```

#### 3.2、宏的使用

可以看出上述的宏主要实现了from_json和to_json两个函数的功能，使用时需要在一个类中调用该宏，并传入(类名，参数1，参数2，参数3…)使用，这样在json对象和class对象之间之间直接赋值可以完成相互转换，具体用法如下：

```
#include <iostream>
#include <iomanip>
#include <fstream>
#include <nlohmann/json.hpp>

using json = nlohmann::json;
// 2.4.3、json value和自定义对象
class person
{
public:
    person() {}
    person(std::string name, int age, double score) : m_name(name), m_age(age), m_score(score) {}

    void show()
    {
        std::cout << "person name=" << m_name << std::endl;
        std::cout << "person age=" << m_age << std::endl;
        std::cout << "person score=" << m_score << std::endl;
    }

    std::string m_name;
    int m_age;
    double m_score;
    // 类名，成员1，成员2，成员3
    NLOHMANN_DEFINE_TYPE_INTRUSIVE(person, m_name, m_age, m_score);
};

int main()
{
    person p{"bob", 22, 95.6};
    std::cout << "调用宏实现:to json" << std::endl;
    json j = p;
    std::cout << j << std::endl;
    std::cout << std::endl;
    std::cout << j.dump() << std::endl;
    std::cout << "调用宏实现:from json" << std::endl;
    j["name"] = "new bob";
    person p2 = j;
    p2.show();

    return EXIT_SUCCESS;
}
```