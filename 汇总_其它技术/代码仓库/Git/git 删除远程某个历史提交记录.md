git 删除远程某个历史提交记录

分两种情况：

一、删除最后一次提交

这种情况比较简单，主要操作分两步：

第一步：回滚上一次提交

git reset --hard HEAD^

第二步：强制提交本地代码

git push origin master -f

或

git push -f

由于本地reset之后本地库落后于远程几个版本，所以需要使用-f强制提交。

二、删除指定commit提交（非最后一次提交）

假定： 现在我们要删除commit--2这条提交记录

图例

![](https://gitee.com/hxc8/images5/raw/master/img/202407180000650.jpg)

- 第一步：查看提交日志，获取要删除记录commit--2的前一次提交commit--1的提交ID

特别提示： rebase -i的ID一定是删除记录的前一次的提交ID

| git reflog | 


展示如下内容：

| b08ec3f HEAD@{4}: commit: commit--3 | 


拿到对应的提交ID为35f96e1

- 第二步：rebase操作

| git rebase -i 35f96e1 | 


- 执行完这个命令后，就可以看到 35f96e1 后的所有 commit 记录。如下图

- 默认是使用 vim 编辑器打开了commit log list。然后我们就可以针对我们不需要的某些 log 进行删除。

- 把原本的 pick 单词修改为 drop 就表示该ID对应的 commit log 我们需要删除。

- vim保存退出。

![](https://gitee.com/hxc8/images5/raw/master/img/202407180000735.jpg)

- 第三步：解决冲突，强制推送更新到远程

| git add .                    | 


再查看远程的提交记录，发现commit--2就没有了。