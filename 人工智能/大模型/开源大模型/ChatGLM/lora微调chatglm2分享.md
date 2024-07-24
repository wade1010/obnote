### 资源信息

模型备份： /llm/model_bin_files/chatglm2-6b

微调代码：/llm/xhcheng/ChatGLM2-6B/tuning  （需要测试的话，拷贝到自己的工作空间）

微调数据：/llm/xhcheng/ChatGLM2-6B/tuning/data/hanbo_cognition.json

### 安装依赖库

创建conda环境后，安装ChatGLM2-6B下面的requirements.txt和安装ChatGLM2-6B/tuning下面的requirements.txt

后续所有操作根目录都是ChatGLM2-6B/tuning下

> 我的环境 conda activate chatglm_etuning


### 数据格式

```
[
  {
    "instruction": "HB",
    "input": "",
    "output": "北京HB科技有限公司聚焦自主可控先进信息技术和产品研发，基于国产自主CPU系列处理器形成了高安全存储、加密存储、网络安全系统等全系列IT产品和解决方案，能够满足自主可控、安全可靠、性能优越的全>面需求，为数据中心、信息基础系统、办公系统等信息化建设提供软硬件全套存储产品和技术。同时，公司面向工业制造、工业安全领域的行业客户，提供自主可控的工业智能解决方案，支持多工业设备的自我感知、互联互通，助>力工业流程的高效建设和稳定运维。公司核心成员来自国内外名校和科技龙头企业，具备核心技术能力和产业落地经验，是公司产品技术更新迭代的坚实基础。专家顾问团队由中国工程院院士领衔的产业资深专家组成，为公司的战>略规划和业务发展提供宝贵建议。"
  },
  {
    "instruction": "HB科技",
    "input": "",
    "output": "北京HB科技有限公司聚焦自主可控先进信息技术和产品研发，基于国产自主CPU系列处理器形成了高安全存储、加密存储、网络安全系统等全系列IT产品和解决方案，能够满足自主可控、安全可靠、性能优越的全>面需求，为数据中心、信息基础系统、办公系统等信息化建设提供软硬件全套存储产品和技术。同时，公司面向工业制造、工业安全领域的行业客户，提供自主可控的工业智能解决方案，支持多工业设备的自我感知、互联互通，助>力工业流程的高效建设和稳定运维。公司核心成员来自国内外名校和科技龙头企业，具备核心技术能力和产业落地经验，是公司产品技术更新迭代的坚实基础。专家顾问团队由中国工程院院士领衔的产业资深专家组成，为公司的战>略规划和业务发展提供宝贵建议。"
  },
  {
    "instruction": "北京HB科技",
    "input": "",
    "output": "北京HB科技有限公司聚焦自主可控先进信息技术和产品研发，基于国产自主CPU系列处理器形成了高安全存储、加密存储、网络安全系统等全系列IT产品和解决方案，能够满足自主可控、安全可靠、性能优越的全>面需求，为数据中心、信息基础系统、办公系统等信息化建设提供软硬件全套存储产品和技术。同时，公司面向工业制造、工业安全领域的行业客户，提供自主可控的工业智能解决方案，支持多工业设备的自我感知、互联互通，助>力工业流程的高效建设和稳定运维。公司核心成员来自国内外名校和科技龙头企业，具备核心技术能力和产业落地经验，是公司产品技术更新迭代的坚实基础。专家顾问团队由中国工程院院士领衔的产业资深专家组成，为公司的战>略规划和业务发展提供宝贵建议。"
  }
 ]
```

instruction+input作为内容传入大模型

output作为回答

### 配置dataset

vim dataset_info.json

