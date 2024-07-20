数据预处理策略：填充（padding）

![](https://gitee.com/hxc8/images0/raw/master/img/202407172039886.jpg)

原始数据通过load_dataset,加载到内存，变成一条条我们可用的数据。tokenizer分词，映射编码，然后变成一个向量。

![](https://gitee.com/hxc8/images0/raw/master/img/202407172039231.jpg)

如果这个attention mask是1，说明它原文，我们没有做更复杂的处理，tokenizer的默认逻辑是，如果不是被填充无意义的值，它的attention mask值就是1，因为它是正文，不是填充的无意义的值。

![](https://gitee.com/hxc8/images0/raw/master/img/202407172039111.jpg)

上面的警告，因为我们就是不用它，我们会丢掉这些权重，然后给它一些随机化的值，然后再去微调这个模型。