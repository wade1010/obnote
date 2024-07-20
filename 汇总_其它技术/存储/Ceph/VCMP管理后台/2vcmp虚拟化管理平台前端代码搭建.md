### 一、如果需要就安装npm 和node

node要8.x，不能安装最新版

先看下本地node，如果不是就卸载 sudo apt remove nodejs npm

wget [https://nodejs.org/dist/latest-v8.x/node-v8.17.0-linux-x64.tar.xz](https://nodejs.org/dist/latest-v8.x/node-v8.17.0-linux-x64.tar.xz)

sudo tar xf node-v8.17.0-linux-x64.tar.xz -C /usr/local/

```bash
sudo ln -s /usr/local/node-v8.17.0-linux-x64/bin/node  /usr/bin/node
sudo ln -s /usr/local/node-v8.17.0-linux-x64/bin/npm  /usr/bin/npm
```

### 二、安装依赖

npm config set registry [https://registry.npm.taobao.org/](https://registry.npm.taobao.org/)

npm install

### 三、增加代理

config/index.js

```
module.exports = {
  dev: {

    // Paths
    assetsSubDirectory: 'statics',
    assetsPublicPath: '/',
    proxyTable: {
      '/api': {
        target: 'http://127.0.0.1:8000',
        changeOrigin: true
      },
      '/ws':{
        target: 'ws://127.0.0.1:8000',
        ws: true
      }
    },

    // Various Dev Server settings
    host: '0.0.0.0', // can be overwritten by process.env.HOST
    port: 9527, // can be overwritten by process.env.PORT, if port is in use, a free one will be determined
    autoOpenBrowser: false,
    errorOverlay: true,
    notifyOnErrors: false,
```

### 三、启动

npm run dev

如果需要node安装别的可执行文件，需要将export PATH="/usr/local/node-v8.17.0-linux-x64/bin:$PATH"加入到source文件里面