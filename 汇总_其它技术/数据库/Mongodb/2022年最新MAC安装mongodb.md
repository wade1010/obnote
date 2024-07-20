## 遇到的问题

1. MongoDB 官网，没有提供安装包，只提供了 tgz 压缩包，要手动安装，比较麻烦

1. Homebrew 由于网络问题，也很难安装成功

## 解决办法

使用国内定制版的 Homebrew 安装脚本安装 Homebrew，解决 Homebrew 安装的网络问题，再使用 Homebrew 命令直接安装 MongoDB。

MongoDB Compass 图形客户端，直接从 MongoDB 官网下载安装包安装。

## 安装 MongoDB

1. 安装 Xcode 命令行工具（已安装过的请忽略，安装略慢需要耐心等待）

```
xcode-select --install
```

2. 安装 Homebrew，过程中会提示选择镜像源等信息，依次选择中科大源、Y、开机密码：

```
/bin/zsh -c "$(curl -fsSL https://gitee.com/cunkai/HomebrewCN/raw/master/Homebrew.sh)"
```

3. 安装 MongoDB

安装好 Homebrew 后，需要把终端关闭再重新打开，才能会有 brew 命令，来安装 MongoDB

```
# 设置 MongoDB 安装源
brew tap mongodb/brew

# 安装 MongoDB
brew install mongodb-community

# 启动 MongoDB，并设置为开机自动启动
brew services start mongodb/brew/mongodb-community
```

```
To start mongodb/brew/mongodb-community now and restart at login:
  brew services start mongodb/brew/mongodb-community
Or, if you don't want/need a background service you can just run:
  mongod --config /usr/local/etc/mongod.conf
```

##  安装 Compass

[https://www.mongodb.com/try/download/compass](https://www.mongodb.com/try/download/compass)