https://blog.csdn.net/myeye520/article/details/103923752



这里golang作为服务端 PHP作为客户端



安装 protoc

```javascript
go get -u google.golang.org/grpc
```



安装用于生成gRPC服务代码的协议编译器，最简单的方法是从下面的链接：https://github.com/google/protobuf/releases下载适合你平台的预编译好的二进制文件（protoc-<version>-<platform>.zip）。



下载完之后，执行下面的步骤：



解压下载好的文件

把protoc二进制文件的路径加到环境变量中

接下来执行下面的命令安装protoc的Go插件：



go get -u github.com/golang/protobuf/protoc-gen-go

编译插件protoc-gen-go将会安装到$GOBIN，默认是$GOPATH/bin，它必须在你的$PATH中以便协议编译器protoc能够找到它。



安装到$GOBIN目录下



使用命令 

```javascript
protoc --help       
Usage: protoc [OPTION] PROTO_FILES
Parse PROTO_FILES and generate output based on the options given:
  -IPATH, --proto_path=PATH   Specify the directory in which to search for
                              imports.  May be specified multiple times;
                              directories will be searched in order.  If not
                              given, the current working directory is used.
                              If not found in any of the these directories,
                              the --descriptor_set_in descriptors will be
                              checked for required proto file.
  --version                   Show version info and exit.
  -h, --help                  Show this text and exit.
  --encode=MESSAGE_TYPE       Read a text-format message of the given type
                              from standard input and write it in binary
                              to standard output.  The message type must
                              be defined in PROTO_FILES or their imports.
  --decode=MESSAGE_TYPE       Read a binary message of the given type from
                              standard input and write it in text format
                              to standard output.  The message type must
                              be defined in PROTO_FILES or their imports.
  --decode_raw                Read an arbitrary protocol message from
                              standard input and write the raw tag/value
                              pairs in text format to standard output.  No
                              PROTO_FILES should be given when using this
                              flag.
  --descriptor_set_in=FILES   Specifies a delimited list of FILES
                              each containing a FileDescriptorSet (a
                              protocol buffer defined in descriptor.proto).
                              The FileDescriptor for each of the PROTO_FILES
                              provided will be loaded from these
                              FileDescriptorSets. If a FileDescriptor
                              appears multiple times, the first occurrence
                              will be used.
  -oFILE,                     Writes a FileDescriptorSet (a protocol buffer,
    --descriptor_set_out=FILE defined in descriptor.proto) containing all of
                              the input files to FILE.
  --include_imports           When using --descriptor_set_out, also include
                              all dependencies of the input files in the
                              set, so that the set is self-contained.
  --include_source_info       When using --descriptor_set_out, do not strip
                              SourceCodeInfo from the FileDescriptorProto.
                              This results in vastly larger descriptors that
                              include information about the original
                              location of each decl in the source file as
                              well as surrounding comments.
  --dependency_out=FILE       Write a dependency output file in the format
                              expected by make. This writes the transitive
                              set of input file paths to FILE
  --error_format=FORMAT       Set the format in which to print errors.
                              FORMAT may be 'gcc' (the default) or 'msvs'
                              (Microsoft Visual Studio format).
  --print_free_field_numbers  Print the free field numbers of the messages
                              defined in the given proto files. Groups share
                              the same field number space with the parent 
                              message. Extension ranges are counted as 
                              occupied fields numbers.

  --plugin=EXECUTABLE         Specifies a plugin executable to use.
                              Normally, protoc searches the PATH for
                              plugins, but you may specify additional
                              executables not in the path using this flag.
                              Additionally, EXECUTABLE may be of the form
                              NAME=PATH, in which case the given plugin name
                              is mapped to the given executable even if
                              the executable's own name differs.
  --cpp_out=OUT_DIR           Generate C++ header and source.
  --csharp_out=OUT_DIR        Generate C# source file.
  --java_out=OUT_DIR          Generate Java source file.
  --js_out=OUT_DIR            Generate JavaScript source.
  --objc_out=OUT_DIR          Generate Objective-C header and source.
  --php_out=OUT_DIR           Generate PHP source file.
  --python_out=OUT_DIR        Generate Python source file.
  --ruby_out=OUT_DIR          Generate Ruby source file.
  @<filename>                 Read options and filenames from file. If a
                              relative file path is specified, the file
                              will be searched in the working directory.
                              The --proto_path option will not affect how
                              this argument file is searched. Content of
                              the file will be expanded in the position of
                              @<filename> as in the argument list. Note
                              that shell expansion is not applied to the
                              content of the file (i.e., you cannot use
                              quotes, wildcards, escapes, commands, etc.).
                              Each line corresponds to a single argument,
                              even if it contains spaces.
~ 

```



go 服务端

![](D:/download/youdaonote-pull-master/data/Technology/RPC/GRPC/images/47EDB05C30F84941A4581B65641B32BDimage.png)



helloworld.proto内容如下

```javascript
syntax = "proto3";  // 指定proto版本

package pb; // 指定包名

//定义 Grpctest 服务
service Grpctest {
    //定义 SayHello 方法
    rpc SayHello(TestRequest) returns (TestReply) {}
}

//TestRequest 请求结构
message TestRequest {
    string name = 1;
}

//TestReply 响应结构
message TestReply {

   //返回数据类型
    message Result {
      int64 id = 1;
      string name = 2;
   }

   repeated Result getataarr = 1;
}
```



进入到$GOPATH



执行  protoc -I gorpcdemo gorpcdemo/pb/helloworld.proto --go_out=plugins=grpc:gorpcdemo



