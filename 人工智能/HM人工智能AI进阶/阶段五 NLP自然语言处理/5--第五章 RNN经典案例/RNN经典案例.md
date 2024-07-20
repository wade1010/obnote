[07_0.RNN经典案例 - 学习手册.pdf](attachments/WEBRESOURCE73bfef30925ae476c974cd4a3feeb89107_0.RNN经典案例 - 学习手册.pdf)

### 使⽤RNN模型构建⼈名分类器

⼩节总结:

学习了关于⼈名分类问题: 以⼀个⼈名为输⼊, 使⽤模型帮助我们判断它最有可能是来⾃

哪⼀个国家的⼈名, 这在某些国际化公司的业务中具有重要意义, 在⽤户注册过程中, 会根

据⽤户填写的名字直接给他分配可能的国家或地区选项, 以及该国家或地区的国旗, 限制

⼿机号码位数等等.

⼈名分类器的实现可分为以下五个步骤:

第⼀步: 导⼊必备的⼯具包.

第⼆步: 对data⽂件中的数据进⾏处理，满⾜训练要求.

第三步: 构建RNN模型(包括传统RNN, LSTM以及GRU).

第四步: 构建训练函数并进⾏训练.

第五步: 构建评估函数并进⾏预测.

第⼀步: 导⼊必备的⼯具包

python版本使⽤3.6.x, pytorch版本使⽤1.3.1

第⼆步: 对data⽂件中的数据进⾏处理，满⾜训练要求

定义数据集路径并获取常⽤的字符数量.字符规范化之unicode转Ascii函数unicodeToAscii.

构建⼀个从持久化⽂件中读取内容到内存的函数readLines.

构建⼈名类别（所属的语⾔）列表与⼈名对应关系字典

将⼈名转化为对应onehot张量表示函数lineToTensor

第三步: 构建RNN模型

构建传统的RNN模型的类class RNN.

构建LSTM模型的类class LSTM.

构建GRU模型的类class GRU.

第四步: 构建训练函数并进⾏训练

从输出结果中获得指定类别函数categoryFromOutput.

随机⽣成训练数据函数randomTrainingExample.

构建传统RNN训练函数trainRNN.

构建LSTM训练函数trainLSTM.

构建GRU训练函数trainGRU.

构建时间计算函数timeSince.

构建训练过程的⽇志打印函数train.得到损失对⽐曲线和训练耗时对⽐图.

损失对⽐曲线分析:

模型训练的损失降低快慢代表模型收敛程度, 由图可知, 传统RNN的模型收敛情况最

好, 然后是GRU, 最后是LSTM, 这是因为: 我们当前处理的⽂本数据是⼈名, 他们的⻓

度有限, 且⻓距离字⺟间基本⽆特定关联, 因此⽆法发挥改进模型LSTM和GRU的⻓距

离捕捉语义关联的优势. 所以在以后的模型选⽤时, 要通过对任务的分析以及实验对

⽐, 选择最适合的模型.

训练耗时对⽐图分析:

模型训练的耗时⻓短代表模型的计算复杂度, 由图可知, 也正如我们之前的理论分析,

传统RNN复杂度最低, 耗时⼏乎只是后两者的⼀半, 然后是GRU, 最后是复杂度最⾼的

LSTM.

结论:模型选⽤⼀般应通过实验对⽐, 并⾮越复杂或越先进的模型表现越好, ⽽是需要结合

⾃⼰的特定任务, 从对数据的分析和实验结果中获得最佳答案.

第五步: 构建评估函数并进⾏预测

构建传统RNN评估函数evaluateRNN.

构建LSTM评估函数evaluateLSTM.

构建GRU评估函数evaluateGRU.

构建预测函数predict.

### 使⽤seq2seq模型架构实现英译法任务

![](https://gitee.com/hxc8/images1/raw/master/img/202407172132049.jpg)

编码器结构图:

![](https://gitee.com/hxc8/images1/raw/master/img/202407172132417.jpg)

解码器结构图

![](https://gitee.com/hxc8/images1/raw/master/img/202407172132409.jpg)

构建基于GRU和Attention的解码器，解码器结构图:

![](https://gitee.com/hxc8/images1/raw/master/img/202407172132362.jpg)