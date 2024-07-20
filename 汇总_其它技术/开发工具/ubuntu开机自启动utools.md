在 $HOME/.config下新建一个autostart文件夹

把 utools 的 .desktop 文件(可能在 /usr/share/applications 下)复制或者软链到 autostart 下都可以

mkdir ~/.config/autostart

ln -s /usr/share/applications/utools.desktop ~/.config/autostart