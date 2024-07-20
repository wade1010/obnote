qa-rust编译

查找 packed_simd_2

![](https://gitee.com/hxc8/images5/raw/master/img/202407172345867.jpg)

将下面红色的删掉

![](https://gitee.com/hxc8/images5/raw/master/img/202407172345829.jpg)

cd qapro-rs

```
cargo +nightly run --color=always --package qapro-rs  --release example.toml
```