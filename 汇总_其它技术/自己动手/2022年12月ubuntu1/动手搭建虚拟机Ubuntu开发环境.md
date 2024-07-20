环境：

mac是10.15.7系统，前两天升级到最新，发现最常用的几个软件不能用了，官网的已经开始收费了，所以果断又降级回来。pase for mac 98

mac系统和数据是分开的，降级的时候不能直接从时间机器选择低版本恢复，要先安装一个全新的旧版本，然后再用时间机器恢复数据。

##### 1、安装VMware Fusion ，这里用的是VMware Fusion 12.1.0 for Mac.dmg

##### 2、安装Ubuntu，这里用的是国内源下载的ubuntu-18.04.6-desktop-amd64.iso

如果启动报错如下：

> 使用VMware fusion 安装虚拟机系统，一直显示“打不开 /dev/vmmon: 断裂管道 请确保已载入内核模块 ’vmmon’”


打开 设置 -> 安全性与隐私 -> 通用

“在允许从以下位置下载的应用”中，选择“任何来源”，同时会在下面显示关于fusion是否被允许，选在允许即可。

如果设置了，就关闭VMFusion，重新打开，再不行就重启下。

##### 3、设置系统

3-1、使用国内源

![](https://gitee.com/hxc8/images7/raw/master/img/202407190754928.jpg)

![](https://gitee.com/hxc8/images7/raw/master/img/202407190754618.jpg)

3-2、下载拼音输入法

![](https://gitee.com/hxc8/images7/raw/master/img/202407190754171.jpg)

![](https://gitee.com/hxc8/images7/raw/master/img/202407190754643.jpg)

![](https://gitee.com/hxc8/images7/raw/master/img/202407190754777.jpg)

安装完 重启下就行

##### 4、安装常用软件

4-1、安装vscode和常用插件

![](https://gitee.com/hxc8/images7/raw/master/img/202407190754121.jpg)

4-2、ssh

sudo apt-get update

su 

apt install vim -y

chmod +w /etc/sudoers

vim /etc/sudoers

```
root ALL=(ALL:ALL) ALL
ceph ALL=(ALL:ALL) ALL
```

chmod -w /etc/sudoers

> sudo usermod -aG sudo ceph 也可以达到目的


sudo apt-get install openssh-server git curl vim -y

4-4、访问github

sudo vi /etc/hosts

```
140.82.114.25                 alive.github.com
140.82.112.25                 live.github.com
185.199.108.154               github.githubassets.com
140.82.112.22                 central.github.com
185.199.108.133               desktop.githubusercontent.com
185.199.108.153               assets-cdn.github.com
185.199.108.133               camo.githubusercontent.com
185.199.108.133               github.map.fastly.net
199.232.69.194                github.global.ssl.fastly.net
140.82.112.4                  gist.github.com
185.199.108.153               github.io
140.82.114.4                  github.com
192.0.66.2                    github.blog
140.82.112.6                  api.github.com
185.199.108.133               raw.githubusercontent.com
185.199.108.133               user-images.githubusercontent.com
185.199.108.133               favicons.githubusercontent.com
185.199.108.133               avatars5.githubusercontent.com
185.199.108.133               avatars4.githubusercontent.com
185.199.108.133               avatars3.githubusercontent.com
185.199.108.133               avatars2.githubusercontent.com
185.199.108.133               avatars1.githubusercontent.com
185.199.108.133               avatars0.githubusercontent.com
185.199.108.133               avatars.githubusercontent.com
140.82.112.10                 codeload.github.com
52.217.223.17                 github-cloud.s3.amazonaws.com
52.217.199.41                 github-com.s3.amazonaws.com
52.217.93.164                 github-production-release-asset-2e65be.s3.amazonaws.com
52.217.174.129                github-production-user-asset-6210df.s3.amazonaws.com
52.217.129.153                github-production-repository-file-5c1aeb.s3.amazonaws.com
185.199.108.153               githubstatus.com
64.71.144.202                 github.community
23.100.27.125                 github.dev
185.199.108.133               media.githubusercontent.com
```

sudo apt install nscd

sudo /etc/init.d/nscd restart

4-5、zsh

sudo apt install zsh -y

chsh -s /bin/zsh

sh -c "$(curl -fsSL https://gitee.com/shmhlsy/oh-my-zsh-install.sh/raw/master/install.sh)"

vim install.sh

```

git clone https://github.com/zsh-users/zsh-syntax-highlighting.git ${ZSH_CUSTOM:-~/.oh-my-zsh/custom}/plugins/zsh-syntax-highlighting --depth=1
git clone https://github.com/zsh-users/zsh-history-substring-search ${ZSH_CUSTOM:-~/.oh-my-zsh/custom}/plugins/zsh-history-substring-search --depth=1
git clone https://github.com/zsh-users/zsh-autosuggestions ${ZSH_CUSTOM:-~/.oh-my-zsh/custom}/plugins/zsh-autosuggestions --depth=1
git clone https://github.com/MichaelAquilina/zsh-you-should-use.git $ZSH_CUSTOM/plugins/you-should-use --depth=1

cd ${ZSH_CUSTOM:-~/.oh-my-zsh/custom}/plugins
git clone https://github.com/joelthelion/autojump.git --depth=1
cd autojump
python3 install.py
```

./install.py 不行的话 就 python3 install.py

将

```
[[ -s /home/bob/.autojump/etc/profile.d/autojump.sh ]] && source /home/bob/.autojump/etc/profile.d/autojump.sh
```

加入到 ~/.zshrc末尾

如果报错是因为没切换到zsh

![](https://gitee.com/hxc8/images7/raw/master/img/202407190754259.jpg)

```
# stamp shown in the history command output.
# You can set one of the optional three formats:
# "mm/dd/yyyy"|"dd.mm.yyyy"|"yyyy-mm-dd"
# or set a custom format using the strftime function format specifications,
# see 'man strftime' for details.
# HIST_STAMPS="mm/dd/yyyy"

# Would you like to use another custom folder than $ZSH/custom?
# ZSH_CUSTOM=/path/to/new-custom-folder

# Which plugins would you like to load?
# Standard plugins can be found in $ZSH/plugins/
# Custom plugins may be added to $ZSH_CUSTOM/plugins/
# Example format: plugins=(rails git textmate ruby lighthouse)
# Add wisely, as too many plugins slow down shell startup.
plugins=(git zsh-autosuggestions sudo autojump zsh-syntax-highlighting history-substring-search z history extract you-should-use copypath)

source $ZSH/oh-my-zsh.sh

# User configuration

# export MANPATH="/usr/local/man:$MANPATH"

# You may need to manually set your language environment
# export LANG=en_US.UTF-8

# Preferred editor for local and remote sessions
# if [[ -n $SSH_CONNECTION ]]; then
#   export EDITOR='vim'
# else
#   export EDITOR='mvim'
# fi

# Compilation flags
# export ARCHFLAGS="-arch x86_64"

# Set personal aliases, overriding those provided by oh-my-zsh libs,
# plugins, and themes. Aliases can be placed here, though oh-my-zsh
# users are encouraged to define aliases within the ZSH_CUSTOM folder.
# For a full list of active aliases, run `alias`.
#
# Example aliases
# alias zshconfig="mate ~/.zshrc"
# alias ohmyzsh="mate ~/.oh-my-zsh"
alias vimzsh='vim ~/.zshrc'
alias vimhosts='sudo vim /etc/hosts'
alias sourcezsh='source ~/.zshrc'
[[ -s /home/ceph/.autojump/etc/profile.d/autojump.sh ]] && source /home/ceph/.autojump/etc/profile.d/autojump.sh
```

给root账户适配zsh

sudo su -

cp -r /home/bob/.oh-my-zsh . && cp -r /home/bob/.autojump . && cp -r /home/bob/.zshrc .

vim .zshrc

```
#export ZSH="/home/bob/.oh-my-zsh"
export ZSH="/root/.oh-my-zsh"
```

zsh    //切换到zsh  不行就试试  chsh -s /bin/zsh

发现执行 cd  会报错，因为找不到python 可以自行   ln -s /usr/bin/python3 /usr/bin/python

vim ~/.zshrc

```
alias vimzsh='vim ~/.zshrc'
alias sourcezsh='source ~/.zshrc'
alias vimhosts='sudo vim /etc/hosts'
```

下载linux clion

上传到用户根目录

tar zxvf CLion-2023.1.tar.gz  （得改成22年的版本，然后激活，再升级）

把激活工具的目录传到clion-2023.1下