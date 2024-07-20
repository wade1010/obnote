[https://www.heywhale.com/mw/project/64984a7b72ebe240516ae79c](https://www.heywhale.com/mw/project/64984a7b72ebe240516ae79c)

微调训练方法：

[https://www.cnblogs.com/ting1/p/17558779.html](https://www.cnblogs.com/ting1/p/17558779.html)

把广告的训练数据放到ptuning目录下

修改了train.sh

```
PRE_SEQ_LEN=128
LR=2e-2
NUM_GPUS=8

CUDA_VISIBLE_DEVICES=2,3,4,5,6,7,8,9 torchrun --standalone --nnodes=1 --nproc-per-node=$NUM_GPUS main.py \
    --do_train \
    --train_file AdvertiseGen/train.json \
    --validation_file AdvertiseGen/dev.json \
    --preprocessing_num_workers 10 \
    --prompt_column content \
    --response_column summary \
    --overwrite_cache \
    --model_name_or_path /llm/xhcheng/text-generation-webui/models/THUDM_chatglm2-6b \
    --output_dir output/adgen-chatglm2-6b-pt-$PRE_SEQ_LEN-$LR \
    --overwrite_output_dir \
    --max_source_length 64 \
    --max_target_length 128 \
    --per_device_train_batch_size 8 \
    --per_device_eval_batch_size 8 \
    --gradient_accumulation_steps 2 \
    --predict_with_generate \
    --max_steps 3000 \
    --logging_steps 10 \
    --save_steps 1000 \
    --learning_rate $LR \
    --pre_seq_len $PRE_SEQ_LEN


```

time bash train.sh

![](https://gitee.com/hxc8/images2/raw/master/img/202407172157357.jpg)

我选了3

```
    --quantization_bit 16 或者32
```

报错如下

![](https://gitee.com/hxc8/images2/raw/master/img/202407172157068.jpg)

如果去掉    --quantization_bit

也会报错

```
RuntimeError: CUDA error: CUBLAS_STATUS_NOT_INITIALIZED when calling `cublasCreate(handle)`
```

最终采取     --quantization_bit 8

训练的时候GPU如下

![](https://gitee.com/hxc8/images2/raw/master/img/202407172157371.jpg)

![](https://gitee.com/hxc8/images2/raw/master/img/202407172158897.jpg)

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

这些字段是训练过程中的指标，它们提供了有关模型性能和训练进度的信息。下面是对这三个字段的解释：

1. loss

：即损失值，是模型在训练过程中优化的目标函数。损失值越小，表示模型对训练数据的拟合越好。

1. learning_rate

：即学习率，是控制模型参数更新步长的超参数。学习率越高，模型参数更新的步长就越大，训练速度也会更快。但如果学习率设置得过高，可能会导致模型无法收敛或者震荡不定。反之，学习率设置得过低，则训练速度会很慢，模型也可能无法收敛到最优解。

1. epoch

：即训练轮数，是指模型在训练数据集上进行一次完整的训练所需要的迭代次数。一个 epoch 包含多个迭代步骤，每个迭代步骤会对 batch 数据进行一次前向传播和反向传播，并更新模型参数。训练轮数越多，模型训练得越充分，但同时也可能会导致过拟合。

这些指标可以帮助我们监控模型的训练进度和性能，并根据需要进行调整，以优化模型的表现。在训练过程中，可以关注损失值是否下降，学习率是否过高或过低，以及训练轮数是否足够充分等信息

![](https://gitee.com/hxc8/images2/raw/master/img/202407172158410.jpg)

wandb: Run wandb online or set WANDB_MODE=online to enable cloud syncing.

0%|                                                                                                                                                                      | 0/3000 [00:00<?, ?it/s]07/27/2023 13:15:15 - WARNING - transformers_modules.THUDM_chatglm2-6b.modeling_chatglm - use_cache=True is incompatible with gradient checkpointing. Setting use_cache=False...

07/27/2023 13:15:15 - WARNING - transformers_modules.THUDM_chatglm2-6b.modeling_chatglm - use_cache=True is incompatible with gradient checkpointing. Setting use_cache=False...

07/27/2023 13:15:15 - WARNING - transformers_modules.THUDM_chatglm2-6b.modeling_chatglm - use_cache=True is incompatible with gradient checkpointing. Setting use_cache=False...

07/27/2023 13:15:15 - WARNING - transformers_modules.THUDM_chatglm2-6b.modeling_chatglm - use_cache=True is incompatible with gradient checkpointing. Setting use_cache=False...

07/27/2023 13:15:15 - WARNING - transformers_modules.THUDM_chatglm2-6b.modeling_chatglm - use_cache=True is incompatible with gradient checkpointing. Setting use_cache=False...

07/27/2023 13:15:15 - WARNING - transformers_modules.THUDM_chatglm2-6b.modeling_chatglm - use_cache=True is incompatible with gradient checkpointing. Setting use_cache=False...

07/27/2023 13:15:15 - WARNING - transformers_modules.THUDM_chatglm2-6b.modeling_chatglm - use_cache=True is incompatible with gradient checkpointing. Setting use_cache=False...

07/27/2023 13:15:15 - WARNING - transformers_modules.THUDM_chatglm2-6b.modeling_chatglm - use_cache=True is incompatible with gradient checkpointing. Setting use_cache=False...

07/27/2023 13:15:15 - WARNING - transformers_modules.THUDM_chatglm2-6b.modeling_chatglm - use_cache=True is incompatible with gradient checkpointing. Setting use_cache=False...

{'loss': 4.5846, 'learning_rate': 0.019933333333333334, 'epoch': 0.01}

0%|▋                                                                                                                                                          | 13/3000 [00:38<2:20:31,  2.82s/it]{'loss': 3.9428, 'learning_rate': 0.019866666666666668, 'epoch': 0.03}

1%|█▏                                                                                                                                                         | 22/3000 [01:03<2:21:09,  2.84s/it]{'loss': 3.9314, 'learning_rate': 0.0198, 'epoch': 0.04}

{'loss': 3.9013, 'learning_rate': 0.019733333333333335, 'epoch': 0.05}

{'loss': 3.8242, 'learning_rate': 0.019666666666666666, 'epoch': 0.06}

{'loss': 3.7747, 'learning_rate': 0.0196, 'epoch': 0.08}

{'loss': 3.6887, 'learning_rate': 0.019533333333333333, 'epoch': 0.09}

{'loss': 3.688, 'learning_rate': 0.019466666666666667, 'epoch': 0.1}

{'loss': 3.6279, 'learning_rate': 0.0194, 'epoch': 0.11}

{'loss': 3.5792, 'learning_rate': 0.019333333333333334, 'epoch': 0.13}

{'loss': 3.5835, 'learning_rate': 0.019266666666666668, 'epoch': 0.14}

{'loss': 3.5703, 'learning_rate': 0.0192, 'epoch': 0.15}

{'loss': 3.5459, 'learning_rate': 0.019133333333333332, 'epoch': 0.16}

{'loss': 3.5245, 'learning_rate': 0.01906666666666667, 'epoch': 0.18}

{'loss': 3.4965, 'learning_rate': 0.019, 'epoch': 0.19}

{'loss': 3.5281, 'learning_rate': 0.018933333333333333, 'epoch': 0.2}

{'loss': 3.4884, 'learning_rate': 0.018866666666666667, 'epoch': 0.21}

{'loss': 3.514, 'learning_rate': 0.0188, 'epoch': 0.23}

{'loss': 3.4806, 'learning_rate': 0.018733333333333334, 'epoch': 0.24}

{'loss': 3.4764, 'learning_rate': 0.018666666666666668, 'epoch': 0.25}

{'loss': 3.4517, 'learning_rate': 0.018600000000000002, 'epoch': 0.26}

{'loss': 3.4709, 'learning_rate': 0.018533333333333332, 'epoch': 0.28}

{'loss': 3.4394, 'learning_rate': 0.018466666666666666, 'epoch': 0.29}

{'loss': 3.4372, 'learning_rate': 0.0184, 'epoch': 0.3}

{'loss': 3.4446, 'learning_rate': 0.018333333333333333, 'epoch': 0.31}

{'loss': 3.4203, 'learning_rate': 0.018266666666666667, 'epoch': 0.33}

{'loss': 3.4276, 'learning_rate': 0.0182, 'epoch': 0.34}

{'loss': 3.4181, 'learning_rate': 0.01813333333333333, 'epoch': 0.35}

{'loss': 3.419, 'learning_rate': 0.01806666666666667, 'epoch': 0.36}

{'loss': 3.4062, 'learning_rate': 0.018000000000000002, 'epoch': 0.38}

{'loss': 3.4001, 'learning_rate': 0.017933333333333332, 'epoch': 0.39}

{'loss': 3.3981, 'learning_rate': 0.017866666666666666, 'epoch': 0.4}

{'loss': 3.3922, 'learning_rate': 0.0178, 'epoch': 0.41}

{'loss': 3.3609, 'learning_rate': 0.017733333333333334, 'epoch': 0.43}

{'loss': 3.3866, 'learning_rate': 0.017666666666666667, 'epoch': 0.44}

{'loss': 3.3642, 'learning_rate': 0.0176, 'epoch': 0.45}

{'loss': 3.3719, 'learning_rate': 0.017533333333333335, 'epoch': 0.46}

{'loss': 3.3801, 'learning_rate': 0.017466666666666665, 'epoch': 0.48}

{'loss': 3.3624, 'learning_rate': 0.0174, 'epoch': 0.49}

{'loss': 3.3609, 'learning_rate': 0.017333333333333336, 'epoch': 0.5}

{'loss': 3.3546, 'learning_rate': 0.017266666666666666, 'epoch': 0.52}

{'loss': 3.378, 'learning_rate': 0.0172, 'epoch': 0.53}

{'loss': 3.3594, 'learning_rate': 0.017133333333333334, 'epoch': 0.54}

{'loss': 3.3597, 'learning_rate': 0.017066666666666667, 'epoch': 0.55}

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

{'loss': 3.3688, 'learning_rate': 0.016066666666666667, 'epoch': 0.74}

{'loss': 3.343, 'learning_rate': 0.016, 'epoch': 0.75}

{'loss': 3.3355, 'learning_rate': 0.015933333333333334, 'epoch': 0.77}

{'loss': 3.3563, 'learning_rate': 0.015866666666666668, 'epoch': 0.78}

{'loss': 3.3271, 'learning_rate': 0.0158, 'epoch': 0.79}

{'loss': 3.3419, 'learning_rate': 0.015733333333333332, 'epoch': 0.8}

{'loss': 3.3495, 'learning_rate': 0.015666666666666666, 'epoch': 0.82}

{'loss': 3.3596, 'learning_rate': 0.015600000000000001, 'epoch': 0.83}

{'loss': 3.3294, 'learning_rate': 0.015533333333333333, 'epoch': 0.84}

{'loss': 3.3412, 'learning_rate': 0.015466666666666667, 'epoch': 0.85}

{'loss': 3.3331, 'learning_rate': 0.0154, 'epoch': 0.87}

{'loss': 3.331, 'learning_rate': 0.015333333333333334, 'epoch': 0.88}

{'loss': 3.3377, 'learning_rate': 0.015266666666666666, 'epoch': 0.89}

{'loss': 3.3535, 'learning_rate': 0.0152, 'epoch': 0.9}

{'loss': 3.33, 'learning_rate': 0.015133333333333334, 'epoch': 0.92}

{'loss': 3.3658, 'learning_rate': 0.015066666666666666, 'epoch': 0.93}

{'loss': 3.3305, 'learning_rate': 0.015, 'epoch': 0.94}

{'loss': 3.3166, 'learning_rate': 0.014933333333333335, 'epoch': 0.95}

{'loss': 3.3485, 'learning_rate': 0.014866666666666667, 'epoch': 0.97}

{'loss': 3.3122, 'learning_rate': 0.0148, 'epoch': 0.98}

{'loss': 3.3331, 'learning_rate': 0.014733333333333334, 'epoch': 0.99}

{'loss': 3.3207, 'learning_rate': 0.014666666666666666, 'epoch': 1.01}

{'loss': 3.2978, 'learning_rate': 0.0146, 'epoch': 1.02}

{'loss': 3.3048, 'learning_rate': 0.014533333333333334, 'epoch': 1.03}

28%|██████████████████████████████████████████▍                                                                                                               | 826/3000 [40:36<1:47:05,  2.96s/it]

{'loss': 3.3156, 'learning_rate': 0.014466666666666668, 'epoch': 1.04}

{'loss': 3.2976, 'learning_rate': 0.0144, 'epoch': 1.06}

{'loss': 3.3352, 'learning_rate': 0.014333333333333333, 'epoch': 1.07}

{'loss': 3.3071, 'learning_rate': 0.014266666666666667, 'epoch': 1.08}

{'loss': 3.3081, 'learning_rate': 0.014199999999999999, 'epoch': 1.09}

{'loss': 3.3165, 'learning_rate': 0.014133333333333333, 'epoch': 1.11}

{'loss': 3.3363, 'learning_rate': 0.014066666666666668, 'epoch': 1.12}

{'loss': 3.3336, 'learning_rate': 0.013999999999999999, 'epoch': 1.13}

{'loss': 3.3204, 'learning_rate': 0.013933333333333334, 'epoch': 1.14}

{'loss': 3.3281, 'learning_rate': 0.013866666666666668, 'epoch': 1.16}

{'loss': 3.3466, 'learning_rate': 0.0138, 'epoch': 1.17}

{'loss': 3.3162, 'learning_rate': 0.013733333333333334, 'epoch': 1.18}

{'loss': 3.3357, 'learning_rate': 0.013666666666666667, 'epoch': 1.19}

{'loss': 3.3341, 'learning_rate': 0.013600000000000001, 'epoch': 1.21}

{'loss': 3.3463, 'learning_rate': 0.013533333333333333, 'epoch': 1.22}

{'loss': 3.325, 'learning_rate': 0.013466666666666667, 'epoch': 1.23}

{'loss': 3.3281, 'learning_rate': 0.0134, 'epoch': 1.24}

{'loss': 3.3185, 'learning_rate': 0.013333333333333332, 'epoch': 1.26}

33%|███████████████████████████████████████████████████                                                                                                      | 1000/3000 [49:10<1:38:34,  2.96s/it]Saving PrefixEncoder

[INFO|configuration_utils.py:458] 2023-07-27 14:04:25,906 >> Configuration saved in output/adgen-chatglm2-6b-pt-128-2e-2/checkpoint-1000/config.json

[INFO|configuration_utils.py:364] 2023-07-27 14:04:25,907 >> Configuration saved in output/adgen-chatglm2-6b-pt-128-2e-2/checkpoint-1000/generation_config.json

[INFO|modeling_utils.py:1853] 2023-07-27 14:04:25,917 >> Model weights saved in output/adgen-chatglm2-6b-pt-128-2e-2/checkpoint-1000/pytorch_model.bin

[INFO|tokenization_utils_base.py:2194] 2023-07-27 14:04:25,917 >> tokenizer config file saved in output/adgen-chatglm2-6b-pt-128-2e-2/checkpoint-1000/tokenizer_config.json

[INFO|tokenization_utils_base.py:2201] 2023-07-27 14:04:25,918 >> Special tokens file saved in output/adgen-chatglm2-6b-pt-128-2e-2/checkpoint-1000/special_tokens_map.json

{'loss': 3.3397, 'learning_rate': 0.013266666666666666, 'epoch': 1.27}

{'loss': 3.3285, 'learning_rate': 0.013200000000000002, 'epoch': 1.28}

{'loss': 3.3036, 'learning_rate': 0.013133333333333332, 'epoch': 1.29}

{'loss': 3.3077, 'learning_rate': 0.013066666666666667, 'epoch': 1.31}

{'loss': 3.3312, 'learning_rate': 0.013000000000000001, 'epoch': 1.32}

{'loss': 3.3336, 'learning_rate': 0.012933333333333333, 'epoch': 1.33}

{'loss': 3.3352, 'learning_rate': 0.012866666666666667, 'epoch': 1.34}

{'loss': 3.3376, 'learning_rate': 0.0128, 'epoch': 1.36}

{'loss': 3.3474, 'learning_rate': 0.012733333333333334, 'epoch': 1.37}

{'loss': 3.3218, 'learning_rate': 0.012666666666666666, 'epoch': 1.38}

{'loss': 3.3184, 'learning_rate': 0.0126, 'epoch': 1.39}

{'loss': 3.3296, 'learning_rate': 0.012533333333333334, 'epoch': 1.41}

{'loss': 3.3477, 'learning_rate': 0.012466666666666666, 'epoch': 1.42}

{'loss': 3.3367, 'learning_rate': 0.0124, 'epoch': 1.43}

{'loss': 3.3273, 'learning_rate': 0.012333333333333335, 'epoch': 1.44}

{'loss': 3.347, 'learning_rate': 0.012266666666666665, 'epoch': 1.46}

{'loss': 3.3587, 'learning_rate': 0.0122, 'epoch': 1.47}

{'loss': 3.3546, 'learning_rate': 0.012133333333333335, 'epoch': 1.48}

{'loss': 3.3178, 'learning_rate': 0.012066666666666668, 'epoch': 1.49}

{'loss': 3.3447, 'learning_rate': 0.012, 'epoch': 1.51}

{'loss': 3.3485, 'learning_rate': 0.011933333333333334, 'epoch': 1.52}

{'loss': 3.3544, 'learning_rate': 0.011866666666666668, 'epoch': 1.53}

{'loss': 3.3529, 'learning_rate': 0.0118, 'epoch': 1.55}

{'loss': 3.3604, 'learning_rate': 0.011733333333333333, 'epoch': 1.56}

{'loss': 3.3296, 'learning_rate': 0.011666666666666667, 'epoch': 1.57}

{'loss': 3.3364, 'learning_rate': 0.0116, 'epoch': 1.58}

{'loss': 3.3509, 'learning_rate': 0.011533333333333333, 'epoch': 1.6}

{'loss': 3.3604, 'learning_rate': 0.011466666666666667, 'epoch': 1.61}

{'loss': 3.3585, 'learning_rate': 0.011399999999999999, 'epoch': 1.62}

{'loss': 3.3616, 'learning_rate': 0.011333333333333332, 'epoch': 1.63}

{'loss': 3.3345, 'learning_rate': 0.011266666666666668, 'epoch': 1.65}

{'loss': 3.362, 'learning_rate': 0.011200000000000002, 'epoch': 1.66}

{'loss': 3.3808, 'learning_rate': 0.011133333333333334, 'epoch': 1.67}

{'loss': 3.3555, 'learning_rate': 0.011066666666666667, 'epoch': 1.68}

{'loss': 3.3891, 'learning_rate': 0.011000000000000001, 'epoch': 1.7}

{'loss': 3.3597, 'learning_rate': 0.010933333333333333, 'epoch': 1.71}

{'loss': 3.3735, 'learning_rate': 0.010866666666666667, 'epoch': 1.72}

{'loss': 3.3661, 'learning_rate': 0.0108, 'epoch': 1.73}

{'loss': 3.3679, 'learning_rate': 0.010733333333333333, 'epoch': 1.75}

{'loss': 3.3779, 'learning_rate': 0.010666666666666666, 'epoch': 1.76}

{'loss': 3.3795, 'learning_rate': 0.0106, 'epoch': 1.77}

{'loss': 3.3707, 'learning_rate': 0.010533333333333332, 'epoch': 1.78}

{'loss': 3.3493, 'learning_rate': 0.010466666666666666, 'epoch': 1.8}

{'loss': 3.3794, 'learning_rate': 0.010400000000000001, 'epoch': 1.81}

{'loss': 3.3819, 'learning_rate': 0.010333333333333335, 'epoch': 1.82}

{'loss': 3.378, 'learning_rate': 0.010266666666666667, 'epoch': 1.83}

{'loss': 3.3967, 'learning_rate': 0.0102, 'epoch': 1.85}

{'loss': 3.3628, 'learning_rate': 0.010133333333333334, 'epoch': 1.86}

{'loss': 3.4027, 'learning_rate': 0.010066666666666666, 'epoch': 1.87}

{'loss': 3.3844, 'learning_rate': 0.01, 'epoch': 1.88}

{'loss': 3.3797, 'learning_rate': 0.009933333333333334, 'epoch': 1.9}

{'loss': 3.3865, 'learning_rate': 0.009866666666666668, 'epoch': 1.91}

{'loss': 3.4027, 'learning_rate': 0.0098, 'epoch': 1.92}

{'loss': 3.3762, 'learning_rate': 0.009733333333333333, 'epoch': 1.93}

{'loss': 3.4342, 'learning_rate': 0.009666666666666667, 'epoch': 1.95}

{'loss': 3.4089, 'learning_rate': 0.0096, 'epoch': 1.96}

{'loss': 3.3996, 'learning_rate': 0.009533333333333335, 'epoch': 1.97}

{'loss': 3.4058, 'learning_rate': 0.009466666666666667, 'epoch': 1.98}

{'loss': 3.3899, 'learning_rate': 0.0094, 'epoch': 2.0}

{'loss': 3.3842, 'learning_rate': 0.009333333333333334, 'epoch': 2.01}

{'loss': 3.4094, 'learning_rate': 0.009266666666666666, 'epoch': 2.02}

{'loss': 3.3977, 'learning_rate': 0.0092, 'epoch': 2.04}

{'loss': 3.4053, 'learning_rate': 0.009133333333333334, 'epoch': 2.05}

{'loss': 3.4313, 'learning_rate': 0.009066666666666666, 'epoch': 2.06}

{'loss': 3.3991, 'learning_rate': 0.009000000000000001, 'epoch': 2.07}

{'loss': 3.4014, 'learning_rate': 0.008933333333333333, 'epoch': 2.09}

{'loss': 3.417, 'learning_rate': 0.008866666666666667, 'epoch': 2.1}

{'loss': 3.4073, 'learning_rate': 0.0088, 'epoch': 2.11}

{'loss': 3.412, 'learning_rate': 0.008733333333333333, 'epoch': 2.12}

{'loss': 3.4067, 'learning_rate': 0.008666666666666668, 'epoch': 2.14}

{'loss': 3.4136, 'learning_rate': 0.0086, 'epoch': 2.15}

{'loss': 3.4261, 'learning_rate': 0.008533333333333334, 'epoch': 2.16}

{'loss': 3.4263, 'learning_rate': 0.008466666666666667, 'epoch': 2.17}

{'loss': 3.4258, 'learning_rate': 0.0084, 'epoch': 2.19}

{'loss': 3.4338, 'learning_rate': 0.008333333333333333, 'epoch': 2.2}

{'loss': 3.4376, 'learning_rate': 0.008266666666666667, 'epoch': 2.21}

{'loss': 3.4244, 'learning_rate': 0.008199999999999999, 'epoch': 2.22}

{'loss': 3.4102, 'learning_rate': 0.008133333333333334, 'epoch': 2.24}

{'loss': 3.4493, 'learning_rate': 0.008066666666666666, 'epoch': 2.25}

{'loss': 3.4337, 'learning_rate': 0.008, 'epoch': 2.26}

{'loss': 3.4253, 'learning_rate': 0.007933333333333334, 'epoch': 2.27}

{'loss': 3.4452, 'learning_rate': 0.007866666666666666, 'epoch': 2.29}

{'loss': 3.454, 'learning_rate': 0.0078000000000000005, 'epoch': 2.3}

{'loss': 3.4335, 'learning_rate': 0.007733333333333333, 'epoch': 2.31}

{'loss': 3.4321, 'learning_rate': 0.007666666666666667, 'epoch': 2.32}

{'loss': 3.4221, 'learning_rate': 0.0076, 'epoch': 2.34}

{'loss': 3.4278, 'learning_rate': 0.007533333333333333, 'epoch': 2.35}

{'loss': 3.4438, 'learning_rate': 0.0074666666666666675, 'epoch': 2.36}

{'loss': 3.4639, 'learning_rate': 0.0074, 'epoch': 2.37}

{'loss': 3.4376, 'learning_rate': 0.007333333333333333, 'epoch': 2.39}

{'loss': 3.4498, 'learning_rate': 0.007266666666666667, 'epoch': 2.4}

{'loss': 3.4434, 'learning_rate': 0.0072, 'epoch': 2.41}

{'loss': 3.428, 'learning_rate': 0.0071333333333333335, 'epoch': 2.42}

{'loss': 3.4439, 'learning_rate': 0.007066666666666666, 'epoch': 2.44}

{'loss': 3.4441, 'learning_rate': 0.006999999999999999, 'epoch': 2.45}

{'loss': 3.4581, 'learning_rate': 0.006933333333333334, 'epoch': 2.46}

{'loss': 3.4617, 'learning_rate': 0.006866666666666667, 'epoch': 2.47}

{'loss': 3.4492, 'learning_rate': 0.0068000000000000005, 'epoch': 2.49}

{'loss': 3.4656, 'learning_rate': 0.006733333333333333, 'epoch': 2.5}

{'loss': 3.4767, 'learning_rate': 0.006666666666666666, 'epoch': 2.51}

67%|██████████████████████████████████████████████████████████████████████████████████████████████████████                                                   | 2000/3000 [1:38:02<49:08,  2.95s/it]Saving PrefixEncoder

[INFO|configuration_utils.py:458] 2023-07-27 14:53:17,264 >> Configuration saved in output/adgen-chatglm2-6b-pt-128-2e-2/checkpoint-2000/config.json

[INFO|configuration_utils.py:364] 2023-07-27 14:53:17,264 >> Configuration saved in output/adgen-chatglm2-6b-pt-128-2e-2/checkpoint-2000/generation_config.json

[INFO|modeling_utils.py:1853] 2023-07-27 14:53:17,274 >> Model weights saved in output/adgen-chatglm2-6b-pt-128-2e-2/checkpoint-2000/pytorch_model.bin

[INFO|tokenization_utils_base.py:2194] 2023-07-27 14:53:17,274 >> tokenizer config file saved in output/adgen-chatglm2-6b-pt-128-2e-2/checkpoint-2000/tokenizer_config.json

[INFO|tokenization_utils_base.py:2201] 2023-07-27 14:53:17,274 >> Special tokens file saved in output/adgen-chatglm2-6b-pt-128-2e-2/checkpoint-2000/special_tokens_map.json

{'loss': 3.47, 'learning_rate': 0.006600000000000001, 'epoch': 2.53}

{'loss': 3.4867, 'learning_rate': 0.006533333333333334, 'epoch': 2.54}

{'loss': 3.4932, 'learning_rate': 0.006466666666666667, 'epoch': 2.55}

{'loss': 3.4627, 'learning_rate': 0.0064, 'epoch': 2.56}

{'loss': 3.4604, 'learning_rate': 0.006333333333333333, 'epoch': 2.58}

{'loss': 3.4759, 'learning_rate': 0.006266666666666667, 'epoch': 2.59}

{'loss': 3.4683, 'learning_rate': 0.0062, 'epoch': 2.6}

{'loss': 3.4853, 'learning_rate': 0.006133333333333333, 'epoch': 2.61}

{'loss': 3.4665, 'learning_rate': 0.006066666666666667, 'epoch': 2.63}

{'loss': 3.4857, 'learning_rate': 0.006, 'epoch': 2.64}

{'loss': 3.4865, 'learning_rate': 0.005933333333333334, 'epoch': 2.65}

{'loss': 3.4573, 'learning_rate': 0.005866666666666667, 'epoch': 2.66}

{'loss': 3.4798, 'learning_rate': 0.0058, 'epoch': 2.68}

{'loss': 3.4896, 'learning_rate': 0.005733333333333333, 'epoch': 2.69}

{'loss': 3.4708, 'learning_rate': 0.005666666666666666, 'epoch': 2.7}

{'loss': 3.4925, 'learning_rate': 0.005600000000000001, 'epoch': 2.71}

{'loss': 3.4909, 'learning_rate': 0.005533333333333334, 'epoch': 2.73}

{'loss': 3.4879, 'learning_rate': 0.0054666666666666665, 'epoch': 2.74}

{'loss': 3.4984, 'learning_rate': 0.0054, 'epoch': 2.75}

{'loss': 3.449, 'learning_rate': 0.005333333333333333, 'epoch': 2.76}

{'loss': 3.533, 'learning_rate': 0.005266666666666666, 'epoch': 2.78}

{'loss': 3.4991, 'learning_rate': 0.005200000000000001, 'epoch': 2.79}

{'loss': 3.4964, 'learning_rate': 0.0051333333333333335, 'epoch': 2.8}

{'loss': 3.4894, 'learning_rate': 0.005066666666666667, 'epoch': 2.81}

{'loss': 3.4875, 'learning_rate': 0.005, 'epoch': 2.83}

{'loss': 3.5316, 'learning_rate': 0.004933333333333334, 'epoch': 2.84}

{'loss': 3.5311, 'learning_rate': 0.004866666666666667, 'epoch': 2.85}

{'loss': 3.4944, 'learning_rate': 0.0048, 'epoch': 2.86}

{'loss': 3.5275, 'learning_rate': 0.004733333333333333, 'epoch': 2.88}

{'loss': 3.484, 'learning_rate': 0.004666666666666667, 'epoch': 2.89}

{'loss': 3.5109, 'learning_rate': 0.0046, 'epoch': 2.9}

{'loss': 3.4961, 'learning_rate': 0.004533333333333333, 'epoch': 2.91}

{'loss': 3.515, 'learning_rate': 0.0044666666666666665, 'epoch': 2.93}

{'loss': 3.4879, 'learning_rate': 0.0044, 'epoch': 2.94}

{'loss': 3.5405, 'learning_rate': 0.004333333333333334, 'epoch': 2.95}

{'loss': 3.5271, 'learning_rate': 0.004266666666666667, 'epoch': 2.96}

{'loss': 3.5407, 'learning_rate': 0.0042, 'epoch': 2.98}

{'loss': 3.5413, 'learning_rate': 0.0041333333333333335, 'epoch': 2.99}

{'loss': 3.5235, 'learning_rate': 0.004066666666666667, 'epoch': 3.0}

{'loss': 3.5405, 'learning_rate': 0.004, 'epoch': 3.02}

{'loss': 3.5317, 'learning_rate': 0.003933333333333333, 'epoch': 3.03}

{'loss': 3.5401, 'learning_rate': 0.0038666666666666667, 'epoch': 3.04}

{'loss': 3.5316, 'learning_rate': 0.0038, 'epoch': 3.05}

{'loss': 3.5369, 'learning_rate': 0.0037333333333333337, 'epoch': 3.07}

{'loss': 3.5263, 'learning_rate': 0.0036666666666666666, 'epoch': 3.08}

{'loss': 3.5363, 'learning_rate': 0.0036, 'epoch': 3.09}

{'loss': 3.545, 'learning_rate': 0.003533333333333333, 'epoch': 3.1}

{'loss': 3.5571, 'learning_rate': 0.003466666666666667, 'epoch': 3.12}

{'loss': 3.5685, 'learning_rate': 0.0034000000000000002, 'epoch': 3.13}

{'loss': 3.5464, 'learning_rate': 0.003333333333333333, 'epoch': 3.14}

{'loss': 3.5387, 'learning_rate': 0.003266666666666667, 'epoch': 3.15}

{'loss': 3.5616, 'learning_rate': 0.0032, 'epoch': 3.17}

{'loss': 3.5728, 'learning_rate': 0.0031333333333333335, 'epoch': 3.18}

{'loss': 3.5224, 'learning_rate': 0.0030666666666666663, 'epoch': 3.19}

{'loss': 3.5456, 'learning_rate': 0.003, 'epoch': 3.2}

{'loss': 3.5595, 'learning_rate': 0.0029333333333333334, 'epoch': 3.22}

{'loss': 3.5312, 'learning_rate': 0.0028666666666666667, 'epoch': 3.23}

{'loss': 3.5749, 'learning_rate': 0.0028000000000000004, 'epoch': 3.24}

{'loss': 3.5716, 'learning_rate': 0.0027333333333333333, 'epoch': 3.25}

{'loss': 3.5487, 'learning_rate': 0.0026666666666666666, 'epoch': 3.27}

{'loss': 3.5655, 'learning_rate': 0.0026000000000000003, 'epoch': 3.28}

{'loss': 3.5732, 'learning_rate': 0.0025333333333333336, 'epoch': 3.29}

{'loss': 3.5805, 'learning_rate': 0.002466666666666667, 'epoch': 3.3}

{'loss': 3.5693, 'learning_rate': 0.0024, 'epoch': 3.32}

{'loss': 3.5564, 'learning_rate': 0.0023333333333333335, 'epoch': 3.33}

{'loss': 3.5839, 'learning_rate': 0.0022666666666666664, 'epoch': 3.34}

{'loss': 3.5703, 'learning_rate': 0.0022, 'epoch': 3.35}

{'loss': 3.5705, 'learning_rate': 0.0021333333333333334, 'epoch': 3.37}

{'loss': 3.5661, 'learning_rate': 0.0020666666666666667, 'epoch': 3.38}

{'loss': 3.5603, 'learning_rate': 0.002, 'epoch': 3.39}

{'loss': 3.6078, 'learning_rate': 0.0019333333333333333, 'epoch': 3.4}

{'loss': 3.5946, 'learning_rate': 0.0018666666666666669, 'epoch': 3.42}

{'loss': 3.581, 'learning_rate': 0.0018, 'epoch': 3.43}

{'loss': 3.5725, 'learning_rate': 0.0017333333333333335, 'epoch': 3.44}

{'loss': 3.5828, 'learning_rate': 0.0016666666666666666, 'epoch': 3.45}

{'loss': 3.5736, 'learning_rate': 0.0016, 'epoch': 3.47}

{'loss': 3.5952, 'learning_rate': 0.0015333333333333332, 'epoch': 3.48}

{'loss': 3.6055, 'learning_rate': 0.0014666666666666667, 'epoch': 3.49}

{'loss': 3.576, 'learning_rate': 0.0014000000000000002, 'epoch': 3.51}

{'loss': 3.5937, 'learning_rate': 0.0013333333333333333, 'epoch': 3.52}

{'loss': 3.5757, 'learning_rate': 0.0012666666666666668, 'epoch': 3.53}

{'loss': 3.5796, 'learning_rate': 0.0012, 'epoch': 3.54}

{'loss': 3.5819, 'learning_rate': 0.0011333333333333332, 'epoch': 3.56}

{'loss': 3.6042, 'learning_rate': 0.0010666666666666667, 'epoch': 3.57}

{'loss': 3.6165, 'learning_rate': 0.001, 'epoch': 3.58}

{'loss': 3.6003, 'learning_rate': 0.0009333333333333334, 'epoch': 3.59}

{'loss': 3.5961, 'learning_rate': 0.0008666666666666667, 'epoch': 3.61}

{'loss': 3.5852, 'learning_rate': 0.0008, 'epoch': 3.62}

{'loss': 3.6057, 'learning_rate': 0.0007333333333333333, 'epoch': 3.63}

{'loss': 3.6019, 'learning_rate': 0.0006666666666666666, 'epoch': 3.64}

{'loss': 3.613, 'learning_rate': 0.0006, 'epoch': 3.66}

{'loss': 3.6118, 'learning_rate': 0.0005333333333333334, 'epoch': 3.67}

{'loss': 3.5987, 'learning_rate': 0.0004666666666666667, 'epoch': 3.68}

{'loss': 3.5885, 'learning_rate': 0.0004, 'epoch': 3.69}

{'loss': 3.5865, 'learning_rate': 0.0003333333333333333, 'epoch': 3.71}

{'loss': 3.6127, 'learning_rate': 0.0002666666666666667, 'epoch': 3.72}

{'loss': 3.5677, 'learning_rate': 0.0002, 'epoch': 3.73}

{'loss': 3.5956, 'learning_rate': 0.00013333333333333334, 'epoch': 3.74}

{'loss': 3.5997, 'learning_rate': 6.666666666666667e-05, 'epoch': 3.76}

{'loss': 3.6117, 'learning_rate': 0.0, 'epoch': 3.77}

100%|█████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████| 3000/3000 [2:27:09<00:00,  2.94s/it]Saving PrefixEncoder

[INFO|configuration_utils.py:458] 2023-07-27 15:42:24,266 >> Configuration saved in output/adgen-chatglm2-6b-pt-128-2e-2/checkpoint-3000/config.json

[INFO|configuration_utils.py:364] 2023-07-27 15:42:24,266 >> Configuration saved in output/adgen-chatglm2-6b-pt-128-2e-2/checkpoint-3000/generation_config.json

[INFO|modeling_utils.py:1853] 2023-07-27 15:42:24,276 >> Model weights saved in output/adgen-chatglm2-6b-pt-128-2e-2/checkpoint-3000/pytorch_model.bin

[INFO|tokenization_utils_base.py:2194] 2023-07-27 15:42:24,276 >> tokenizer config file saved in output/adgen-chatglm2-6b-pt-128-2e-2/checkpoint-3000/tokenizer_config.json

[INFO|tokenization_utils_base.py:2201] 2023-07-27 15:42:24,277 >> Special tokens file saved in output/adgen-chatglm2-6b-pt-128-2e-2/checkpoint-3000/special_tokens_map.json

[INFO|trainer.py:2053] 2023-07-27 15:42:24,299 >>

Training completed. Do not forget to share your model on huggingface.co/models =)

{'train_runtime': 8838.9773, 'train_samples_per_second': 48.874, 'train_steps_per_second': 0.339, 'train_loss': 3.4511859970092775, 'epoch': 3.77}

100%|█████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████| 3000/3000 [2:27:09<00:00,  2.94s/it]

***** train metrics *****

epoch                    =       3.77

train_loss               =     3.4512

train_runtime            = 2:27:18.97

train_samples            =     114599

train_samples_per_second =     48.874

train_steps_per_second   =      0.339

wandb: Waiting for W&B process to finish... (success).

wandb: - 0.000 MB of 0.000 MB uploaded (0.000 MB deduped)

wandb: Run history:

wandb:                    train/epoch ▁▁▁▁▂▂▂▂▂▃▃▃▃▃▃▄▄▄▄▄▄▅▅▅▅▅▆▆▆▆▆▆▇▇▇▇▇███

wandb:              train/global_step ▁▁▁▁▂▂▂▂▂▃▃▃▃▃▃▄▄▄▄▄▅▅▅▅▅▅▆▆▆▆▆▆▇▇▇▇▇███

wandb:            train/learning_rate ████▇▇▇▇▇▆▆▆▆▆▆▅▅▅▅▅▅▄▄▄▄▄▃▃▃▃▃▃▂▂▂▂▂▁▁▁

wandb:                     train/loss █▆▄▃▂▂▂▂▂▂▁▁▁▁▁▂▂▂▂▂▂▂▂▃▃▃▃▃▃▃▃▄▄▄▄▄▄▅▅▅

wandb:               train/total_flos ▁

wandb:               train/train_loss ▁

wandb:            train/train_runtime ▁

wandb: train/train_samples_per_second ▁

wandb:   train/train_steps_per_second ▁

wandb:

wandb: Run summary:

wandb:                    train/epoch 3.77

wandb:              train/global_step 3000

wandb:            train/learning_rate 0.0

wandb:                     train/loss 3.6117

wandb:               train/total_flos 1.5621534993937859e+18

wandb:               train/train_loss 3.45119

wandb:            train/train_runtime 8838.9773

wandb: train/train_samples_per_second 48.874

wandb:   train/train_steps_per_second 0.339

wandb:

wandb: You can sync this run to the cloud by running:

wandb: wandb sync /llm/xhcheng/ChatGLM2-6B/ptuning/wandb/offline-run-20230727_131512-wuv3n4cv

wandb: Find logs at: ./wandb/offline-run-20230727_131512-wuv3n4cv/logs

real    148m31.192s

user    943m31.698s

sys     416m17.987s

推理

```
PRE_SEQ_LEN=128
CHECKPOINT=adgen-chatglm2-6b-pt-128-2e-2
STEP=3000
NUM_GPUS=8

torchrun --standalone --nnodes=1 --nproc-per-node=$NUM_GPUS main.py \
    --do_predict \
    --validation_file AdvertiseGen/dev.json \
    --test_file AdvertiseGen/dev.json \
    --overwrite_cache \
    --prompt_column content \
    --response_column summary \
    --model_name_or_path /llm/xhcheng/text-generation-webui/models/THUDM_chatglm2-6b \
    --ptuning_checkpoint ./output/$CHECKPOINT/checkpoint-$STEP \
    --output_dir ./output/$CHECKPOINT/evaluate_output \
    --overwrite_output_dir \
    --max_source_length 64 \
    --max_target_length 64 \
    --per_device_eval_batch_size 8 \
    --predict_with_generate \
    --pre_seq_len $PRE_SEQ_LEN \
    --quantization_bit 4

```

![](https://gitee.com/hxc8/images2/raw/master/img/202407172158784.jpg)

pip install cchardet

time bash evaluate.sh

![](https://gitee.com/hxc8/images2/raw/master/img/202407172158085.jpg)

推理结果解释

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

- predict_bleu-4: 这是BLEU-4分数，它是一种机器翻译质量的评估指标，它测量了生成的文本和参考翻译之间的相似程度。BLEU-4是四元组的BLEU分数，表示生成的文本与参考翻译重叠的4元组数量的准确率。

- predict_rouge-1: 这是ROUGE-1分数，它是一种评估文本摘要质量的指标。它测量了生成的文本和参考摘要之间的重叠程度。

- predict_rouge-2: 这是ROUGE-2分数，它是ROUGE-1的扩展，它测量了生成的文本和参考摘要之间的相似程度，其中包含两个连续的重叠词组。

- predict_rouge-l: 这是ROUGE-L分数，它是另一种评估文本摘要质量的指标。它测量了生成的文本和参考摘要之间的最长公共子序列的长度。

- predict_runtime: 这是模型在测试集上的评估时间，以秒为单位。

- predict_samples: 这是用于评估模型的样本数。

- predict_samples_per_second: 这是模型在评估期间处理的样本数每秒。

- predict_steps_per_second: 这是模型在评估期间执行的步骤数每秒。

启动训练后的模型

cd /llm/xxxx/ChatGLM2-6B/ptuning

增加0.0.0.0的监听

![](https://gitee.com/hxc8/images2/raw/master/img/202407172158872.jpg)

bash web_demo.sh

![](https://gitee.com/hxc8/images2/raw/master/img/202407172158422.jpg)

从上图可以看出，只能问跟训练数据相关的。

训练后output目录下面内容：

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
trainer_state.json: 这个文件包含了训练过程中Trainer的状态，包括当前的epoch、step、学习率等信息。
train_results.json: 这个文件包含了整个训练过程中训练集的评估结果，包括每个checkpoint的评估结果和最终的评估结果。

```

all_results.json内容解释

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

checkpoint-X000内容解释

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

tokenizer_config.json内容解释

```
这是一个分词器（tokenizer）的配置文件，用于指定分词器的超参数和结构等。以下是每个字段的解释：
auto_map: 自动映射，用于自动将分词器的名称映射到对应的类。在这个例子中，将“AutoTokenizer”映射到“tokenization_chatglm.ChatGLMTokenizer”类。
clean_up_tokenization_spaces: 是否清除分词后的空格。在这个例子中，不清除分词后的空格。
do_lower_case: 是否将文本转换为小写。在这个例子中，不将文本转换为小写。
model_max_length: 分词器的最大输入长度。在这个例子中，分词器的最大输入长度为1000000000000000019884624838656。
padding_side: 填充的位置。在这个例子中，填充在左边。
remove_space: 是否删除空格。在这个例子中，不删除空格。
tokenizer_class: 分词器的类名。在这个例子中，分词器的类名为“ChatGLMTokenizer”。

```

![](https://gitee.com/hxc8/images2/raw/master/img/202407172158580.jpg)