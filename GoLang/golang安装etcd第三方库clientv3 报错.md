很简单，在main的目录下再次执行以下前面的步骤即可

go mod init [可添加你的项目名称]
go mod edit -replace github.com/coreos/bbolt@v1.3.4=go.etcd.io/bbolt@v1.3.4
go mod edit -replace google.golang.org/grpc@v1.29.1=google.golang.org/grpc@v1.26.0
go mod tidy
go run main.go





