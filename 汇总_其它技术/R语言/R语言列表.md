

![](https://gitee.com/hxc8/images7/raw/master/img/202407190021076.jpg)





![](https://gitee.com/hxc8/images7/raw/master/img/202407190021384.jpg)



![](https://gitee.com/hxc8/images7/raw/master/img/202407190021067.jpg)



![](https://gitee.com/hxc8/images7/raw/master/img/202407190021324.jpg)





![](https://gitee.com/hxc8/images7/raw/master/img/202407190021043.jpg)



![](https://gitee.com/hxc8/images7/raw/master/img/202407190021331.jpg)





![](https://gitee.com/hxc8/images7/raw/master/img/202407190021659.jpg)





![](https://gitee.com/hxc8/images7/raw/master/img/202407190021540.jpg)







![](https://gitee.com/hxc8/images7/raw/master/img/202407190021418.jpg)





![](https://gitee.com/hxc8/images7/raw/master/img/202407190021118.jpg)



```javascript
simu.1 <- function(n,p,trueb){
  x<-matrix(rnorm(n*p),n,p)
  y<-x%*%trueb+rnorm(n)
  lsfit(x,y)
}
#Example
n<-100
p<-3
trueb <- c(-3,-2,3)
simu.1(n,p,trueb)
```





![](https://gitee.com/hxc8/images7/raw/master/img/202407190021771.jpg)

