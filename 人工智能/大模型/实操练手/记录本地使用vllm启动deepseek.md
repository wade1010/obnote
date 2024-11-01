本地找到了一个已经安装vllm的python环境

conda  activate xxxx

CUAD_VISIBLE_DEVICES=0,1,2,3 python -m vllm.entrypoints.openai.api_server --model deepseek-ai/DeepSeek-V2-Lite-Chat --port 11434 --tensor-parallel-size 4 --gpu-memory-utilization 0.9 --max-model-len 8192 --trust-remote-code --enforce_eager --dtype=half

CUDA_VISIBLE_DEVICES=0,1,2,3 python -m vllm.entrypoints.openai.api_server --served-model-name Qwen1.5-14B-Chat --model /llm/xhcheng/text-generation-webui/models/DeepSeek-V2-Lite-Chat --tensor-parallel-size 4