在 Linux 中使用 `screen` 命令可以创建多个终端会话，并且可以在这些会话之间切换。以下是如何在 `screen` 中创建新会话、切换到现有会话或创建新会话的步骤：

### 1. 创建一个新的 `screen` 会话

如果你还没有创建任何 `screen` 会话，或者你想创建一个新的会话，可以使用以下命令：

```bash
screen -S session_name
```

其中 `session_name` 是你为这个会话指定的名称。

### 2. 切换到另一个 `screen` 会话

如果你已经创建了多个 `screen` 会话，并且想在它们之间切换，可以使用以下命令：

```bash
screen -r session_name
```

其中 `session_name` 是你想切换到的会话名称。

### 3. 列出所有 `screen` 会话

如果你想查看当前所有 `screen` 会话的列表，可以使用以下命令：

```bash
screen -ls
```

这将显示所有当前运行的 `screen` 会话及其名称。

### 4. 在 `screen` 会话中创建新的会话

如果你已经在某个 `screen` 会话中，并且想创建一个新的会话，可以使用以下命令：

```bash
screen -S new_session_name
```

这将创建一个新的 `screen` 会话，并切换到该会话。

### 5. 在 `screen` 会话之间切换

如果你已经在某个 `screen` 会话中，并且想切换到另一个会话，可以使用以下快捷键：

- `Ctrl-a` 然后按 `"`：这将显示所有 `screen` 会话的列表，你可以选择一个会话进行切换。
- `Ctrl-a` 然后按 `n`：这将切换到下一个 `screen` 会话。
- `Ctrl-a` 然后按 `p`：这将切换到上一个 `screen` 会话。

### 6. 分离和重新连接 `screen` 会话

如果你在一个 `screen` 会话中，并且想暂时离开（分离），可以使用以下快捷键：

- `Ctrl-a` 然后按 `d`：这将分离当前 `screen` 会话，并返回到原始终端。

如果你想重新连接到一个分离的 `screen` 会话，可以使用以下命令：

```bash
screen -r session_name
```

### 示例流程

1. 创建一个新的 `screen` 会话并运行一个前端程序：

    ```bash
    screen -S frontend
    # 运行你的前端程序
    ```

2. 创建一个新的 `screen` 会话：

    ```bash
    screen -S backend
    # 运行你的后端程序
    ```

3. 切换到 `frontend` 会话：

    ```bash
    screen -r frontend
    ```

4. 切换到 `backend` 会话：

    ```bash
    screen -r backend
    ```

通过这些步骤，你可以在多个 `screen` 会话之间自由切换，并且可以同时运行多个程序。