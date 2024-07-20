https://github.com/mickael-kerjean/filestash



https://www.filestash.app/2018/05/31/release-note-v0.1/



https://www.filestash.app/docs/







自己本地装好像只支持docker

https://www.filestash.app/docs/install-and-upgrade/#requirement



启动后



http://127.0.0.1:8334







后来研究了下，docker里面只是编译好的文件



然后就查阅了相关信息，在git上找到，可以通过在docker容器 里面编译



https://github.com/mickael-kerjean/filestash/issues/368



https://nodejs.org/zh-cn/download/releases/



https://github.com/mickael-kerjean/filestash/blob/master/CONTRIBUTING.md#building-from-source



```javascript
# Download the source
git clone https://github.com/mickael-kerjean/filestash
cd filestash

# Install dependencies
npm config set registry https://registry.npm.taobao.org
npm install # frontend dependencies
make build_init # install the required static libraries
mkdir -p ./dist/data/state/
cp -R config ./dist/data/state/

# Create the build
make build_frontend
make build_backend

# Run the program
./dist/filestash
```



```javascript
1  npm -v
    2  npm install -g npm@7.0.8
    3  npm -v
    4  npm install
    5  make build_init
    6   
    7  make build_frontend
    8  npm rebuild node-sass
    9  npm rebuild node-sass
   10  make build_backend
   11  make build_backend
   12  go build -o dist/filestash server/main.go
   13  make build_init
   14  mkdir -p ./dist/data/state/
   15  cp -R config ./dist/data/state/
   16  make build_frontend
   17  make build_backend
   18  cd server/plugin/
   19  ls
   20  cd plg_image_light/
   21  ls
   22  sh install.sh 
   23  cd deps/
   24  ls
   25  sh create_libtranscode.sh
   26  sh create_libresize.sh 
   27  cd
   28  ll
   29  ls
   30  cd /var/app/
   31  ls
   32  make build_backend
   33  go mod tidy
   34  make build_init
   35  mkdir -p ./dist/data/state/
   36  cp -R config ./dist/data/state/
   37  make build_frontend
   38  make build_backend
   39  /dist/filestash
   40  ll
   41  ./dist/filestash

```





本地运行





![](https://gitee.com/hxc8/images6/raw/master/img/202407190003160.jpg)





main.go



添加一行







![](https://gitee.com/hxc8/images6/raw/master/img/202407190003194.jpg)





index.go

删除一行





![](https://gitee.com/hxc8/images6/raw/master/img/202407190003424.jpg)



将上面docker启动后里面的静态文件拷贝出来。只要public目录

拷贝到main.go添加行指定的目录下面



复制覆盖这



![](https://gitee.com/hxc8/images6/raw/master/img/202407190003534.jpg)





然后就可以本地启动了



go run server/main.go





