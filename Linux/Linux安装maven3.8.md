wget https://mirrors.tuna.tsinghua.edu.cn/apache/maven/maven-3/3.8.1/binaries/apache-maven-3.8.1-bin.tar.gz

2022-10-07 10:15:10发现上面链接失效



wget https://mirrors.tuna.tsinghua.edu.cn/apache/maven/maven-3/3.6.3/binaries/apache-maven-3.6.3-bin.tar.gz



wget https://mirrors.tuna.tsinghua.edu.cn/apache/maven/maven-3/3.8.1/binaries/apache-maven-3.8.1-bin.tar.gz

tar zxvf apache-maven-3.8.1-bin.tar.gz 

mv apache-maven-3.8.1 /usr/local/maven

cd /usr/local/maven/

mkdir ck

cd conf/

vim settings.xml 



```javascript
<localRepository>/usr/local/maven/ck</localRepository>
<mirror>
  <id>alimaven</id>
  <name>aliyun maven</name>
   <url>http://maven.aliyun.com/nexus/content/groups/public/</url>
  <mirrorOf>central</mirrorOf>
</mirror>
```



vi /etc/profile

export MAVEN_HOME=/usr/local/maven

export PATH=$PATH:$MAVEN_HOME/bin

![](D:/download/youdaonote-pull-master/data/Technology/Linux/images/484192E480B54B14AEE497FA4356D4E9image.png)



source /etc/profile

mvn -v