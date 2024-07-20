info replication 可以查看 偏移量 offset



写个脚本监控主从偏移量 读取偏移量 



把 偏移量大的从机剔除



如果超过某个阈值就报警







编写脚本定时定时查询Redis主机跟从机的偏移量，如果偏移量过大则重新生成配置文件



$redis=new Redis();

$redis->connect('127.0.0.1',6379);



swoole_timer_tick(100,function()use($redis){

$rep=$redis->info('replication');

$slaveCount=$rep['connected_slaves'];

for($i=0;$i<$slaveCount;$i++){

//ip=xxx.xxx.xxx.xxx,port=6379,state=online,offset=5555,lag=0

pre_match('/ip=(.*?),port=(\d+)/',$rep['slave'.$i],$match);

$masterOffset=$rep['master_repl_offset'];

$slaveOffset=$match[2];

$port=$match[1];

$ip=$match[0];

$slaveConfig=[];

if($masterOffset-$slaveOffset<10){//延迟范围内，写入到正常配置

$slaveConfig[]='tcp://'.$ip.':'.$port.'?alias=slave_'.$i;

}

}

});