默认使用的是proxy.golang.org，在国内无法访问，如下图所示：



```javascript
bogon:demo-path user$ make build_darwin
rm -rf target/demo-0.6.0 
mkdir -p target/demo-0.6.0/bin 
env CGO_ENABLED=1 GO111MODULE=on go run build/spec.go target/demo-0.6.0/bin/demo-spec-0.6.0.yaml
go: github.com/StackExchange/wmi@v0.0.0-20190523213315-cbe66965904d: Get "https://proxy.golang.org/github.com/%21stack%21exchange/wmi/@v/v0.0.0-20190523213315-cbe66965904d.mod": dial tcp 34.64.4.17:443: i/o timeout
make: *** [build_yaml] Error 1
bogon:demo-path user$ make build_darwin
```





解决方法：

换一个国内能访问的代理地址：https://goproxy.cn

执行命令：

```javascript
go env -w GOPROXY=https://goproxy.cn
```




重新执行命令，完美通过！











go env -w GO111MODULE=on

go env -w GOPROXY=https://goproxy.cn,https://goproxy.io,direct