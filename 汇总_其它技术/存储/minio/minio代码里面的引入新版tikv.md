go get github.com/tikv/client-go/v2



下载后点击go.mod里面的 github.com/tikv/client-go/v2 v2.0.0-alpha  进入到 Go Modules ，复制目录到minio项目根目录下,并改名为tikv



![](https://gitee.com/hxc8/images6/raw/master/img/202407190007902.jpg)



改名后

![](https://gitee.com/hxc8/images6/raw/master/img/202407190007839.jpg)





在 tikv目录 全局替换  github.com/tikv/client-go/v2  替换为  github.com/minio/minio/tikv    这个名字前部分红色部分是go.mod里面的module名称



![](https://gitee.com/hxc8/images6/raw/master/img/202407190007868.jpg)



go.mod 最下方加入 replace google.golang.org/grpc => google.golang.org/grpc v1.29.1



go mod tidy



去掉 go.mod  里面的 github.com/pingcap/tidb v2.0.11+incompatible // indirect    这个没用到  也可以不去





然后跑程序会报如下错误



![](https://gitee.com/hxc8/images6/raw/master/img/202407190007987.jpg)





这就是etcd版本冲突导致的





tikv目录下全局替换 

go.etcd.io/etcd/mvcc/mvccpb 替换为  go.etcd.io/etcd/api/v3/mvccpb

go.etcd.io/etcd/clientv3 替换为  go.etcd.io/etcd/client/v3





ok!

 

