### 1 申请下载模型权限

[https://ai.meta.com/resources/models-and-libraries/llama-downloads/](https://ai.meta.com/resources/models-and-libraries/llama-downloads/)

稍微认证填一填，我这次大概10分钟左右给我通过了

邮件内容如下：

![](https://gitee.com/hxc8/images2/raw/master/img/202407172156412.jpg)

### 2 下载llama源码

```
git clone git@github.com:facebookresearch/llama.git
```

### 3 下载模型

使用源码里面的download.sh进行下载

如下图

![](https://gitee.com/hxc8/images2/raw/master/img/202407172156288.jpg)

第一步让你输入邮件里面那个授权url，很长，[https://download.llamameta.net](https://download.llamameta.net)开头

第二步让你输入想要下载的模型名称，这里下载的是70B-chat

之后会下载几个LICENSE和tokenizer.model等

再之后就是我们最需要的模型文件了。如下图

![](https://gitee.com/hxc8/images2/raw/master/img/202407172156299.jpg)

### 4 下载花絮

2023-7-22 11:20:30，开始下载的时候是2023-7-21 17:30，过去这么久，下载了不少模型了，但是刚刚发现报错了。。。。

![](https://gitee.com/hxc8/images2/raw/master/img/202407172156516.jpg)

不知道能不能继续

![](https://gitee.com/hxc8/images2/raw/master/img/202407172156427.jpg)

然后重新执行download.sh脚本，发现会重新下载已经下载过的模型，ε=(´ο｀*)))唉！！！！

![](https://gitee.com/hxc8/images2/raw/master/img/202407172156096.jpg)

只能改下源码，跳过已经下载过的。

我这里原本下载好了00 01 02  03 04 05 06，07也有，由于07是最后一个，不确定下载完成没，所以也当做没下载，另外00在我重试download.sh脚本的时候覆盖了，也是不完整的，所以我把download.sh脚本改为如下图

![](https://gitee.com/hxc8/images2/raw/master/img/202407172156503.jpg)

if [[ $s != "01" && $s != "02" && $s != "03" && $s != "04" && $s != "05" && $s != "06" ]]

wget xxxx

fi

继续下载，后续再补充

2023-7-22 14:50:21总算下载完成

![](https://gitee.com/hxc8/images2/raw/master/img/202407172156834.jpg)

模型大概129G

![](https://gitee.com/hxc8/images2/raw/master/img/202407172157127.jpg)

### 5 跑官方demo

​

2023-7-24 22:10

​编辑

![](https://gitee.com/hxc8/images2/raw/master/img/202407172157294.jpg)

![](https://gitee.com/hxc8/images2/raw/master/img/202407172157595.jpg)

官方说了这里需要8个MP，所以我跑的时候指定了8个GPU

CUDA_VISIBLE_DEVICES=1,2,3,4,6,7,8,9 torchrun --nproc_per_node 8 --master_port=29501 example_chat_completion.py --ckpt_dir llama-2-70b-chat/ --tokenizer_path tokenizer.model --max_seq_len 512 --max_batch_size 4

启动命令后，查看GPU状态，如下图

​编辑

![](https://gitee.com/hxc8/images2/raw/master/img/202407172157741.jpg)

![](https://gitee.com/hxc8/images2/raw/master/img/202407172157676.jpg)

查看终端输出

​编辑

![](https://gitee.com/hxc8/images2/raw/master/img/202407172157649.jpg)

![](https://gitee.com/hxc8/images2/raw/master/img/202407172157164.jpg)

很顺利的运行了！ 

### 6 微调

2023-7-26 15:01:05

准备使用text-generation-webui，

我把下载的模型移动到text-generation-webui的models目录下让后web界面操作

![](https://gitee.com/hxc8/images2/raw/master/img/202407172157031.jpg)

然后load，发现报错如下

![](https://gitee.com/hxc8/images2/raw/master/img/202407172157604.jpg)

错误内容：

models/llama-2-70b-chat does not appear to have a file named config.json

发现我下载的不是hf（半精度）版本，还需要转换下

[https://github.com/oobabooga/text-generation-webui/blob/main/docs/LLaMA-v2-model.md](https://github.com/oobabooga/text-generation-webui/blob/main/docs/LLaMA-v2-model.md)

操作如下：

把llama-2-70b-chat这个模型文件目录还原到llama2的目录下

cd llama

git clone '[https://github.com/huggingface/transformers](https://github.com/huggingface/transformers)'

ln -s llama-2-70b-chat 70B

mkdir llama-2-70b-chat-hf

python ./transformers/src/transformers/models/llama/convert_llama_weights_to_hf.py --input_dir . --model_size 70B --output_dir llama-2-70b-chat-hf --safe_serialization true

2023-7-26 17:21分开始执行，2023-7-26 17:32结束

 

移动llama-2-70b-chat-hf到text-generation-webui/models

设置下GPU 

![](https://gitee.com/hxc8/images2/raw/master/img/202407172157915.jpg)

我这里用1,2,3,4,6,7,8,9

![](https://gitee.com/hxc8/images2/raw/master/img/202407172157657.jpg)

[https://github.com/mlc-ai/mlc-llm](https://github.com/mlc-ai/mlc-llm)  手机电脑都能跑llma2  

vulkan.lunarg.com 下载驱动