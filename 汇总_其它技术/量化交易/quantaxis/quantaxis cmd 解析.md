quantaxis cmd 解析

执行quantaxis进入到交互命令行

```
def QA_cmd():
    cli = CLI()
    cli.cmdloop()
```

CLI里面好多以do_开头的  do_xxx方法，比如：

```
def do_shell(self, arg):
    "run a shell commad"
    print(">", arg)
    sub_cmd = subprocess.Popen(arg, shell=True, stdout=subprocess.PIPE)
    print(sub_cmd.communicate()[0])

def do_version(self, arg):
    QA_util_log_info(__version__)
    
def do_crawl(self, arg):
    xxxxx
def do_save(self, arg):
    xxxxx
```

![](https://gitee.com/hxc8/images5/raw/master/img/202407172345613.jpg)

![](https://gitee.com/hxc8/images5/raw/master/img/202407172345916.jpg)

输入do_xxx 后面的xxx就能执行相应的命令。