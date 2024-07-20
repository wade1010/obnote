# 数据类型

character:字符型

numeric:数值型，指实数或小数

integer:整型

complex:复数型   基本不用

logical:逻辑型





# 数据结构

数据分析的对象

-数值变量

-分类变量(有序、无序)

向量、因子

矩阵、数据框

数组、列表





# 数据结构一向量

创建向量

- c()

- 冒号操作符 :

- seq()

- rep()

- vector()、 numeric()、

integer()、character()、

logical()、complex()



# 数据结构一向量

提取子集

- 下标运算符[]

- 数字下标(正数、 负数)

- which()函数

查看属性

-长度length()

-类型mode()、class()



# 数据结构一因子

因子一分类变量



级别一类别



创建因子

- factor()    把字符向量、数字向量转成因子

-gl()     把数字向量转成因子

查看属性

-mode()、 class()





# 数据结构一向量

类型转换

- 安全级别:字符串>数字>逻辑值

- 隐式

- 显示as家族





# 数据结构一 矩阵

创建矩阵

- matrix()

-修改向量dim属性

- rbind、 cbind 

操作

一 提取子集[,] 

一 行、列命名

一 缺失值处理





# 数据结构- --矩阵

矩阵运算

--加减

--转置t()

一数乘、对应元素相乘、矩阵乘法%*%

一对角阵、单位阵diag()

一特征值、特征向量eigen()

一求逆、方程组的解solve()



# 数据结构一数据框  （最贴近日常使用）

创建数据框

一 read.csv()

一data.frame()

常用操作

一 $

一 attach、detach、 with、 within

一 添加新列

一 subset



# 数据结构一列表

创建列表

一 list()

提取子集

一 []

一 [[]]



# 数据结构—数组  （工作中很少用）

矩阵的延伸





# 数据结构

如何判断对象类型

一 is.list

一 is.data.frame 

一  ... ...

类型转换

一 as.matrix

一 as.d ata.frame





# 分支结构

if- else

if (condition) {

...

}else {

... 

}

Ifelse()函数

ifelse(b,u,v)



循环结构

for 

for(n in x){

...

}



while

while (condition){

...

}



repeat

repeat{

...

break

}





break、next 类似continue





```javascript
#分支结构
x=1:10
y=ifelse(x%%2==0,'A','B')
y


for (x in 1:5) {
  print(x^2)
}


i=1
while (i<6) {
  print(i^2)
  i=i+1
}


i=1
repeat{
  print(i^2)
  i=i+1
  if (i>5)break
}
```





函数



Function 

myfunc=function(par1,par2...){

... ...

}



查看函数代码，不加括号的函数名,page



source

source('D:/test/06.function.r')









# 向量化计算和apply家族

向量化计算

— +-*/

— ><!=

* apply家族

一 apply

一 lapply、 sapply

一 mapply、 tapply



























































