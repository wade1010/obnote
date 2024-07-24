报错如下：

llama-2-70b-chat does not appear to have a file named config.json

解决

cd llama

git clone '[https://github.com/huggingface/transformers](https://github.com/huggingface/transformers)'

ln -s llama-2-70b-chat 70B

mkdir llama-2-70b-chat-hf

python ./transformers/src/transformers/models/llama/convert_llama_weights_to_hf.py --input_dir . --model_size 70B --output_dir llama-2-70b-chat-hf --safe_serialization true

2023-7-26 17:21分开始执行，2023-7-26 17:32结束

llama-2-70b-chat-hf就是最终产物了，之后使用这个目录进行模型加载