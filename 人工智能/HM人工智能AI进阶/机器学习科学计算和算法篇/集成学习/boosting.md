## 1 什么是boosting

![](https://gitee.com/hxc8/images1/raw/master/img/202407172140406.jpg)

**随着学习的积累从弱到强**

**简而言之：每新加入一个弱学习器，整体能力就会得到提升**

代表算法：Adaboost，GBDT，XGBoost，LightGBM

## 2 实现过程：

**1.训练第一个学习器**

![](images/WEBRESOURCE273d031913ffdb4bb0feffe0f923e4b3截图.png)

**2.调整数据分布**

![](https://gitee.com/hxc8/images1/raw/master/img/202407172140815.jpg)

**3.训练第二个学习器**

![](https://gitee.com/hxc8/images1/raw/master/img/202407172140953.jpg)

**4.再次调整数据分布**

![](https://gitee.com/hxc8/images1/raw/master/img/202407172140939.jpg)

**5.依次训练学习器，调整数据分布**

![](https://gitee.com/hxc8/images1/raw/master/img/202407172140966.jpg)

**6.整体过程实现**

![](https://gitee.com/hxc8/images1/raw/master/img/202407172140014.jpg)

## 3 bagging与boosting的区别

- 区别一:数据方面

	- Bagging：对数据进行采样训练；

	- Boosting：根据前一轮学习结果调整数据的重要性。

- 区别二:投票方面

	- Bagging：所有学习器平权投票；

	- Boosting：对学习器进行加权投票。

- 区别三:学习顺序

	- Bagging的学习是并行的，每个学习器没有依赖关系；

	- Boosting学习是串行，学习有先后顺序。

- 区别四:主要作用

	- Bagging主要用于提高泛化性能（解决过拟合，也可以说降低方差）

	- Boosting主要用于提高训练精度 （解决欠拟合，也可以说降低偏差）

![](https://gitee.com/hxc8/images1/raw/master/img/202407172140858.jpg)

## 4 AdaBoost介绍

### 4.1 构造过程细节

![](https://gitee.com/hxc8/images1/raw/master/img/202407172141041.jpg)

![](https://gitee.com/hxc8/images1/raw/master/img/202407172141895.jpg)

### 4.2 关键点剖析

**如何确认投票权重？**

**如何调整数据分布？**

![](https://gitee.com/hxc8/images1/raw/master/img/202407172141031.jpg)

### 4.3 案例介绍

给定下面这张训练数据表所示的数据，假设弱分类器由xv产生，其阈值v使该分类器在训练数据集上的分类误差率最低，试用Adaboost算法学习一个强分类器。

![](https://gitee.com/hxc8/images1/raw/master/img/202407172141219.jpg)

问题解答：

**步骤一：初始化训练数据权重相等，训练第一个学习器：**

![](https://gitee.com/hxc8/images1/raw/master/img/202407172141075.jpg)

**步骤二：AdaBoost反复学习基本分类器，在每一轮m=1,2,...,M顺次的执行下列操作：**

**当m=1的时候：**

（a）在权值分布为D1的训练数据上，阈值v取2.5时分类误差率最低，故基本分类器为:

> 6,7,8被分错


![](https://gitee.com/hxc8/images1/raw/master/img/202407172141722.jpg)

![](https://gitee.com/hxc8/images1/raw/master/img/202407172141514.jpg)

根据下公式，计算各个权重值

![](https://gitee.com/hxc8/images1/raw/master/img/202407172141380.jpg)

![](https://gitee.com/hxc8/images1/raw/master/img/202407172141160.jpg)

![](https://gitee.com/hxc8/images1/raw/master/img/202407172141867.jpg)

![](https://gitee.com/hxc8/images1/raw/master/img/202407172141677.jpg)

分类器H

1(x)在训练数据集上有3个误分类点。

**当m=2的时候：**

（a）在权值分布为D2的训练数据上，阈值v取8.5时分类误差率最低，故基本分类器为:

> 3,4,5被分错


![](https://gitee.com/hxc8/images1/raw/master/img/202407172141456.jpg)

![](https://gitee.com/hxc8/images1/raw/master/img/202407172141228.jpg)

**当m=3的时候：**

（a）在权值分布为D3的训练数据上，阈值v取5.5时分类误差率最低，故基本分类器为:

![](https://gitee.com/hxc8/images1/raw/master/img/202407172141070.jpg)

![](https://gitee.com/hxc8/images1/raw/master/img/202407172141889.jpg)

**步骤三：对m个学习器进行加权投票,获取最终分类器**

![](https://gitee.com/hxc8/images1/raw/master/img/202407172141759.jpg)

### 4.4 api

- from sklearn.ensemble import AdaBoostClassifier

	- api链接:

## 5 小结

- 什么是Boosting 【知道】

	- 随着学习的积累从弱到强

	- 代表算法：Adaboost，GBDT，XGBoost，LightGBM

- bagging和boosting的区别【知道】

	- 区别一:数据方面

		- Bagging：对数据进行采样训练；

		- Boosting：根据前一轮学习结果调整数据的重要性。

	- 区别二:投票方面

		- Bagging：所有学习器平权投票；

		- Boosting：对学习器进行加权投票。

	- 区别三:学习顺序

		- Bagging的学习是并行的，每个学习器没有依赖关系；

		- Boosting学习是串行，学习有先后顺序。

	- 区别四:主要作用

		- Bagging主要用于提高泛化性能（解决过拟合，也可以说降低方差）

		- Boosting主要用于提高训练精度 （解决欠拟合，也可以说降低偏差）

- AdaBoost构造过程【知道】

	- 步骤一：初始化训练数据权重相等，训练第一个学习器;

	- 步骤二：AdaBoost反复学习基本分类器;

	- 步骤三：对m个学习器进行加权投票