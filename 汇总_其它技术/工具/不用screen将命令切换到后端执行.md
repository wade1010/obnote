### 方法 1: 使用 `Ctrl + Z` 和 `bg`

1. 首先，按下 `Ctrl + Z` 来暂停当前的前台进程。
    
2. 然后，输入 `bg` 命令将暂停的进程放到后台运行。





1. 使用 fg 命令
fg 命令可以将后台运行的进程恢复到前台执行。

bash
复制
$ fg
如果你有多个后台任务，fg 会默认恢复最近暂停的任务。如果你需要恢复特定的任务，可以使用任务编号。例如：

bash
复制
$ fg %1
这里的 %1 表示任务编号为 1 的任务。

2. 查看后台任务
你可以使用 jobs 命令查看当前 shell 会话中的所有后台任务及其编号。

bash
复制
$ jobs
输出示例：

复制
[1]+  Running                 long_running_command &
[2]-  Running                 another_command &
在这个例子中，[1] 和 [2] 是任务编号，你可以使用 fg %1 或 fg %2 来恢复特定的任务。

3. 示例操作
假设你有一个长时间运行的命令 long_running_command，并且你已经使用 Ctrl + Z 和 bg 将其放到后台运行：

bash
复制
$ long_running_command
# 按下 Ctrl + Z
[1]+  Stopped                 long_running_command
$ bg
[1]+ long_running_command &
现在，如果你想将其恢复到前台执行，可以使用 fg 命令：

bash
复制
$ fg
或者，如果你有多个后台任务，并且你想恢复特定的任务：

bash
复制
$ fg %1
通过这些步骤，你可以将后台运行的任务恢复到前台执行。