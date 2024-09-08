平时 conda activate xxx和conda env list使用比较多。
使用下面命令简化使用

vim .bashrc

加入下面代码
```shell
function cl() {
    conda env list | awk '
    BEGIN {print "# conda environments:"}
    NR==2 {print $0}
    NR>2 && NF>0 {
        env_name = $1
        if ($(NF-1) == "*") {
            env_name = env_name " *"
            path = $NF
        } else {
            path = $NF
        }
        printf "%-3d %-25s %s\n", NR-2, env_name, path
    }
    '
}
function ca() {
    if [[ "$1" =~ ^[0-9]+$ ]]; then
        env_name=$(conda env list | awk 'NR>2 {print $1}' | sed -n "${1}p")
        if [ -n "$env_name" ]; then
            conda activate "$env_name"
        else
            echo "Environment number $1 does not exist."
        fi
    else
        conda activate "$1"
    fi
}
```
![image.png](https://gitee.com/hxc8/images9/raw/master/img/202409081704488.png)

source .bashrc


之后就可以快捷使用了
cl命令查看 conda 环境列表

ca 1  、ca 2等切换环境
![image.png](https://gitee.com/hxc8/images9/raw/master/img/202409081703730.png)
