TODO

上一章节把所有包给升级到最新版本，webpack也升级到4了。

这里就花点时间琢磨下方法

先安装 vuecli 

> npm install -g @vue/cli

即使是淘宝源有时候也下载不下来，多试几次就好了。



```
npm install -g @vue/cli                                            bob@cmbp
npm WARN deprecated chokidar@2.1.8: Chokidar 2 will break on node v14+. Upgrade to chokidar 3 with 15x less dependencies.
/usr/local/bin/vue -> /usr/local/lib/node_modules/@vue/cli/bin/vue.js

> fsevents@1.2.13 install /usr/local/lib/node_modules/@vue/cli/node_modules/fsevents
> node install.js

  SOLINK_MODULE(target) Release/.node
  CXX(target) Release/obj.target/fse/fsevents.o
  SOLINK_MODULE(target) Release/fse.node

> core-js@3.6.5 postinstall /usr/local/lib/node_modules/@vue/cli/node_modules/core-js
> node -e "try{require('./postinstall')}catch(e){}"

Thank you for using core-js ( https://github.com/zloirock/core-js ) for polyfilling JavaScript standard library!

The project needs your help! Please consider supporting of core-js on Open Collective or Patreon: 
> https://opencollective.com/core-js 
> https://www.patreon.com/zloirock 

Also, the author of core-js ( https://github.com/zloirock ) is looking for a good job -)


> @apollo/protobufjs@1.0.3 postinstall /usr/local/lib/node_modules/@vue/cli/node_modules/@apollo/protobufjs
> node scripts/postinstall


> nodemon@1.19.4 postinstall /usr/local/lib/node_modules/@vue/cli/node_modules/nodemon
> node bin/postinstall || exit 0

Love nodemon? You can now support the project via the open collective:
 > https://opencollective.com/nodemon/donate


> ejs@2.7.4 postinstall /usr/local/lib/node_modules/@vue/cli/node_modules/ejs
> node ./postinstall.js

Thank you for installing EJS: built with the Jake JavaScript build tool (https://jakejs.com/)

+ @vue/cli@4.3.1
added 1176 packages from 675 contributors in 38.847s
```
