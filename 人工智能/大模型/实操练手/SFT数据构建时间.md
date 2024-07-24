24台服务器，每台服务器配置一张40G显存的A100

然后在另外一台节点上启动多进程处理32万条原始数据，32万条数据平均分配到24个节点，然后构造prompt，让Qwen产生QA问答对。

核心代码如下
```
chat_response = client.chat.completions.create(  
    model=model,  
    messages=[  
        {"role": "system", "content": "你是一个XXX行业数据集处理专家"},  
        {"role": "user", "content": content},  
    ],  
    max_tokens=4000  
)
```
耗时估算，大概每次请求耗时，平均下来10秒。

```
320000/24*12/3600/24=1.85天
```
