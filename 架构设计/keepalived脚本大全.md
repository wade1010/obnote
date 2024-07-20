```
vrrp_script chk_mantaince_down {
   script "[[ -f /etc/keepalived/down ]] && exit 1 || exit 0"  #[ -f /etc/keepalived/down ]检查是否有down这个文件，如果真返回1，1代表失败，如果假返回0，0代表成功
   # commadn1 && command2  只有在 && 左边的命令返回真（命令返回值 $? == 0），&& 右边的命令才会被执行
   # commadn1 || command2  只有在 || 左边的命令返回假（命令返回值 $? == 1），|| 右边的命令才会被执行。
   interval 1
   weight -2
   #只要上面exit 1,weight就-2，下面的101 -2 = 99 比BACKUP的100少了，优先级低了，就变成备用状态了，再次检测如果down没了，权重又成101了，就又变成MASTER了。这样就可以手动干预vip在节点间切换
}
```
1. 当服务器改变为主时执行此脚本
```
# cat to_master.sh 

#!/bin/bash

Date=$(date +%F" "%T)

IP=$(ifconfig eth0 |grep "inet addr" |cut -d":" -f2 |awk '{print $1}')

Mail="baojingtongzhi@163.com"

echo "$Date $IP change to master." |mail -s "Master-Backup Change Status" $Mail
```
2. 当服务器改变为备时执行此脚本
```
# cat to_backup.sh

#!/bin/bash

Date=$(date +%F" "%T)

IP=$(ifconfig eth0 |grep "inet addr" |cut -d":" -f2 |awk '{print $1}')

Mail="baojingtongzhi@163.com"

echo "$Date $IP change to backup." |mail -s "Master-Backup Change Status" $Mail
```
3. 当服务器改变为故障时执行此脚本
```
# cat to_fault.sh

#!/bin/bash

Date=$(date +%F" "%T)

IP=$(ifconfig eth0 |grep "inet addr" |cut -d":" -f2 |awk '{print $1}')

Mail="baojingtongzhi@163.com"

echo "$Date $IP change to fault." |mail -s "Master-Backup Change Status" $Mail
```
4. 当检测TCP端口3306为不可用时，执行此脚本，杀死keepalived，实现切换
```
# cat mysql_down.sh

#!/bin/bash

Date=$(date +%F" "%T)

IP=$(ifconfig eth0 |grep "inet addr" |cut -d":" -f2 |awk '{print $1}')

Mail="baojingtongzhi@163.com"

pkill keepalived

echo "$Date $IP The mysql service failure,kill keepalived." |mail -s "Master-Backup MySQL Monitor" $Mail
```
5. 当检测TCP端口3306可用时，执行此脚本
```
# cat mysql_up.sh

#!/bin/bash

Date=$(date +%F" "%T)

IP=$(ifconfig eth0 |grep "inet addr" |cut -d":" -f2 |awk '{print $1}')

Mail="baojingtongzhi@163.com"

echo "$Date $IP The mysql service is recovery." |mail -s "Master-Backup MySQL Monitor" $Mail
```