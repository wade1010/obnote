K Nearest Neighbor算法又叫KNN算法，这个算法是机器学习里面一个比较经典的算法， 总体来说KNN算法是相对比较容易理解的算法

- 定义

如果一个样本在特征空间中的**k个最相似(即特征空间中最邻近)的样本中的大多数属于某一个类别**，则该样本也属于这个类别。

> 来源：KNN算法最早是由Cover和Hart提出的一种分类算法


 KNN算法流程总结

1）计算已知类别数据集中的点与当前点之间的距离

2）按距离递增次序排序

3）选取与当前点距离最小的k个点

4）统计前k个点所在的类别出现的频率

5）返回前k个点出现频率最高的类别作为当前点的预测分类

- K-近邻算法简介【了解】

	- 定义:就是通过你的"邻居"来判断你属于哪个类别

	- 如何计算你到你的"邻居"的距离：一般时候,都是使用欧氏距离

```
 # 1 构造数据
x = [[1],[2],[10],[20]]
y = [0,0,1,1]

# 2 训练模型
# 2.1 实例化一个估计器对象
estimator = KNeighborsClassifier(n_neighbors=1)

# 2.2 调用fit方法，进行训练
estimator.fit(x,y)

# 3 预测数据
ret = estimator.predict([[0]])
print(ret)

ret2 = estimator.predict([[100]])
print(ret2)
```

- sklearn的优势:

	- 文档多,且规范

	- 包含的算法多

	- 实现起来容易

- knn中的api

	- sklearn.neighbors.KNeighborsClassifier(n_neighbors=5)