![](https://gitee.com/hxc8/images2/raw/master/img/202407172157424.jpg)

### 微调

```
CUDA_VISIBLE_DEVICES=0 python src/train_bash.py     --stage sft     --model_name_or_path /llm/model_bin_files/chatglm2-6b     --do_train     --dataset hanbo_cognition     --finetuning_type lora     --output_dir path_to_sft_checkpoint     --per_device_train_batch_size 2     --gradient_accumulation_steps 2     --lr_scheduler_type cosine     --logging_steps 10     --save_steps 1000     --learning_rate 1e-3     --num_train_epochs 10.0     --fp16
```

### 验证

#### CLI运行

```
CUDA_VISIBLE_DEVICES=0 python src/cli_demo.py \
--model_name_or_path /llm/model_bin_files/chatglm2-6b \
    --checkpoint_dir path_to_sft_checkpoint
```

![](https://gitee.com/hxc8/images2/raw/master/img/202407172157756.jpg)

#### web运行

```
CUDA_VISIBLE_DEVICES=1,2,3 python src/web_demo.py \
    --model_name_or_path /llm/model_bin_files/chatglm2-6b \
     --finetuning_type lora \
    --checkpoint_dir path_to_sft_checkpoint
```

![](https://gitee.com/hxc8/images2/raw/master/img/202407172157961.jpg)

### 扩展-PtuningV2训练产物认识学习

#### 训练过程中日志

```
{'loss': 3.3445, 'learning_rate': 0.017, 'epoch': 0.57}
{'loss': 3.3433, 'learning_rate': 0.016933333333333335, 'epoch': 0.58}
{'loss': 3.3642, 'learning_rate': 0.01686666666666667, 'epoch': 0.59}
{'loss': 3.3622, 'learning_rate': 0.0168, 'epoch': 0.6}
{'loss': 3.3532, 'learning_rate': 0.016733333333333333, 'epoch': 0.62}
{'loss': 3.3513, 'learning_rate': 0.016666666666666666, 'epoch': 0.63}
{'loss': 3.3894, 'learning_rate': 0.0166, 'epoch': 0.64}
{'loss': 3.3386, 'learning_rate': 0.016533333333333334, 'epoch': 0.65}
{'loss': 3.3513, 'learning_rate': 0.016466666666666668, 'epoch': 0.67}
{'loss': 3.3322, 'learning_rate': 0.016399999999999998, 'epoch': 0.68}
{'loss': 3.3509, 'learning_rate': 0.01633333333333333, 'epoch': 0.69}
{'loss': 3.3478, 'learning_rate': 0.01626666666666667, 'epoch': 0.7}
{'loss': 3.3641, 'learning_rate': 0.016200000000000003, 'epoch': 0.72}
{'loss': 3.3413, 'learning_rate': 0.016133333333333333, 'epoch': 0.73}
{'loss': 3.3688, 'learning_rate': 0.016066666666666667, 'epoch': 0.74

loss
即损失值，是模型在训练过程中优化的目标函数。损失值越小，表示模型对训练数据的拟合越好。
learning_rate
即学习率，是控制模型参数更新步长的超参数。学习率越高，模型参数更新的步长就越大，训练速度也会更快。但如果学习率设置得过高，可能会导致模型无法收敛或者震荡不定。反之，学习率设置得过低，则训练速度会很慢，模型也可能无法收敛到最优解。
epoch
即训练轮数，是指模型在训练数据集上进行一次完整的训练所需要的迭代次数。一个 epoch 包含多个迭代步骤，每个迭代步骤会对 batch 数据进行一次前向传播和反向传播，并更新模型参数。训练轮数越多，模型训练得越充分，但同时也可能会导致过拟合。
这些指标可以帮助我们监控模型的训练进度和性能，并根据需要进行调整，以优化模型的表现。在训练过程中，可以关注损失值是否下降，学习率是否过高或过低，以及训练轮数是否足够充分等信息
```

#### 推理结果

```
{
"predict_bleu-4": 7.735923271028038,
"predict_rouge-1": 29.51147121495327,
"predict_rouge-2": 6.489174953271028,
"predict_rouge-l": 23.8471385046729,
"predict_runtime": 65.7331,
"predict_samples": 1070,
"predict_samples_per_second": 16.278,
"predict_steps_per_second": 0.259
}

predict_bleu-4: 这是BLEU-4分数，它是一种机器翻译质量的评估指标，它测量了生成的文本和参考翻译之间的相似程度。BLEU-4是四元组的BLEU分数，表示生成的文本与参考翻译重叠的4元组数量的准确率。
predict_rouge-1: 这是ROUGE-1分数，它是一种评估文本摘要质量的指标。它测量了生成的文本和参考摘要之间的重叠程度。
predict_rouge-2: 这是ROUGE-2分数，它是ROUGE-1的扩展，它测量了生成的文本和参考摘要之间的相似程度，其中包含两个连续的重叠词组。
predict_rouge-l: 这是ROUGE-L分数，它是另一种评估文本摘要质量的指标。它测量了生成的文本和参考摘要之间的最长公共子序列的长度。
predict_runtime: 这是模型在测试集上的评估时间，以秒为单位。
predict_samples: 这是用于评估模型的样本数。
predict_samples_per_second: 这是模型在评估期间处理的样本数每秒。
predict_steps_per_second: 这是模型在评估期间执行的步骤数每秒。
```

#### 训练后output目录

-rw-rw-r-- 1 cheng cheng   197 7月  27 15:42 all_results.json

drwxrwxr-x 2 cheng cheng  4096 7月  27 14:04 checkpoint-1000/

drwxrwxr-x 2 cheng cheng  4096 7月  27 14:53 checkpoint-2000/

drwxrwxr-x 2 cheng cheng  4096 7月  27 15:42 checkpoint-3000/

drwxrwxr-x 2 cheng cheng  4096 7月  27 15:55 evaluate_output/

drwxrwxr-x 3 cheng cheng  4096 7月  27 13:15 runs/

-rw-rw-r-- 1 cheng cheng 35446 7月  27 15:42 trainer_state.json

-rw-rw-r-- 1 cheng cheng   197 7月  27 15:42 train_results.json

```
all_results.json: 包含了整个训练过程中所有的评估结果，包括每个checkpoint的评估结果和最终的评估结果。
checkpoint-1000/: 包含了训练过程中第1000个checkpoint的模型权重和优化器状态。在训练过程中，每隔一段时间就会保存一个checkpoint，以便在训练过程中出现问题时可以从中断的地方恢复训练。
checkpoint-2000/: 包含了训练过程中第2000个checkpoint的模型权重和优化器状态。
checkpoint-3000/: 包含了训练过程中第3000个checkpoint的模型权重和优化器状态。
runs/: 这个文件夹包含了训练过程中TensorBoard日志文件。TensorBoard是一个可视化工具，可以用来监视模型的训练过程。
trainer_state.json: 包含了训练过程中Trainer的状态，包括当前的epoch、step、学习率等信息。
train_results.json: 包含了整个训练过程中训练集的评估结果，包括每个checkpoint的评估结果和最终的评估结果。

```

##### all_results.json内容解释

```

{
    "epoch": 3.77,
    "train_loss": 3.4511859970092775,
    "train_runtime": 8838.9773,
    "train_samples": 114599,
    "train_samples_per_second": 48.874,
    "train_steps_per_second": 0.339
}
epoch: 训练过程中当前的轮数。在这个例子中，当前轮数为3.77。

train_loss: 训练集上的损失值。在这个例子中，训练集上的损失值为3.4511859970092775。

train_runtime: 训练过程中的运行时间。在这个例子中，训练过程中的运行时间为8838.9773秒。

train_samples: 训练集的样本数。在这个例子中，训练集的样本数为114599。

train_samples_per_second: 训练过程中每秒处理的样本数。在这个例子中，训练过程中每秒处理的样本数为48.874个。

train_steps_per_second: 训练过程中每秒执行的训练步骤数。在这个例子中，训练过程中每秒执行的训练步骤数为0.339个。
```

##### checkpoint-X000内容解释

```
这是一个PyTorch模型的训练检查点，包含了训练过程中的各种文件。以下是每个文件的解释：
config.json: 模型的配置信息，包括模型的超参数和结构等。
generation_config.json: 生成对话时使用的配置信息，包括生成对话时的参数等。
optimizer.pt: 训练过程中优化器的状态，包括优化器的参数和动量等。
pytorch_model.bin: 训练过程中保存的模型权重，以二进制格式保存。
rng_state_0.pth ~ rng_state_8.pth: 随机数生成器的状态，用于确保在模型恢复训练时可以重现相同的随机过程。
scheduler.pt: 训练过程中学习率调度器的状态，包括学习率调度器的参数和当前学习率等。
special_tokens_map.json: 特殊词的映射表，用于在对话生成时将特殊词转换为相应的标记。
tokenizer_config.json: 分词器的配置信息，包括分词器的超参数和结构等。
tokenizer.model: 训练过程中保存的分词器模型，以二进制格式保存。
trainer_state.json: Trainer的状态，包括当前的epoch、step、学习率等信息。
training_args.bin: 训练过程中的训练参数，包括batch_size、学习率、训练轮数等。

```

##### tokenizer_config.json内容解释

```
这是一个分词器的配置文件，用于指定分词器的超参数和结构等。以下是每个字段的解释：
auto_map: 自动映射，用于自动将分词器的名称映射到对应的类。在这个例子中，将“AutoTokenizer”映射到“tokenization_chatglm.ChatGLMTokenizer”类。
clean_up_tokenization_spaces: 是否清除分词后的空格。在这个例子中，不清除分词后的空格。
do_lower_case: 是否将文本转换为小写。在这个例子中，不将文本转换为小写。
model_max_length: 分词器的最大输入长度。在这个例子中，分词器的最大输入长度为1000000000000000019884624838656。
padding_side: 填充的位置。在这个例子中，填充在左边。
remove_space: 是否删除空格。在这个例子中，不删除空格。
tokenizer_class: 分词器的类名。在这个例子中，分词器的类名为“ChatGLMTokenizer”。

```