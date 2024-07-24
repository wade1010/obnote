可以在web_demo.py的最后一行，修改为

demo.queue().launch(share=False, inbrowser=True, server_name='192.168.XX.XX', server_port=XXXX)

其它常见问题

1、web_demo如何开放给其他人用？如何修改端口？

可以在web_demo.py的最后一行，修改为

demo.queue().launch(share=False, inbrowser=True, server_name='192.168.XX.XX', server_port=XXXX)

2、我爆显存了，如何开启8bit量化？

在finetune.py大概77行，找到model = AutoModel.from_pretrained函数，改为load_in_8bit=True开启量化。

3、如何接着上次的训练结果训练？

在finetune.py大概100行，找到model = get_peft_model(model, peft_config)，另起一行在下面加上以下代码

 peft_path = "output\checkpoint-400\optimizer.pt"

model.load_state_dict(torch.load(peft_path), strict=False)

其中，checkpoint-400需要修改为你要接着训练的文件所在的目录

4、微调需要多大的显存？

全精度需要至少14G显存，8bit需要12G显存

5、手动写数据集太麻烦了，有没有什么方便的办法。

可以让chatgpt帮忙生成对话数据。例如

我想要一个xxx设定的角色/一些XXX背景的数据，有1、2、3这些要求，帮我按照如下格式，生成100条对话数据，其中instruction是问题，output是答案，input不用填。

{

  "instruction": "你是谁",

  "input": "",

  "output": "我是XXX"

 },

6、数据集必须要问答吗，可以用单纯的文本吗。

不可以，如果你有大量的文本，建议选用闻达或者langchain。如果只是少量的文本，可以让chatgpt帮你整理成问答格式。例如

我有一些文本，以下：XXXXXXX。帮我整理成问答格式，格式如下。其中instruction是问题，output是答案，input不用填。

{

  "instruction": "你是谁",

  "input": "",

  "output": "我是XXX"

 },

7、我训练完效果很差，问他训练的一些问题，老是答非所问。或者一些常识性的答案也受到了影响。

这个是正常现象，目前的微调，原理上就是让模型去学习你给他的数据集，他的回答肯定不会按照标准答案来。如果回答不理想，建议从多个角度问同一问题，增大数据量。如果常识性问题受影响，属于过拟合，建议训练步数少一些，一般loss到0.01差不多，learning rate到1左右，就可以停了。