

![](https://gitee.com/hxc8/images7/raw/master/img/202407190022863.jpg)

```javascript
# = <- -> <<-区别
# =和<-的区别：
#大部分情况下连着是等价的，都具有赋值功能
#x=3 x<-9
#很小的区别就是在函数使用是
#> source("1function.R")
#> aa(10)
#[1] 20
#> aa(x=10)
#[1] 20
#> aa(x<-10)
#[1] 20
aa <- function(x,a=10) {
  x<-a+x
  x
}

bb <- function(a=10,x) {
  x<-a+x
  x
}

#> aa(10)
#[1] 20
#> bb(10)
#Error in bb(10) : 缺少参数"x",也没有缺省值
#> bb(x<-10)
#Error in bb(x <- 10) : 缺少参数"x",也没有缺省值

```



![](https://gitee.com/hxc8/images7/raw/master/img/202407190022118.jpg)



![](D:/download/youdaonote-pull-master/data/Technology/R语言/images/3E15C1DC2A994C40A53EE15E24A11160image.png)

