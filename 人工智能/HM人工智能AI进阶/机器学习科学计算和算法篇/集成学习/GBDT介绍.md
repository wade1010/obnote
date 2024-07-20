GBDT 的全称是 Gradient Boosting Decision Tree，梯度提升树，在传统机器学习算法中，GBDT算的上TOP3的算法。

想要理解GBDT的真正意义，那就必须理解GBDT中的**Gradient Boosting** 和**Decision Tree**分别是什么？

## 1 CART回归树

首先，**GBDT使用的决策树是CART回归树**，无论是处理回归问题还是二分类以及多分类，GBDT使用的决策树通通都是都是CART回归树。

- 为什么不用CART分类树呢？

	- 因为**GBDT每次迭代要拟合的是梯度值**，是连续值所以要用回归树。

对于回归树算法来说最重要的是寻找最佳的划分点，那么回归树中的可划分点包含了所有特征的所有可取的值。

在分类树中最佳划分点的判别标准是熵或者基尼系数，都是用纯度来衡量的，但是在回归树中的样本标签是连续数值，所以再使用熵之类的指标不再合适，**取而代之的是平方误差，它能很好的评判拟合程度。**

### 1.1 回归树生成算法（复习）

- 输入：训练数据集D:

- 输出：回归树f(x)​.

- 在训练数据集所在的输入空间中，递归的将每个区域划分为两个子区域并决定每个子区域上的输出值，构建二叉决策树：

（1）选择最优切分特征j与切分点s，求解

