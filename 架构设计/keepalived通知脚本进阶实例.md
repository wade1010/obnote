下面的脚本可以接受选项，其中：

-s, –service SERVICE,…：指定服务脚本名称，当状态切换时可自动启动、重启或关闭此服务；

-a, –address VIP: 指定相关虚拟路由器的VIP地址；

-m, –mode {mm|mb}：指定虚拟路由的模型，mm表示主主，mb表示主备；它们表示相对于同一种服务而方，其VIP的工作类型；

-n, –notify {master|backup|fault}：指定通知的类型，即vrrp角色切换的目标角色；

-h, –help：获取脚本的使用帮助；


```
#!/bin/bash

# Author: Amd5 <linuxedu@foxmail.com>

# description: An example of notify script

# Usage: notify.sh -m|–mode {mm|mb} -s|–service SERVICE1,… -a|–address VIP  -n|–notify {master|backup|falut} -h|–help 

#c>

helpflag=0

serviceflag=0

modeflag=0

addressflag=0

notifyflag=0

c>

Usage() {

  echo "Usage: notify.sh [-m|–mode {mm|mb}] [-s|–service SERVICE1,…] <-a|–address VIP>  <-n|–notify {master|backup|falut}>" 

  echo "Usage: notify.sh -h|–help"

}

ParseOptions() {

  local I=1;

  if [ $# -gt 0 ]; then

    while [ $I -le $# ]; do

      case $1 in

   -s|–service)

 [ $# -lt 2 ] && return 3

      serviceflag=1

  services=(`echo $2|awk -F"," '{for(i=1;i<=NF;i++) print $i}'`)

 shift 2 ;;

   -h|–help)

  helpflag=1

 return 0

        shift

 ;;

   -a|–address)

 [ $# -lt 2 ] && return 3

     addressflag=1

 vip=$2

 shift 2

 ;;

   -m|–mode)

 [ $# -lt 2 ] && return 3

 mode=$2

 shift 2

 ;;

   -n|–notify)

 [ $# -lt 2 ] && return 3

 notifyflag=1

 notify=$2

 shift 2

 ;;

   *)

 echo "Wrong options…"

 Usage

 return 7

 ;;

       esac

    done

    return 0

  fi

}

#workspace=$(dirname $0)

RestartService() {

  if [ ${#@} -gt 0 ]; then

    for I in $@; do

      if [ -x /etc/rc.d/init.d/$I ]; then

        /etc/rc.d/init.d/$I restart

      else

        echo "$I is not a valid service…"

      fi

    done

  fi

}

StopService() {

  if [ ${#@} -gt 0 ]; then

    for I in $@; do

      if [ -x /etc/rc.d/init.d/$I ]; then

        /etc/rc.d/init.d/$I stop

      else

        echo "$I is not a valid service…"

      fi

    done

  fi

}

Notify() {

    mailsubject="`hostname` to be $1: $vip floating"

    mailbody="`date '+%F %H:%M:%S'`, vrrp transition, `hostname` changed to be $1."

    echo $mailbody | mail -s "$mailsubject" $contact

}

# Main Function

ParseOptions $@

[ $? -ne 0 ] && Usage && exit 5

[ $helpflag -eq 1 ] && Usage && exit 0

if [ $addressflag -ne 1 -o $notifyflag -ne 1 ]; then

  Usage

  exit 2

fi

mode=${mode:-mb}

case $notify in

'master')

  if [ $serviceflag -eq 1 ]; then

      RestartService ${services[*]}

  fi

  Notify master

  ;;

'backup')

  if [ $serviceflag -eq 1 ]; then

    if [ "$mode" == 'mb' ]; then

      StopService ${services[*]}

    else

      RestartService ${services[*]}

    fi

  fi

  Notify backup

  ;;

'fault')

  Notify fault

  ;;

*)

  Usage

  exit 4

  ;;

esac
```