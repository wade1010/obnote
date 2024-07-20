https://www.liwenzhou.com/posts/Go/go_dependency/



go module

go module是Go1.11版本之 后官方推出的版本管理工具。



GO111MODULE



要启用go module 支持首先要设置环境变量 GO111MODULE , 通过它可以开启或关闭模块支持,它有三个可选值:

off 、on、 auto ,默认值是auto

1. GO111MODULE=off禁用模块支持,编译时会从GOPATH和vendor文件夹中查找包。

2. G0111MODULE=on启用模块支持,编译时会忽略GOPATH和vendor文件夹,只根据go.mod下载依赖。

3. G0111MODULE=auto ,当项目在$GOPATH/src外且项目根目录有go.mod文件时,开启模块支持。

简单来说,设置GO111MODULE=on 之后就可以使用|go module|了,以后就没有必要在GOPATH中创建项目了, 并且还能够很好的管理项目依赖的第三方包信息。



![](https://gitee.com/hxc8/images7/raw/master/img/202407190752295.jpg)





![](https://gitee.com/hxc8/images7/raw/master/img/202407190752685.jpg)



![](https://gitee.com/hxc8/images7/raw/master/img/202407190752968.jpg)





![](https://gitee.com/hxc8/images7/raw/master/img/202407190752048.jpg)



![](https://gitee.com/hxc8/images7/raw/master/img/202407190752406.jpg)





在项目中使用go module





![](https://gitee.com/hxc8/images7/raw/master/img/202407190752627.jpg)

 