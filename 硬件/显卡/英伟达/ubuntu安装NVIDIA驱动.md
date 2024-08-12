您遇到的错误信息表明NVIDIA系统管理接口（nvidia-smi）无法与NVIDIA驱动程序通信。这通常意味着NVIDIA驱动程序没有正确安装或正在运行。以下是一些可能的解决方案：

1. **检查驱动程序安装**：
   - 确保您已经安装了适用于您的GPU的最新NVIDIA驱动程序。您可以访问NVIDIA官方网站，根据您的GPU型号下载并安装最新的驱动程序。

2. **重启系统**：
   - 有时候，简单地重启系统可以解决驱动程序通信问题。

3. **检查驱动程序状态**：
   - 在终端中输入以下命令来检查NVIDIA驱动程序是否正在运行：
     ```bash
     sudo lshw -C display
     ```
   - 或者使用以下命令查看内核模块是否加载：
     ```bash
     lsmod | grep nvidia
     ```

4. **卸载已经安装驱动程序**：
   - 如果驱动程序已安装但仍然无法通信，您可以尝试卸载并重新安装驱动程序。使用以下命令卸载驱动程序：
     ```bash
     sudo apt-get purge nvidia-*
     ```


执行 `sudo apt-get purge nvidia-*` 命令后，您已经卸载了所有与NVIDIA相关的软件包。接下来，您需要重新安装NVIDIA驱动程序。以下是安装NVIDIA驱动程序的一般步骤：

### 方法一：通过Ubuntu软件仓库安装

1. **更新软件包列表**：
   ```bash
   sudo apt-get update
   ```

2. **安装NVIDIA驱动程序**：
   - 查找可用的NVIDIA驱动程序：
     ```bash
     ubuntu-drivers devices
     ```
   - 选择推荐的驱动程序进行安装，例如：
     ```bash
     sudo apt-get install nvidia-driver-XXX
     ```
     其中 `XXX` 是推荐的驱动程序版本号。我一般选择最大的那个版本号

3. **重启系统（最好重启）**：
   ```bash
   sudo reboot
   ```

### 方法二：通过NVIDIA官方网站下载并安装

1. **下载驱动程序**：
   - 访问 [NVIDIA官方网站](https://www.nvidia.com/Download/index.aspx)，根据您的GPU型号和操作系统选择合适的驱动程序，并下载对应的 `.run` 文件。

2. **禁用Nouveau驱动程序**（如果存在）：
   - 编辑 `/etc/modprobe.d/blacklist-nouveau.conf` 文件：
     ```bash
     sudo nano /etc/modprobe.d/blacklist-nouveau.conf
     ```
   - 添加以下内容：
     ```
     blacklist nouveau
     options nouveau modeset=0
     ```
   - 更新initramfs：
     ```bash
     sudo update-initramfs -u
     ```

3. **重启系统并进入文本模式**：
   ```bash
   sudo reboot
   ```
   - 在启动时选择进入文本模式（通常是按 `Ctrl+Alt+F1` 或 `Ctrl+Alt+F2`）。

4. **停止图形界面服务**：
   ```bash
   sudo service lightdm stop
   ```

5. **安装驱动程序**：
   - 导航到下载的驱动程序文件所在的目录，并运行：
     ```bash
     sudo chmod +x NVIDIA-Linux-x86_64-XXX.YY.run
     sudo ./NVIDIA-Linux-x86_64-XXX.YY.run
     ```
   - 按照安装向导的提示完成安装。

6. **重启系统**：
   ```bash
   sudo reboot
   ```

### 验证安装

安装完成后，您可以使用以下命令验证NVIDIA驱动程序是否安装成功：

```bash
nvidia-smi
```

如果命令输出显示了GPU信息，说明驱动程序安装成功并且 `nvidia-smi` 能够正常通信。

希望这些步骤能帮助您成功安装NVIDIA驱动程序。如果遇到任何问题，请随时查阅NVIDIA的官方文档或寻求社区支持。