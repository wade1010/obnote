创建不带版本的桶后

上传一个文件a.txt

这时候是没有版本的。

```javascript
input := &s3.ListObjectVersionsInput{
   Bucket: bucket,
   Prefix: aws.String("a.txt"),
}
result, err := s3Client.ListObjectVersions(input)
```



![](https://gitee.com/hxc8/images6/raw/master/img/202407190006511.jpg)





然后开启多版本



再传一次a.txt

s3Client.ListObjectVersions结果

![](https://gitee.com/hxc8/images6/raw/master/img/202407190006590.jpg)





然后暂停多版本

再传一次a.txt 他会替换掉第一次那个versionID为null

![](https://gitee.com/hxc8/images6/raw/master/img/202407190006852.jpg)



开启多版本，

再传一次a.txt

![](https://gitee.com/hxc8/images6/raw/master/img/202407190006971.jpg)





结论：

ListObjectVersions最多存在一个未开启多版本时传的对象。

如果我们没开启多版本也给对象赋值一个版本，那样我们就不能跟S3兼容了。我们会有多个非多版本对象。

1、如果我们不开版本也加versionID,只能给对象加一个是不是多版本属性，这样如果我们在多版本开->暂停->开的转换过程 就中能保证只有一个没有版本的数据出现

2、如果不开版本，不加versionID(也就是符合S3的情况),你那边 打包、垃圾回收可能就要改改了





附加测试

```javascript
object, err := s3Client.GetObject(&s3.GetObjectInput{
   Bucket:    bucket,
   Key:       aws.String("a.txt"),
   VersionId: aws.String("null"),
})
```



![](https://gitee.com/hxc8/images6/raw/master/img/202407190006172.jpg)





测试2：

创建一个不带版本的桶，然后传一个b.txt

然后开启多版本，再传一个b.txt

然后暂停多版本，再调用GetObject或者信息，它会获取到最新的那条数据。