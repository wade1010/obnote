![](https://gitee.com/hxc8/images1/raw/master/img/202407172145350.jpg)

### 3.1 pyplot模块

matplotlib.pytplot包含了一系列类似于matlab的画图函数。

```python
import matplotlib.pyplot as plt

```

### 3.2 图形绘制流程

- 1.创建画布 -- plt.figure()

```python
plt.figure(figsize=(), dpi=)
figsize:指定图的长宽
      dpi:图像的清晰度
      返回fig对象
```

- 2.绘制图像 -- plt.plot(x, y)

```
以折线图为例

```

- 3.显示图像 -- plt.show()

### 3.3 折线图绘制与显示

**举例：展现上海一周的天气,比如从星期一到星期日的天气温度如下**

```python
import matplotlib.pyplot as plt

# 1.创建画布
plt.figure(figsize=(10, 10), dpi=100)

# 2.绘制折线图
plt.plot([1, 2, 3, 4, 5, 6 ,7], [17,17,18,15,11,11,13])

# 3.显示图像
plt.show()
```

![](https://gitee.com/hxc8/images1/raw/master/img/202407172145430.jpg)

## 4 认识Matplotlib图像结构

![](https://gitee.com/hxc8/images1/raw/master/img/202407172146382.jpg)

![](https://gitee.com/hxc8/images1/raw/master/img/202407172146524.jpg)

![](https://gitee.com/hxc8/images1/raw/master/img/202407172146385.jpg)

### 不懂常查询官网

[https://matplotlib.org/stable/search.html?q=subplots](https://matplotlib.org/stable/search.html?q=subplots)

## 常见图形种类及意义

**折线图**：api：plt.plot(x, y) 

**散点图：**api：plt.scatter(x, y)

**柱状图：**api：plt.bar(x, width, align='center', **kwargs)

**直方图：**api：plt.hist(x, bins=None)

**饼图：**api：plt.pie(x, labels=,autopct=,colors)