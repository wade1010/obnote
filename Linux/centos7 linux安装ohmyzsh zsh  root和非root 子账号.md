切换到root



vim /etc/sudoers







安装ohmyzsh前必须安装zsh



```javascript
yum install zsh
```



```javascript
sh -c "$(curl -fsSL https://raw.github.com/ohmyzsh/ohmyzsh/master/tools/install.sh)"
```



如果访问不了 参考下面解决



https://blog.csdn.net/wowbing2/article/details/105797442/   （里面的地址不能用力改成这个 http://ip.webmasterhome.cn/?ip=raw.githubusercontent.com）







手动按装2(1有时候半天clone不下来)

浏览器打开 https://github.com/robbyrussell/oh-my-zsh.git 下载zip包 然后复制到 ~/.oh-my-zsh-git 下

进入~/.oh-my-zsh-git/tools文件夹，执行



./install.sh



之后可以删除



---

安装autojump



git clone git://github.com/joelthelion/autojump.git





cd autojump

./install.py 安装



./uninstall.py  卸载





在 ~/.zshrc 中加入下面 代码



```javascript
[[ -s ~/.autojump/etc/profile.d/autojump.sh ]] && . ~/.autojump/etc/profile.d/autojump.sh
```





source ~/.zshrc





测试

autojump --version



---





安装 zsh-autosuggestions





git clone https://github.com/zsh-users/zsh-autosuggestions ${ZSH_CUSTOM:-~/.oh-my-zsh/custom}/plugins/zsh-autosuggestions





在 .zshrc中开头添加



ZSH_DISABLE_COMPFIX="true"



然后添加plugins里面的内容



plugins=(zsh-autosuggestions)





重新打开terminal





---



安装 zsh-syntax-highlighting



git clone https://github.com/zsh-users/zsh-syntax-highlighting.git ${ZSH_CUSTOM:-~/.oh-my-zsh/custom}/plugins/zsh-syntax-highlighting



添加到plugins



plugins=( [plugins...] zsh-syntax-highlighting)



重新打开terminal



---

安装  zsh-history-substring-search



git clone https://github.com/zsh-users/zsh-history-substring-search ${ZSH_CUSTOM:-~/.oh-my-zsh/custom}/plugins/zsh-history-substring-search






在 .zshrc中添加  （注意开头没有 zsh-）



plugins=(history-substring-search)





重新打开terminal



### history-substring-search

先介绍 zsh-history-substring-search。它的主要用途是什么？

一般情况下，在使用 zsh 时，通过 ↑ 或 ↓ 方向键，能实现类似按前缀匹配补齐的效果。

而如果输入的是中间的字符串，则没法自动补齐。这个插件真是为这个目的而生的。

使用这个插件前，除了启用插件以外，还需要进一步配置下，将 zsh-history-substring-search 提供的能力绑定到快捷按键。

例如，上下方向键 ↑ 和 ↓。

```text
bindkey '^[[A' history-substring-search-up
bindkey '^[[B' history-substring-search-down
```

在生效配置后，测试失败的话，查看文档，其中有介绍：

> However, if the observed values don't work, you can try using terminfo:bindkey "$terminfo[kcuu1]" history-substring-search-up bindkey "$terminfo[kcud1]" history-substring-search-down

那我们就增加这两行配置吧。

```text
bindkey "$terminfo[kcuu1]" history-substring-search-up
bindkey "$terminfo[kcud1]" history-substring-search-down
```

除了 ↑ ↓ 按键外，我一般还习惯使用 CTRL+P/N 上下查找历史记录，配置如下：

```text
bindkey '^p' history-substring-search-up
bindkey '^n' history-substring-search-down
```

如果希望支持 vi 的 jk，配置如下：

```text
bindkey -M vicmd 'k' history-substring-search-up
bindkey -M vicmd 'j' history-substring-search-up
```

上面说的配置保存在.zshrc里面即可

保存生效配置，测试下最终的成功成果吧。效果如下所示：

https://zhuanlan.zhihu.com/p/663838129



---



直接添加 vi-mode





plugins=(vi-mode)

---





给子账户也加上zsh



以root身份 cp





cp -r .oh-my-zsh /home/bob





cp -r .autojump /home/bob





chown -R bob:bob  /home/bob/.autojump



chown -R bob:bob  /home/bob/.oh-my-zsh



切到子账户bob



cd ~



vim .zshrc



```javascript
# Path to your oh-my-zsh installation.
#export ZSH="/root/.oh-my-zsh"
export ZSH="/home/bob/.oh-my-zsh"
```





vim .bash_profile 末尾加上下面代码



```javascript
export PATH
exec /bin/zsh -l
```











然后重启一个terminal 登录 即可







theme 改成 mira  可以看到是哪个用户 默认的看不到用户 不怎么好





或者自己去 https://github.com/ohmyzsh/ohmyzsh/wiki/Themes  找个自己喜欢的