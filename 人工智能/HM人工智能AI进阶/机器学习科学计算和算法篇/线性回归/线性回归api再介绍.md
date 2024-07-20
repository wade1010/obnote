- sklearn.linear_model.LinearRegression(fit_intercept=True)

	- 通过正规方程优化

	- 参数

		- fit_intercept：是否计算偏置

	- 属性

		- LinearRegression.coef_：回归系数

		- LinearRegression.intercept_：偏置

- sklearn.linear_model.SGDRegressor(loss="squared_loss", fit_intercept=True, learning_rate ='invscaling', eta0=0.01)

	- SGDRegressor类实现了随机梯度下降学习，它支持不同的**loss函数和正则化惩罚项**来拟合线性回归模型。

	- 参数：

		- loss:损失类型

			- loss=”squared_loss”: 普通最小二乘法

		- fit_intercept：是否计算偏置

		- learning_rate : string, optional

			- 学习率填充

			- 'constant': eta = eta0

			- 'optimal': eta = 1.0 / (alpha * (t + t0))

			- 'invscaling': eta = eta0 / pow(t, power_t)[default]

				- power_t=0.25:存在父类当中

			- 对于一个常数值的学习率来说，可以使用learning_rate=’constant’ ，并使用eta0来指定学习率。

	- 属性：

		- SGDRegressor.coef_：回归系数

		- SGDRegressor.intercept_：偏置

> sklearn提供给我们两种实现的API， 可以根据选择使用


小结

- 正规方程

	- sklearn.linear_model.LinearRegression()

- 梯度下降法

	- sklearn.linear_model.SGDRegressor(）