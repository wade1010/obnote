### 安装包下载（brew 安装我觉得比较慢）

> https://mirrors.huaweicloud.com/elasticsearch/7.5.0/

#### 启动

> bin/elasticsearch

### 安装可视化工具Kibana

> https://mirrors.huaweicloud.com/kibana/7.5.0/

#### 设置中文

> vim config/kibana.yml

末尾加上

```
i18n.locale: "zh-CN"
```

#### 启动

> bin/kibana

#### 访问

> http://localhost:5601


### 安装 lagstash

> https://mirrors.huaweicloud.com/logstash/7.5.0/


bin/elasticsearch-plugin install https://github.com/medcl/elasticsearch-analysis-ik/releases/download/v7.5.0/elasticsearch-analysis-ik-7.5.0.zip