![](https://gitee.com/hxc8/images1/raw/master/img/202407172139263.jpg)

遍历特征j,对固定的切分特征j扫描切分点s,选择使得上式达到最小值的对 (j,s).

（2）用选定的对(j,s)划分区域并决定相应的输出值：

![](https://gitee.com/hxc8/images1/raw/master/img/202407172139186.jpg)

（3）继续对两个子区域调用步骤（1）和（2），直至满足停止条件。

（4）将输入空间划分为M个区域R1,R2,……,Rm, 生成决策树：

![](https://gitee.com/hxc8/images1/raw/master/img/202407172139067.jpg)

## 2 拟合负梯度

梯度提升树（Grandient Boosting）是提升树（Boosting Tree）的一种改进算法，所以在讲梯度提升树之前先来说一下提升树。

先来个通俗理解：假如有个人30岁，我们首先用20岁去拟合，发现损失有10岁，这时我们用6岁去拟合剩下的损失，发现差距还有4岁，第三轮我们用3岁拟合剩下的差距，差距就只有一岁了。如果我们的迭代轮数还没有完，可以继续迭代下面，每一轮迭代，拟合的岁数误差都会减小。最后将每次拟合的岁数加起来便是模型输出的结果。

![](https://gitee.com/hxc8/images1/raw/master/img/202407172139805.jpg)

上面伪代码中的残差是什么？

在提升树算法中，

![](https://gitee.com/hxc8/images1/raw/master/img/202407172139676.jpg)

![](https://gitee.com/hxc8/images1/raw/master/img/202407172139520.jpg)

这里，

![](https://gitee.com/hxc8/images1/raw/master/img/202407172140359.jpg)

是当前模型拟合数据的残差（residual）。

所以，对于提升树来说只需要简单地拟合当前模型的残差。

回到我们上面讲的那个通俗易懂的例子中，第一次迭代的残差是10岁，第二 次残差4岁,,,,,,

当损失函数是平方损失和指数损失函数时，梯度提升树每一步优化是很简单的，但是对于一般损失函数而言，往往每一步优化起来不那么容易。

针对这一问题，Friedman提出了梯度提升树算法，这是利用最速下降的近似方法，其关键是利用损失函数的负梯度作为提升树算法中的残差的近似值。

那么负梯度长什么样呢？

那么负梯度长什么样呢？

第t轮的第i个样本的损失函数的负梯度为：

![](https://gitee.com/hxc8/images1/raw/master/img/202407172140211.jpg)

此时不同的损失函数将会得到不同的负梯度，如果选择平方损失：

![](https://gitee.com/hxc8/images1/raw/master/img/202407172140240.jpg)

负梯度为：

![](https://gitee.com/hxc8/images1/raw/master/img/202407172140079.jpg)

此时我们发现GBDT的负梯度就是残差，所以说对于回归问题，我们要拟合的就是残差。

那么对于分类问题呢？

二分类和多分类的损失函数都是logloss。

本文以回归问题为例进行讲解。

## 3 GBDT算法原理

上面两节分别将Decision Tree和Gradient Boosting介绍完了，下面将这两部分组合在一起就是我们的GBDT了。

**GBDT算法：**

（1）初始化弱学习器

![](https://gitee.com/hxc8/images1/raw/master/img/202407172140876.jpg)

（2）对m=1,2,...,M有：

（a）对每个样本i=1,2,...,N，计算负梯度，即残差

![](https://gitee.com/hxc8/images1/raw/master/img/202407172140672.jpg)

![](https://gitee.com/hxc8/images1/raw/master/img/202407172140611.jpg)

![](https://gitee.com/hxc8/images1/raw/master/img/202407172140817.jpg)

![](https://gitee.com/hxc8/images1/raw/master/img/202407172140519.jpg)

（d）更新强学习器

![](https://gitee.com/hxc8/images1/raw/master/img/202407172140343.jpg)

（3）得到最终学习器

![](https://gitee.com/hxc8/images1/raw/master/img/202407172140146.jpg)

4 实例介绍

### 4.1 数据介绍

根据如下数据，预测最后一个样本的身高。

| 编号 | 年龄(岁) | 体重（kg） | 身高(m)(标签值) | 
| -- | -- | -- | -- |
| 0 | 5 | 20 | 1.1 | 
| 1 | 7 | 30 | 1.3 | 
| 2 | 21 | 70 | 1.7 | 
| 3 | 30 | 60 | 1.8 | 
| 4(要预测的) | 25 | 65 | ？ | 


### 4.2 模型训练

#### **4.2.1 设置参数：**

- 学习率：learning_rate=0.1

- 迭代次数：n_trees=5

- 树的深度：max_depth=3

#### 4.2.2 开始训练

**（1）初始化弱学习器:**

![](https://gitee.com/hxc8/images1/raw/master/img/202407172140194.jpg)

损失函数为平方损失，因为平方损失函数是一个凸函数，直接求导，倒数等于零，得到c。 

![](https://gitee.com/hxc8/images1/raw/master/img/202407172140089.jpg)

令导数等于0

![](https://gitee.com/hxc8/images1/raw/master/img/202407172140889.jpg)

所以初始化时，c取值为所有训练样本标签值的均值。

![](https://gitee.com/hxc8/images1/raw/master/img/202407172140708.jpg)

**（2）对迭代轮数m=1,2,…,M:**

由于我们设置了迭代次数：n_trees=5，这里的M=5。

计算负梯度，根据上文损失函数为平方损失时，负梯度就是残差，再直白一点就是 y与上一轮得到的学习器fm-1的差值：

![](https://gitee.com/hxc8/images1/raw/master/img/202407172140580.jpg)

残差在下表列出：

![](https://gitee.com/hxc8/images1/raw/master/img/202407172140887.jpg)

此时将残差作为样本的真实值来训练弱学习器f1(x)，即下表数据

![](https://gitee.com/hxc8/images1/raw/master/img/202407172140633.jpg)

![](https://gitee.com/hxc8/images1/raw/master/img/202407172140314.jpg)

所有可能划分情况如下表所示：

![](https://gitee.com/hxc8/images1/raw/master/img/202407172140311.jpg)

以上划分点是的总平方损失最小为**0.025**有两个划分点：年龄21和体重60，所以随机选一个作为划分点，这里我们选 **年龄21** 现在我们的第一棵树长这个样子：

![](https://gitee.com/hxc8/images1/raw/master/img/202407172140126.jpg)

我们设置的参数中树的深度max_depth=3，现在树的深度只有2，需要再进行一次划分，这次划分要对左右两个节点分别进行划分：

对于**左节点**，只含有0,1两个样本，根据下表我们选择**年龄7**划分

![](https://gitee.com/hxc8/images1/raw/master/img/202407172140060.jpg)

对于**右节点**，只含有2,3两个样本，根据下表我们选择**年龄30**划分（也可以选**体重70**）

![](https://gitee.com/hxc8/images1/raw/master/img/202407172140918.jpg)

现在我们的第一棵树长这个样子：

![](https://gitee.com/hxc8/images1/raw/master/img/202407172140684.jpg)

此时我们的树深度满足了设置，还需要做一件事情，给这每个叶子节点分别赋一个参数 r ，来拟合残差。

![](https://gitee.com/hxc8/images1/raw/master/img/202407172140700.jpg)

这里其实和上面初始化学习器是一个道理，平方损失，求导，令导数等于零，化简之后得到每个叶子节点的参数 r ，其实就是标签值的均值。这个地方的标签值不是原始的 y，而是本轮要拟合的标残差 y - f0(x).

根据上述划分结果，为了方便表示，规定从左到右为第1,2,3,4个叶子结点

![](https://gitee.com/hxc8/images1/raw/master/img/202407172140504.jpg)

此时的树长这个样子：

![](https://gitee.com/hxc8/images1/raw/master/img/202407172140393.jpg)

此时可更新强学习器，需要用到参数学习率：learning_rate=0.1，用 lr 表示。

![](https://gitee.com/hxc8/images1/raw/master/img/202407172140263.jpg)

为什么要用学习率呢？这是**Shrinkage**的思想，如果每次都全部加上（学习率为1）很容易一步学到位导致过拟合。

重复此步骤，直到 m>5 结束，最后生成5棵树。

![](https://gitee.com/hxc8/images1/raw/master/img/202407172140016.jpg)

结果中，0.9倍这个现象，和其学习率有关。这是因为数据简单每棵树长得一样，导致每一颗树的拟合效果一样，而每棵树都只学上一棵树残差的0.1倍，导致这颗树只能拟合剩余0.9了。

**（3）得到最后的强学习器：**

![](https://gitee.com/hxc8/images1/raw/master/img/202407172140923.jpg)

![](https://gitee.com/hxc8/images1/raw/master/img/202407172140783.jpg)

## 5 小结

GBDT算法原理【知道】

（1）初始化弱学习器

![](https://gitee.com/hxc8/images1/raw/master/img/202407172140882.jpg)

（2）对m=1,2,...,M有：

- （a）对每个样本i=1,2,...,N，计算负梯度，即残差

![](https://gitee.com/hxc8/images1/raw/master/img/202407172140634.jpg)

![](https://gitee.com/hxc8/images1/raw/master/img/202407172140481.jpg)

![](https://gitee.com/hxc8/images1/raw/master/img/202407172140296.jpg)

（d）更新强学习器

![](https://gitee.com/hxc8/images1/raw/master/img/202407172140148.jpg)

（3）得到最终学习器 

![](https://gitee.com/hxc8/images1/raw/master/img/202407172140995.jpg)