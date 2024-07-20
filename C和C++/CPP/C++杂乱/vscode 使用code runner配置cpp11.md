- 打开 文件 -> 首选项 -> 设置 -> 输入 setting.json -> 打开 "在 setting.json 中编辑"

- 输入 "code-runner.executorMap" -> 点击回车 -> 自动补全

- 找到 "cpp" 选项 -> 将 " : " 之后的值改为 "cd $dir && g++ -std=c++11 $fileName -o $fileNameWithoutExt && $dir$fileNameWithoutExt"

- 保存退出 -> 即可编译 c++11 标准的 cpp 文件

![](https://gitee.com/hxc8/images3/raw/master/img/202407172242452.jpg)

![](https://gitee.com/hxc8/images3/raw/master/img/202407172242660.jpg)

![](https://gitee.com/hxc8/images3/raw/master/img/202407172242230.jpg)

![](https://gitee.com/hxc8/images3/raw/master/img/202407172242978.jpg)