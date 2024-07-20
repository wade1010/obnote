## ndarray的优势

#### 4.1 内存块风格

ndarray到底跟原生python列表有什么不同呢，请看一张图：

![](https://gitee.com/hxc8/images1/raw/master/img/202407172145588.jpg)

从图中我们可以看出ndarray在存储数据的时候，数据与数据的地址都是连续的，这样就给使得批量操作数组元素时速度更快。

这是因为ndarray中的所有元素的类型都是相同的，而**Python列表中的元素类型是任意的**，所以ndarray在存储元素时内存可以连续，而python原生list就只能通过寻址方式找到下一个元素，这虽然也导致了在通用性能方面Numpy的ndarray不及Python原生list，但在科学计算中，Numpy的ndarray就可以省掉很多循环语句，代码使用方面比Python原生list简单的多。

#### 4.2 支持并行化运算

numpy内置了并行运算功能，当系统有多个核心时，做某种计算时，numpy会自动做并行计算（向量化运算）。

#### 4.3 效率远高于纯Python代码

Numpy底层使用C语言编写，**内部解除了GIL（**全局解释器锁），其对数组的操作速度不受Python解释器的限制，所以，其效率远高于纯Python代码。

## ndarray的属性

数组属性反映了数组本身固有的信息。

| 属性名字 | 属性解释 | 
| -- | -- |
| ndarray.shape | 数组维度的元组 | 
| ndarray.ndim | 数组维数 | 
| ndarray.size | 数组中的元素数量 | 
| ndarray.itemsize | 一个数组元素的长度（字节） | 
| ndarray.dtype | 数组元素的类型 | 


## 形状修改

### 3.1 ndarray.reshape()

ndarray.reshape(shape, order)

- 返回一个具有相同数据域，但shape不一样的**视图**

- 行、列不进行互换

```python
# 在转换形状的时候，一定要注意数组的元素匹配
stock_change.reshape([5, 4])
stock_change.reshape([-1,10])  # 数组的形状被修改为: (2, 10), -1: 表示通过待计算
```

## np.where(三元运算符)

通过使用np.where能够进行更加复杂的运算

- np.where()

```python
# 判断前四名学生,前四门课程中，成绩中大于60的置为1，否则为0
temp = score[:4, :4]
np.where(temp > 60, 1, 0)
```

- 复合逻辑需要结合np.logical_and和np.logical_or使用

```python
# 判断前四名学生,前四门课程中，成绩中大于60且小于90的换为1，否则为0
np.where(np.logical_and(temp > 60, temp < 90), 1, 0)

# 判断前四名学生,前四门课程中，成绩中大于90或小于60的换为1，否则为0
np.where(np.logical_or(temp > 90, temp < 60), 1, 0)
```

np.argmax(axis=) — 最大元素对应的下标

np.argmin(axis=) — 最小元素对应的下标

- 知道数组与数之间的运算

- 知道数组与数组之间的运算

- 说明数组间运算的广播机制

数组在进行矢量化运算时，**要求数组的形状是相等的**。当形状不相等的数组执行算术运算的时候，就会出现广播机制，该机制会对数组进行扩展，使数组的shape属性值一样，这样，就可以进行矢量化运算了。

下面通过一个例子进行说明：

```python
arr1 = np.array([[0],[1],[2],[3]])
arr1.shape
# (4, 1)

arr2 = np.array([1,2,3])
arr2.shape
# (3,)

arr1+arr2
# 结果是：
array([[1, 2, 3],
       [2, 3, 4],
       [3, 4, 5],
       [4, 5, 6]])
```

上述代码中，数组arr1是4行1列，arr2是1行3列。这两个数组要进行相加，按照广播机制会对数组arr1和arr2都进行扩展，使得数组arr1和arr2都变成4行3列。

下面通过一张图来描述广播机制扩展数组的过程：

![](https://gitee.com/hxc8/images1/raw/master/img/202407172145506.jpg)

这句话乃是理解广播的核心。广播主要发生在两种情况，一种是两个数组的维数不相等，但是它们的后缘维度的轴长相符，另外一种是有一方的长度为1。

广播机制实现了时两个或两个以上数组的运算，即使这些数组的shape不是完全相同的，只需要满足如下任意一个条件即可。

- 如果**两个数组的后缘维度（trailing dimension，即从末尾开始算起的维度）的轴长度相符**，

- 或**其中的一方的长度为1**。

广播会在缺失和（或）长度为1的维度上进行。

广播机制需要**扩展维度小的数组**，使得它与维度最大的数组的shape值相同，以便使用元素级函数或者运算符进行运算。

如果是下面这样，则不匹配：

```python
A  (1d array): 10
B  (1d array): 12
A  (2d array):      2 x 1
B  (3d array):  8 x 4 x 3
```

**思考：下面两个ndarray是否能够进行运算？**

```python
arr1 = np.array([[1, 2, 3, 2, 1, 4], [5, 6, 1, 2, 3, 1]])
arr2 = np.array([[1], [3]])
```

 小结

- 数组运算,满足广播机制,就OK【知道】

	- 1.维度相等

	- 2.shape(其中对应的地方为1,也是可以的)

```python
A  (2d array):      2 x 1
B  (3d array):  8 x 4 x 3

后缘维度，就是从后往前（从右往左）
1和3是满足上面第二条的
2和4，不满足，可以修改A，把2改成4（满足第一条）或者把2改成1（满足第二条）
如果修改B，可以把4修改为2（满足第一条）或者把4修改为1（满足第一条）
 
 再往前 A是0了，就不需要关注了
```

![](https://gitee.com/hxc8/images1/raw/master/img/202407172145002.jpg)

**矩阵乘法遵循准则：**

**(M行, N列)*(N行, L列) = (M行, L列)**

![](https://gitee.com/hxc8/images1/raw/master/img/202407172145780.jpg)

![](https://gitee.com/hxc8/images1/raw/master/img/202407172145681.jpg)

###  矩阵乘法api介绍

- np.matmul

- np.dot

**np.matmul和np.dot的区别:**

> 二者都是矩阵乘法。


- np.matmul中禁止矩阵与标量的乘法。

- 在矢量乘矢量的內积运算中，np.matmul与np.dot没有区别。