或者gorpcdemo项目根目录



执行 protoc -I ../gorpcdemo ../gorpcdemo/pb/helloworld.proto --go_out=plugins=grpc:../gorpcdemo 



server.go

```javascript
package main

import (
   "context"
   "fmt"
   "gorpcdemo/pb"
   "math/rand"
   "net"

   "google.golang.org/grpc"
   "google.golang.org/grpc/reflection"
)

type server struct{}

func (s *server) SayHello(ctx context.Context, in *pb.TestRequest) (*pb.TestReply, error) {
   id := rand.Intn(10000)
   var result = &pb.TestReply_Result{
      Id:   int64(id),
      Name: in.GetName(),
   }
   var arr []*pb.TestReply_Result
   arr = append(arr, result)
   return &pb.TestReply{
      Getataarr: arr,
   }, nil
}

func main() {
   // 监听本地的8972端口
   lis, err := net.Listen("tcp", ":8972")
   if err != nil {
      fmt.Printf("failed to listen: %v", err)
      return
   }
   s := grpc.NewServer()                   // 创建gRPC服务器
   pb.RegisterGrpctestServer(s, &server{}) // 在gRPC服务端注册服务

   reflection.Register(s) //在给定的gRPC服务器上注册服务器反射服务
   // Serve方法在lis上接受传入连接，为每个连接创建一个ServerTransport和server的goroutine。
   // 该goroutine读取gRPC请求，然后调用已注册的处理程序来响应它们。
   err = s.Serve(lis)
   if err != nil {
      fmt.Printf("failed to serve: %v", err)
      return
   }
}
```



先在go上测试下client

client.go

```javascript
package main

import (
   "context"
   "fmt"
   "gorpcdemo/pb"

   "google.golang.org/grpc"
)

func main() {
   // 连接服务器
   conn, err := grpc.Dial(":8972", grpc.WithInsecure())
   if err != nil {
      fmt.Printf("faild to connect: %v", err)
   }
   defer conn.Close()

   c := pb.NewGrpctestClient(conn)
   // 调用服务端的SayHello
   r, err := c.SayHello(context.Background(), &pb.TestRequest{Name: "crab"})
   if err != nil {
      fmt.Printf("could not greet: %v", err)
   }
   fmt.Printf("Greeting: %s !\n", r.Getataarr)
}
```



测试



启动server

再启动client

![](D:/download/youdaonote-pull-master/data/Technology/RPC/GRPC/images/57030F32CD4A4ACE9F6532D970979AEDimage.png)



没问题 接着就弄PHP了





安装PHP扩展



下载 PHP的gRPC扩展和protobuf扩展

PHP的gRPC扩展：http://pecl.php.net/package/gRPC

PHP的protobuf扩展： http://pecl.php.net/package/protobuf

也可以的直接 pecl安装

pecl install protobuf

pecl install grpc



安装 grpc_php_plugin

https://github.com/grpc/grpc/tree/master/src/php#grpc_php_plugin-protoc-plugin





没折腾好。按官网步骤 不行啊。就没弄这个了。自己网上找了php的client





新建一个PHP测试项目



进入根目录

新建 helloworld.proto  复制前面.proto的内容



命令行执行

protoc --php_out=. helloworld.proto



创建client.php



```javascript
<?php

class client extends \Grpc\BaseStub
{

    public function __construct($hostname, $opts, $channel = null)
    {
        parent::__construct($hostname, $opts, $channel);
    }

    public function SayHello(\Pb\TestRequest $argument, $metadata = [], $options = [])
    {
        // (/Grpctest/SayHello) 是请求服务端那个服务和方法，和 proto 文件定义一样
        // (\Pb\TestReply) 是响应信息（那个类），和 proto 文件定义一样
        return $this->_simpleRequest('/pb.Grpctest/SayHello',
            $argument,
            ['\Pb\TestReply', 'decode'],
            $metadata, $options);
    }

}
```



创建test.php

```javascript
<?php
//引入 composer 的自动载加
require __DIR__ . '/vendor/autoload.php';
require "client.php";
//用于连接 服务端
$client = new client('127.0.0.1:8972', [
    'credentials' => Grpc\ChannelCredentials::createInsecure()
]);

//实例化 TestRequest 请求类
$request = new Pb\TestRequest();
$request->setName("grpc demo");

//调用远程服务
$get = $client->SayHello($request)->wait();

//返回数组
//$reply 是 TestReply 对象
/**
 * @var Pb\TestReply $reply
 */
list($reply, $status) = $get;

$replyData = $reply->getGetataarr();
foreach ($replyData as $k => $v) {
    echo 'id:', $v->getId(), ' name:', $v->getName(), PHP_EOL;
}
```





composer require google/protobuf



composer require grpc/grpc



添加自动加载



```javascript
{
    "require": {
        "google/protobuf": "^3.14",
        "grpc/grpc": "^1.34"
    },
    "autoload": {
        "psr-4": {
            "GPBMetadata\\": "GPBMetadata/",
            "Pb\\": "Pb/"
        }
    }
}
```



执行



composer dump-autoload



此后目录如下

![](D:/download/youdaonote-pull-master/data/Technology/RPC/GRPC/images/26CA7A09A30C44C29946B8E170207898image.png)





执行test.php

返回



![](D:/download/youdaonote-pull-master/data/Technology/RPC/GRPC/images/F024BC6F4F5A4CB5B943C763D8CB2254image.png)





简单的测试就完成了







