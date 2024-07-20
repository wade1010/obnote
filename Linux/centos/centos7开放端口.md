添加命令

firewall-cmd --zone=public --add-port=9200/tcp --permanent

firewall-cmd --reload



查询是否成功

firewall-cmd --zone=public --query-port=9200/tcp