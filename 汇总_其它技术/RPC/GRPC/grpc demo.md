

![](D:/download/youdaonote-pull-master/data/Technology/RPC/GRPC/images/A41BA748CA5B4DC6A2D36E2C9254077Eimage.png)

1、项目根目录命令行执行

go mod init gorpcdemo

2、创建文件

helloworld.proto



```javascript
syntax = "proto3"; // 版本声明，使用Protocol Buffers v3版本

package pb; // 包名


// 定义一个打招呼服务
service Greeter {
    // SayHello 方法
    rpc SayHello (HelloRequest) returns (HelloReply) {}
}

// 包含人名的一个请求消息
message HelloRequest {
    string name = 1;
}

// 包含问候语的响应消息
message HelloReply {
    string message = 1;
}
```





创建文件后

3、进入GOPATH目录



protoc -I gorpcdemo gorpcdemo/pb/helloworld.proto --go_out=plugins=grpc:gorpcdemo





