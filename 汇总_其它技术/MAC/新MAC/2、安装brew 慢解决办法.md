
### 运行官方安装脚本
```
/bin/bash -c "$(curl -fsSL https://raw.githubusercontent.com/Homebrew/install/master/install.sh)"
```

### 执行后会出现问题
```
==> This script will install:
/usr/local/bin/brew
/usr/local/share/doc/homebrew
/usr/local/share/man/man1/brew.1
/usr/local/share/zsh/site-functions/_brew
/usr/local/etc/bash_completion.d/brew
/usr/local/Homebrew

Press RETURN to continue or any other key to abort
==> Downloading and installing Homebrew...
HEAD is now at 41b1faccb Merge pull request #7561 from Homebrew/dependabot/bundler/Library/Homebrew/mime-types-data-3.2020.0512
==> Tapping homebrew/core
Cloning into '/usr/local/Homebrew/Library/Taps/homebrew/homebrew-core'...
```

卡在这里 不用等了 直接退出，执行下面命令

```
cd /usr/local/Homebrew/Library/Taps
mkdir homebrew
cd homebrew
git clone https://mirrors.tuna.tsinghua.edu.cn/git/homebrew/homebrew-core.git
---
### 把Homebrew的镜像地址也设为清华大学的国内镜像

```
cd "$(brew --repo)"

git remote set-url origin https://mirrors.tuna.tsinghua.edu.cn/git/homebrew/brew.git

cd "$(brew --repo)/Library/Taps/homebrew/homebrew-core"

git remote set-url origin https://mirrors.tuna.tsinghua.edu.cn/git/homebrew/homebrew-core.git

brew update
```

#### 最终显示Already up-to-date就OK啦