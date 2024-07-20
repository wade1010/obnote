![](https://gitee.com/hxc8/images4/raw/master/img/202407172255238.jpg)

![](https://gitee.com/hxc8/images4/raw/master/img/202407172255823.jpg)

![](https://gitee.com/hxc8/images4/raw/master/img/202407172255533.jpg)

![](https://gitee.com/hxc8/images4/raw/master/img/202407172255053.jpg)

![](https://gitee.com/hxc8/images4/raw/master/img/202407172255506.jpg)

![](https://gitee.com/hxc8/images4/raw/master/img/202407172255464.jpg)

![](https://gitee.com/hxc8/images4/raw/master/img/202407172255176.jpg)

[https://rustwasm.github.io/docs/book/game-of-life/setup.html](https://rustwasm.github.io/docs/book/game-of-life/setup.html)

# Install wasm-pack

1 、 curl [https://rustwasm.github.io/wasm-pack/installer/init.sh](https://rustwasm.github.io/wasm-pack/installer/init.sh) -sSf | sh

```
% curl https://rustwasm.github.io/wasm-pack/installer/init.sh -sSf | sh

info: downloading wasm-pack
info: successfully installed wasm-pack to `/Users/xxx/.cargo/bin/wasm-pack`
```

2、 cargo install cargo-generate

3、如果没装npm就装下

[https://rustwasm.github.io/docs/book/game-of-life/hello-world.html](https://rustwasm.github.io/docs/book/game-of-life/hello-world.html)

1、cargo generate --git [https://github.com/rustwasm/wasm-pack-template](https://github.com/rustwasm/wasm-pack-template)

![](https://gitee.com/hxc8/images4/raw/master/img/202407172255493.jpg)

build 下项目

进入项目根目录执行 wasm-pack build

![](https://gitee.com/hxc8/images4/raw/master/img/202407172255887.jpg)

build后生成pkg目录

![](https://gitee.com/hxc8/images4/raw/master/img/202407172255510.jpg)

项目根目录生成生成前端项目

npm init wasm-app www

Using our Local wasm-game-of-life Package in www

```
{
  // ...
  "dependencies": {                     // Add this three lines block!
    "wasm-game-of-life": "file:../pkg"
  },
  "devDependencies": {
    //...
  }
}
```

Next, modify wasm-game-of-life/www/index.js to import wasm-game-of-life instead of the hello-wasm-pack package:

```
import * as wasm from "wasm-game-of-life";

wasm.greet();
```

cd www

npm install

npm run start

访问网页

看到下面alert就OK了

![](https://gitee.com/hxc8/images4/raw/master/img/202407172255704.jpg)

修改代码

打开src/lib.rs

```
#[wasm_bindgen]
pub fn greet() {
    alert("Hello, wasm-game-of-life!");
}

```

改成

```
#[wasm_bindgen]
pub fn greet(str: &str) {
    alert(format!("Hello, {}!", str).as_str());
}
```

返回根目录

wasm-pack build

cd www

npm install && npm run start

打开网址

这时候页面不会alert

修改www/index.js 传参

```
import * as wasm from "wasm-game-of-life";

wasm.greet("rust");

```

修改保存页面就会弹出了

![](https://gitee.com/hxc8/images4/raw/master/img/202407172255276.jpg)