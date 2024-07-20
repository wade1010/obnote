### aof+rdb

appendonly yes

appendfsync erverysec

aof-use-rdb-preamble yes  



影响的是重写

 执行bgrewriteaof的时候，先生成快照保存已有数据，后续追加的数据用aof保存，最后整合两文件生成新的aof文件
 
 
 
 
 ## Redis开启aof时
 
有rdb都不会加载rdb，以aof为主,redis认为aof更可靠

所以rdb迁移 要先关闭目标服务器的aof