###### 1 使用以下命令生成SSH密钥对：

```
ssh-keygen
```

然后，按照提示输入密钥文件的名称和密码（如果需要），并将密钥保存在您选择的位置上。

###### 2 打开公钥文件，将公钥复制到剪贴板中：

```
cat ~/.ssh/id_rsa.pub
```

该命令将显示公钥内容。

###### 3 在目标机器上，使用以下命令创建~/.ssh/authorized_keys文件（如果该文件不存在）：

```
mkdir -p ~/.ssh && touch ~/.ssh/authorized_keys
```

###### 4 将复制的公钥粘贴到~/.ssh/authorized_keys文件中：

```
nano ~/.ssh/authorized_keys
```

然后，将公钥粘贴到文件中并保存。

###### 5 更改authorized_keys文件的权限，以确保只有您可以访问：

```
chmod 600 ~/.ssh/authorized_keysCopy
```

现在，您可以使用SSH免密登录到目标机器了。请确保您的私钥文件已添加到SSH代理中，以避免每次登录时都需要输入密码。