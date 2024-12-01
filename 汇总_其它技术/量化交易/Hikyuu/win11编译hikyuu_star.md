## 安装正确的vs

vs版本说要求是2022 17.9及以下

版本下载链接：
https://answers.microsoft.com/zh-hans/msoffice/forum/all/%E5%A6%82%E4%BD%95%E4%B8%8B%E8%BD%BDms-visual/576e8cab-00b3-4371-9824-3eb698903a19

如果您只是需要下载 Visual Studio 2022 Community 17.9.1 版本，可以点击这个链接下载：

[https://download.visualstudio.microsoft.com/download/pr/63fee7e3-bede-41ad-97a2-97b8b9f535d1/26d25ab6417a061f392e4a679d5662abc348423a52febcad809e4075e38852e8/vs_Community.exe](https://download.visualstudio.microsoft.com/download/pr/63fee7e3-bede-41ad-97a2-97b8b9f535d1/26d25ab6417a061f392e4a679d5662abc348423a52febcad809e4075e38852e8/vs_Community.exe "download.visualstudio.microsoft.com")

如果不信任上述链接，或者希望下载其它历史版本，可以使用 Winget 包管理工具来安装旧版 Visual Studio 2022 Community：  
  
按下 **Win+R**，输入 **cmd**，然后按下回车，打开命令提示符。输入：

```
winget show --versions "Microsoft.VisualStudio.2022.Community"
```

按下回车运行。  
  
系统输出一列 **17.x.x** 格式的版本号，从中找到你需要安装的版本。

此处以 17.9.1 版本为例，输入（注意把里面的 _17.9.1_ 替换成您实际需要安装的版本）：

```
winget install -v 17.9.1 "Microsoft.VisualStudio.2022.Community"
```

按下回车运行，就会开始安装对应版本的 Visual Studio。

（安装过程中，可能会弹出提示 Do you agree to all the source agreement terms? [Y] Yes [N] No:

如果弹出了以上提示，输入 **y**，按下回车运行。）

## 下载hikyuu_star代码
这个需要群主给权限下载，代码在gitecode上面

## python环境
conda create -n hikyuu_start python=3.12
pip install hikyuu

## 编译测试
进入项目根目录

cd star_hub
