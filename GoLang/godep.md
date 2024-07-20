Go语言从v1.5开始开始引入| vendor 模式,如果项目目录下有vendor目录,那么go工具链会优先使用| vendor|内的包进

行编译、测试等。

godep |是一个通过vender模式实现的Go语言的第三 方依赖管理工具,类似的还有由社区维护准官方包管理工具dep .





安装

执行以下命令安装godep|工具。



go get github. com/tools/godep





![](https://gitee.com/hxc8/images7/raw/master/img/202407190752486.jpg)





godep开发流程

1.保证程序能够正常编译

2.执行godep save保存当前项自的所有第三方依赖的版本信息和代码

3.提交Godeps目录和vender目录到代码库。

4.如果要更新依赖的版本,可以直接修改Godeps . json文件中的对应项