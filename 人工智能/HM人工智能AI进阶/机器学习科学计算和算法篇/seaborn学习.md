Matplotlib虽然已经是比较优秀的绘图库了，但是它有个今人头疼的问题，那就是API使用过于复杂，它里面有上千个函数和参数，属于典型的那种可以用它做任何事，却无从下手。

Seaborn基于 Matplotlib核心库进行了更高级的API封装，可以轻松地画出更漂亮的图形，而Seaborn的漂亮主要体现在配色更加舒服，以及图形元素的样式更加细腻。

## 绘制单变量分布

可以采用最简单的直方图描述单变量的分布情况。 Seaborn中提供了 distplot()函数，它默认绘制的是一个带有核密度估计曲线的直方图。 distplot()函数的语法格式如下。

```
seaborn.distplot(a, bins=None, hist=True, kde=True, rug=False, fit=None, color=None)

```

上述函数中常用参数的含义如下：

- (1) a：表示要观察的数据，可以是 Series、一维数组或列表。

- (2) bins：用于控制条形的数量。

- (3) hist：接收布尔类型，表示是否绘制(标注)直方图。

- (4) kde：接收布尔类型，表示是否绘制高斯核密度估计曲线。

- (5) rug：接收布尔类型，表示是否在支持的轴方向上绘制rugplot。

通过 distplot())函数绘制直方图的示例如下。

```python
import numpy as np

sns.set()
np.random.seed(0)  # 确定随机数生成器的种子,如果不使用每次生成图形不一样
arr = np.random.randn(100)  # 生成随机数组

ax = sns.distplot(arr, bins=10, hist=True, kde=True, rug=True)  # 绘制直方图
```

上述示例中，首先导入了用于生成数组的numpy库，然后使用 seaborn调用set()函数获取默认绘图，并且调用 random模块的seed函数确定随机数生成器的种子，保证每次产生的随机数是一样的，接着调用 randn()函数生成包含100个随机数的数组，最后调用 distplot()函数绘制直方图。

## 小结

- Seaborn的基本使用【了解】

- 绘制单变量分布图形【知道】

	- seaborn.distplot()

- 绘制双变量分布图形【知道】

	- seaborn.jointplot()

- 绘制成对的双变量分布图形【知道】

	- Seaborn.pairplot()

- 