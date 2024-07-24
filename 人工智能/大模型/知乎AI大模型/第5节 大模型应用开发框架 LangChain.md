## LangChain 的核心组件

1. 模型 I/O 封装

	- LLMs：大语言模型

	- Chat Models：一般基于 LLMs，但按对话结构重新封装

	- PromptTemple：提示词模板

	- OutputParser：解析输出

1. 数据连接封装

	- Document Loaders：各种格式文件的加载器

	- Document transformers：对文档的常用操作，如：split, filter, translate, extract metadata, etc

	- Text Embedding Models：文本向量化表示，用于检索等操作（啥意思？别急，后面详细讲）

	- Verctor stores: （面向检索的）向量的存储

	- Retrievers: 向量的检索

1. 记忆封装

	- Memory：这里不是物理内存，从文本的角度，可以理解为“上文”、“历史记录”或者说“记忆力”的管理

1. 架构封装

	- Chain：实现一个功能或者一系列顺序功能组合

	- Agent：根据用户输入，自动规划执行步骤，自动选择每步需要的工具，最终完成用户指定的功能


		- Tools：调用外部功能的函数，例如：调 google 搜索、文件 I/O、Linux Shell 等等

		- Toolkits：操作某软件的一组工具集，例如：操作 DB、操作 Gmail 等等

1. Callbacks 