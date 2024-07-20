

![](D:/download/youdaonote-pull-master/data/Technology/开发工具/images/51DC611142EF49F1B75E5B279B503C92image.png)



2.安装 bootstrap 或者vue.js 提示插件。安装后重启



然后再想要输入代码的地方按下 CMD+j  然后输入bs就有提示了。或者输入vue





3.安装emmet插件，然后重启

然后输入#id>span.log        输入后按一下tab键就自动生成代码了





4.去掉不用的提示    搜索 live templates  然后去掉前面的勾



![](D:/download/youdaonote-pull-master/data/Technology/开发工具/images/194B73B647894753BB1BA151A5352CDAimage.png)





5.安装laravel插件，搜索下laravel 安装次数最多的那个重启之后，就多了个下图的东西

![](D:/download/youdaonote-pull-master/data/Technology/开发工具/images/4030160E284E42DF8955618114F2FA0Bimage.png)





6.laravel-ide-helper



composer require --dev barryvdh/laravel-ide-helper



Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class,



public function register()

{

    if ($this->app->environment() !== 'production') {

        $this->app->register(\Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class);

    }

    // ...

}



具体可以参考官网

https://packagist.org/packages/barryvdh/laravel-ide-helper



![](D:/download/youdaonote-pull-master/data/Technology/开发工具/images/DC3F8B7248B045B39F69A3ADA138CC72image.png)



![](D:/download/youdaonote-pull-master/data/Technology/开发工具/images/B2C7BC00AAE74580A9685C50E94DD45Aimage.png)



设置完之后



运行命令菜单或使用 Ctrl + Shift + X（Mac上的Cmd + Shift + X ）





7.editorconfig

在 phpstorm 插件中安装 editorconfig 插件，然后在项目根目录创建 .editorconfig 文件内容如下：



---

root = true



[*]

charset = utf-8

end_of_line = lf

insert_final_newline = true

indent_style = space

indent_size = 4

trim_trailing_whitespace = true



[*.md]

trim_trailing_whitespace = false



[*.yml]

indent_style = space

indent_size = 2



---

其他常用 

cmd+1



