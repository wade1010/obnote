.bashrc 快捷配置如下
```
alias ns='nvidia-smi'
alias vimbashrc='vim ~/.bashrc'
alias hisoff=' set +o history'
alias hison=' set -o history'
alias goworkspace='cd /xxx/xxx/workspace'
alias ol='ollama list'
alias op='ollama ps'
alias sl='screen -ls'
alias sr='screen -r'
alias sS='screen -S'
alias shelp='echo "screen -S name、screen -r name、screen -ls、Ctrl-a 然后按d将分离当前screen会话，并返回到原始终端、Ctrl-a 然后按 \"、Ctrl-a 然后按 n、Ctrl-a 然后按 p"'
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
