- 绑定参数是 PHQL语言的一部分, 可帮助您保护代码

- PHQL只允许每个调用执行一个 sql 语句, 以防止注入

- PHQL忽略 sql 注入中经常使用的所有 sql 注释

- PHQL只允许数据操作语句, 避免错误地更改或删除表数据库或未经授权在外部更改或删除

- PHQL 实现了一个高级抽象, 允许您将表作为模型处理, 将字段作为类属性处理





http://phalcondoc.p2hp.com/zh/4.0/db-phql





```javascript
$query = new Query('select * from user', $this->di);
var_dump($query->execute()->toArray());

$result = $this->modelsManager->executeQuery('select * from User');
var_dump($result->toArray());


$result = $this->modelsManager->executeQuery('select * from User where id = :id:', ['id' => 3]);
var_dump($result->toArray());

```

