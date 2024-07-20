object = encodeDirObject(object)





```javascript
// On MinIO a directory object is stored as a regular object with "__XLDIR__" suffix.
// For ex. "prefix/" is stored as "prefix__XLDIR__"
func encodeDirObject(object string) string {
   if HasSuffix(object, slashSeparator) {
      return strings.TrimSuffix(object, slashSeparator) + globalDirSuffix
   }
   return object
}
```



globalDirSuffix                = "__XLDIR__"



将空目录作为文件存入

![](https://gitee.com/hxc8/images6/raw/master/img/202407190006007.jpg)



如果你在空目录下存入一个文件，如 bk1111123/1.txt则变成如下

![](https://gitee.com/hxc8/images6/raw/master/img/202407190006924.jpg)

