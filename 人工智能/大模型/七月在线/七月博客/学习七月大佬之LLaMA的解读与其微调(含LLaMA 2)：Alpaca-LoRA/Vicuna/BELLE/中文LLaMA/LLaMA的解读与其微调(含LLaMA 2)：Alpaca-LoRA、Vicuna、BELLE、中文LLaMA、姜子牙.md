### ### LLaMA的模型架构：改造Transformer——RMSNorm/SwiGLU/RoPE
#### 工具包：
fairscale是用来做GPU分布的，一般是当使用DDP仍然遇到超显存的问题时使用fairscale
fire，fire是一个命令行工具，用或者不用他都可以
sentencepiece，sentencepiece是用于tokenizer的工具包
「 _SentencePiece 实现了subword单元（例如，字节对编码(BPE)和 unigram语言模型），并可以直接从原始句子训练字词模型(subword model)，这是对SentencePiece的解读：[大模型词表扩充必备工具SentencePiece](https://zhuanlan.zhihu.com/p/630696264 "大模型词表扩充必备工具SentencePiece")_ 」


#### RMSNorm：对每个Transformer子层的输入进行归一化。
为了提高训练的稳定性，对每个transformer层的输入进行归一化，而不是对输出进行归一化，RMSNorm是一般layerNorm的一种辩题，可以在梯度下降时令损失更加平滑，与layerNorm相比，RMSNorm的主要区别在于删除了减去均值的部分，只保留了方差部分。

#### SwiGLU替代ReLU
