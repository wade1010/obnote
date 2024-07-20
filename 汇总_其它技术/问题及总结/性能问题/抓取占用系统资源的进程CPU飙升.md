vim monitor.sh

```text
#!/bin/bash
#获取占用IO的进程信息
function io_monitor(){
  out=`pidstat -d 1 1 | grep Average | sed -n '2,$p' | sed ':a;N;$!ba;s/\n/,/g'`
  declare -A io_map
  i=1
  total_read=0
  total_write=0
  while ((1==1)); do
      if [ "$out" == "" ]; then
          break;
      fi
      tmp=`echo $out | cut -d "," -f $i`
      if [ "$tmp" == "" ]; then
          break;
else
          read_val=`echo $tmp | awk '{print $4}' | cut -d"." -f 1`
          write_val=`echo $tmp | awk '{print $5}' | cut -d"." -f 1`
          cmd_val=`echo $tmp | awk '{print $8}'`
          total_read=$(($total_read+$read_val))
          total_write=$(($total_write+$write_val))
          map_val=$read_val","$write_val
          io_map[$cmd_val]=$map_val
          ((i++))
      fi
      if [[ $out =~ .*,.* ]]; then
          continue
      else
          break
      fi
  done
  if [[ $total_read -gt 10240 ]] || [[ $total_write -gt 10240 ]];then
      echo "--------------$(date +"%m-%d %H:%M:%S")--------------------" >> io.log
      for key in ${!io_map[*]};do
          val=${io_map[$key]}
          val_arr=(${val//,/ })
          if [[ ${val_arr[0]} -gt 0 ]] || [[ ${val_arr[1]} -gt 0 ]];then
              echo "task_name:$key   IO_read(kB/s):${val_arr[0]}     IO_write(kB/s):${val_arr[1]}" >> io.log
          fi
      done
  fi
}
#获取占用CPU和内存的信息
function cpu_Mem_monitor(){
  cpu_us=`sar -u 1 1 | sed -n '$p' | awk '{print $3}' | cut -d"." -f 1`
  cpu_sy=`sar -u 1 1 | sed -n '$p' | awk '{print $5}' | cut -d"." -f 1`
  mem_total=$(free -m | grep Mem | awk '{print $2}')
  mem_used=$(free -m | grep Mem | awk '{print $3}')
  mem_rate=`echo "scale=2;$mem_used/$mem_total" | bc | awk -F. '{print $2}'`
  sum_cpu=$(($cpu_us+$cpu_sy))
  if [[ $sum_cpu -gt 80 ]] || [[ $mem_rate -gt 80 ]];then
      echo "--------------$(date +"%m-%d %H:%M:%S")--------------------" >> cpu.log
      top -b -n 1 | head -n 30 >> cpu.log
  fi
}

#退出死循环的条件（可以选择其他方式）
function exit_loop(){
  if [[ -e "io.log" ]] && [[ -e "cpu.log" ]]; then
      iolog_size=`du io.log | awk '{print $1}'`
      cpulog_size=`du cpu.log | awk '{print $1}'`
      total_size=$(($iolog_size+$cpulog_size))
      if [ $total_size -gt 1024000 ];then
          exit
      fi 
  fi
}
#每隔两秒执行一次，以避免占用太多系统资源（如果选择计划任务的方式，可以去掉while循环）
while [ 2 -gt 1 ]
do
  sleep 2
  io_monitor
  cpu_Mem_monitor
  exit_loop

done
```

启动 

```text
nohup bash monitor.sh > nohup.log 2>&1 &
```

示例：

cat cpu.log

![](https://gitee.com/hxc8/images7/raw/master/img/202407190802500.jpg)