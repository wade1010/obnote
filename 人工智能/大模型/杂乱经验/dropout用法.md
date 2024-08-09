Dropout是一种正则化技术，用于防止神经网络过拟合。它在训练过程中随机地将网络中的一些神经元输出设置为零，从而使得这些神经元在本次前向传播和反向传播中不参与计算。Dropout在前向传播中使用，而不是在反向传播中使用。

具体来说，Dropout的工作流程如下：

1. **前向传播**：在每次前向传播时，Dropout层会根据设定的概率（例如0.5）随机选择一些神经元，并将它们的输出设置为零。这样做的效果是模拟了一个更小的网络，因为一些神经元被“丢弃”了。

2. **反向传播**：在反向传播过程中，只有那些在前向传播中未被丢弃的神经元会更新其权重。被丢弃的神经元不参与梯度计算和权重更新。

3. **测试阶段**：在模型测试或推理阶段，Dropout层通常是关闭的，即所有神经元都参与计算，但是它们的输出会乘以Dropout概率，以保持与训练时的期望值一致。

在实际编码中，可以使用深度学习框架（如PyTorch或TensorFlow）提供的Dropout函数来实现Dropout。例如，在PyTorch中，可以这样使用：

```python
import torch
import torch.nn as nn
import torch.nn.functional as F

class Net(nn.Module):
    def __init__(self):
        super(Net, self).__init__()
        self.fc1 = nn.Linear(10, 20)
        self.fc2 = nn.Linear(20, 10)
        self.dropout = nn.Dropout(p=0.5)  # 设置Dropout概率为0.5

    def forward(self, x):
        x = F.relu(self.fc1(x))
        x = self.dropout(x)  # 在前向传播中使用Dropout
        x = self.fc2(x)
        return x

# 创建模型实例
model = Net()

# 假设我们有一个输入张量 x
x = torch.randn(1, 10)  # 1个样本，每个样本有10个特征

# 前向传播
output = model(x)
```

在这个例子中，`nn.Dropout`在前向传播过程中随机丢弃一些神经元的输出，从而实现正则化的效果。


---
dropout可以减少过拟合，可以应用于下面几个地方：
- 计算注意力权重后（最常见）
- 将注意力权重与值向量相乘后。

使用0.5的dropout，意味着未被屏蔽的值按照1/0.5=2的比例相应缩放，比如原始矩阵里面都是1，经过dropout之后，未被屏蔽的值就从1变成了2。
使用0.2的dropout，意味着未被屏蔽的值按照1/(1-0.2)=1.25的比例相应缩放，比如原始矩阵里面都是1，经过dropout之后，未被屏蔽的值就从1变成了1.25。
