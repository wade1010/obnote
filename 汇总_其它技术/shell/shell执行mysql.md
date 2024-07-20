mysql -uroot hly_bk -N -e 'select name,id from team' | while read a b; do echo "a:$a -- b:$b"; done 





#! /bin/bash

MD_MYSQL=/usr/local/bin/mysql



HOSTNAME="localhost"                   #数据库信息

PORT="3306"

USERNAME="root"

PASSWORD=""

DBNAME="hly"                      #数据库名称



cmd="select if(pmuid.uid is null, 0, pmuid.uid) pm_uid,Fid, Fname, 0 type, if(updated_at is null, now(), updated_at) updated_at, if(created_at is null, now(), created_at) created_at

from hly.company left join (select u.id uid,com_pm.Fcompany_id mycomid from (select Fcompany_id,Fpm_id from hly.user where Ftype=2 and Fcom_admin=1 and Fcompany_id>0 group by Fcompany_id) com_pm join hly_bk.user u on com_pm.Fpm_id=u.gh_uid) pmuid

on company.Fid = pmuid.mycomid

where company.Fid in(select Fid from hly.company where Fid not in (select id from hly_bk.company) and Ftype=2)"

cnt=$(mysql -uroot hly -s -e "${cmd}"|while read a b c d e f g h;do echo "pm_uid:$a  Fid:$b  Fname:$c  type:$d  updated_at:$e $f  created_at:$g $h"; done)

echo ${cnt}

for i in ${cnt[@]}; do

    echo "$i"

done



结果如下：

还有点问题



[mycrontab] sh synccompany.sh                                                                                                                                                                            

pm_uid:5 Fid:242 Fname:上海利得基金销售有限公司 type:0 updated_at:2016-05-30 18:10:23 created_at:2016-06-24 18:44:14

pm_uid:5

Fid:242

Fname:上海利得基金销售有限公司

type:0

updated_at:2016-05-30

18:10:23

created_at:2016-06-24

18:44:14