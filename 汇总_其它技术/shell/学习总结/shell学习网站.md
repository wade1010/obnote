http://www.runoob.com/linux/linux-shell.html





shell脚本实现仅保留某目录下最新的两个文件

#!/bin/sh



export DS_DIR=/home/cxy/test



if [ ! -d $DS_DIR ]; then

    mkdir $DS_DIR

else

    echo "$DS_DIR is not existed!"

fi



cd $DS_DIR

if [ $(ls -l | grep "install-*" | wc -l) > 3 ]; then

    echo "more than 2 dst_files in $DS_DIR"

    rm -r $(ls -rt | head -n2)

fi





grep 'file'  diff_resume_201801101642.log | awk '{print $5}' | sort -u | wc -l  