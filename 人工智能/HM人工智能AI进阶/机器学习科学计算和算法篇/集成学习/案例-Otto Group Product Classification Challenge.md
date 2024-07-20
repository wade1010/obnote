## 1.背景介绍

奥托集团是世界上最大的电子商务公司之一，在20多个国家设有子公司。该公司每天都在世界各地销售数百万种产品,所以对其产品根据性能合理的分类非常重要。

不过,在实际工作中,工作人员发现,许多相同的产品得到了不同的分类。本案例要求,你对奥拓集团的产品进行正确的分类。尽可能的提供分类的准确性。

链接：[https://www.kaggle.com/c/otto-group-product-classification-challenge/overview](https://www.kaggle.com/c/otto-group-product-classification-challenge/overview)

![](https://gitee.com/hxc8/images1/raw/master/img/202407172141197.jpg)

## 2.数据集介绍

- 本案例中，数据集包含大约200,000种产品的93个特征。

- 其目的是建立一个能够区分otto公司主要产品类别的预测模型。

- 所有产品共被分成九个类别（例如时装，电子产品等）。

![](https://gitee.com/hxc8/images1/raw/master/img/202407172141071.jpg)

- id - 产品id

- feat_1, feat_2, ..., feat_93 - 产品的各个特征

- target - 产品被划分的类别

## 3.评分标准

本案例中，最后结果使用多分类对数损失进行评估。

具体公式：

![](https://gitee.com/hxc8/images1/raw/master/img/202407172141925.jpg)

上公式中，

- N：样本数

- M：类别数

- Pij：代表第i个样本属于类别j的概率（i表示样本，j表示类别。）

- 如果第i个样本真的属于类别j，则yij等于1，否则为0。

- 根据上公式，假如你将所有的测试样本都正确分类，所有pij都是1，那每个log(pij)都是0，最终的logloss也是0。

- 假如第1个样本本来是属于1类别的，但是你给它的类别概率pij=0.1，那logloss就会累加上log(0.1)这一项。我们知道这一项是负数，而且pij越小，负得越多，如果pij=0，将是无穷。这会导致这种情况：你分错了一个，logloss就是无穷。这当然不合理，为了避免这一情况，我们对非常小的值做如下处理：

![](https://gitee.com/hxc8/images1/raw/master/img/202407172141762.jpg)

- 也就是说最小不会小于10^-15。

## 4.实现过程

### 4.1 流程分析

- 获取数据

- 数据基本处理

	- 数据量比较大，尝试是否可以进行数据分割

	- 转换目标值表示方式

- 模型训练

	- 模型基本训练

### 4.2 代码实现

- 具体见【[RF]OTTO Group Product Classification Challenge.ipynb】