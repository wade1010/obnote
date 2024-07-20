029_3自定义derive宏

```
4、过程宏介绍
过程宏接收 Rust 代码作为输入，在这些代码上进行操作，然后产生另一些代码作为输出，
而非像声明式宏那样匹配对应模式然后以另一部分代码替换当前代码。

定义过程宏的函数接受一个 TokenStream 作为输入并产生一个 TokenStream 作为输出。
这也就是宏的核心：宏所处理的源代码组成了输入 TokenStream，同时宏生成的代码是输出 TokenStream。
如下：
use proc_macro;
#[some_attribute]
pub fn some_name(input: TokenStream) -> TokenStream {
}

过程宏中的derive宏   fmt::Display trait
#[derive(Debug)]
struct A {
    a : i32,
}

说明：在hello_macro_derive函数的实现中，syn 中的 parse_derive_input 函数获取一个 TokenStream 
并返回一个表示解析出 Rust 代码的 DeriveInput 结构体（对应代码syn::parse(input).unwrap();）。
该结构体相关的内容大体如下：
DeriveInput {
    // --snip--

    ident: Ident {
        ident: "Pancakes",
        span: #0 bytes(95..103)
    },
    data: Struct(
        DataStruct {
            struct_token: Struct,
            fields: Unit,
            semi_token: Some(
                Semi
            )
        }
    )
}
```

![](https://gitee.com/hxc8/images3/raw/master/img/202407172246879.jpg)

Cargo.toml

```
[workspace]
members = ["hello_macro", "main"]

```

hello_macro/src/lib.rs

```
pub trait HelloMacro {
    fn hello_macro();
}

```

hello_macro/hello_macro_derive/Cargo.toml

```
[package]
name = "hello_macro_derive"
version = "0.1.0"
edition = "2021"

# See more keys and their definitions at https://doc.rust-lang.org/cargo/reference/manifest.html

[lib]
proc-macro = true

[dependencies]
syn = "0.14.4"
quote = "0.6.3"

```

hello_macro/hello_macro_derive/src/lib.rs

```
extern crate proc_macro;
use crate::proc_macro::TokenStream;
use quote::quote;
use syn;

fn impl_hello_macro(ast: &syn::DeriveInput) -> TokenStream {
    let name = &ast.ident;
    let gen = quote! {
        impl HelloMacro for #name{
            fn hello_macro(){
                println!("Hello,in my macro,my name is {}",stringify!(#name));
            }
        }
    };
    gen.into()
}

#[proc_macro_derive(HelloMacro)]
pub fn hello_macro_derive(input: TokenStream) -> TokenStream {
    let ast = syn::parse(input).unwrap(); //这里syn::parse就是解析成结构体，结构体为DeriveInput
    impl_hello_macro(&ast)
}

```

main/Cargo.toml

```
[package]
name = "main"
version = "0.1.0"
edition = "2021"

# See more keys and their definitions at https://doc.rust-lang.org/cargo/reference/manifest.html

[dependencies]
hello_macro = { path = "../hello_macro" }
hello_macro_derive = { path = "../hello_macro/hello_macro_derive" }

```

main/src/main.rs

```
use hello_macro::HelloMacro;
use hello_macro_derive::HelloMacro;

#[derive(HelloMacro)]
struct Main;

fn main() {
    Main::hello_macro();
}

```

Hello,in my macro,my name is Main