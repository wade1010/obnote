Git global setup
git config --global user.name "Administrator"
git config --global user.email "admin@example.com"

Create a new repository
git clone ssh://git@192.168.1.118:122/root/quantaxisc.git
cd quantaxisc
git switch -c main
touch README.md
git add README.md
git commit -m "add README"
git push -u origin main

Push an existing folder
cd existing_folder
git init --initial-branch=main
git remote add origin ssh://git@192.168.1.118:122/root/quantaxisc.git
git add .
git commit -m "Initial commit"
git push -u origin main

Push an existing Git repository
cd existing_repo
git remote rename origin old-origin
git remote add origin ssh://git@192.168.1.118:122/root/quantaxisc.git
git push -u origin --all
git push -u origin --tags





### 新版本


```
Git global setup
git config --global user.name "aaaaa"
git config --global user.email "aaaaa@qq.com"

Create a new repository
git clone git@39.104.111.111:aaaaa/test2.git
cd test2
git switch -c main
touch README.md
git add README.md
git commit -m "add README"
git push -u origin main

Push an existing folder
cd existing_folder
git init --initial-branch=main
git remote add origin git@39.104.162.143:aaaaa/test2.git
git add .
git commit -m "Initial commit"
git push -u origin main

Push an existing Git repository
cd existing_repo
git remote rename origin old-origin
git remote add origin git@39.104.162.143:aaaaa/test2.git
git push -u origin --all
git push -u origin --tags
```



### 老版本 以前是master为主分支

##### Create a new repository
```
git clone git@gitlab.com:xhcheng/vue-admin-system.git
cd vue-admin-system
touch README.md
git add README.md
git commit -m "add README"
git push -u origin master
```

#### Push an existing folder
```
cd existing_folder
git init
git remote add origin vue-admin-system.git
git add .
git commit -m "Initial commit"
git push -u origin master
```

#### Push an existing Git repository
```
cd existing_repo
git remote rename origin old-origin
git remote add origin git@gitlab.com:xhcheng/vue-admin-system.git
git push -u origin --all
git push -u origin --tags
```