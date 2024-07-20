[https://cloud.tencent.com/developer/article/1967583](https://cloud.tencent.com/developer/article/1967583)

### **1. 背景&解决方案**

JuiceFS的Badger引擎改造完成以后，需要在Windows下面进行后台运行。因为现有的JuiceFS中还没有在Windows下的后台运行实现，所以需要通过其他途径解决。

现有代码地址 [https://github.com/juicedata/juicefs/blob/main/cmd/mount_windows.go#L48](https://github.com/juicedata/juicefs/blob/main/cmd/mount_windows.go#L48)

```javascript
func makeDaemon(c *cli.Context, name, mp string, m meta.Meta) error {
    logger.Warnf("Cannot run in background in Windows.")
    return nil
}

```

复制

网上找了一大圈，发现一个nssm的[命令行工具](https://cloud.tencent.com/product/cli?from=10680)比较好用

- 

- 

- 

### **2. 脚本实现**

以Windows10下为例，将相关操作封装成对应的批处理。具体如下

#### **1. 服务注册脚本**

解压对应的工具到Windows10下面的的D:/juicefs目录即可，同时将编译好的juicefs.exe也放置在同一个目录,创建一个初始化脚本InstallService.bat，该脚本用于注册一个名为JuiceFS的系统服务(开机自启动)，并指定对应的挂载盘符，内容如下

```javascript
@echo off
@title Run JuiceFS Background
echo ********************************
echo Setting mount path,(example: mount_path=Z)
set mount_path=Z
set /p mount_path="Set mount_path="
echo Mount JuiceFS To %mount_path%
set dir_name=badger_test
echo dir_name:badgerDB path
echo cache_dir:directory paths of local cache
echo max_uploads:directory paths of local cache
echo cache_size : size of cached objects in MiB (default: 102400)
echo ********************************

set max_uploads=150
set cache_size=102400
set juicefs_dir=D:\juicefs\
set cache_dir=%juicefs_dir%cache

if exist %cache_dir% (
echo "cache exist"
) else (
md %cache_dir%
echo "create cache_dir"
)

%juicefs_dir%nssm.exe install JuiceFS Application=%juicefs_dir%juicefs.exe
%juicefs_dir%nssm.exe set JuiceFS Application %juicefs_dir%juicefs.exe
%juicefs_dir%nssm.exe set JuiceFS AppDirectory %juicefs_dir%
%juicefs_dir%nssm.exe set JuiceFS AppParameters mount  --cache-dir=%cache_dir% --cache-size=%cache_size% --max-uploads=%max_uploads% --no-usage-report --debug  badger://%dir_name%  %mount_path%:
%juicefs_dir%nssm.exe start JuiceFS

```

复制

#### **2. 服务关停脚本**

脚本名称StopService.bat

```javascript
@echo off
set juicefs_dir=D:\juicefs\
%juicefs_dir%nssm.exe stop JuiceFS

```

复制

#### **3. 服务卸载脚本**

脚本名称RemoveService.bat

```javascript
@echo off
set juicefs_dir=D:\juicefs\
%juicefs_dir%nssm.exe remove JuiceFS confirm

```

复制

### **3. 运行须知**

需要注意的是，上面的脚本都需要用系统管理员权限运行

![](https://gitee.com/hxc8/images6/raw/master/img/202407190003595.jpg)

运行成功以后，可以成功在资源管理器中看到对应的盘符

![](D:/download/youdaonote-pull-master/data/Technology/存储/juiceFS/images/WEBRESOURCEf556f7ce02692f32b9b480e37fef478fstickPicture.png)

系统服务面板会注册一个名为JuiceFS的后台服务

![](https://gitee.com/hxc8/images6/raw/master/img/202407190003874.jpg)