chrome扩展安装不了

错误信息：程序包无效。详细信息：“Cannot load extension with file or directory name _. Filenames starting with "_" are reserved for use by the system.”。



解决办法：

先下载离线crx安装包：参考百度百科《下​载​c​h​r​o​m​e​插​件​和​离​线​安​装​C​R​X​文​件​的​方​法》

把crx后缀名改为rar，解压缩得到文件夹（有错误提示不用理会）

打开该文件夹，把里面的"_metadata"文件夹改名为"metadata"（去掉下杠）

进入扩展程序中心，启用开发者模式，加载正在开发的程序包，选择刚才的文件夹就行了，搞定！



另外提示这个错误是因为goole版本太老或者太新（没搞清楚，应该是太旧了），下个新的版本。再安装就行了。（不要从百度推荐的那个下，貌似那个更新很慢）