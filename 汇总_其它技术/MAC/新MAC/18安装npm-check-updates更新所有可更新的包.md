### 安装

> npm install -g npm-check-updates


```
 npm install -g npm-check-updates                                                                                                                                                     (master✱) 
npm WARN deprecated request@2.88.2: request has been deprecated, see https://github.com/request/request/issues/3142
/usr/local/bin/npm-check-updates -> /usr/local/lib/node_modules/npm-check-updates/bin/npm-check-updates
/usr/local/bin/ncu -> /usr/local/lib/node_modules/npm-check-updates/bin/ncu
+ npm-check-updates@6.0.1
added 311 packages from 141 contributors in 96.121s

```


### 使用：

检查package.json中dependencies的最新版本：

>ncu

更新dependencies到新版本：

>ncu -u


更新全部到最新版本：

>npm install


```
[bob:...e/jisqworkspace/ijisq-front]$ npm install                                                                                                                                                                           (master✱) 
npm WARN deprecated chokidar@2.1.8: Chokidar 2 will break on node v14+. Upgrade to chokidar 3 with 15x less dependencies.
npm WARN deprecated fsevents@1.2.13: fsevents 1 will break on node v14+ and could be using insecure binaries. Upgrade to fsevents 2.
npm notice created a lockfile as package-lock.json. You should commit this file.
npm WARN notsup Unsupported engine for watchpack-chokidar2@2.0.0: wanted: {"node":"<8.10.0"} (current: {"node":"14.2.0","npm":"6.14.4"})
npm WARN notsup Not compatible with your version of node/npm: watchpack-chokidar2@2.0.0
npm WARN extract-text-webpack-plugin@3.0.2 requires a peer of webpack@^3.1.0 but none is installed. You must install peer dependencies yourself.
npm WARN babel-loader@8.1.0 requires a peer of @babel/core@^7.0.0 but none is installed. You must install peer dependencies yourself.
npm WARN eslint-plugin-vue@6.2.2 requires a peer of eslint@^5.0.0 || ^6.0.0 but none is installed. You must install peer dependencies yourself.

added 145 packages from 90 contributors, removed 395 packages, updated 126 packages, moved 18 packages and audited 1250 packages in 218.023s

```
