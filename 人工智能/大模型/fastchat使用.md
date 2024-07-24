进行pod 
   kubectl exec -it synthetic-data-bfdb55689-5q9vc -n hanbo -- /bin/bash

进入FastChat根目录  cd code/FastChat
（1）controller中心服务，类似注册中心
 
nohup python -m fastchat.serve.controller --port 21001  > ./logs/fastchat.serve.controller.log 2>&1 &

（2）model_worker加载模型
1.Llama  
当端口冲突时可以指定端口

CUDA_VISIBLE_DEVICES=0  nohup  python -m fastchat.serve.model_worker --model-name Meta-Llama-3-8B-Instruct --model-path ./../Meta-Llama-3-8B-Instruct  --controller http://localhost:31001 --port 31000 --worker http://localhost:31000  > ./logs/fastchat.serve.model_worker.Meta-Llama-3-8B-Instruct.log 2>&1 &


2.千问

CUDA_VISIBLE_DEVICES=1 nohup    python -m fastchat.serve.model_worker --model-name Qwen1.5-14B-Chat --model-path ./../Qwen1.5-14B-Chat  --controller http://localhost:31001 --port 31002 --worker http://localhost:31002  > ./logs/fastchat.serve.model_worker.Qwen1.5-14B-Chat.log 2>&1 &




（4）openapi
pip install shortuuid

nohup  python -m fastchat.serve.openai_api_server --host 172.20.69.216 --port 5010 --controller-address --temperature 0.2  --top_p 0.9 --top_k 40 --max_new_tokens 1024 --repetition_penalty 1.1 http://localhost:21001  > ./logs/fastchat.serve.openai_api_server.log 2>&1 &

nohup  python -m fastchat.serve.openai_api_server_hanbo  --host 172.20.69.217 --port 5010 --controller-address http://localhost:31001  > ./logs/fastchat.serve.openai_api_server_hanbo.log 2>&1 &

向量模型运行
 cd code
 nohup python acge-text-embedding-tiktoken-base_api.py > ./acge_logs/acge.log 2>&1 &
 nohup python local_reranker.py > ./bge_rerank_logs/rerank.log 2>&1 &

