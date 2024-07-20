### 安装

> brew install npm


```
[0] % brew install npm
==> Downloading https://mirrors.ustc.edu.cn/homebrew-bottles/bottles/node-14.2.0.catalina.bottle.tar.gz
######################################################################## 100.0%
==> Pouring node-14.2.0.catalina.bottle.tar.gz
==> Caveats
Bash completion has been installed to:
  /usr/local/etc/bash_completion.d
==> Summary
🍺  /usr/local/Cellar/node/14.2.0: 4,659 files, 60.8MB

```

### 测试

⇒  node -v
dyld: Library not loaded: /usr/local/opt/icu4c/lib/libicui18n.66.dylib
  Referenced from: /usr/local/bin/node
  Reason: image not found
[1]    7893 abort      node -v


#### 原因
我这里本地切换到的php5.6，而PHP5.6用的icu4c是64老版本

#### 解决办法 都是建立在我 整个安装流程得出的

1、装东西的时候切换到PHP7(PHP5.6会报错)

> brew-php-switcher 7.3

2、只切换 icu4c版本(PHP5.6会报错)

> brew switch icu4c 66.1


如果你不是按我整套顺序安装的，更新下icu4c到最新版本就好了

> brew upgrade icu4c

### 使用国内镜像-淘宝镜像

> npm config set registry https://registry.npm.taobao.org

#### 验证

> npm config get registry

### 使用

进入项目根目录 使用 npm install 安装package.json中的包