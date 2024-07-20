左边表所有行全部由，右边表多行匹配，要求只显示一行。



例如左表：SELECT * FROM `job_resu` WHERE id=302

![](https://gitee.com/hxc8/images8/raw/master/img/202407191058452.jpg)





右表：SELECT * FROM `job_resu_view_log` WHERE `job_resu_id` = 302

![](https://gitee.com/hxc8/images8/raw/master/img/202407191058667.jpg)



可以看见，左表就一条记录，右表有三条。我只需要左边表显示一行，右边表也只匹配一行。



1、不是想要的结果。

SELECT 

    job_resu.*,job_resu_view_log.*

FROM 

    `job_resu`

    LEFT join job_resu_view_log on job_resu.id = job_resu_view_log.job_resu_id

WHERE 

     job_resu.id=302

![](https://gitee.com/hxc8/images8/raw/master/img/202407191058789.jpg)





2、想要的结果

SELECT 

    job_resu.*,job_resu_view_log.*

FROM 

    `job_resu`,(SELECT * from job_resu_view_log GROUP BY job_resu_view_log.job_resu_id)job_resu_view_log

WHERE 

     job_resu.id=302

     AND job_resu_view_log.job_resu_id=job_resu.id

![](https://gitee.com/hxc8/images8/raw/master/img/202407191058071.jpg)







另外：坐标全部，右表任意匹配，但是如果on的字段重复则只取一条  下面是右表job_view_log 中的job_id可能是重复的，只取一条

select 

    job.id, 

    job.sn, 

    job.pos_id, 

    job.com_rec_fixed as com_recom, 

    job.com_share, 

    job.client_id, 

    job.showalias, 

    job.pos_lev_id, 

    job_view_logs.id as isread 

from 

    job LEFT JOIN (SELECT * FROM job_view_log WHERE job_view_log.user_id = 670 GROUP BY job_view_log.job_id)job_view_logs ON job_view_logs.job_id=job.id

where 

    status = 1 

    and unix_timestamp(enddate)>= unix_timestamp('2015-12-21 00:00:00') 

    AND job.indu_id = '5' 

    AND job.address in (

        select 

            id 

        from 

            client_address 

        where 

            'a' = 'a' 

            AND province = '9'

    ) 

order by 

    job.time DESC, 

    job.id DESC

