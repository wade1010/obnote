本地找到了一个已经安装vllm的python环境

conda  activate xxxx


CUDA_VISIBLE_DEVICES=5,6,7,8 python -m vllm.entrypoints.openai.api_server --served-model-name Qwen1.5-14B-Chat --model /llm/xhcheng/text-generation-webui/models/DeepSeek-V2-Lite-Chat --tensor-parallel-size 4