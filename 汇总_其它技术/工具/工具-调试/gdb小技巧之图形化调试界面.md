开始就进入可视化界面 gdb -tui a.out

最开始执行gdb的时候没有开启，在进入后，可以输入tui enable    或者输入tui，会输出所有相关选项

```
(gdb) tui
"tui" must be followed by the name of a tui command.
List of tui subcommands:

tui disable -- Disable TUI display mode.
tui enable -- Enable TUI display mode.
tui reg -- TUI command to control the register window.

Type "help tui" followed by tui subcommand name for full documentation.
Type "apropos word" to search for commands related to "word".
Type "apropos -v word" for full documentation of commands related to "word".
Command name abbreviations are allowed if unambiguous.

```

如果想要看汇编代码输入:layout asm

同时看源码和汇编：layout split

显示寄存器窗口：layout regs

查看浮点寄存器：tui reg float

显示系统寄存器：tui reg system

切换回显示通用寄存器：tui reg general

layout：用于分割窗口，可以一边查看代码，一边测试。主要有以下几种用法：

layout src：显示源代码窗口

layout asm：显示汇编窗口

layout regs：显示源代码/汇编和寄存器窗口

layout split：显示源代码和汇编窗口

layout next：显示下一个layout

layout prev：显示上一个layout

Ctrl + L：刷新窗口

Ctrl + x，再按1：单窗口模式，显示一个窗口

Ctrl + x，再按2：双窗口模式，显示两个窗口

Ctrl + x，再按a：回到传统模式，即退出layout，回到执行layout之前的调试窗口。

调整窗口大小

指令：winheight <win_name> [+ | -]count

winheight缩写为win。win_name可以是src、cmd、asm和regs

窗口缩小：winheight src -5

窗口放大：winheight src +5