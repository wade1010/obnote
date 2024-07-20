我们有时候需要查看mysql的执行历史，比如我们做sql优化的时候，起码要知道执行的sql是什么，框架一般会帮我们拼装sql，所以在程序中不一定能够打印出sql，这个时候就需要mysql的general 

log了。





查看设置mysql genneral log



show VARIABLES like '%general_log%';



set GLOBAL general_log = off;// on-打开; off-关闭



general_log ON

general_log_file    /var/log/mysql/query.log







使用mysql general log



tail -f /path/to/log/query.log | grep yourtable

13518 Prepare   SELECT count(*) AS `count` FROM `babysitter_tips` WHERE (tip_type = '1') AND (is_enable = 1) AND (is_tip = 2)

        13518 Query DESCRIBE `babysitter_tips`

        13518 Close stmt

        13518 Prepare   SELECT `babysitter_tips`.* FROM `babysitter_tips` WHERE (tip_type = '1') AND (is_enable = 1) AND (is_tip = 2) ORDER BY `created_time` desc LIMIT 5

        13518 Reset stmt

        13518 Close stmt

        13518 Prepare   SELECT count(*) AS `count` FROM `babysitter_tips` WHERE (tip_type = '1') AND (is_enable = 1) AND (is_tip = 2) AND (tip_id > 15440)

        13518 Close stmt

        13518 Prepare   SELECT count(*) AS `count` FROM `babysitter_tips` WHERE (tip_type = '3') AND (is_enable = 1) AND (is_tip = 2)

        13518 Query DESCRIBE `babysitter_tips`

        13518 Close stmt

        13518 Prepare   SELECT `babysitter_tips`.* FROM `babysitter_tips` WHERE (tip_type = '3') AND (is_enable = 1) AND (is_tip = 2) ORDER BY `created_time` desc LIMIT 5

        13518 Reset stmt

        13518 Close stmt

        13518 Prepare   SELECT count(*) AS `count` FROM `babysitter_tips` WHERE (tip_type = '3') AND (is_enable = 1) AND (is_tip = 2) AND (tip_id > '')

        13518 Close stmt

        13518 Prepare   SELECT count(*) AS `count` FROM `babysitter_tips` WHERE (tip_type = '2') AND (is_enable = 1) AND (is_tip = 2)

        13518 Query DESCRIBE `babysitter_tips`

        13518 Close stmt

        13518 Prepare   SELECT `babysitter_tips`.* FROM `babysitter_tips` WHERE (tip_type = '2') AND (is_enable = 1) AND (is_tip = 2) ORDER BY `created_time` desc LIMIT 5

        13518 Reset stmt

        13518 Close stmt



清理mysql general log



general log会比较大，所以默认市关闭的，所以最好需要的时候打开，随手关闭。如果发现query.log过大，可以手动删除。在general log打开的情况下，query.log文件类似于mysql表的lock情况，不允许修改和删除，关闭general log就可以操作了。