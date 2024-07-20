此代码在jupiter上不起作用！！！

```
pd.set_option('display.max_rows', 500)
data1.sex

```

如果要查看所有行，请执行以下操作：

```
pd.options.display.max_rows = None
data1.sex

```

如果您只想看到60行：**推荐这个**

```
pd.set_option('display.max_rows', 100)
data1.sex.head(60)
```