![](https://gitee.com/hxc8/images1/raw/master/img/202407172144628.jpg)

- K值过小：

	- 容易受到异常点的影响

- k值过大：

	- 受到样本均衡的问题

## 小结

- KNN中K值大小选择对模型的影响【知道】

	- K值过小：

		- 容易受到异常点的影响

		- 容易过拟合

	- k值过大：

		- 受到样本均衡的问题

		- 容易欠拟合

- 近似误差、估计误差基本概念介绍【了解】

	- 近似误差

		- 对现有训练集的训练误差，**关注训练集**

	- 估计误差

		- 可以理解为对测试集的测试误差，**关注测试集**

# kd树

**k近邻法最简单的实现是线性扫描（穷举搜索），即要计算输入实例与每一个训练实例的距离。计算并存储好以后，再查找K近邻。**当训练集很大时，计算非常耗时。

### 什么是kd树

根据**KNN**每次需要预测一个点时，我们都需要计算训练数据集里每个点到这个点的距离，然后选出距离最近的k个点进行投票。**当数据集很大时，这个计算成本非常高，针对N个样本，D个特征的数据集，其算法复杂度为O（DN2）**。

**kd树**：为了避免每次都重新计算一遍距离，算法会把距离信息保存在一棵树里，这样在计算之前从树里查询距离信息，尽量避免重新计算。其基本原理是，**如果A和B距离很远，B和C距离很近，那么A和C的距离也很远**。有了这个信息，就可以在合适的时候跳过距离远的点。

**最近邻域搜索（Nearest-Neighbor Lookup）**

kd树(K-dimension tree)是**一种对k维空间中的实例点进行存储以便对其进行快速检索的树形数据结构。**kd树是一种二叉树，表示对k维空间的一个划分，**构造kd树相当于不断地用垂直于坐标轴的超平面将K维空间切分，构成一系列的K维超矩形区域**。kd树的每个结点对应于一个k维超矩形区域。**利用kd树可以省去对大部分数据点的搜索，从而减少搜索的计算量。**

## 构造方法

（1）**构造根结点，使根结点对应于K维空间中包含所有实例点的超矩形区域；**

（2）**通过递归的方法，不断地对k维空间进行切分，生成子结点。**在超矩形区域上选择一个坐标轴和在此坐标轴上的一个切分点，确定一个超平面，这个超平面通过选定的切分点并垂直于选定的坐标轴，将当前超矩形区域切分为左右两个子区域（子结点）；这时，实例被分到两个子区域。

（3）**上述过程直到子区域内没有实例时终止（终止时的结点为叶结点）**。在此过程中，将实例保存在相应的结点上。

（4）通常，循环的选择坐标轴对空间切分，选择训练实例点在坐标轴上的中位数为切分点，这样得到的kd树是平衡的（平衡二叉树：它是一棵空树，或其左子树和右子树的深度之差的绝对值不超过1，且它的左子树和右子树都是平衡二叉树）。

KD树中每个节点是一个向量，和二叉树按照数的大小划分不同的是，KD树每层需要选定向量中的某一维，然后根据这一维按左小右大的方式划分数据。在构建KD树时，关键需要解决2个问题：

**（1）选择向量的哪一维进行划分；**

**（2）如何划分数据；**

第一个问题简单的解决方法可以是随机选择某一维或按顺序选择，但是**更好的方法应该是在数据比较分散的那一维进行划分（分散的程度可以根据方差来衡量）**。

第二个问题中，好的划分方法可以使构建的树比较平衡，可以每次选择中位数来进行划分。

## 案例分析

### 3.1 树的建立

给定一个二维空间数据集：T={(2,3),(5,4),(9,6),(4,7),(8,1),(7,2)}，构造一个平衡kd树。

![](https://gitee.com/hxc8/images1/raw/master/img/202407172144524.jpg)

（1）思路引导：

根结点对应包含数据集T的矩形，选择x(1)轴，6个数据点的x(1)坐标中位数是6，这里选最接近的(7,2)点，以平面x(1)=7将空间分为左、右两个子矩形（子结点）；接着左矩形以x(2)=4分为两个子矩形（左矩形中{(2,3),(5,4),(4,7)}点的x(2)坐标中位数正好为4），右矩形以x(2)=6分为两个子矩形，如此递归，最后得到如下图所示的特征空间划分和kd树。

![](https://gitee.com/hxc8/images1/raw/master/img/202407172144528.jpg)

### 3.2 最近领域的搜索

假设标记为星星的点是 test point， 绿色的点是找到的近似点，在回溯过程中，需要用到一个队列，存储需要回溯的点，在判断其他子节点空间中是否有可能有距离查询点更近的数据点时，做法是以查询点为圆心，以当前的最近距离为半径画圆，这个圆称为候选超球（candidate hypersphere），如果圆与回溯点的轴相交，则需要将轴另一边的节点都放到回溯队列里面来。

![](https://gitee.com/hxc8/images1/raw/master/img/202407172144444.jpg)

样本集{(2,3),(5,4), (9,6), (4,7), (8,1), (7,2)}

#### 3.2.1 查找点(2.1,3.1)

![](https://gitee.com/hxc8/images1/raw/master/img/202407172144423.jpg)

在(7,2)点测试到达(5,4)，在(5,4)点测试到达(2,3)，然后search_path中的结点为<(7,2),(5,4), (2,3)>，从search_path中取出(2,3)作为当前最佳结点nearest, dist为0.141；

然后回溯至(5,4)，以(2.1,3.1)为圆心，以dist=0.141为半径画一个圆，并不和超平面y=4相交，如上图，所以不必跳到结点(5,4)的右子空间去搜索，因为右子空间中不可能有更近样本点了。

于是再回溯至(7,2)，同理，以(2.1,3.1)为圆心，以dist=0.141为半径画一个圆并不和超平面x=7相交，所以也不用跳到结点(7,2)的右子空间去搜索。

至此，search_path为空，结束整个搜索，返回nearest(2,3)作为(2.1,3.1)的最近邻点，最近距离为0.141。

#### 3.2.2 查找点(2,4.5)

![](https://gitee.com/hxc8/images1/raw/master/img/202407172144942.jpg)

在(7,2)处测试到达(5,4)，在(5,4)处测试到达(4,7)【优先选择在本域搜索】，然后search_path中的结点为<(7,2),(5,4), (4,7)>，从search_path中取出(4,7)作为当前最佳结点nearest, dist为3.202；

然后回溯至(5,4)，以(2,4.5)为圆心，以dist=3.202为半径画一个圆与超平面y=4相交，所以需要跳到(5,4)的左子空间去搜索。所以要将(2,3)加入到search_path中，现在search_path中的结点为<(7,2),(2, 3)>；另外，(5,4)与(2,4.5)的距离为3.04 < dist = 3.202，所以将(5,4)赋给nearest，并且dist=3.04。

回溯至(2,3)，(2,3)是叶子节点，直接平判断(2,3)是否离(2,4.5)更近，计算得到距离为1.5，所以nearest更新为(2,3)，dist更新为(1.5)

回溯至(7,2)，同理，以(2,4.5)为圆心，以dist=1.5为半径画一个圆并不和超平面x=7相交, 所以不用跳到结点(7,2)的右子空间去搜索。

至此，search_path为空，结束整个搜索，返回nearest(2,3)作为(2,4.5)的最近邻点，最近距离为1.5。

## 案例分析2

### 情况1

![](https://gitee.com/hxc8/images1/raw/master/img/202407172144094.jpg)

查找(2.1,3.1)这个点，

1、从树的根节点开始查找，小于分割点的去左子树查找，大于分割点的去右子树查找，知道叶子节点为止，把经过的点添加到栈中；

上图x(1)代表x轴，x(2)代表y轴，所以是按x->y->x->y....依次交替。首先根据x轴判断，即2.1和7相比，小于，跑到左子树。然后按照y轴判断，即3.1和4比较，小于，跑到左子树，发现是叶子节点，结束，添加到栈中。

![](https://gitee.com/hxc8/images1/raw/master/img/202407172144041.jpg)

2、取出(2,3)点，计算查找点到(2,3)点的距离，距离为0.141,最近点(2,3)最近距离是0.141

3、取出(5,4)点，然后以查找点为圆心以0.141为半径画圆，这个圆与y=4这条线不相交，无需去另一边查找。

![](https://gitee.com/hxc8/images1/raw/master/img/202407172144768.jpg)

4、取出（7,2）点，这个圆与x=7也不相交，栈空了，那么距离(2.1,3.1)最近的点事（2,3），最近距离是0.141.

### 情况2

查找点是（2,4.5）

1、从树的根节点开始查找，小于分割点的去左子树查找，大于分割点的去右子树查找，知道椰子节点位置，把经过的点添加到栈中

![](https://gitee.com/hxc8/images1/raw/master/img/202407172145672.jpg)

 

2、取出（4,7）点，计算查找点到（4,7）的距离3.202，此时最近点事(4,7)点，最近距离是3.202

3、取出（5,4）点，然后以查找点为圆心以3.202为半径画圆，这个圆与y=4这条线相交，需要去另一边查找；添加另一边的(2,3)点到栈中；

![](https://gitee.com/hxc8/images1/raw/master/img/202407172145600.jpg)

 4、计算查找点到（5,4）点距离为3.04，小于3.202，现在最近点事（5,4），最近距离是3.04；

5、取出（2,3）点，该点事叶子节点，直接计算查找点到该点的距离为1.5，小于3.04，所以最近点距离为1.5，最近点为（2,3）

6、回溯到（7,2）点，以查找点为圆心，以1.5为半径画圆，不与x=7相交；栈已空，最终最近的点就是（2,3）点，最近距离为1.5。

## 总结

- kd树的构建过程【知道】

	- 1.**构造根节点**

	- 2.**通过递归的方法，不断地对k维空间进行切分，生成子节点**

	- 3.重复第二步骤，直到子区域中没有实例时终止

	- 需要关注细节：**a.选择向量的哪一维进行划分；b.如何划分数据**

- kd树的搜索过程【知道】

	- 1**.二叉树搜索比较待查询节点和分裂节点的分裂维的值**，（小于等于就进入左子树分支，大于就进入右子树分支直到叶子结点）

	- 2.**顺着“搜索路径”找到最近邻的近似点**

	- 3.**回溯搜索路径**，并判断搜索路径上的结点的其他子结点空间中是否可能有距离查询点更近的数据点，如果有可能，则需要跳到其他子结点空间中去搜索

	- 4.**重复这个过程直到搜索路径为空**

![](https://gitee.com/hxc8/images1/raw/master/img/202407172145551.jpg)

## 再识K-近邻算法API

- sklearn.neighbors.KNeighborsClassifier(n_neighbors=5,algorithm='auto')

	- n_neighbors：

		- int,可选（默认= 5），k_neighbors查询默认使用的邻居数

	- algorithm：{‘auto’，‘ball_tree’，‘kd_tree’，‘brute’}

		- 快速k近邻搜索算法，默认参数为auto，可以理解为算法自己决定合适的搜索算法。除此之外，用户也可以自己指定搜索算法ball_tree、kd_tree、brute方法进行搜索，

			- brute是蛮力搜索，也就是线性扫描，当训练集很大时，计算非常耗时。

			- kd_tree，构造kd树存储数据以便对其进行快速检索的树形数据结构，kd树也就是数据结构中的二叉树。以中值切分构造的树，每个结点是一个超矩形，在维数小于20时效率高。

			- ball tree是为了克服kd树高维失效而发明的，其构造过程是以质心C和半径r分割样本空间，每个节点是一个超球体。

```
from sklearn.neighbors import KNeighborsClassifier
from sklearn.datasets import load_iris
from sklearn.model_selection import train_test_split
from sklearn.preprocessing import StandardScaler
# 1 获取数据
iris = load_iris()

# 2 数据基本处理
x_train,x_test,y_train,y_test = train_test_split(iris.data,iris.target,test_size=0.2,random_state=22)

# 3 特征工程-特征预处理
transfer = StandardScaler()
x_train = transfer.fit_transform(x_train) # 计算训练集的均值和方差
x_test = transfer.transform(x_test)

# 4 机器学习-KNN
# 4.1实例化一个估计器
estimator = KNeighborsClassifier(n_neighbors=5)
# 4.2 模型训练
estimator.fit(x_train,y_train)

# 5 模型评估
# 5.1 预测值结果输出
y_pred = estimator.predict(x_test)
print("预测值是：",y_pred)
print("预测值和真实值的对比是：",y_pred==y_test)

# 5.2 准确率计算
score = estimator.score(x_test,y_test)
print("准确率为：",score)

输出

预测值是： [0 2 1 2 1 1 1 1 1 0 2 1 2 2 0 2 1 1 1 1 0 2 0 1 2 0 2 2 2 2]
预测值和真实值的对比是： [ True  True  True  True  True  True  True False  True  True  True  True
  True  True  True  True  True  True False  True  True  True  True  True
  True  True  True  True  True  True]
准确率为： 0.9333333333333333
```

# 1.9 KNN算法总结

## 1 k近邻算法优缺点汇总

- 优点：

	- 简单有效

	- 重新训练的代价低

	- 适合类域交叉样本

		- KNN方法主要靠周围有限的邻近的样本,而不是靠判别类域的方法来确定所属类别的，因此对于类域的交叉或重叠较多的待分样本集来说，KNN方法较其他方法更为适合。

	- 适合样本容量比较大的类域自动分类

		- 该算法比较**适用于样本容量比较大的类域的自动分类**，而那些**样本容量较小的类域采用这种算法比较容易产生误分**。

```
样本量、样本个数与样本容量的关系举例

一个箱子最多能放50个苹果（样本），从中取样30个。
在这里，苹果是样本，箱子最多能放的个数（即苹果的总数）50是这个样本的样本（容）量，而所抽取的样本个数30则是样本量。
```

- 缺点：

	- 惰性学习

		- KNN算法是懒散学习方法（lazy learning,基本上不学习），一些积极学习的算法要快很多

	- 类别评分不是规格化

		- 不像一些通过概率评分的分类

	- 输出可解释性不强

		- 例如决策树的输出可解释性就较强

	- 对不均衡的样本不擅长

		- 当样本不平衡时，如一个类的样本容量很大，而其他类样本容量很小时，有可能导致当输入一个新样本时，该样本的K个邻居中大容量类的样本占多数。该算法只计算“最近的”邻居样本，某一类的样本数量很大，那么或者这类样本并不接近目标样本，或者这类样本很靠近目标样本。无论怎样，数量并不能影响运行结果。可以采用权值的方法（和该样本距离小的邻居权值大）来改进。

	- 计算量较大

		- 目前常用的解决方法是事先对已知样本点进行剪辑，事先去除对分类作用不大的样本。

# 1 什么是交叉验证(cross validation)

交叉验证：将拿到的训练数据，分为训练和验证集。以下图为例：将数据分成4份，其中一份作为验证集。然后经过4次(组)的测试，每次都更换不同的验证集。即得到4组模型的结果，取平均值作为最终结果。又称4折交叉验证。

### 1.1 分析

我们之前知道数据分为训练集和测试集，但是**为了让从训练得到模型结果更加准确。**做以下处理

- 训练集：训练集+验证集

- 测试集：测试集

## 2 什么是网格搜索(Grid Search)

通常情况下，**有很多参数是需要手动指定的（如k-近邻算法中的K值），这种叫超参数**。但是手动过程繁杂，所以需要对模型预设几种超参数组合。**每组超参数都采用交叉验证来进行评估。最后选出最优参数组合建立模型。**

## 3 交叉验证-网格搜索API：

- sklearn.model_selection.GridSearchCV(estimator, param_grid=None,cv=None)

	- 解释：对估计器的指定参数值进行详尽搜索

	- 参数：

		- estimator：估计器对象

		- param_grid：估计器参数(dict){“n_neighbors”:[1,3,5]}

		- cv：指定几折交叉验证

	- 方法：

		- fit：输入训练数据

		- score：准确率

	- 结果分析：

		- best***score__:在交叉验证中验证的最好结果***

		- best***estimator***：最好的参数模型

		- cv***results***:每次交叉验证后的验证集准确率结果和训练集准确率结果 

```
from sklearn.neighbors import KNeighborsClassifier
from sklearn.datasets import load_iris
from sklearn.model_selection import train_test_split,GridSearchCV
from sklearn.preprocessing import StandardScaler
# 1、获取数据集
iris = load_iris()
# 2、数据基本处理 -- 划分数据集
x_train, x_test, y_train, y_test = train_test_split(iris.data, iris.target, random_state=22)

# 3、特征工程：标准化
# 实例化一个转换器类
transfer = StandardScaler()
# 调用fit_transform
x_train = transfer.fit_transform(x_train)
x_test = transfer.transform(x_test)

# 4、KNN预估器流程
#  4.1 实例化预估器类
estimator = KNeighborsClassifier()

# 4.2 模型选择与调优——网格搜索和交叉验证
# 准备要调的超参数
param_dict = {'n_neighbors':[1,3,5,7]}
estimator = GridSearchCV(estimator,param_grid=param_dict,cv=5)

# 4.3 fit数据进行训练
estimator.fit(x_train,y_train)

# 5、评估模型效果
# 方法a：比对预测结果和真实值
y_predict = estimator.predict(x_test)
print("比对预测结果和真实值：\n", y_predict == y_test)

# 方法b：直接计算准确率
score = estimator.score(x_test, y_test)
print("直接计算准确率：\n", score)

#然后进行评估查看最终选择的结果和交叉验证的结果
print("在交叉验证中验证的最好结果：\n", estimator.best_score_)
print("最好的参数模型：\n", estimator.best_estimator_)
print("每次交叉验证后的准确率结果：\n", estimator.cv_results_)
```

输出

```
比对预测结果和真实值：
 [ True  True  True  True  True  True  True False  True  True  True  True
  True  True  True  True  True  True False  True  True  True  True  True
  True  True  True  True  True  True  True  True  True  True  True  True
  True  True]
直接计算准确率：
 0.9473684210526315
在交叉验证中验证的最好结果：
 0.9553359683794467
最好的参数模型：
 KNeighborsClassifier()
每次交叉验证后的准确率结果：
 {'mean_fit_time': array([0.00084419, 0.00078721, 0.00080686, 0.0007925 ]), 'std_fit_time': array([5.85565738e-05, 1.23779432e-05, 1.56104280e-05, 1.44631871e-06]), 'mean_score_time': array([0.00314808, 0.00352588, 0.00313988, 0.00318003]), 'std_score_time': array([1.23808636e-04, 7.56915459e-04, 2.61585929e-05, 3.74309576e-05]), 'param_n_neighbors': masked_array(data=[1, 3, 5, 7],
             mask=[False, False, False, False],
       fill_value='?',
            dtype=object), 'params': [{'n_neighbors': 1}, {'n_neighbors': 3}, {'n_neighbors': 5}, {'n_neighbors': 7}], 'split0_test_score': array([0.95652174, 0.95652174, 1.        , 1.        ]), 'split1_test_score': array([0.91304348, 0.95652174, 0.91304348, 0.91304348]), 'split2_test_score': array([1., 1., 1., 1.]), 'split3_test_score': array([0.86363636, 0.86363636, 0.90909091, 0.90909091]), 'split4_test_score': array([0.95454545, 0.95454545, 0.95454545, 0.95454545]), 'mean_test_score': array([0.93754941, 0.94624506, 0.95533597, 0.95533597]), 'std_test_score': array([0.04607075, 0.04470773, 0.03979356, 0.03979356]), 'rank_test_score': array([4, 3, 1, 1], dtype=int32)}
```

## 5 总结

- 交叉验证【知道】

	- 定义：

		- 将拿到的训练数据，分为训练和验证集

		- *折交叉验证

	- 分割方式：

		- 训练集：训练集+验证集

		- 测试集：测试集

	- 为什么需要交叉验证

		- 为了让被评估的模型更加准确可信

		- **注意：交叉验证不能提高模型的准确率**

- 网格搜索【知道】

	- 超参数:

		- sklearn中,需要手动指定的参数,叫做超参数

	- 网格搜索就是把这些超参数的值,通过字典的形式传递进去,然后进行选择最优值

- api【知道】

	- sklearn.model_selection.GridSearchCV(estimator, param_grid=None,cv=None)

		- estimator -- 选择了哪个训练模型

		- param_grid -- 需要传递的超参数

		- cv -- 几折交叉验证