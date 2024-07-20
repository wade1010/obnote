系统偏好设置 -> 安全性与隐私 -> 通用 -> 选择“任何来源”


显示"任何来源"选项在控制台中执行：

sudo spctl --master-disable

不显示"任何来源"选项（macOS 10.12默认为不显示）在控制台中执行：

sudo spctl --master-enable
