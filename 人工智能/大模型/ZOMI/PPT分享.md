# 一、介绍

## AI 系统概述

AI 历史，现状与发展

算法与体系结构的进步

AI 系统的组成与生态

大模型对AI系统的挑战

大模型对 AI 系统的挑战

### 什么是大模型

大模型在算法层挑战

大模型对 AI 系统冲击

大模型  + AI 系统技术栈

# 二、硬件

### AI 计算体系

深度学习计算模式

计算体系与矩阵运算

### AI 芯片基础

#### 通用处理器 CPU

#### 从数据看 CPU 计算

#### 通用图形处理器 GPU

##### 硬件基础

##### GPU 工作原理

##### GPU AI编程本质

##### 英伟达 GPU 架构

###### GPU基础概念

###### 从 Fermi 到 Volta 架构

###### Turing 到 Hopper 架构

###### **Tensor Code 和 NVLink 详解**

###### 从 Fermi 到 Hopper 架构

##### GPU 图形处理流水线

###### GPU 逻辑模块划分

###### 算法到 GPU 硬件

###### GPU 的软件栈

###### 图形流水线基础

###### 流水线不可编译单元

###### 光线跟踪流水线

##### 图形流水线基础

##### GPU 逻辑模块划分

##### 图形处理算法到硬件

#### AI专用处理器 NPU/TPU

华为昇腾 NPU

达芬奇架构

昇腾AI处理器

谷歌 TPU

TPU 核心脉动阵列

TPU 系列架构

特斯拉 DOJO

DOJO 架构

国内外其他AI芯片

AI芯片的思考

#### 计算体系架构的黄金10年

# 三、编译器

#### 基本介绍

Compiler and interpreter - 编译器与解释器

Just in time and Ahead of time - JIT和AOT编译方式

Pass and IR - Pass和中间表达IR

#### 传统编译器

History of Compiler - 编译器的发展

GCC process and principle – GCC 编译过程和原理

LLVM/Clang process and principle – LLVM 编译过程和原理

#### AI编译器

History of AI Compiler – AI编译器的发展

Base Common architecture – AI编译器的通用架构

Different and challenge of the future – 与传统编译器的区别，未来的挑战与思考

##### AI 编译器前端优化

图层 - Graph IR

算子融合 - OP Fusion

布局转换 - Layout Transform

内存分配 - Memory Allocation

常量折叠 – Constant Fold

公共子表达式消除 - CSE

死代码消除 - DCE

代数简化 - ARM

##### AI 编译器后端优化

后端优化概念

算子执行与调度

算子循环优化

Auto-Tuning

Polyhedral

#### PyTroch 2.0 新特性

2.0 新特性回顾

PyTorch 2.0 安装与新特性使用

PyTorch 2.0 对厂商的启发和思考

TorchDynamo 解读

TorchDynamo 效果

TorchDynamo 实现方案

AOTAutograd 解读

AOTAutograd 效果

AOTAutograd 实现方案

TorchInductor 新特性

Triton 使用解读

Triton 深度剖析

# 四、推理

#### 推理系统介绍

推理系统与推理引擎区别

推理工作流程

推理系统介绍

推理引擎介绍

#### 模型小型化

NAS神经网络搜索

CNN小型化结构

Transform小型化结构

#### 离线优化压缩

低比特量化

二值化网络

模型模型剪枝

模型模型蒸馏

#### 部署和运行优化

图转换优化（算子融合/重排/替换）

并发执行与内存分配

动态batch与bin Packing

#### 模型转换与优化

架构与流程

模型格式转换

模型离线优化

#### Kernel 优化

算法优化 (Winograd / Strassen)

内存布局 (NC1HWC0 / NCHW4)

汇编优化 (指令与汇编)

调度优化

#### Runtime与在线优化

动态batch

bin Packing

多副本并行

# 五、框架

#### 基本概念

