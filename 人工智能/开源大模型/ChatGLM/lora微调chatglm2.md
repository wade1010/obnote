  conda activate chatglm_etuning

![](https://gitee.com/hxc8/images2/raw/master/img/202407172157531.jpg)

accelerate launch src/train_bash.py \    --stage sft \    --model_name_or_path /llm/xhcheng/text-generation-webui/models/THUDM_chatglm2-6b \    --do_train \    --dataset hanbo_cognition \    --finetuning_type lora \    --output_dir path_to_sft_checkpoint \    --per_device_train_batch_size 4 \    --gradient_accumulation_steps 4 \    --lr_scheduler_type cosine \    --logging_steps 10 \    --save_steps 1000 \    --learning_rate 5e-5 \    --num_train_epochs 3.0 \    --fp16    

![](https://gitee.com/hxc8/images2/raw/master/img/202407172157907.jpg)

改为一个

CUDA_VISIBLE_DEVICES=0 python src/train_bash.py \    --stage sft \    --model_name_or_path /llm/xhcheng/text-generation-webui/models/THUDM_chatglm2-6b \    --do_train \    --dataset hanbo_cognition \    --finetuning_type lora \    --output_dir path_to_sft_checkpoint \    --per_device_train_batch_size 4 \    --gradient_accumulation_steps 4 \    --lr_scheduler_type cosine \    --logging_steps 10 \    --save_steps 1000 \    --learning_rate 5e-5 \    --num_train_epochs 3.0 \    --fp16

(chatglm_etuning) cheng@hanbo-SYS-4029GP-TRT2:/llm/xhcheng/ChatGLM2-6B/tuning$ CUDA_VISIBLE_DEVICES=0 python src/train_bash.py     --stage sft     --model_name_or_path /llm/xhcheng/text-generation-webui/models/THUDM_chatglm2-6b     --do_train     --dataset hanbo_cognition     --finetuning_type lora     --output_dir path_to_sft_checkpoint     --per_device_train_batch_size 4     --gradient_accumulation_steps 4     --lr_scheduler_type cosine     --logging_steps 10     --save_steps 1000     --learning_rate 5e-5     --num_train_epochs 3.0     --fp16

[2023-07-27 22:49:04,792] [INFO] [real_accelerator.py:133:get_accelerator] Setting ds_accelerator to cuda (auto detect)

07/27/2023 22:49:05 - WARNING - glmtuner.tuner.core.parser - ddp_find_unused_parameters needs to be set as False in DDP training.

07/27/2023 22:49:05 - INFO - glmtuner.tuner.core.parser - Process rank: 0, device: cuda:0, n_gpu: 1

distributed training: True, 16-bits training: True

07/27/2023 22:49:05 - INFO - glmtuner.tuner.core.parser - Training/evaluation parameters Seq2SeqTrainingArguments(

_n_gpu=1,

adafactor=False,

adam_beta1=0.9,

adam_beta2=0.999,

adam_epsilon=1e-08,

auto_find_batch_size=False,

bf16=False,

bf16_full_eval=False,

data_seed=None,

dataloader_drop_last=False,

dataloader_num_workers=0,

dataloader_pin_memory=True,

ddp_backend=None,

ddp_broadcast_buffers=None,

ddp_bucket_cap_mb=None,

ddp_find_unused_parameters=False,

ddp_timeout=1800,

debug=[],

deepspeed=None,

disable_tqdm=False,

do_eval=False,

do_predict=False,

do_train=True,

eval_accumulation_steps=None,

eval_delay=0,

eval_steps=None,

evaluation_strategy=no,

fp16=True,

fp16_backend=auto,

fp16_full_eval=False,

fp16_opt_level=O1,

fsdp=[],

fsdp_config={'fsdp_min_num_params': 0, 'xla': False, 'xla_fsdp_grad_ckpt': False},

fsdp_min_num_params=0,

fsdp_transformer_layer_cls_to_wrap=None,

full_determinism=False,

generation_config=None,

generation_max_length=None,

generation_num_beams=None,

gradient_accumulation_steps=4,

gradient_checkpointing=False,

greater_is_better=None,

group_by_length=False,

half_precision_backend=auto,

hub_model_id=None,

hub_private_repo=False,

hub_strategy=every_save,hub_token=

,

ignore_data_skip=False,

include_inputs_for_metrics=False,

jit_mode_eval=False,

label_names=None,

label_smoothing_factor=0.0,

learning_rate=5e-05,

length_column_name=length,

load_best_model_at_end=False,

local_rank=0,

log_level=passive,

log_level_replica=warning,

log_on_each_node=True,

logging_dir=path_to_sft_checkpoint/runs/Jul27_22-49-05_hanbo-SYS-4029GP-TRT2,

logging_first_step=False,

logging_nan_inf_filter=True,

logging_steps=10,

logging_strategy=steps,

lr_scheduler_type=cosine,

max_grad_norm=1.0,

max_steps=-1,

metric_for_best_model=None,

mp_parameters=,

no_cuda=False,

num_train_epochs=3.0,

optim=adamw_torch,

optim_args=None,

output_dir=path_to_sft_checkpoint,

overwrite_output_dir=False,

past_index=-1,

per_device_eval_batch_size=8,

per_device_train_batch_size=4,

predict_with_generate=False,

prediction_loss_only=False,

push_to_hub=False,

push_to_hub_model_id=None,

push_to_hub_organization=None,push_to_hub_token=

,

ray_scope=last,

remove_unused_columns=True,

report_to=[],

resume_from_checkpoint=None,

run_name=path_to_sft_checkpoint,

save_on_each_node=False,

save_safetensors=False,

save_steps=1000,

save_strategy=steps,

save_total_limit=None,

seed=42,

sharded_ddp=[],

skip_memory_metrics=True,

sortish_sampler=False,

tf32=None,

torch_compile=False,

torch_compile_backend=None,

torch_compile_mode=None,

torchdynamo=None,

tpu_metrics_debug=False,

tpu_num_cores=None,

use_ipex=False,

use_legacy_prediction_loop=False,

use_mps_device=False,

warmup_ratio=0.0,

warmup_steps=0,

weight_decay=0.0,

xpu_backend=None,

)

07/27/2023 22:49:05 - INFO - glmtuner.dsets.loader - Loading dataset hanbo_cognition.json...

07/27/2023 22:49:05 - WARNING - glmtuner.dsets.loader - Checksum failed for data/hanbo_cognition.json. It may vary depending on the platform.

/home/cheng/miniconda3/envs/chatglm_etuning/lib/python3.10/site-packages/datasets/load.py:2066: FutureWarning: 'use_auth_token' was deprecated in favor of 'token' in version 2.14.0 and will be removed in 3.0.0.

You can remove this warning by passing 'token=None' instead.

warnings.warn(

Using custom data configuration default-62d750feb80b3535

Loading Dataset Infos from /home/cheng/miniconda3/envs/chatglm_etuning/lib/python3.10/site-packages/datasets/packaged_modules/json

Generating dataset json (/home/cheng/.cache/huggingface/datasets/json/default-62d750feb80b3535/0.0.0/8bb11242116d547c741b2e8a1f18598ffdd40a1d4f2a2872c7a28b697434bc96)

Downloading and preparing dataset json/default to /home/cheng/.cache/huggingface/datasets/json/default-62d750feb80b3535/0.0.0/8bb11242116d547c741b2e8a1f18598ffdd40a1d4f2a2872c7a28b697434bc96...

Downloading data files: 100%|█████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████| 1/1 [00:00<00:00, 6442.86it/s]

Downloading took 0.0 min

Checksum Computation took 0.0 min

Extracting data files: 100%|██████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████| 1/1 [00:00<00:00, 2163.13it/s]

Generating train split

Generating train split: 80 examples [00:00, 16722.03 examples/s]

Unable to verify splits sizes.

Dataset json downloaded and prepared to /home/cheng/.cache/huggingface/datasets/json/default-62d750feb80b3535/0.0.0/8bb11242116d547c741b2e8a1f18598ffdd40a1d4f2a2872c7a28b697434bc96. Subsequent calls will reuse this data.

[INFO|tokenization_utils_base.py:1837] 2023-07-27 22:49:09,250 >> loading file tokenizer.model

[INFO|tokenization_utils_base.py:1837] 2023-07-27 22:49:09,250 >> loading file added_tokens.json

[INFO|tokenization_utils_base.py:1837] 2023-07-27 22:49:09,250 >> loading file special_tokens_map.json

[INFO|tokenization_utils_base.py:1837] 2023-07-27 22:49:09,250 >> loading file tokenizer_config.json

[INFO|configuration_utils.py:710] 2023-07-27 22:49:09,276 >> loading configuration file /llm/xhcheng/text-generation-webui/models/THUDM_chatglm2-6b/config.json

[INFO|configuration_utils.py:710] 2023-07-27 22:49:09,276 >> loading configuration file /llm/xhcheng/text-generation-webui/models/THUDM_chatglm2-6b/config.json

[INFO|configuration_utils.py:768] 2023-07-27 22:49:09,277 >> Model config ChatGLMConfig {

"_name_or_path": "/llm/xhcheng/text-generation-webui/models/THUDM_chatglm2-6b",

"add_bias_linear": false,

"add_qkv_bias": true,

"apply_query_key_layer_scaling": true,

"apply_residual_connection_post_layernorm": false,

"architectures": [

"ChatGLMModel"

],

"attention_dropout": 0.0,

"attention_softmax_in_fp32": true,

"auto_map": {

"AutoConfig": "configuration_chatglm.ChatGLMConfig",

"AutoModel": "modeling_chatglm.ChatGLMForConditionalGeneration",

"AutoModelForCausalLM": "modeling_chatglm.ChatGLMForConditionalGeneration",

"AutoModelForSeq2SeqLM": "modeling_chatglm.ChatGLMForConditionalGeneration"

},

"bias_dropout_fusion": true,

"eos_token_id": 2,

"ffn_hidden_size": 13696,

"fp32_residual_connection": false,

"hidden_dropout": 0.0,

"hidden_size": 4096,

"kv_channels": 128,

"layernorm_epsilon": 1e-05,

"model_type": "chatglm",

"multi_query_attention": true,

"multi_query_group_num": 2,

"num_attention_heads": 32,

"num_layers": 28,

"original_rope": true,

"pad_token_id": 0,

"padded_vocab_size": 65024,

"post_layer_norm": true,

"pre_seq_len": null,

"prefix_projection": false,

"quantization_bit": 0,

"rmsnorm": true,

"seq_length": 32768,

"tie_word_embeddings": false,

"torch_dtype": "float16",

"transformers_version": "4.31.0",

"use_cache": true,

"vocab_size": 65024

}

[INFO|modeling_utils.py:2600] 2023-07-27 22:49:09,426 >> loading weights file /llm/xhcheng/text-generation-webui/models/THUDM_chatglm2-6b/pytorch_model.bin.index.json

[INFO|configuration_utils.py:599] 2023-07-27 22:49:09,427 >> Generate config GenerationConfig {

"_from_model_config": true,

"eos_token_id": 2,

"pad_token_id": 0,

"transformers_version": "4.31.0"

}

Loading checkpoint shards: 100%|████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████| 7/7 [00:07<00:00,  1.10s/it]

[INFO|modeling_utils.py:3329] 2023-07-27 22:49:17,201 >> All model checkpoint weights were used when initializing ChatGLMForConditionalGeneration.

[INFO|modeling_utils.py:3337] 2023-07-27 22:49:17,202 >> All the weights of ChatGLMForConditionalGeneration were initialized from the model checkpoint at /llm/xhcheng/text-generation-webui/models/THUDM_chatglm2-6b.

If your task is similar to the task the model of the checkpoint was trained on, you can already use ChatGLMForConditionalGeneration for predictions without further training.

[INFO|modeling_utils.py:2949] 2023-07-27 22:49:17,203 >> Generation config file not found, using a generation config created from the model config.

07/27/2023 22:49:17 - INFO - glmtuner.tuner.core.adapter - Fine-tuning method: LoRA

trainable params: 1949696 || all params: 6245533696 || trainable%: 0.0312Running tokenizer on dataset:   0%|                                                                                                                                                                                                                             | 0/80 [00:00

Running tokenizer on dataset: 100%|██████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████| 80/80 [00:00<00:00, 3854.39 examples/s]

input_ids:

[64790, 64792, 790, 30951, 517, 30910, 30939, 30996, 13, 13, 54761, 31211, 39701, 13, 13, 55437, 31211, 30910, 48214, 31123, 33030, 56605, 55121, 31123, 31623, 54781, 55313, 56728, 31748, 43809, 11265, 30910, 42481, 31123, 48895, 32254, 55353, 31155, 55073, 39905, 54558, 38549, 54725, 42391, 31514, 2]

inputs:

[Round 1]

问：你好

答： 您好，我是庙，一个由科技开发的 AI 助手，很高兴认识您。请问我能为您做些什么？

label_ids:

[-100, -100, -100, -100, -100, -100, -100, -100, -100, -100, -100, -100, -100, -100, -100, -100, -100, 30910, 48214, 31123, 33030, 56605, 55121, 31123, 31623, 54781, 55313, 56728, 31748, 43809, 11265, 30910, 42481, 31123, 48895, 32254, 55353, 31155, 55073, 39905, 54558, 38549, 54725, 42391, 31514, 2]

labels:

您好，我是庙算，一个由HB科技开发的 AI 助手，很高兴认识您。请问我能为您做些什么？

[INFO|trainer.py:1686] 2023-07-27 22:49:27,603 >> ***** Running training *****

[INFO|trainer.py:1687] 2023-07-27 22:49:27,603 >>   Num examples = 80

[INFO|trainer.py:1688] 2023-07-27 22:49:27,603 >>   Num Epochs = 3

[INFO|trainer.py:1689] 2023-07-27 22:49:27,603 >>   Instantaneous batch size per device = 4

[INFO|trainer.py:1692] 2023-07-27 22:49:27,603 >>   Total train batch size (w. parallel, distributed & accumulation) = 16

[INFO|trainer.py:1693] 2023-07-27 22:49:27,603 >>   Gradient Accumulation steps = 4

[INFO|trainer.py:1694] 2023-07-27 22:49:27,603 >>   Total optimization steps = 15

[INFO|trainer.py:1695] 2023-07-27 22:49:27,604 >>   Number of trainable parameters = 1,949,696

{'loss': 5.0057, 'learning_rate': 1.2500000000000006e-05, 'epoch': 2.0}

100%|█████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████| 15/15 [00:13<00:00,  1.29it/s][INFO|trainer.py:1934] 2023-07-27 22:49:40,930 >>

Training completed. Do not forget to share your model on huggingface.co/models =)

{'train_runtime': 13.3262, 'train_samples_per_second': 18.01, 'train_steps_per_second': 1.126, 'train_loss': 4.95634765625, 'epoch': 3.0}

100%|█████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████| 15/15 [00:13<00:00,  1.13it/s]

***** train metrics *****

epoch                    =        3.0

train_loss               =     4.9563

train_runtime            = 0:00:13.32

train_samples_per_second =      18.01

train_steps_per_second   =      1.126

07/27/2023 22:49:40 - INFO - glmtuner.tuner.core.trainer - Saving model checkpoint to path_to_sft_checkpoint    

![](https://gitee.com/hxc8/images2/raw/master/img/202407172157579.jpg)

python src/web_demo.py \    --model_name_or_path /llm/xhcheng/text-generation-webui/models/THUDM_chatglm2-6b \    --finetuning_type lora \    --checkpoint_dir /llm/xhcheng/ChatGLM2-6B/tuning/examples/path_to_sft_checkpoint

[https://blog.csdn.net/feifeiyechuan/article/details/131458322](https://blog.csdn.net/feifeiyechuan/article/details/131458322)

```
CUDA_VISIBLE_DEVICES=0 python src/train_bash.py     --stage sft     --model_name_or_path /llm/xhcheng/text-generation-webui/models/THUDM_chatglm2-6b     --do_train     --dataset hanbo_cognition     --finetuning_type lora     --output_dir path_to_sft_checkpoint     --per_device_train_batch_size 2     --gradient_accumulation_steps 2     --lr_scheduler_type cosine     --logging_steps 10     --save_steps 1000     --learning_rate 1e-3     --num_train_epochs 10.0     --fp16
```

```
CUDA_VISIBLE_DEVICES=0 python src/cli_demo.py \
--model_name_or_path /llm/xhcheng/text-generation-webui/models/THUDM_chatglm2-6b \
    --checkpoint_dir path_to_sft_checkpoint
```

成功，激动啊。

后来增加语料，大概1000多条，方向上面单GPU跑要1个多小时

于是想改成多GPU，按照文档里面的提示，报错

可能是accelerate config配置错误

![](https://gitee.com/hxc8/images2/raw/master/img/202407172157188.jpg)

最后把训练数据重复的去掉，大概100条。挺快的

![](https://gitee.com/hxc8/images2/raw/master/img/202407172157258.jpg)

### 浏览器测试

```
CUDA_VISIBLE_DEVICES=1,2,3 python src/web_demo.py \
    --model_name_or_path /llm/xhcheng/text-generation-webui/models/THUDM_chatglm2-6b \
     --finetuning_type lora \
    --checkpoint_dir path_to_sft_checkpoint
```

```
python src/export_model.py \
    --checkpoint_dir path_to_sft_checkpoint \
    --model_name_or_path /llm/xhcheng/text-generation-webui/models/THUDM_chatglm2-6b \
    --output_dir path_to_save_model
    
    

    python src/web_demo.py \
    --model_name_or_path path_to_save_model \
    --finetuning_type lora
```

[http://192.168.100.13:7860/](http://192.168.100.13:7860/)

![](https://gitee.com/hxc8/images2/raw/master/img/202407172157088.jpg)

![](https://gitee.com/hxc8/images2/raw/master/img/202407172157153.jpg)

![](https://gitee.com/hxc8/images2/raw/master/img/202407172157399.jpg)