CLI声明周期

php_module_startup 模块初始化

php_request_startup  请求初始化

php_execute_script  读取php代码进行语法解析->ast抽象语法树->opcode->执行得到对应结果

php_request_shutdown 请求关闭

php_module_shutdown 模块关闭