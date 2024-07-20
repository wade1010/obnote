https://www.liwenzhou.com/posts/Go/protobuf/



protobuf是一种高效的数据格式，平台无关、语言无关、可扩展，可用于 RPC 系统和持续数据存储系统。

protobuf

protobuf介绍

Protobuf是Protocol Buffer的简称，它是Google公司于2008年开源的一种高效的平台无关、语言无关、可扩展的数据格式，目前Protobuf作为接口规范的描述语言，可以作为Go语言RPC接口的基础工具。

protobuf使用

protobuf是一个与语言无关的一个数据协议，所以我们需要先编写IDL文件然后借助专用工具生成指定语言的代码，从而实现数据的序列化与反序列化过程。

大致开发流程如下： 1. IDL编写 2. 生成指定语言的代码 3. 序列化和反序列化

protobuf语法

protobuf3语法指南

编译器安装

ptotoc

protobuf协议编译器是用c++编写的，根据自己的操作系统下载对应版本的protoc编译器：https://github.com/protocolbuffers/protobuf/releases，解压后拷贝到GOPATH/bin目录下。

protoc-gen-go

安装生成Go语言代码的工具

go get -u github.com/golang/protobuf/protoc-gen-go


编写IDL代码

在protobuf_demo/address目录下新建一个名为person.proto的文件具体内容如下：

// 指定使用protobuf版本
// 此处使用v3版本
syntax = "proto3";

// 包名，通过protoc生成go文件
package address;

// 性别类型
// 枚举类型第一个字段必须为0
enum GenderType {
    SECRET = 0;
    FEMALE = 1;
    MALE = 2;
}

// 人
message Person {
    int64 id = 1;
    string name = 2;
    GenderType gender = 3;
    string number = 4;
}

// 联系簿
message ContactBook {
    repeated Person persons = 1;
}


生成go语言代码

在protobuf_demo/address目录下执行以下命令。

address $ protoc --go_out=. ./person.proto 


此时在当前目录下会生成一个person.pb.go文件，我们的Go语言代码里就是使用这个文件。 在protobuf_demo/main.go文件中：

package main

import (
	"fmt"
	"io/ioutil"

	"github.com/golang/protobuf/proto"

	"github.com/Q1mi/studygo/code_demo/protobuf_demo/address"
)

// protobuf demo

func main() {
	var cb address.ContactBook

	p1 := address.Person{
		Name:   "小王子",
		Gender: address.GenderType_MALE,
		Number: "7878778",
	}
	fmt.Println(p1)
	cb.Persons = append(cb.Persons, &p1)
	// 序列化
	data, err := proto.Marshal(&p1)
	if err != nil {
		fmt.Printf("marshal failed,err:%v\n", err)
		return
	}
	ioutil.WriteFile("./proto.dat", data, 0644)

	data2, err := ioutil.ReadFile("./proto.dat")
	if err != nil {
		fmt.Printf("read file failed, err:%v\n", err)
		return
	}
	var p2 address.Person
	proto.Unmarshal(data2, &p2)
	fmt.Println(p2)
}