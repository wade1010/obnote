# embedding演示


# 8代表的是词表的大小(nn.Embedding(8, x)), 也就是只能编码0-7(torch.LongTensor([[1, 2, 3, 4], [4, 7, 2, 1]]))  最大是7


# 如果是10(nn.Embedding(10, x))， 代表只能编码0-9 (torch.LongTensor([[1, 2, 3, 4], [4, 9, 2, 1]]))  最大是9

这里有11出现所以尚明embedding的时候要写成12


embedding = nn.Embedding(12, 3)


input = torch.LongTensor([[1, 2, 3, 4], [4, 11, 2, 1]])

[[1, 2, 3, 4], [4, 11, 2, 1]]，这里相当于两个样本，每个样本有4个字符/单词，通过embedding = nn.Embedding(12, 3)，知道每个字符/单词被映射为3个维度。

可以结合如下图理解

![](https://gitee.com/hxc8/images1/raw/master/img/202407172130275.jpg)

向下的箭头，理解为batchsize  为2

横轴，表示sequence length，表示每个样本有多少长度。这里为4

斜上方向，表示每个词被映射成多少个维度，这里为3

```
import torch
import torch.nn as nn
import math
from torch.autograd import Variable

class Embeddings(nn.Module):
    def __init__(self, d_model, vocab):
        super(Embeddings, self).__init__()
        self.lut = nn.Embedding(vocab, d_model)
        self.d_model = d_model


    def forward(self, x):
        return self.lut(x) * math.sqrt(self.d_model)

# 词嵌入维度是512维
d_model = 512

# 词表大小是1000
vocab = 1000
# 输入x是一个使用Variable封装的长整型张量, 形状是2 x 4 注意： 这里必须保证两句话的长度一致！！！
x = Variable(torch.LongTensor([[100,2,421,508],[491,998,1,221]]))

emb = Embeddings(d_model, vocab)
embr = emb(x)
print("embr:", embr.shape)
```

执行embr = emb(x)时会执行forward方法是因为在nn.Module子类中，PyTorch会自动调用模块的__call__方法，而__call__方法会调用模块的forward方法。

在nn.Module的子类中，定义了forward方法，该方法定义了模块的前向传播逻辑。当我们调用模块对象（如emb）时，会自动触发__call__方法，进而调用模块的forward方法。