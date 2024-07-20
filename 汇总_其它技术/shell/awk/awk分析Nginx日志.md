#### 分析某个日志目前为止访问量最高的ip排行
```
awk '{print $1}' /usr/local/nginx/logs/access.log|sort|uniq -c|sort -nr|head -20
``` 

#### 找到当前日志中502或者404错误的页面并统计
```
awk '{print $1}' /usr/local/nginx/logs/error.log|egrep '404|502'|akw '{print $1,$7,$9}'|more
```
#### 分析某个日志某个时间段访问量最高的ip排行
![image](https://gitee.com/hxc8/images7/raw/master/img/202407190748517.jpg)