AI框架作用：深度学习基础 – AI框架的作用 -  AI框架的目的

AI框架之争：第一代框架 – 第二代框架 – 第三代框架

编程范式：声明式编程 - 命令式编程

#### 自动微分

微分基本概念：数值微分 - 符号微分 - 自动微分

自动微分模式：前向微分 – 后向微分 – 雅克比原理

具体实现方式：表达式或图 – 操作符重载OO – 源码转换 AST

手把手实现：基于表达式的前向自动微分

手把手实现：基于OO的反向自动微分

自动微分的未来挑战

#### 计算图

计算图（数据流图）：AI系统化问题 – 计算图的提出

计算图和自动微分：回顾自动微分 – 计算图表达自动微分

图的调度和执行：图切分与调度 – 图与控制流

计算图的挑战与未来

# 六、基本概念

AI 集群建设：计算、通信、存储的建设

大模型数据：大模型数据集、数据处理、向量数据库

大模型算法：从传统 NLP 到预训练 LLM 大模型

大模型训练：大模型训练普通算法手段与稳定性分析

分布式并行：模型并行、数据并行、优化器并行等

大模型微调：全参微调、低参微调、指令微调算法

大模型推理：量化压缩、长序列扩充推理、Cache方法

大模型评测：NLP 下游任务、CV 下游任务、测评方案

大模型智能体：RLHF 流程、智能体、终身学习

#### AI集群+大模型

AI 集群服务器架构：参数服务器模式 – 同步与异步并行

AI 集群通讯方式：通信硬件实现 - 集群组网 – 集群软件通信 - 通信实现方式

分布式通讯原语：通信源语 - 点对点通信 – 集合通信

分布式存储系统：大模型权重存储方式 – 多级存储系统

AI 集群回顾：NVIDIA 与 TPU 超级计算节点POD

#### 存储

数据存储现状和场景：存储软件类型、存储硬件类型的发展

大模型对存储的挑战：存储性能指标、存储遇到大模型挑战与新机会点

大模型训练CKPT优化：大模型训练过程、CKPT过程分解、CKPT优化

大模型时代对存储的思考：什么样的存储架构才是AI大模型时代的选择？

#### 网络

AI 集群服务器架构：AI集群组成 - 参数服务器模式 – 同步与异步并行

AI 集群通信方式：通信硬件实现 - 集群组网 – 集群软件通信 - 通信实现方式

分布式通信原语：通信源语 - 点对点通信 – 集合通信

分布式存储系统：大模型权重存储方式 – 多级存储系统

AI 集群回顾：NVIDIA 与 TPU 超级计算节点POD

#### 智能体

AI Agent 组成介绍：LLM + 记忆 + 规划 + 工具

AI Agent 规划手段： Task Decomposition 与 Self Reflection

AI Agent 热门应用：交互式 Agent、自动化 Agent 与多模态 Agent

AI Agent 问题与挑战： Agent 的问题、Agent 的局限性

# 七、分布式算法

分布式+AI集群：服务器架构 – 集群软硬件通信 - 通信原语 - AI框架分布式功能

大模型与训练挑战：什么是大模型 – 大模型训练的四个挑战

大模型算法结构：大模型算法发展 – Transformer结构 – MOE结构

SOTA大模型算法：BERT – GPT3 – Switch Transformer

分布式并行：数据并行 – 张量并行 – 自动并行 – 多维混合并行

# **八、分布式并行、训练**

分布式+AI集群：服务器架构 – 集群软硬件通信 - 通信原语 - AI框架分布式功能

大模型算法：挑战 – 算法结构 – SOTA大模型

数据并行 ：数据并行DP – 分布式数据并行 DDP – 分片共享数据并行 FSDP

模型并行：模型并行MP - 张量并行 TP – 流水线并行 PP

自动并行： MindSpore 张量自动并行

多维混合并行：Embedding并行 – 数据并行&模型并行 MPDP