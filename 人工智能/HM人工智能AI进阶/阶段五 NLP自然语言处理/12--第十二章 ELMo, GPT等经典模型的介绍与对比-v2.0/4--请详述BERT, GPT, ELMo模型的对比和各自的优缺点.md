### 学习目标

- 理解BERT, GPT, ELMo相互间的不同点.

- 理解BERT, GPT, ELMo相互比较下的各自优点和缺点.

### BERT, GPT, ELMo之间的不同点

- 关于特征提取器:

	- ELMo采用两部分双层双向LSTM进行特征提取, 然后再进行特征拼接来融合语义信息.

	- GPT和BERT采用Transformer进行特征提取.

	- 很多NLP任务表明Transformer的特征提取能力强于LSTM, 对于ELMo而言, 采用1层静态token embedding + 2层LSTM, 提取特征的能力有限.

- 单/双向语言模型:

	- 三者之中, 只有GPT采用单向语言模型, 而ELMo和BERT都采用双向语言模型.

	- ELMo虽然被认为采用了双向语言模型, 但实际上是左右两个单向语言模型分别提取特征, 然后进行特征拼接, 这种融合特征的能力比BERT一体化的融合特征方式弱.

	- 三者之中, 只有ELMo没有采用Transformer. GPT和BERT都源于Transformer架构, GPT的单向语言模型采用了经过修改后的Decoder模块, Decoder采用了look-ahead mask, 只能看到context before上文信息, 未来的信息都被mask掉了. 而BERT的双向语言模型采用了Encoder模块, Encoder只采用了padding mask, 可以同时看到context before上文信息, 以及context after下文信息.

### BERT, GPT, ELMo各自的优点和缺点

- ELMo:

	- 优点:

		- 从早期的Word2Vec预训练模型的最大缺点出发, 进行改进, 这一缺点就是无法解决多义词的问题.

		- ELMo根据上下文动态调整word embedding, 可以解决多义词的问题.

	- 缺点:

		- ELMo使用LSTM提取特征的能力弱于Transformer.

		- ELMo使用向量拼接的方式融合上下文特征的能力弱于Transformer.

- GPT:

	- 优点:

		- GPT使用了Transformer提取特征, 使得模型能力大幅提升.

	- 缺点:

		- GPT只使用了单向Decoder, 无法融合未来的信息.

- BERT:

	- 优点:

		- BERT使用了双向Transformer提取特征, 使得模型能力大幅提升.

		- 添加了两个预训练任务, MLM + NSP的多任务方式进行模型预训练.

	- 缺点:

		- 模型过于庞大, 参数量太多, 需要的数据和算力要求过高, 训练好的模型应用场景要求高.

		- 更适合用于语言嵌入表达, 语言理解方面的任务, 不适合用于生成式的任务.

### 小节总结

- 学习了BERT, GPT, ELMo之间的区别:

	- 三者所选取的特征提取器不同.

		- BERT采用的是Transformer架构中的Encoder模块.

		- GPT采用的是Transformer架构中的Decoder模块.

		- ELMo采用的双层双向LSTM模块.

	- 三者所采用的语言模型单/双向不同.

		- BERT采用的是最彻底的双向语言模型, 可以同时关注context before和context after.

		- GPT采用的是单向语言模型, 即Transformer中的Decoder, 由于采用了mask机制, 所以未来信息context after都不可见.

		- ELMo表面上被认为是双向语言模型, 但实际上是左右两个单向LSTM模型分别提取特征, 在进行简单的拼接融合.