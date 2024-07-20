您好，看了router.go里面的代码，按自己理解的操作了下。

go代码注释里面写的是VUE_APP_BASE_API和VUE_APP_BASE_PATH，

我在web/.env.production下看到的是

ENV = 'production'

VITE_CLI_PORT = 8080

VITE_SERVER_PORT = 8888

VITE_BASE_API = /api

#下方修改为你的线上ip

VITE_BASE_PATH = [https://demo.gin-vue-admin.com](https://demo.gin-vue-admin.com)

没有APP字眼。我就把VITE_BASE_API和VITE_BASE_PATH改了下

ENV = 'production'

VITE_CLI_PORT = 8080

VITE_SERVER_PORT = 8888

VITE_BASE_API = /

#下方修改为你的线上ip

VITE_BASE_PATH = [https://127.0.0.1](https://127.0.0.1)

然后 npm run build 

然后把dist目录拷贝到server目录

然后打开router.go里面的4行注释

然后启动server

访问[http://127.0.0.1:8888](http://127.0.0.1:8888/)

报404

然后我看了下路径

改了下router.go

原代码

```
Router.LoadHTMLGlob("./dist/*.html") // npm打包成dist的路径
Router.Static("/favicon.ico", "./dist/favicon.ico")
Router.Static("/static", "./dist/assets")   // dist里面的静态资源
Router.StaticFile("/", "./dist/index.html") // 前端网页入口页面
```

我改了之后

```
Router.LoadHTMLGlob("./dist/*.html") // npm打包成dist的路径
Router.Static("/favicon.ico", "./dist/favicon.ico")
Router.Static("/assets", "./dist/assets")   // dist里面的静态资源
Router.Static("/gva", "./dist/gva")   // dist里面的静态资源
Router.Static("/js", "./dist/js")   // dist里面的静态资源
Router.StaticFile("/", "./dist/index.html") // 前端网页入口页面
```