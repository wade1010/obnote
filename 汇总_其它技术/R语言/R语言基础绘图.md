

![](https://gitee.com/hxc8/images7/raw/master/img/202407190020759.jpg)



![](https://gitee.com/hxc8/images7/raw/master/img/202407190020480.jpg)



![](https://gitee.com/hxc8/images7/raw/master/img/202407190020323.jpg)



```javascript
str(mtcars)

coplot(wt~mpg | as.factor(am),data=mtcars)

coplot(wt~mpg | am,data=mtcars)
```





![](https://gitee.com/hxc8/images7/raw/master/img/202407190020478.jpg)



![](https://gitee.com/hxc8/images7/raw/master/img/202407190020022.jpg)





```javascript
str(Orange)
x <- runif(50,-2,2)
y <- runif(50,-2,2)
plot(x,y,type="n",xlab = "",ylab = "",xlim = c(-2,2),ylim = c(-2,2))
points(x,y)
points(0,0,lty=17,col="red",cex=3)
title(xlab="x-label",ylab = "y-label",main="main title",sub = "subtitle")
text(0,0,"Origin point")
abline(h=0,v=0,lty=2)
```





![](https://gitee.com/hxc8/images7/raw/master/img/202407190020112.jpg)





