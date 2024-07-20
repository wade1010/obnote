- 本次比赛的目的是**预测一个人将要签到的地方。**

- 为了本次比赛，Facebook创建了一个虚拟世界，其中包括**10公里*10公里共100平方公里的约10万个地方。**

- 对于给定的坐标集，您的任务将**根据用户的位置，准确性和时间戳等预测用户下一次的签到位置。**

- 数据被制作成类似于来自移动设备的位置数据。

- 请注意：您只能使用提供的数据进行预测。

官网：[https://www.kaggle.com/c/facebook-v-predicting-check-ins](https://www.kaggle.com/c/facebook-v-predicting-check-ins)

```python
文件说明 train.csv, test.csv
  row id：签入事件的id
  x y：坐标
  accuracy: 准确度，定位精度
  time: 时间戳
  place_id: 签到的位置，这也是你需要预测的内容
```

## 3 步骤分析

- 对于数据做一些基本处理（这里所做的一些处理不一定达到很好的效果，我们只是简单尝试，有些特征我们可以根据一些特征选择的方式去做处理）

	- 1 缩小数据集范围 DataFrame.query()

	- 2 选取有用的时间特征

	- 3 将签到位置少于n个用户的删除

- 分割数据集

- 标准化处理

- k-近邻预测

```
具体步骤：
# 1.获取数据集
# 2.基本数据处理
# 2.1 缩小数据范围
# 2.2 选择时间特征
# 2.3 去掉签到较少的地方
# 2.4 确定特征值和目标值
# 2.5 分割数据集
# 3.特征工程 -- 特征预处理(标准化)
# 4.机器学习 -- knn+cv
# 5.模型评估
```

## 4 代码实现

- 1.获取数据集

```python
# 1、获取数据集
facebook = pd.read_csv("./data/FBlocation/train.csv")
```

- 2.基本数据处理

```python
# 2.基本数据处理
# 2.1 缩小数据范围
facebook_data = facebook.query("x>2.0 & x<2.5 & y>2.0 & y<2.5")
# 2.2 选择时间特征
time = pd.to_datetime(facebook_data["time"], unit="s")
time = pd.DatetimeIndex(time)
facebook_data["day"] = time.day
facebook_data["hour"] = time.hour
facebook_data["weekday"] = time.weekday
# 2.3 去掉签到较少的地方
place_count = facebook_data.groupby("place_id").count()
place_count = place_count[place_count["row_id"]>3]
facebook_data = facebook_data[facebook_data["place_id"].isin(place_count.index)]
# 2.4 确定特征值和目标值
x = facebook_data[["x", "y", "accuracy", "day", "hour", "weekday"]]
y = facebook_data["place_id"]
# 2.5 分割数据集
x_train, x_test, y_train, y_test = train_test_split(x, y, random_state=22)
```

- 3.特征工程--特征预处理(标准化)

```python
# 3.特征工程--特征预处理(标准化)
# 3.1 实例化一个转换器
transfer = StandardScaler()
# 3.2 调用fit_transform
x_train = transfer.fit_transform(x_train)
x_test = transfer.transform(x_test)
```

- 4.机器学习--knn+cv

```python
# 4.机器学习--knn+cv
# 4.1 实例化一个估计器
estimator = KNeighborsClassifier()
# 4.2 调用gridsearchCV
param_grid = {"n_neighbors": [1, 3, 5, 7, 9]}
estimator = GridSearchCV(estimator, param_grid=param_grid, cv=5)
# 4.3 模型训练
estimator.fit(x_train, y_train)
```

- 5.模型评估

```python
# 5.模型评估
# 5.1 基本评估方式
score = estimator.score(x_test, y_test)
print("最后预测的准确率为:\n", score)

y_predict = estimator.predict(x_test)
print("最后的预测值为:\n", y_predict)
print("预测值和真实值的对比情况:\n", y_predict == y_test)

# 5.2 使用交叉验证后的评估方式
print("在交叉验证中验证的最好结果:\n", estimator.best_score_)
print("最好的参数模型:\n", estimator.best_estimator_)
print("每次交叉验证后的验证集准确率结果和训练集准确率结果:\n",estimator.cv_results_)
```