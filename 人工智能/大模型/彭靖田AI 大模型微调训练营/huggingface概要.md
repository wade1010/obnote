名词解释、基本概念

token： 分词，就是一句话分成的每个词

token_type_ids：分词的类型id，比如是个句子对，则属于第一句的token就把他的id设置为0，第二句的就设置为1。

demo：

Bert 的输入需要用 [CLS] 和 [SEP] 进行标记，开头用 [CLS]，句子结尾用 [SEP]

两个句子：

tokens：[CLS] is this jack ##son ##ville ? [SEP] no it is not . [SEP]

token_type_ids：0 0 0 0 0 0 0 0 1 1 1 1 1 1

第一个 [SEP] 属于第一句

一个句子：

tokens：[CLS] the dog is hairy . [SEP]

token_type_ids：0 0 0 0 0 0 0