[https://dlcdn.apache.org/maven/maven-3/3.6.3/binaries/apache-maven-3.6.3-bin.tar.gz](https://dlcdn.apache.org/maven/maven-3/3.6.3/binaries/apache-maven-3.6.3-bin.tar.gz)

我这里解压后发到了 /Users/bob/workspace/service/apache-maven-3.6.3目录

vim ~/.zshrc

export M2_HOME=/Users/bob/workspace/service/apache-maven-3.6.3

export PATH=$PATH:$M2_HOME/bin

source ~/.zshrc

mvn -v