问题： 公司网络需要设置代理，而家里却又不需要。每次都要到网络设置里选择启用或者取消代理，太麻烦了。

解决：写一个python3的启动脚本，自动根据本机IP地址进行判断。如发现是在公司，通过修改注册表自动启用代理；如检测到在家里，则取消代理。

首先安装好python3，到官网下载安装。http://python.org/

代码写好保存成python文件，将快捷方式放到菜单->启动 里面，windows开机时就会自动执行了。



具体代码如下：



import socket

import os

import winreg



def get_local_address():

    return socket.gethostbyname(socket.gethostname())

# get the ip address of this computer

ip = get_local_address()

#get the first 3 digits of ip

ipStart = ip[:3]

print(ip)

key = winreg.OpenKey(winreg.HKEY_CURRENT_USER,r"Software\Microsoft\Windows\CurrentVersion\Internet Settings",0,winreg.KEY_ALL_ACCESS) 

#in company

if ipStart == "XXX":

     print("proxy")

     winreg.SetValueEx(key,"ProxyEnable",0, winreg.REG_DWORD, 1)  

     winreg.SetValueEx(key,"ProxyServer",0, winreg.REG_SZ, "proxy:port") 

     winreg.SetValueEx(key,"ProxyOverride",0, winreg.REG_SZ, "*.company.com;<local>") 

#home

else:

     print("disable proxy")

     winreg.SetValueEx(key,"ProxyEnable",0, winreg.REG_DWORD, 0)  





import io, sys, time, re, os
import winreg


def enableProxy(IP, Port):
    proxy = IP + ":" + str(Port)
    xpath = "Software\Microsoft\Windows\CurrentVersion\Internet Settings"
    try:
        key = winreg.OpenKey(winreg.HKEY_CURRENT_USER, xpath, 0, winreg.KEY_WRITE)
        winreg.SetValueEx(key, "ProxyEnable", 0, winreg.REG_DWORD, 1)
        winreg.SetValueEx(key, "ProxyServer", 0, winreg.REG_SZ, proxy)
    except Exception as e:
        print("ERROR: " + str(e.args))
    finally:
        None

def disableProxy():
    proxy = ""
    xpath = "Software\Microsoft\Windows\CurrentVersion\Internet Settings"
    try:
        key = winreg.OpenKey(winreg.HKEY_CURRENT_USER, xpath, 0, winreg.KEY_WRITE)
        winreg.SetValueEx(key, "ProxyEnable", 0, winreg.REG_DWORD, 0)
        winreg.SetValueEx(key, "ProxyServer", 0, winreg.REG_SZ, proxy)
    except Exception as e:
        print("ERROR: " + str(e.args))
    finally:
        None

def main():
    proxyIP = "10.10.1.2"
    proxyPort = 123

    try:
        disableProxy()

        enableProxy(proxyIP, proxyPort)
    except Exception as e:
        print("ERROR: " + str(e.args))
    finally:
        pass


if __name__ == '__main__':
    main()









