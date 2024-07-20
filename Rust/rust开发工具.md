下载社区版itelli J IDEA

安装插件rust ，smartsemicolon

 保存自动格式化

![](https://gitee.com/hxc8/images5/raw/master/img/202407172327251.jpg)

运行debug会提示你安装debug工具

添加关闭所有窗口的快捷键

![](https://gitee.com/hxc8/images5/raw/master/img/202407172327623.jpg)

添加terminal快捷键

![](https://gitee.com/hxc8/images5/raw/master/img/202407172327097.jpg)

添加运行快捷键

![](https://gitee.com/hxc8/images5/raw/master/img/202407172327431.jpg)

增加live template

```
dr    #[derive($END$)]
drd   #[derive(Debug$END$)]
sf    String::from("$END$");
```

test  

```
#[cfg(test)]
mod tests {
    use super::*;

    #[test]
    fn it_works() {
        $END$
    }
}
```

![](https://gitee.com/hxc8/images5/raw/master/img/202407172327995.jpg)