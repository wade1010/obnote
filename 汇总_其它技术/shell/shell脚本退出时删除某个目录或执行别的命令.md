```
DIR=/tmp/install-deps.$$
trap "rm -fr $DIR" EXIT
mkdir -p $DIR

```

这些代码创建一个临时目录，用于存放下载的依赖项。脚本使用 

trap 命令设置了一个退出陷阱，以便在脚本退出时删除临时目录。