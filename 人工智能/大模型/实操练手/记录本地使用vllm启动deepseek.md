本地找到了一个已经安装vllm的python环境  ，如果没有可以参考 https://53ai.com/news/finetuning/2024090663471.html

conda  activate xxxx


CUDA_VISIBLE_DEVICES=5,6,7,8 python -m vllm.entrypoints.openai.api_server --port 11111 --served-model-name DeepSeek-V2-Lite-Chat --model /llm/xhcheng/text-generation-webui/models/DeepSeek-V2-Lite-Chat --tensor-parallel-size 4 --trust-remote-code --gpu-memory-utilization 0.95 --max-model-len 14000
