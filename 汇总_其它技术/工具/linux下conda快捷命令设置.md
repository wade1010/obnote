平时 conda activate xxx和conda env list使用比较多。
使用下面命令简化使用

vim .bashrc

加入下面代码
```
alias cl='conda env list'
function ca() {
    env_name=$(conda env list | awk 'NR>2 {print $1}' | sed -n "${1}p")
    if [ -n "$env_name" ]; then
        conda activate "$env_name"
    else
        echo "Environment number $1 does not exist."
    fi
}
```
source .bashrc


之后就可以快捷使用了

