Baidu 了一下发现是 Node JS 17 的 BUG，相关 ISSUE 也给出了解决办法，就是修改package.json，在相关构建命令之前加入set NODE_OPTIONS=–openssl-legacy-provider

"scripts": {

"serve": "set NODE_OPTIONS=--openssl-legacy-provider & vue-cli-service serve",

"build": "set NODE_OPTIONS=--openssl-legacy-provider & vue-cli-service build",

"build:report": "set NODE_OPTIONS=--openssl-legacy-provider & vue-cli-service build --report",

如果是 Linux 或者 WSL 环境，请加入

export NODE_OPTIONS=--openssl-legacy-provider

————————————————

版权声明：本文为CSDN博主「北漂燕郊杨哥」的原创文章，遵循CC 4.0 BY-SA版权协议，转载请附上原文出处链接及本声明。

原文链接：[https://blog.csdn.net/itopit/article/details/127280592](https://blog.csdn.net/itopit/article/details/127280592)