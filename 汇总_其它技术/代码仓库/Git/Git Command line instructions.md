新版

### …or create a new repository on the command line

```javascript
echo "# cpp_jsoncpp_3rd" >> README.md
git init
git add README.md
git commit -m "first commit"
git branch -M main
git remote add origin https://github.com/xxxx/xxx.git
git push -u origin main
```

### …or push an existing repository from the command line

```javascript
git remote add origin https://github.com/xxx/xxx.git
git branch -M main
git push -u origin main
```





旧版

Git global setup

git config --global user.name "xxx"
git config --global user.email "xxx@qq.com"


Create a new repository

git clone git@gitlab.xxx/xxx.git
cd docker_hello_yml
touch README.md
git add README.md
git commit -m "add README"
git push -u origin master

Existing folder

cd existing_folder
git init
git remote add origin git@gitlab.xxx/xxx.git
git add .
git commit -m "Initial commit"
git push -u origin master

Existing Git repository

cd existing_repo
git remote rename origin old-origin
git remote add origin git@gitlab.xxx/xxx.git
git push -u origin --all
git push -u origin --tags