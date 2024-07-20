#### index
是指用到了索引覆盖,效率非常高
#### using where
是指光靠索引定位不了,还得where判断一下 
#### using temporary
是指用上了临时表, group by 与order by 不同列时,或group by ,order by 别的表的列.  
#### using filesort
文件排序(文件可能在磁盘,也可能在内存)