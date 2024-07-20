PowerProto主要用于解决下面三个问题：



降低gRPC的使用门槛与使用成本。

解决protoc以及其相关插件（比如protoc-gen-go、protoc-gen-grpc-gateway）的版本控制问题。

高效管理proto的编译，实现多平台兼容、一键安装与编译。

功能

实现protoc的一键安装与多版本管理。

实现protoc相关插件（比如protoc-gen-go）的一键安装与多版本管理。

通过配置文件管理proto的编译，而非shell脚本，提高可读性与兼容性。

引导式生成配置文件，跨平台兼容，一份配置在多个平台均可以实现一键编译。

支持批量、递归编译proto文件，提高效率。

跨平台支持PostAction，可以在编译完成之后执行一些常规操作（比如替换掉所有生成文件中的"omitempty"）。

支持PostShell，在编译完成之后执行特定的shell脚本。

支持 google api, gogo protobuf 等的一键安装与版本控制。

安装与依赖

目前版本的 PowerProto 依赖 go(>=1.16) 以及 git（未来可能会直接使用CDN拉取构建好的二进制），请确保运行环境中包含这两个命令。

protoc的下载源是Github，PowerProto在下载protoc时尊重 HTTP_PROXY、HTTPS_PROXY环境变量，如果遇到网络问题，请自行配置代理。

在查询protoc的版本列表时，会对github.com使用git ls-remote，如果遇到网络问题，请自行为git配置代理。

在当前版本，下载和查询插件版本均依赖go命令，所以如果遇到网络问题，请自行配置 GOPROXY环境变量。

默认会使用 用户目录/.powerproto作为安装目录，用于放置下载好的各种插件以及全局配置，可以通过 POWERPROTO_HOME环境变量来修改安装目录。

如果认为powerproto名字太长，可以通过alias成一个更简单的名字来提高输入效率，比如没有人会介意你叫它pp。



一、通过Go进行安装

直接执行下面的命令即可进行安装：

```javascript
go install github.com/storyicon/powerproto/cmd/powerproto@latest
```



二、开箱即用版本

可以通过  Github Release Page 下载开箱即用版本。



https://github.com/storyicon/powerproto

