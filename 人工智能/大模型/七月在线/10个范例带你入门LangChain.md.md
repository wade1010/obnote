本节课通过演示10个具有代表性的应用范例，带你零基础入门langchain，所有代码全部可执行

1，文本总结(Summarization): 对文本/聊天内容的重点内容总结。

2，文档问答(Question and Answering Over Documents): 使用文档作为上下文信息，基于文档内容进行问答。

3，信息抽取(Extraction): 从文本内容中抽取结构化的内容。

4，结果评估(Evaluation): 分析并评估LLM输出的结果的好坏。

5，数据库问答(Querying Tabular Data): 从数据库/类数据库内容中抽取数据信息。

6，代码理解(Code Understanding): 分析代码，并从代码中获取逻辑，同时也支持QA。

7，API交互(Interacting with APIs): 通过对API文档的阅读，理解API文档并向真实世界调用API获取真实数据。

8，聊天机器人(Chatbots): 具备记忆能力的聊天机器人框架（有UI交互能力)

9，用不到 50 行代码实现一个文档对话机器人

10，智能体(Agents): 使用LLMs进行任务分析和决策，并调用工具执行决策

第十个演示

```
from langchain.agents import load_tools, initialize_agent,AgentType
from langchain.chat_models import ChatOpenAI
llm = ChatOpenAI(temperature = 0, openai_api_key=openai_api_key, openai_api_base=openai_api_base) #改成你自己的
tools = load_tools(['wikipedia','llm-math'], llm = llm)
agent = initialize_agent(tools,
                  llm,
                  agent = AgentType.ZERO_SHOT_REACT_DESCRIPTION,
                  verbose = True)
agent.run("奥巴马的生日是哪天？到2024年他多少岁了？")
```


![image.png](https://gitee.com/hxc8/images10/raw/master/img/202407291353690.png)