# GRPC编译安装、各种语言插件及测试

官网

[https://grpc.io/docs/languages/cpp/quickstart/](https://grpc.io/docs/languages/cpp/quickstart/)

编译 安装 官方测试 手写测试 跨语言测试(这里用golang)

# 一、编译和安装

c++中，对于管理项目依赖，没有普遍接受的标准，所以在运行之前，需要自己build和install gRPC

## 1.1源码编译安装

git clone [https://github.com/grpc/grpc](https://github.com/grpc/grpc) --depth 1       （也可以指定版本  git clone -b RELEASE_TAG_HERE [https://github.com/grpc/grpc](https://github.com/grpc/grpc)）

cd grpc

git submodule update --init      （这个可能比较慢，我试了全局科学上网，才行。也没多试）

mkdir -p cmake/build

cd cmake/build

cmake ../..      (这个需要点时间)

make

sudo make install

查看生成的编译生成的东西

```
➜   ls 
CMakeCache.txt            cmake_install.cmake       grpc_csharp_plugin        grpc_ruby_plugin          libgrpc++.a               libgrpc.a                 libupb.a
CMakeFiles                gRPCConfig.cmake          grpc_node_plugin          http_archives             libgrpc++_alts.a          libgrpc_plugin_support.a  protos
CPackConfig.cmake         gRPCConfigVersion.cmake   grpc_objective_c_plugin   lib                       libgrpc++_error_details.a libgrpc_unsecure.a        third_party
CPackSourceConfig.cmake   gens                      grpc_php_plugin           libaddress_sorting.a      libgrpc++_reflection.a    libgrpcpp_channelz.a
Makefile                  grpc_cpp_plugin           grpc_python_plugin        libgpr.a                  libgrpc++_unsecure.a      libs

```

上面可以看出几个参见的plugin有：

```
-rwxr-xr-x   1 bob  staff   6.0M 10 31 19:18 grpc_cpp_plugin
-rwxr-xr-x   1 bob  staff   6.1M 10 31 19:20 grpc_csharp_plugin
-rwxr-xr-x   1 bob  staff   5.9M 10 31 19:20 grpc_node_plugin
-rwxr-xr-x   1 bob  staff   6.0M 10 31 19:20 grpc_objective_c_plugin
-rwxr-xr-x   1 bob  staff   6.1M 10 31 19:20 grpc_php_plugin
-rwxr-xr-x   1 bob  staff   6.0M 10 31 19:20 grpc_python_plugin
-rwxr-xr-x   1 bob  staff   5.8M 10 31 19:20 grpc_ruby_plugin
```

安装protobuf

不要手动安装，不然版本可能和grpc版本不匹配，推荐使用grpc执行git submodule update --init命令后生成的third_party/protobuf里面biany8安装对应的protobuf

cd third_party/protobuf

./autogen.sh 这里第一次执行报错内容如下

```
test -d third_party/googletest
mkdir -p third_party/googletest/m4
autoreconf -f -i -Wall,no-obsolete
Can't exec "aclocal": No such file or directory at /usr/local/Cellar/autoconf/2.71/share/autoconf/Autom4te/FileUtils.pm line 274.
autoreconf: error: aclocal failed with exit status: 2
```

解决 brew install automake

再执行 ./autogen.sh  就OK了

./configure --prefix=/usr/local

make     (时间较久)

sudo make install

protoc --version可以验证

```
> protoc --version                                                                             
libprotoc 3.21.6
```

## 1.2 mac brew 安装

可以参考官网[https://grpc.io/docs/languages/cpp/quickstart/](https://grpc.io/docs/languages/cpp/quickstart/)

```
export MY_INSTALL_DIR=$HOME/.local
mkdir -p $MY_INSTALL_DIR
export PATH="$MY_INSTALL_DIR/bin:$PATH"
brew install cmake
brew install autoconf automake libtool pkg-config
//下面这一步很慢，可能还有失败的，后来终端科学上网才完全OK,有一次没下载全，下面cmake命令会失败
//如果下面这一步试了几次还有失败，可以cd grpc 执行 git submodule update --init
git clone --recurse-submodules -b v1.50.0 --depth 1 --shallow-submodules https://github.com/grpc/grpc
cd grpc
mkdir -p cmake/build
pushd cmake/build
cmake -DgRPC_INSTALL=ON \
      -DgRPC_BUILD_TESTS=OFF \
      -DCMAKE_INSTALL_PREFIX=$MY_INSTALL_DIR \
      ../..
make -j 4    
make install
popd
```

# 二、测试

## 2.1测试官方示例

```
$ cd examples/cpp/helloworld
$ mkdir -p cmake/build
$ pushd cmake/build
$ cmake -DCMAKE_PREFIX_PATH=$MY_INSTALL_DIR ../..
$ make -j 4
```

启动 server

./greeter_server

```
> $ ./greeter_server                                                                                       [±91091e3 ✓]
Server listening on 0.0.0.0:50051
```

另外一个终端打开执行

./greeter_client

```
./greeter_client
Greeter received: Hello world
```

更新proto文件

cd examples/protos

vim helloworld.proto

```
syntax = "proto3";

option java_multiple_files = true;
option java_package = "io.grpc.examples.helloworld";
option java_outer_classname = "HelloWorldProto";
option objc_class_prefix = "HLW";

package helloworld;

// The greeting service definition.
service Greeter {
  // Sends a greeting
  rpc SayHello (HelloRequest) returns (HelloReply) {}
  rpc SayHelloAgain (HelloRequest) returns (HelloReply) {}   //添加这一行
}

// The request message containing the user's name.
message HelloRequest {
  string name = 1;
}

// The response message containing the greetings
message HelloReply {
  string message = 1;
}
```

保存后，重新生成grpc代码

cd examples/cpp/helloworld/cmake/build

make -j 8

更新server端代码

greeter_server.cc

```
class GreeterServiceImpl final : public Greeter::Service {
  Status SayHello(ServerContext* context, const HelloRequest* request,
                  HelloReply* reply) override {
     // ...
  }
//添加如下代码
  Status SayHelloAgain(ServerContext* context, const HelloRequest* request,
                       HelloReply* reply) override {
    std::string prefix("Hello again ");
    reply->set_message(prefix + request->name());
    return Status::OK;
  }
};
```

更新client端

greeter_client.cc

```
class GreeterClient {
 public:
  // ...
  std::string SayHello(const std::string& user) {
     // ...
  }
//添加代码如下
  std::string SayHelloAgain(const std::string& user) {
    // Follows the same pattern as SayHello.
    HelloRequest request;
    request.set_name(user);
    HelloReply reply;
    ClientContext context;

    // Here we can use the stub's newly available method we just added.
    Status status = stub_->SayHelloAgain(&context, request, &reply);
    if (status.ok()) {
      return reply.message();
    } else {
      std::cout << status.error_code() << ": " << status.error_message()
                << std::endl;
      return "RPC failed";
    }
  }
```

在client 的main函数里面调用

```
int main(int argc, char** argv) {
  // ...
  std::string reply = greeter.SayHello(user);
  std::cout << "Greeter received: " << reply << std::endl;
//添加代码如下
  reply = greeter.SayHelloAgain(user);
  std::cout << "Greeter received: " << reply << std::endl;

  return 0;
}
```

运行

cd examples/cpp/helloworld/cmake/build

编译 make -j 8 

启动server     ./greeter_server

```
./greeter_server
Server listening on 0.0.0.0:50051
```

其他终端测试client

```
./greeter_client
Greeter received: Hello world
Greeter received: Hello again world
```

## 2.2自己写的测试

创建项目

![](D:/download/youdaonote-pull-master/data/Technology/RPC/GRPC/images/WEBRESOURCE881ac28457984c755350cf8da888f429截图.png)

cmake/common.cmake 是官方的内容，直接复制来就行

[https://github.com/grpc/grpc/blob/master/examples/cpp/cmake/common.cmake](https://github.com/grpc/grpc/blob/master/examples/cpp/cmake/common.cmake)

2022-11-02 14:32:55时内容如下

```
# Copyright 2018 gRPC authors.
#
# Licensed under the Apache License, Version 2.0 (the "License");
# you may not use this file except in compliance with the License.
# You may obtain a copy of the License at
#
#     http://www.apache.org/licenses/LICENSE-2.0
#
# Unless required by applicable law or agreed to in writing, software
# distributed under the License is distributed on an "AS IS" BASIS,
# WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
# See the License for the specific language governing permissions and
# limitations under the License.
#
# cmake build file for C++ route_guide example.
# Assumes protobuf and gRPC have been installed using cmake.
# See cmake_externalproject/CMakeLists.txt for all-in-one cmake build
# that automatically builds all the dependencies before building route_guide.

cmake_minimum_required(VERSION 3.5.1)

if (NOT DEFINED CMAKE_CXX_STANDARD)
  set (CMAKE_CXX_STANDARD 14)
endif()

if(MSVC)
  add_definitions(-D_WIN32_WINNT=0x600)
endif()

find_package(Threads REQUIRED)

if(GRPC_AS_SUBMODULE)
  # One way to build a projects that uses gRPC is to just include the
  # entire gRPC project tree via "add_subdirectory".
  # This approach is very simple to use, but the are some potential
  # disadvantages:
  # * it includes gRPC's CMakeLists.txt directly into your build script
  #   without and that can make gRPC's internal setting interfere with your
  #   own build.
  # * depending on what's installed on your system, the contents of submodules
  #   in gRPC's third_party/* might need to be available (and there might be
  #   additional prerequisites required to build them). Consider using
  #   the gRPC_*_PROVIDER options to fine-tune the expected behavior.
  #
  # A more robust approach to add dependency on gRPC is using
  # cmake's ExternalProject_Add (see cmake_externalproject/CMakeLists.txt).

  # Include the gRPC's cmake build (normally grpc source code would live
  # in a git submodule called "third_party/grpc", but this example lives in
  # the same repository as gRPC sources, so we just look a few directories up)
  add_subdirectory(../../.. ${CMAKE_CURRENT_BINARY_DIR}/grpc EXCLUDE_FROM_ALL)
  message(STATUS "Using gRPC via add_subdirectory.")

  # After using add_subdirectory, we can now use the grpc targets directly from
  # this build.
  set(_PROTOBUF_LIBPROTOBUF libprotobuf)
  set(_REFLECTION grpc++_reflection)
  if(CMAKE_CROSSCOMPILING)
    find_program(_PROTOBUF_PROTOC protoc)
  else()
    set(_PROTOBUF_PROTOC $<TARGET_FILE:protobuf::protoc>)
  endif()
  set(_GRPC_GRPCPP grpc++)
  if(CMAKE_CROSSCOMPILING)
    find_program(_GRPC_CPP_PLUGIN_EXECUTABLE grpc_cpp_plugin)
  else()
    set(_GRPC_CPP_PLUGIN_EXECUTABLE $<TARGET_FILE:grpc_cpp_plugin>)
  endif()
elseif(GRPC_FETCHCONTENT)
  # Another way is to use CMake's FetchContent module to clone gRPC at
  # configure time. This makes gRPC's source code available to your project,
  # similar to a git submodule.
  message(STATUS "Using gRPC via add_subdirectory (FetchContent).")
  include(FetchContent)
  FetchContent_Declare(
    grpc
    GIT_REPOSITORY https://github.com/grpc/grpc.git
    # when using gRPC, you will actually set this to an existing tag, such as
    # v1.25.0, v1.26.0 etc..
    # For the purpose of testing, we override the tag used to the commit
    # that's currently under test.
    GIT_TAG        vGRPC_TAG_VERSION_OF_YOUR_CHOICE)
  FetchContent_MakeAvailable(grpc)

  # Since FetchContent uses add_subdirectory under the hood, we can use
  # the grpc targets directly from this build.
  set(_PROTOBUF_LIBPROTOBUF libprotobuf)
  set(_REFLECTION grpc++_reflection)
  set(_PROTOBUF_PROTOC $<TARGET_FILE:protoc>)
  set(_GRPC_GRPCPP grpc++)
  if(CMAKE_CROSSCOMPILING)
    find_program(_GRPC_CPP_PLUGIN_EXECUTABLE grpc_cpp_plugin)
  else()
    set(_GRPC_CPP_PLUGIN_EXECUTABLE $<TARGET_FILE:grpc_cpp_plugin>)
  endif()
else()
  # This branch assumes that gRPC and all its dependencies are already installed
  # on this system, so they can be located by find_package().

  # Find Protobuf installation
  # Looks for protobuf-config.cmake file installed by Protobuf's cmake installation.
  set(protobuf_MODULE_COMPATIBLE TRUE)
  find_package(Protobuf CONFIG REQUIRED)
  message(STATUS "Using protobuf ${Protobuf_VERSION}")

  set(_PROTOBUF_LIBPROTOBUF protobuf::libprotobuf)
  set(_REFLECTION gRPC::grpc++_reflection)
  if(CMAKE_CROSSCOMPILING)
    find_program(_PROTOBUF_PROTOC protoc)
  else()
    set(_PROTOBUF_PROTOC $<TARGET_FILE:protobuf::protoc>)
  endif()

  # Find gRPC installation
  # Looks for gRPCConfig.cmake file installed by gRPC's cmake installation.
  find_package(gRPC CONFIG REQUIRED)
  message(STATUS "Using gRPC ${gRPC_VERSION}")

  set(_GRPC_GRPCPP gRPC::grpc++)
  if(CMAKE_CROSSCOMPILING)
    find_program(_GRPC_CPP_PLUGIN_EXECUTABLE grpc_cpp_plugin)
  else()
    set(_GRPC_CPP_PLUGIN_EXECUTABLE $<TARGET_FILE:gRPC::grpc_cpp_plugin>)
  endif()
endif()

```

helloworld.proto

```
syntax = "proto3";
option java_multiple_files = true;
option java_package = "io.grpc.examples.helloworld";
option java_outer_classname = "HelloWorldProto";
option objc_class_prefix = "HLW";
package helloworld;
service Greeter {
  rpc SayHello (HelloRequest) returns (HelloReply) {}
  rpc SayHelloAgain (HelloRequest) returns (HelloReply) {}
}

message HelloRequest {
  string name = 1;
}

message HelloReply {
  string message = 1;
}

```

CMakeLists.txt

```
cmake_minimum_required(VERSION 3.5.1)

project(HelloWorld C CXX)

include(./cmake/common.cmake)

# Proto file
get_filename_component(hw_proto "./protos/helloworld.proto" ABSOLUTE)
get_filename_component(hw_proto_path "${hw_proto}" PATH)

# Generated sources
set(hw_proto_srcs "${CMAKE_CURRENT_BINARY_DIR}/helloworld.pb.cc")
set(hw_proto_hdrs "${CMAKE_CURRENT_BINARY_DIR}/helloworld.pb.h")
set(hw_grpc_srcs "${CMAKE_CURRENT_BINARY_DIR}/helloworld.grpc.pb.cc")
set(hw_grpc_hdrs "${CMAKE_CURRENT_BINARY_DIR}/helloworld.grpc.pb.h")
add_custom_command(
        OUTPUT "${hw_proto_srcs}" "${hw_proto_hdrs}" "${hw_grpc_srcs}" "${hw_grpc_hdrs}"
        COMMAND ${_PROTOBUF_PROTOC}
        ARGS --grpc_out "${CMAKE_CURRENT_BINARY_DIR}"
        --cpp_out "${CMAKE_CURRENT_BINARY_DIR}"
        -I "${hw_proto_path}"
        --plugin=protoc-gen-grpc="${_GRPC_CPP_PLUGIN_EXECUTABLE}"
        "${hw_proto}"
        DEPENDS "${hw_proto}")

# Include generated *.pb.h files
include_directories("${CMAKE_CURRENT_BINARY_DIR}")

# hw_grpc_proto
add_library(hw_grpc_proto
        ${hw_grpc_srcs}
        ${hw_grpc_hdrs}
        ${hw_proto_srcs}
        ${hw_proto_hdrs})
target_link_libraries(hw_grpc_proto
        ${_REFLECTION}
        ${_GRPC_GRPCPP}
        ${_PROTOBUF_LIBPROTOBUF})

foreach(_target
        greeter_client greeter_server)
    add_executable(${_target} "${_target}.cc")
    target_link_libraries(${_target}
            hw_grpc_proto
            ${_REFLECTION}
            ${_GRPC_GRPCPP}
            ${_PROTOBUF_LIBPROTOBUF})
endforeach()

```

greeter_server.cc

```
#include <iostream>
#include <memory>
#include <string>

#include <grpcpp/ext/proto_server_reflection_plugin.h>
#include <grpcpp/grpcpp.h>
#include <grpcpp/health_check_service_interface.h>

#ifdef BAZEL_BUILD
#include "examples/protos/helloworld.grpc.pb.h"
#else
#include "helloworld.grpc.pb.h"
#endif

using grpc::Server;
using grpc::ServerBuilder;
using grpc::ServerContext;
using grpc::Status;
using helloworld::Greeter;
using helloworld::HelloReply;
using helloworld::HelloRequest;

// Logic and data behind the server's behavior.
class GreeterServiceImpl final : public Greeter::Service {
    Status SayHello(ServerContext* context, const HelloRequest* request,
                    HelloReply* reply) override {
        std::string prefix("Hello ");
        reply->set_message(prefix + request->name());
        return Status::OK;
    }
    Status SayHelloAgain(ServerContext* context, const HelloRequest* request,
                         HelloReply* reply) override {
        std::string prefix("Hello again ");
        reply->set_message(prefix + request->name());
        return Status::OK;
    }
};

void RunServer() {
    std::string server_address("0.0.0.0:50051");
    GreeterServiceImpl service;

    grpc::EnableDefaultHealthCheckService(true);
    grpc::reflection::InitProtoReflectionServerBuilderPlugin();
    ServerBuilder builder;
    builder.AddListeningPort(server_address, grpc::InsecureServerCredentials());
    builder.RegisterService(&service);
    std::unique_ptr<Server> server(builder.BuildAndStart());
    std::cout << "Server listening on " << server_address << std::endl;
    server->Wait();
}

int main(int argc, char** argv) {
    RunServer();

    return 0;
}

```

greeter_client.cc

```
#include <iostream>
#include <memory>
#include <string>

#include <grpcpp/grpcpp.h>

#ifdef BAZEL_BUILD
#include "examples/protos/helloworld.grpc.pb.h"
#else
#include "helloworld.grpc.pb.h"
#endif

using grpc::Channel;
using grpc::ClientContext;
using grpc::Status;
using helloworld::Greeter;
using helloworld::HelloReply;
using helloworld::HelloRequest;

class GreeterClient {
public:
    GreeterClient(std::shared_ptr<Channel> channel)
            : stub_(Greeter::NewStub(channel)) {}

    // Assembles the client's payload, sends it and presents the response back
    // from the server.
    std::string SayHello(const std::string& user) {
        // Data we are sending to the server.
        HelloRequest request;
        request.set_name(user);

        // Container for the data we expect from the server.
        HelloReply reply;

        // Context for the client. It could be used to convey extra information to
        // the server and/or tweak certain RPC behaviors.
        ClientContext context;

        // The actual RPC.
        Status status = stub_->SayHello(&context, request, &reply);

        // Act upon its status.
        if (status.ok()) {
            return reply.message();
        } else {
            std::cout << status.error_code() << ": " << status.error_message()
                      << std::endl;
            return "RPC failed";
        }
    }

    std::string SayHelloAgain(const std::string& user) {
        // Follows the same pattern as SayHello.
        HelloRequest request;
        request.set_name(user);
        HelloReply reply;
        ClientContext context;

        // Here we can use the stub's newly available method we just added.
        Status status = stub_->SayHelloAgain(&context, request, &reply);
        if (status.ok()) {
            return reply.message();
        } else {
            std::cout << status.error_code() << ": " << status.error_message()
                      << std::endl;
            return "RPC failed";
        }
    }

private:
    std::unique_ptr<Greeter::Stub> stub_;
};

int main(int argc, char** argv) {
    // Instantiate the client. It requires a channel, out of which the actual RPCs
    // are created. This channel models a connection to an endpoint specified by
    // the argument "--target=" which is the only expected argument.
    // We indicate that the channel isn't authenticated (use of
    // InsecureChannelCredentials()).
    std::string target_str;
    std::string arg_str("--target");
    if (argc > 1) {
        std::string arg_val = argv[1];
        size_t start_pos = arg_val.find(arg_str);
        if (start_pos != std::string::npos) {
            start_pos += arg_str.size();
            if (arg_val[start_pos] == '=') {
                target_str = arg_val.substr(start_pos + 1);
            } else {
                std::cout << "The only correct argument syntax is --target="
                          << std::endl;
                return 0;
            }
        } else {
            std::cout << "The only acceptable argument is --target=" << std::endl;
            return 0;
        }
    } else {
        target_str = "localhost:50051";
    }
    GreeterClient greeter(
            grpc::CreateChannel(target_str, grpc::InsecureChannelCredentials()));
    std::string user("world");
    std::string reply = greeter.SayHello(user);
    std::cout << "Greeter received: " << reply << std::endl;

    reply = greeter.SayHelloAgain(user);
    std::cout << "Greeter received: " << reply << std::endl;

    return 0;
}

```

开始编译

protoc --cpp_out=./ protos/helloworld.proto

protoc --grpc_out=. --plugin=protoc-gen-grpc=which grpc_cpp_plugin protos/helloworld.proto

![](D:/download/youdaonote-pull-master/data/Technology/RPC/GRPC/images/WEBRESOURCE9df0555ad6145a280d9be8711dce08d3截图.png)

mkdir build && cd build

cmake ..

make -j 4

运行

```
 ./greeter_server
Server listening on 0.0.0.0:50051
```

client连接

```
./greeter_client
Greeter received: Hello world
Greeter received: Hello again world

```

2.3golang作为lient调用

项目结构

├── go.mod

├── go.sum

├── main.go

└── protos

  └── helloworld.proto

![](D:/download/youdaonote-pull-master/data/Technology/RPC/GRPC/images/WEBRESOURCE5ae383271f153adc16e3b900a1239aba截图.png)

proto内容  注意go_package

```
syntax = "proto3";
//option go_package = "path;name";
option go_package = "./protos;pb";

package helloworld;
service Greeter {
    rpc SayHello (HelloRequest) returns (HelloReply) {}
    rpc SayHelloAgain (HelloRequest) returns (HelloReply) {}
}

message HelloRequest {
    string name = 1;
}

message HelloReply {
    string message = 1;
}

```

执行下面两个命令

protoc --go_out=. --go_opt=paths=source_relative protos/helloworld.proto

protoc --go-grpc_out=. --go-grpc_opt=paths=source_relative protos/helloworld.proto

会生成两个文件

![](D:/download/youdaonote-pull-master/data/Technology/RPC/GRPC/images/WEBRESOURCE8b2c99be2241ba1af75efe533e90f9b9截图.png)

main.go

```
package main

import (
   pb "awesomeProject1/protos"
   "context"
   "fmt"
   "google.golang.org/grpc"
   "google.golang.org/grpc/credentials/insecure"
   "log"
)

func main() {
   conn, err := grpc.Dial("localhost:50051", grpc.WithTransportCredentials(insecure.NewCredentials()))
   defer conn.Close()
   if err != nil {
      log.Fatal(err)
   }
   client := pb.NewGreeterClient(conn)
   reply, err := client.SayHello(context.Background(), &pb.HelloRequest{Name: "test"})
   if err != nil {
      fmt.Println(err)
   }
   fmt.Println(reply)
}

```

go run main.go

```
 go run main.go                                                                                                                                                                                                               bob@cmbp
message:"Hello test"

```

OK