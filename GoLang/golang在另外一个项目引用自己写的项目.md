A项目是你写的包。现在B项目引用



A项目结构   项目目录 /Users/bob/workspace/cyworkspace/lib1

![](https://gitee.com/hxc8/images7/raw/master/img/202407190750426.jpg)



module名字随便起









B项目结构  

![](https://gitee.com/hxc8/images7/raw/master/img/202407190750659.jpg)



这时候go.mod飘红



项目根目录执行 

```javascript
go get comtest    
```

这里的comtest是你随便起的名字。指向目标就是A项目的根目录



执行 go get comtest后 就不飘红了

![](https://gitee.com/hxc8/images7/raw/master/img/202407190750685.jpg)



然后就可以用了

![](https://gitee.com/hxc8/images7/raw/master/img/202407190750839.jpg)

