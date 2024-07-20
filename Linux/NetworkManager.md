[https://access.redhat.com/documentation/zh-cn/red_hat_enterprise_linux/8/html/configuring_and_managing_networking/configuring-networkmanager-to-ignore-certain-devices_configuring-and-managing-networking](https://access.redhat.com/documentation/zh-cn/red_hat_enterprise_linux/8/html/configuring_and_managing_networking/configuring-networkmanager-to-ignore-certain-devices_configuring-and-managing-networking)

vim /usr/lib/NetworkManager/conf.d/10-globally-managed-devices.conf

```
[keyfile]
unmanaged-devices=interface-name:usb0,except:type:wifi,except:type:gsm,except:type:cdma
```

上面的interface-name通过nmcli device status来查看

systemctl reload NetworkManager