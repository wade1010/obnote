### scikit-learn数据集API介绍

- sklearn.datasets

	- 加载获取流行数据集

	- datasets.load_*()

		- 获取小规模数据集，数据包含在datasets里

	- datasets.fetch_*(data_home=None)

		- 获取大规模数据集，需要从网络上下载，函数的第一个参数是data_home，表示数据集下载的目录,默认是 ~/scikit_learn_data/

seaborn介绍

- Seaborn 是基于 Matplotlib 核心库进行了更高级的 API 封装，可以让你轻松地画出更漂亮的图形。而 Seaborn 的漂亮主要体现在配色更加舒服、以及图形元素的样式更加细腻。

- 安装 pip3 install seaborn

- seaborn.lmplot() 是一个非常有用的方法，它会在绘制二维散点图时，自动完成回归拟合

	- sns.lmplot() 里的 x, y 分别代表横纵坐标的列名,

	- data= 是关联到数据集,

	- hue=*代表按照 species即花的类别分类显示,

	- fit_reg=是否进行线性拟合。

### 数据集的划分

机器学习一般的数据集会划分为两个部分：

- 训练数据：用于训练，**构建模型**

- 测试数据：在模型检验时使用，用于**评估模型是否有效**

划分比例：

- 训练集：70% 80% 75%

- 测试集：30% 20% 25%

**数据集划分api**

- sklearn.model_selection.train_test_split(arrays, *options)

	- 参数：

		- x 数据集的特征值

		- y 数据集的标签值

		- test_size 测试集的大小，一般为float

		- random_state 随机数种子,不同的种子会造成不同的随机采样结果。相同的种子采样结果相同。

	- return

		- x_train, x_test, y_train, y_test

![](https://gitee.com/hxc8/images1/raw/master/img/202407172145220.jpg)

## 1 什么是特征预处理

### 1.1 特征预处理定义

通过**一些转换函数**将特征数据**转换成更加适合算法模型**的特征数据过程

![](https://gitee.com/hxc8/images1/raw/master/img/202407172145337.jpg)

- 为什么我们要进行归一化/标准化？

	- 特征的**单位或者大小相差较大，或者某特征的方差相比其他的特征要大出几个数量级**，**容易影响（支配）目标结果**，使得一些算法无法学习到其它的特征

我们需要用到一些方法进行**无量纲化**，**使不同规格的数据转换到同一规格**

## 2 归一化

### 2.1 定义

通过对原始数据进行变换把数据映射到(默认为[0,1])之间

### 2.2 公式

![](https://gitee.com/hxc8/images1/raw/master/img/202407172145223.jpg)

作用于每一列，max为一列的最大值，min为一列的最小值,那么X’’为最终结果，mx，mi分别为指定区间值默认mx为1,mi为0

那么怎么理解这个过程呢？我们通过一个例子

![](https://gitee.com/hxc8/images1/raw/master/img/202407172145198.jpg)

### 2.3 API

- sklearn.preprocessing.MinMaxScaler (feature_range=(0,1)… )

	- MinMaxScalar.fit_transform(X)

		- X:numpy array格式的数据[n_samples,n_features]

	- 返回值：转换后的形状相同的array

**问题：如果数据中异常点较多，会有什么影响？**

![](https://gitee.com/hxc8/images1/raw/master/img/202407172145270.jpg)

### 2.5 归一化总结

注意最大值最小值是变化的，另外，最大值与最小值非常容易受异常点影响，**所以这种方法鲁棒性较差，只适合传统精确小数据场景。**

怎么办？

## 3 标准化

### 3.1 定义

通过对原始数据进行变换把数据变换到均值为0,标准差为1范围内

![](https://gitee.com/hxc8/images1/raw/master/img/202407172145201.jpg)

作⽤于每⼀列，mean为平均值，σ为标准差

所以回到刚才异常点的地方，我们再来看看标准化

![](images/WEBRESOURCE7041b3686c45b8eaf99e1cd66d7b7d69截图.png)

- 对于归一化来说：如果出现异常点，影响了最大值和最小值，那么结果显然会发生改变

- 对于标准化来说：如果出现异常点，由于具有一定数据量，少量的异常点对于平均值的影响并不大，从而方差改变较小。

###   3.5 标准化总结

在已有样本足够多的情况下比较稳定，适合现代嘈杂大数据场景。

## 4 总结

- 什么是特征工程【知道】

	- 定义

		- 通过一些转换函数将特征数据转换成更加适合算法模型的特征数据过程

	- 包含内容:

		- 归一化

		- 标准化

- 归一化【知道】

	- 定义:

		- 对原始数据进行变换把数据映射到(默认为[0,1])之间

	- api:

		- sklearn.preprocessing.MinMaxScaler (feature_range=(0,1)… )

		- 参数:feature_range -- 自己指定范围,默认0-1

	- 总结:

		- 鲁棒性比较差(容易受到异常点的影响)

		- 只适合传统精确小数据场景(以后不会用你了)

- 标准化【掌握】

	- 定义:

		- 对原始数据进行变换把数据变换到均值为0,标准差为1范围内

	- api:

		- sklearn.preprocessing.StandardScaler( )

	- 总结:

		- 异常值对我影响小

		- 适合现代嘈杂大数据场景(以后就是用你了)