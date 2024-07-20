export https_proxy=[http://192.168.1.128:7890;export](http://192.168.1.128:7890;export) http_proxy=[http://192.168.1.128:7890;export](http://192.168.1.128:7890;export) all_proxy=socks5://192.168.1.128:7890

export https_proxy=[http://192.168.10.88:7890;export](http://192.168.10.88:7890;export) http_proxy=[http://192.168.10.88:7890;export](http://192.168.10.88:7890;export) all_proxy=socks5://192.168.10.88:7890

export https_proxy=[http://192.168.10.95:7890;export](http://192.168.10.95:7890;export) http_proxy=[http://192.168.10.95:7890;export](http://192.168.10.95:7890;export) all_proxy=socks5://192.168.10.95:7890

export https_proxy=[http://127.0.0.1:7890](http://127.0.0.1:7890) http_proxy=[http://127.0.0.1:7890](http://127.0.0.1:7890) all_proxy=socks5://127.0.0.1:7890

永久官网：shandianpro.com (目前已被污染需挂梯子访问)

备用地址：58sd.net

备用地址：58sd.me

备用地址：58sdyun.cc

备用地址：sdyun.xyz

备用地址：sdyunpro.cc

[https://www.ahdark.blog/som/1643.shtml](https://www.ahdark.blog/som/1643.shtml)  开机自启动

图形化：

[https://github.com/Fndroid/clash_for_windows_pkg/releases](https://github.com/Fndroid/clash_for_windows_pkg/releases)

目前最新版是 [Clash.for.Windows-0.20.23-x64-linux.tar.gz](https://github.com/Fndroid/clash_for_windows_pkg/releases/download/0.20.23/Clash.for.Windows-0.20.23-x64-linux.tar.gz)

解压 进入目录(我没在linux里面解压，windows里面解压后传到linux,启动后报日志没有权限，后来从linux解压就好了)

./cfw

![](https://gitee.com/hxc8/images6/raw/master/img/202407182358343.jpg)

------以前----------

wget [https://github.com/Dreamacro/clash/releases/download/v1.13.0/clash-linux-amd64-v1.13.0.gz](https://github.com/Dreamacro/clash/releases/download/v1.13.0/clash-linux-amd64-v1.13.0.gz)

gunzip clash-linux-amd64-v1.13.0.gz

mv clash-linux-amd64-v1.13.0 clash

mkdir clash_proxy

mv clash clash_proxy

cd clash_proxy

vim config.yaml，这个内容从windows clash那个profile获取

大概选择的问题件内容如下

```
mixed-port: 7890
allow-lan: true
bind-address: '*'
mode: rule
log-level: info
external-controller: '127.0.0.1:9091'
dns:
    enable: true
    ipv6: false
    default-nameserver: [223.5.5.5, 119.29.29.29]
    enhanced-mode: fake-ip
    fake-ip-range: 198.18.0.1/16
    use-hosts: true
    nameserver: ['https://doh.pub/dns-query', 'https://dns.alidns.com/dns-query']
    fallback: ['https://doh.dns.sb/dns-query', 'https://dns.cloudflare.com/dns-query', 'https://dns.twnic.tw/dns-query', 'tls://8.8.4.4:853']
    fallback-filter: { geoip: true, ipcidr: [240.0.0.0/4, 0.0.0.0/32] }
proxies:
    - { name: 'Lv2 香港 01 [1.5]', type: ss, server: v3-hk.aliyun-obsd.xyz, port: 5601, cipher: aes-128-gcm, password: b7b796b2-0580-470e-b203-afe13a34c349, udp: true }
    - { name: 'Lv2 香港 02 [1.5]', type: ss, server: v3-hk.aliyun-obsd.xyz, port: 5602, cipher: aes-128-gcm, password: b7b796b2-0580-470e-b203-afe13a34c349, udp: true }
    - { name: 'Lv2 香港 03 [1.5]', type: ss, server: v3-hk.aliyun-obsd.xyz, port: 5603, cipher: aes-128-gcm, password: b7b796b2-0580-470e-b203-afe13a34c349, udp: true }
    - { name: 'Lv2 香港 04 [1.5]', type: ss, server: v3-hk.aliyun-obsd.xyz, port: 5604, cipher: aes-128-gcm, password: b7b796b2-0580-470e-b203-afe13a34c349, udp: true }
    - { name: 'Lv2 香港 05 [1.5]', type: ss, server: v3-hk.aliyun-obsd.xyz, port: 5605, cipher: aes-128-gcm, password: b7b796b2-0580-470e-b203-afe13a34c349, udp: true }
    - { name: 'Lv2 香港 06 [1.5]', type: ss, server: v3-hk.aliyun-obsd.xyz, port: 5606, cipher: aes-128-gcm, password: b7b796b2-0580-470e-b203-afe13a34c349, udp: true }
    - { name: 'Lv2 香港 07 [1.5]', type: ss, server: v3-hk.aliyun-obsd.xyz, port: 5607, cipher: aes-128-gcm, password: b7b796b2-0580-470e-b203-afe13a34c349, udp: true }
    - { name: 'Lv2 香港 08 [1.5]', type: ss, server: v3-hk.aliyun-obsd.xyz, port: 5608, cipher: aes-128-gcm, password: b7b796b2-0580-470e-b203-afe13a34c349, udp: true }
    - { name: 'Lv2 日本 01 [1.5]', type: ss, server: v3-jp.aliyun-obsd.xyz, port: 5615, cipher: aes-128-gcm, password: b7b796b2-0580-470e-b203-afe13a34c349, udp: true }
    - { name: 'Lv2 日本 02 [1.5]', type: ss, server: v3-jp.aliyun-obsd.xyz, port: 5616, cipher: aes-128-gcm, password: b7b796b2-0580-470e-b203-afe13a34c349, udp: true }
    - { name: 'Lv2 日本 03 [1.5]', type: ss, server: v3-jp.aliyun-obsd.xyz, port: 5617, cipher: aes-128-gcm, password: b7b796b2-0580-470e-b203-afe13a34c349, udp: true }
    - { name: 'Lv2 日本 04 [1.5]', type: ss, server: v3-jp.aliyun-obsd.xyz, port: 5618, cipher: aes-128-gcm, password: b7b796b2-0580-470e-b203-afe13a34c349, udp: true }
    - { name: 'Lv2 日本 05 [1.5]', type: ss, server: v3-jp.aliyun-obsd.xyz, port: 5619, cipher: aes-128-gcm, password: b7b796b2-0580-470e-b203-afe13a34c349, udp: true }
    - { name: 'Lv2 日本 06 [1.5]', type: ss, server: v3-jp.aliyun-obsd.xyz, port: 5620, cipher: aes-128-gcm, password: b7b796b2-0580-470e-b203-afe13a34c349, udp: true }
    - { name: 'Lv2 新加坡 01 [1.5]', type: ss, server: v3-sg.aliyun-obsd.xyz, port: 5609, cipher: aes-128-gcm, password: b7b796b2-0580-470e-b203-afe13a34c349, udp: true }
    - { name: 'Lv2 新加坡 02 [1.5]', type: ss, server: v3-sg.aliyun-obsd.xyz, port: 5610, cipher: aes-128-gcm, password: b7b796b2-0580-470e-b203-afe13a34c349, udp: true }
    - { name: 'Lv2 新加坡 03 [1.5]', type: ss, server: v3-sg.aliyun-obsd.xyz, port: 5611, cipher: aes-128-gcm, password: b7b796b2-0580-470e-b203-afe13a34c349, udp: true }
    - { name: 'Lv2 新加坡 04 [1.5]', type: ss, server: v3-sg.aliyun-obsd.xyz, port: 5612, cipher: aes-128-gcm, password: b7b796b2-0580-470e-b203-afe13a34c349, udp: true }
    - { name: 'Lv2 新加坡 05 [1.5]', type: ss, server: v3-sg.aliyun-obsd.xyz, port: 5613, cipher: aes-128-gcm, password: b7b796b2-0580-470e-b203-afe13a34c349, udp: true }
    - { name: 'Lv2 新加坡 06 [1.5]', type: ss, server: v3-sg.aliyun-obsd.xyz, port: 5614, cipher: aes-128-gcm, password: b7b796b2-0580-470e-b203-afe13a34c349, udp: true }
    - { name: 'Lv2 美国 01 [1.5]', type: ss, server: v3-us.aliyun-obsd.xyz, port: 5627, cipher: aes-128-gcm, password: b7b796b2-0580-470e-b203-afe13a34c349, udp: true }
    - { name: 'Lv2 美国 02 [1.5]', type: ss, server: v3-us.aliyun-obsd.xyz, port: 5628, cipher: aes-128-gcm, password: b7b796b2-0580-470e-b203-afe13a34c349, udp: true }
    - { name: 'Lv2 美国 03 [1.5]', type: ss, server: v3-us.aliyun-obsd.xyz, port: 5629, cipher: aes-128-gcm, password: b7b796b2-0580-470e-b203-afe13a34c349, udp: true }
    - { name: 'Lv2 美国 04 [1.5]', type: ss, server: v3-us.aliyun-obsd.xyz, port: 5630, cipher: aes-128-gcm, password: b7b796b2-0580-470e-b203-afe13a34c349, udp: true }
    - { name: 'Lv2 美国 05 [1.5]', type: ss, server: v3-us.aliyun-obsd.xyz, port: 5631, cipher: aes-128-gcm, password: b7b796b2-0580-470e-b203-afe13a34c349, udp: true }
    - { name: 'Lv2 美国 06 [1.5]', type: ss, server: v3-us.aliyun-obsd.xyz, port: 5632, cipher: aes-128-gcm, password: b7b796b2-0580-470e-b203-afe13a34c349, udp: true }
    - { name: 'Lv2 台湾 01 [1.5]', type: ss, server: v3-tw.aliyun-obsd.xyz, port: 5621, cipher: aes-128-gcm, password: b7b796b2-0580-470e-b203-afe13a34c349, udp: true }
    - { name: 'Lv2 台湾 02 [1.5]', type: ss, server: v3-tw.aliyun-obsd.xyz, port: 5622, cipher: aes-128-gcm, password: b7b796b2-0580-470e-b203-afe13a34c349, udp: true }
    - { name: 'Lv2 台湾 03 [1.5]', type: ss, server: v3-tw.aliyun-obsd.xyz, port: 5623, cipher: aes-128-gcm, password: b7b796b2-0580-470e-b203-afe13a34c349, udp: true }
    - { name: 'Lv2 台湾 04 [1.5]', type: ss, server: v3-tw.aliyun-obsd.xyz, port: 5624, cipher: aes-128-gcm, password: b7b796b2-0580-470e-b203-afe13a34c349, udp: true }
    - { name: 'Lv2 台湾 05 [1.5]', type: ss, server: v3-tw.aliyun-obsd.xyz, port: 5625, cipher: aes-128-gcm, password: b7b796b2-0580-470e-b203-afe13a34c349, udp: true }
    - { name: 'Lv2 台湾 06 [1.5]', type: ss, server: v3-tw.aliyun-obsd.xyz, port: 5626, cipher: aes-128-gcm, password: b7b796b2-0580-470e-b203-afe13a34c349, udp: true }
proxy-groups:
    - { name: 闪电⚡, type: select, proxies: [自动选择, 故障转移, 'Lv2 香港 01 [1.5]', 'Lv2 香港 02 [1.5]', 'Lv2 香港 03 [1.5]', 'Lv2 香港 04 [1.5]', 'Lv2 香港 05 [1.5]', 'Lv2 香港 06 [1.5]', 'Lv2 香港 07 [1.5]', 'Lv2 香港 08 [1.5]', 'Lv2 日本 01 [1.5]', 'Lv2 日本 02 [1.5]', 'Lv2 日本 03 [1.5]', 'Lv2 日本 04 [1.5]', 'Lv2 日本 05 [1.5]', 'Lv2 日本 06 [1.5]', 'Lv2 新加坡 01 [1.5]', 'Lv2 新加坡 02 [1.5]', 'Lv2 新加坡 03 [1.5]', 'Lv2 新加坡 04 [1.5]', 'Lv2 新加坡 05 [1.5]', 'Lv2 新加坡 06 [1.5]', 'Lv2 美国 01 [1.5]', 'Lv2 美国 02 [1.5]', 'Lv2 美国 03 [1.5]', 'Lv2 美国 04 [1.5]', 'Lv2 美国 05 [1.5]', 'Lv2 美国 06 [1.5]', 'Lv2 台湾 01 [1.5]', 'Lv2 台湾 02 [1.5]', 'Lv2 台湾 03 [1.5]', 'Lv2 台湾 04 [1.5]', 'Lv2 台湾 05 [1.5]', 'Lv2 台湾 06 [1.5]'] }
    - { name: 自动选择, type: url-test, proxies: ['Lv2 香港 01 [1.5]', 'Lv2 香港 02 [1.5]', 'Lv2 香港 03 [1.5]', 'Lv2 香港 04 [1.5]', 'Lv2 香港 05 [1.5]', 'Lv2 香港 06 [1.5]', 'Lv2 香港 07 [1.5]', 'Lv2 香港 08 [1.5]', 'Lv2 日本 01 [1.5]', 'Lv2 日本 02 [1.5]', 'Lv2 日本 03 [1.5]', 'Lv2 日本 04 [1.5]', 'Lv2 日本 05 [1.5]', 'Lv2 日本 06 [1.5]', 'Lv2 新加坡 01 [1.5]', 'Lv2 新加坡 02 [1.5]', 'Lv2 新加坡 03 [1.5]', 'Lv2 新加坡 04 [1.5]', 'Lv2 新加坡 05 [1.5]', 'Lv2 新加坡 06 [1.5]', 'Lv2 美国 01 [1.5]', 'Lv2 美国 02 [1.5]', 'Lv2 美国 03 [1.5]', 'Lv2 美国 04 [1.5]', 'Lv2 美国 05 [1.5]', 'Lv2 美国 06 [1.5]', 'Lv2 台湾 01 [1.5]', 'Lv2 台湾 02 [1.5]', 'Lv2 台湾 03 [1.5]', 'Lv2 台湾 04 [1.5]', 'Lv2 台湾 05 [1.5]', 'Lv2 台湾 06 [1.5]'], url: 'http://www.gstatic.com/generate_204', interval: 86400 }
    - { name: 故障转移, type: fallback, proxies: ['Lv2 香港 01 [1.5]', 'Lv2 香港 02 [1.5]', 'Lv2 香港 03 [1.5]', 'Lv2 香港 04 [1.5]', 'Lv2 香港 05 [1.5]', 'Lv2 香港 06 [1.5]', 'Lv2 香港 07 [1.5]', 'Lv2 香港 08 [1.5]', 'Lv2 日本 01 [1.5]', 'Lv2 日本 02 [1.5]', 'Lv2 日本 03 [1.5]', 'Lv2 日本 04 [1.5]', 'Lv2 日本 05 [1.5]', 'Lv2 日本 06 [1.5]', 'Lv2 新加坡 01 [1.5]', 'Lv2 新加坡 02 [1.5]', 'Lv2 新加坡 03 [1.5]', 'Lv2 新加坡 04 [1.5]', 'Lv2 新加坡 05 [1.5]', 'Lv2 新加坡 06 [1.5]', 'Lv2 美国 01 [1.5]', 'Lv2 美国 02 [1.5]', 'Lv2 美国 03 [1.5]', 'Lv2 美国 04 [1.5]', 'Lv2 美国 05 [1.5]', 'Lv2 美国 06 [1.5]', 'Lv2 台湾 01 [1.5]', 'Lv2 台湾 02 [1.5]', 'Lv2 台湾 03 [1.5]', 'Lv2 台湾 04 [1.5]', 'Lv2 台湾 05 [1.5]', 'Lv2 台湾 06 [1.5]'], url: 'http://www.gstatic.com/generate_204', interval: 7200 }
rules:
    - 'DOMAIN,update.cdn-sd.xyz,DIRECT'
    - 'DOMAIN-SUFFIX,services.googleapis.cn,闪电⚡'
    - 'DOMAIN-SUFFIX,xn--ngstr-lra8j.com,闪电⚡'
    - 'DOMAIN,safebrowsing.urlsec.qq.com,DIRECT'
    - 'DOMAIN,safebrowsing.googleapis.com,DIRECT'
    - 'DOMAIN,developer.apple.com,闪电⚡'
    - 'DOMAIN-SUFFIX,digicert.com,闪电⚡'
    - 'DOMAIN,ocsp.apple.com,闪电⚡'
    - 'DOMAIN,ocsp.comodoca.com,闪电⚡'
    - 'DOMAIN,ocsp.usertrust.com,闪电⚡'
    - 'DOMAIN,ocsp.sectigo.com,闪电⚡'
    - 'DOMAIN,ocsp.verisign.net,闪电⚡'
    - 'DOMAIN-SUFFIX,apple-dns.net,闪电⚡'
    - 'DOMAIN,testflight.apple.com,闪电⚡'
    - 'DOMAIN,sandbox.itunes.apple.com,闪电⚡'
    - 'DOMAIN,itunes.apple.com,闪电⚡'
    - 'DOMAIN-SUFFIX,apps.apple.com,闪电⚡'
    - 'DOMAIN-SUFFIX,blobstore.apple.com,闪电⚡'
    - 'DOMAIN,cvws.icloud-content.com,闪电⚡'
    - 'DOMAIN-SUFFIX,mzstatic.com,DIRECT'
    - 'DOMAIN-SUFFIX,itunes.apple.com,DIRECT'
    - 'DOMAIN-SUFFIX,icloud.com,DIRECT'
    - 'DOMAIN-SUFFIX,icloud-content.com,DIRECT'
    - 'DOMAIN-SUFFIX,me.com,DIRECT'
    - 'DOMAIN-SUFFIX,aaplimg.com,DIRECT'
    - 'DOMAIN-SUFFIX,cdn20.com,DIRECT'
    - 'DOMAIN-SUFFIX,cdn-apple.com,DIRECT'
    - 'DOMAIN-SUFFIX,akadns.net,DIRECT'
    - 'DOMAIN-SUFFIX,akamaiedge.net,DIRECT'
    - 'DOMAIN-SUFFIX,edgekey.net,DIRECT'
    - 'DOMAIN-SUFFIX,mwcloudcdn.com,DIRECT'
    - 'DOMAIN-SUFFIX,mwcname.com,DIRECT'
    - 'DOMAIN-SUFFIX,apple.com,DIRECT'
    - 'DOMAIN-SUFFIX,apple-cloudkit.com,DIRECT'
    - 'DOMAIN-SUFFIX,apple-mapkit.com,DIRECT'
    - 'DOMAIN-SUFFIX,126.com,DIRECT'
    - 'DOMAIN-SUFFIX,126.net,DIRECT'
    - 'DOMAIN-SUFFIX,127.net,DIRECT'
    - 'DOMAIN-SUFFIX,163.com,DIRECT'
    - 'DOMAIN-SUFFIX,360buyimg.com,DIRECT'
    - 'DOMAIN-SUFFIX,36kr.com,DIRECT'
    - 'DOMAIN-SUFFIX,acfun.tv,DIRECT'
    - 'DOMAIN-SUFFIX,air-matters.com,DIRECT'
    - 'DOMAIN-SUFFIX,aixifan.com,DIRECT'
    - 'DOMAIN-KEYWORD,alicdn,DIRECT'
    - 'DOMAIN-KEYWORD,alipay,DIRECT'
    - 'DOMAIN-KEYWORD,taobao,DIRECT'
    - 'DOMAIN-SUFFIX,amap.com,DIRECT'
    - 'DOMAIN-SUFFIX,autonavi.com,DIRECT'
    - 'DOMAIN-KEYWORD,baidu,DIRECT'
    - 'DOMAIN-SUFFIX,bdimg.com,DIRECT'
    - 'DOMAIN-SUFFIX,bdstatic.com,DIRECT'
    - 'DOMAIN-SUFFIX,bilibili.com,DIRECT'
    - 'DOMAIN-SUFFIX,bilivideo.com,DIRECT'
    - 'DOMAIN-SUFFIX,caiyunapp.com,DIRECT'
    - 'DOMAIN-SUFFIX,clouddn.com,DIRECT'
    - 'DOMAIN-SUFFIX,cnbeta.com,DIRECT'
    - 'DOMAIN-SUFFIX,cnbetacdn.com,DIRECT'
    - 'DOMAIN-SUFFIX,cootekservice.com,DIRECT'
    - 'DOMAIN-SUFFIX,csdn.net,DIRECT'
    - 'DOMAIN-SUFFIX,ctrip.com,DIRECT'
    - 'DOMAIN-SUFFIX,dgtle.com,DIRECT'
    - 'DOMAIN-SUFFIX,dianping.com,DIRECT'
    - 'DOMAIN-SUFFIX,douban.com,DIRECT'
    - 'DOMAIN-SUFFIX,doubanio.com,DIRECT'
    - 'DOMAIN-SUFFIX,duokan.com,DIRECT'
    - 'DOMAIN-SUFFIX,easou.com,DIRECT'
    - 'DOMAIN-SUFFIX,ele.me,DIRECT'
    - 'DOMAIN-SUFFIX,feng.com,DIRECT'
    - 'DOMAIN-SUFFIX,fir.im,DIRECT'
    - 'DOMAIN-SUFFIX,frdic.com,DIRECT'
    - 'DOMAIN-SUFFIX,g-cores.com,DIRECT'
    - 'DOMAIN-SUFFIX,godic.net,DIRECT'
    - 'DOMAIN-SUFFIX,gtimg.com,DIRECT'
    - 'DOMAIN,cdn.hockeyapp.net,DIRECT'
    - 'DOMAIN-SUFFIX,hongxiu.com,DIRECT'
    - 'DOMAIN-SUFFIX,hxcdn.net,DIRECT'
    - 'DOMAIN-SUFFIX,iciba.com,DIRECT'
    - 'DOMAIN-SUFFIX,ifeng.com,DIRECT'
    - 'DOMAIN-SUFFIX,ifengimg.com,DIRECT'
    - 'DOMAIN-SUFFIX,ipip.net,DIRECT'
    - 'DOMAIN-SUFFIX,iqiyi.com,DIRECT'
    - 'DOMAIN-SUFFIX,jd.com,DIRECT'
    - 'DOMAIN-SUFFIX,jianshu.com,DIRECT'
    - 'DOMAIN-SUFFIX,knewone.com,DIRECT'
    - 'DOMAIN-SUFFIX,le.com,DIRECT'
    - 'DOMAIN-SUFFIX,lecloud.com,DIRECT'
    - 'DOMAIN-SUFFIX,lemicp.com,DIRECT'
    - 'DOMAIN-SUFFIX,licdn.com,DIRECT'
    - 'DOMAIN-SUFFIX,luoo.net,DIRECT'
    - 'DOMAIN-SUFFIX,meituan.com,DIRECT'
    - 'DOMAIN-SUFFIX,meituan.net,DIRECT'
    - 'DOMAIN-SUFFIX,mi.com,DIRECT'
    - 'DOMAIN-SUFFIX,miaopai.com,DIRECT'
    - 'DOMAIN-SUFFIX,microsoft.com,DIRECT'
    - 'DOMAIN-SUFFIX,microsoftonline.com,DIRECT'
    - 'DOMAIN-SUFFIX,miui.com,DIRECT'
    - 'DOMAIN-SUFFIX,miwifi.com,DIRECT'
    - 'DOMAIN-SUFFIX,mob.com,DIRECT'
    - 'DOMAIN-SUFFIX,netease.com,DIRECT'
    - 'DOMAIN-SUFFIX,office.com,DIRECT'
    - 'DOMAIN-SUFFIX,office365.com,DIRECT'
    - 'DOMAIN-KEYWORD,officecdn,DIRECT'
    - 'DOMAIN-SUFFIX,oschina.net,DIRECT'
    - 'DOMAIN-SUFFIX,ppsimg.com,DIRECT'
    - 'DOMAIN-SUFFIX,pstatp.com,DIRECT'
    - 'DOMAIN-SUFFIX,qcloud.com,DIRECT'
    - 'DOMAIN-SUFFIX,qdaily.com,DIRECT'
    - 'DOMAIN-SUFFIX,qdmm.com,DIRECT'
    - 'DOMAIN-SUFFIX,qhimg.com,DIRECT'
    - 'DOMAIN-SUFFIX,qhres.com,DIRECT'
    - 'DOMAIN-SUFFIX,qidian.com,DIRECT'
    - 'DOMAIN-SUFFIX,qihucdn.com,DIRECT'
    - 'DOMAIN-SUFFIX,qiniu.com,DIRECT'
    - 'DOMAIN-SUFFIX,qiniucdn.com,DIRECT'
    - 'DOMAIN-SUFFIX,qiyipic.com,DIRECT'
    - 'DOMAIN-SUFFIX,qq.com,DIRECT'
    - 'DOMAIN-SUFFIX,qqurl.com,DIRECT'
    - 'DOMAIN-SUFFIX,rarbg.to,DIRECT'
    - 'DOMAIN-SUFFIX,ruguoapp.com,DIRECT'
    - 'DOMAIN-SUFFIX,segmentfault.com,DIRECT'
    - 'DOMAIN-SUFFIX,sinaapp.com,DIRECT'
    - 'DOMAIN-SUFFIX,smzdm.com,DIRECT'
    - 'DOMAIN-SUFFIX,snapdrop.net,DIRECT'
    - 'DOMAIN-SUFFIX,sogou.com,DIRECT'
    - 'DOMAIN-SUFFIX,sogoucdn.com,DIRECT'
    - 'DOMAIN-SUFFIX,sohu.com,DIRECT'
    - 'DOMAIN-SUFFIX,soku.com,DIRECT'
    - 'DOMAIN-SUFFIX,speedtest.net,DIRECT'
    - 'DOMAIN-SUFFIX,sspai.com,DIRECT'
    - 'DOMAIN-SUFFIX,suning.com,DIRECT'
    - 'DOMAIN-SUFFIX,taobao.com,DIRECT'
    - 'DOMAIN-SUFFIX,tencent.com,DIRECT'
    - 'DOMAIN-SUFFIX,tenpay.com,DIRECT'
    - 'DOMAIN-SUFFIX,tianyancha.com,DIRECT'
    - 'DOMAIN-SUFFIX,tmall.com,DIRECT'
    - 'DOMAIN-SUFFIX,tudou.com,DIRECT'
    - 'DOMAIN-SUFFIX,umetrip.com,DIRECT'
    - 'DOMAIN-SUFFIX,upaiyun.com,DIRECT'
    - 'DOMAIN-SUFFIX,upyun.com,DIRECT'
    - 'DOMAIN-SUFFIX,veryzhun.com,DIRECT'
    - 'DOMAIN-SUFFIX,weather.com,DIRECT'
    - 'DOMAIN-SUFFIX,weibo.com,DIRECT'
    - 'DOMAIN-SUFFIX,xiami.com,DIRECT'
    - 'DOMAIN-SUFFIX,xiami.net,DIRECT'
    - 'DOMAIN-SUFFIX,xiaomicp.com,DIRECT'
    - 'DOMAIN-SUFFIX,ximalaya.com,DIRECT'
    - 'DOMAIN-SUFFIX,xmcdn.com,DIRECT'
    - 'DOMAIN-SUFFIX,xunlei.com,DIRECT'
    - 'DOMAIN-SUFFIX,yhd.com,DIRECT'
    - 'DOMAIN-SUFFIX,yihaodianimg.com,DIRECT'
    - 'DOMAIN-SUFFIX,yinxiang.com,DIRECT'
    - 'DOMAIN-SUFFIX,ykimg.com,DIRECT'
    - 'DOMAIN-SUFFIX,youdao.com,DIRECT'
    - 'DOMAIN-SUFFIX,youku.com,DIRECT'
    - 'DOMAIN-SUFFIX,zealer.com,DIRECT'
    - 'DOMAIN-SUFFIX,zhihu.com,DIRECT'
    - 'DOMAIN-SUFFIX,zhimg.com,DIRECT'
    - 'DOMAIN-SUFFIX,zimuzu.tv,DIRECT'
    - 'DOMAIN-SUFFIX,zoho.com,DIRECT'
    - 'DOMAIN-KEYWORD,amazon,闪电⚡'
    - 'DOMAIN-KEYWORD,google,闪电⚡'
    - 'DOMAIN-KEYWORD,gmail,闪电⚡'
    - 'DOMAIN-KEYWORD,youtube,闪电⚡'
    - 'DOMAIN-KEYWORD,facebook,闪电⚡'
    - 'DOMAIN-SUFFIX,fb.me,闪电⚡'
    - 'DOMAIN-SUFFIX,fbcdn.net,闪电⚡'
    - 'DOMAIN-KEYWORD,twitter,闪电⚡'
    - 'DOMAIN-KEYWORD,instagram,闪电⚡'
    - 'DOMAIN-KEYWORD,dropbox,闪电⚡'
    - 'DOMAIN-SUFFIX,twimg.com,闪电⚡'
    - 'DOMAIN-KEYWORD,blogspot,闪电⚡'
    - 'DOMAIN-SUFFIX,youtu.be,闪电⚡'
    - 'DOMAIN-KEYWORD,whatsapp,闪电⚡'
    - 'DOMAIN-KEYWORD,admarvel,REJECT'
    - 'DOMAIN-KEYWORD,admaster,REJECT'
    - 'DOMAIN-KEYWORD,adsage,REJECT'
    - 'DOMAIN-KEYWORD,adsmogo,REJECT'
    - 'DOMAIN-KEYWORD,adsrvmedia,REJECT'
    - 'DOMAIN-KEYWORD,adwords,REJECT'
    - 'DOMAIN-KEYWORD,adservice,REJECT'
    - 'DOMAIN-SUFFIX,appsflyer.com,REJECT'
    - 'DOMAIN-KEYWORD,domob,REJECT'
    - 'DOMAIN-SUFFIX,doubleclick.net,REJECT'
    - 'DOMAIN-KEYWORD,duomeng,REJECT'
    - 'DOMAIN-KEYWORD,dwtrack,REJECT'
    - 'DOMAIN-KEYWORD,guanggao,REJECT'
    - 'DOMAIN-KEYWORD,lianmeng,REJECT'
    - 'DOMAIN-SUFFIX,mmstat.com,REJECT'
    - 'DOMAIN-KEYWORD,mopub,REJECT'
    - 'DOMAIN-KEYWORD,omgmta,REJECT'
    - 'DOMAIN-KEYWORD,openx,REJECT'
    - 'DOMAIN-KEYWORD,partnerad,REJECT'
    - 'DOMAIN-KEYWORD,pingfore,REJECT'
    - 'DOMAIN-KEYWORD,supersonicads,REJECT'
    - 'DOMAIN-KEYWORD,uedas,REJECT'
    - 'DOMAIN-KEYWORD,umeng,REJECT'
    - 'DOMAIN-KEYWORD,usage,REJECT'
    - 'DOMAIN-SUFFIX,vungle.com,REJECT'
    - 'DOMAIN-KEYWORD,wlmonitor,REJECT'
    - 'DOMAIN-KEYWORD,zjtoolbar,REJECT'
    - 'DOMAIN-SUFFIX,9to5mac.com,闪电⚡'
    - 'DOMAIN-SUFFIX,abpchina.org,闪电⚡'
    - 'DOMAIN-SUFFIX,adblockplus.org,闪电⚡'
    - 'DOMAIN-SUFFIX,adobe.com,闪电⚡'
    - 'DOMAIN-SUFFIX,akamaized.net,闪电⚡'
    - 'DOMAIN-SUFFIX,alfredapp.com,闪电⚡'
    - 'DOMAIN-SUFFIX,amplitude.com,闪电⚡'
    - 'DOMAIN-SUFFIX,ampproject.org,闪电⚡'
    - 'DOMAIN-SUFFIX,android.com,闪电⚡'
    - 'DOMAIN-SUFFIX,angularjs.org,闪电⚡'
    - 'DOMAIN-SUFFIX,aolcdn.com,闪电⚡'
    - 'DOMAIN-SUFFIX,apkpure.com,闪电⚡'
    - 'DOMAIN-SUFFIX,appledaily.com,闪电⚡'
    - 'DOMAIN-SUFFIX,appshopper.com,闪电⚡'
    - 'DOMAIN-SUFFIX,appspot.com,闪电⚡'
    - 'DOMAIN-SUFFIX,arcgis.com,闪电⚡'
    - 'DOMAIN-SUFFIX,archive.org,闪电⚡'
    - 'DOMAIN-SUFFIX,armorgames.com,闪电⚡'
    - 'DOMAIN-SUFFIX,aspnetcdn.com,闪电⚡'
    - 'DOMAIN-SUFFIX,att.com,闪电⚡'
    - 'DOMAIN-SUFFIX,awsstatic.com,闪电⚡'
    - 'DOMAIN-SUFFIX,azureedge.net,闪电⚡'
    - 'DOMAIN-SUFFIX,azurewebsites.net,闪电⚡'
    - 'DOMAIN-SUFFIX,bing.com,闪电⚡'
    - 'DOMAIN-SUFFIX,bintray.com,闪电⚡'
    - 'DOMAIN-SUFFIX,bit.com,闪电⚡'
    - 'DOMAIN-SUFFIX,bit.ly,闪电⚡'
    - 'DOMAIN-SUFFIX,bitbucket.org,闪电⚡'
    - 'DOMAIN-SUFFIX,bjango.com,闪电⚡'
    - 'DOMAIN-SUFFIX,bkrtx.com,闪电⚡'
    - 'DOMAIN-SUFFIX,blog.com,闪电⚡'
    - 'DOMAIN-SUFFIX,blogcdn.com,闪电⚡'
    - 'DOMAIN-SUFFIX,blogger.com,闪电⚡'
    - 'DOMAIN-SUFFIX,blogsmithmedia.com,闪电⚡'
    - 'DOMAIN-SUFFIX,blogspot.com,闪电⚡'
    - 'DOMAIN-SUFFIX,blogspot.hk,闪电⚡'
    - 'DOMAIN-SUFFIX,bloomberg.com,闪电⚡'
    - 'DOMAIN-SUFFIX,box.com,闪电⚡'
    - 'DOMAIN-SUFFIX,box.net,闪电⚡'
    - 'DOMAIN-SUFFIX,cachefly.net,闪电⚡'
    - 'DOMAIN-SUFFIX,chromium.org,闪电⚡'
    - 'DOMAIN-SUFFIX,cl.ly,闪电⚡'
    - 'DOMAIN-SUFFIX,cloudflare.com,闪电⚡'
    - 'DOMAIN-SUFFIX,cloudfront.net,闪电⚡'
    - 'DOMAIN-SUFFIX,cloudmagic.com,闪电⚡'
    - 'DOMAIN-SUFFIX,cmail19.com,闪电⚡'
    - 'DOMAIN-SUFFIX,cnet.com,闪电⚡'
    - 'DOMAIN-SUFFIX,cocoapods.org,闪电⚡'
    - 'DOMAIN-SUFFIX,comodoca.com,闪电⚡'
    - 'DOMAIN-SUFFIX,crashlytics.com,闪电⚡'
    - 'DOMAIN-SUFFIX,culturedcode.com,闪电⚡'
    - 'DOMAIN-SUFFIX,d.pr,闪电⚡'
    - 'DOMAIN-SUFFIX,danilo.to,闪电⚡'
    - 'DOMAIN-SUFFIX,dayone.me,闪电⚡'
    - 'DOMAIN-SUFFIX,db.tt,闪电⚡'
    - 'DOMAIN-SUFFIX,deskconnect.com,闪电⚡'
    - 'DOMAIN-SUFFIX,disq.us,闪电⚡'
    - 'DOMAIN-SUFFIX,disqus.com,闪电⚡'
    - 'DOMAIN-SUFFIX,disquscdn.com,闪电⚡'
    - 'DOMAIN-SUFFIX,dnsimple.com,闪电⚡'
    - 'DOMAIN-SUFFIX,docker.com,闪电⚡'
    - 'DOMAIN-SUFFIX,dribbble.com,闪电⚡'
    - 'DOMAIN-SUFFIX,droplr.com,闪电⚡'
    - 'DOMAIN-SUFFIX,duckduckgo.com,闪电⚡'
    - 'DOMAIN-SUFFIX,dueapp.com,闪电⚡'
    - 'DOMAIN-SUFFIX,dytt8.net,闪电⚡'
    - 'DOMAIN-SUFFIX,edgecastcdn.net,闪电⚡'
    - 'DOMAIN-SUFFIX,edgekey.net,闪电⚡'
    - 'DOMAIN-SUFFIX,edgesuite.net,闪电⚡'
    - 'DOMAIN-SUFFIX,engadget.com,闪电⚡'
    - 'DOMAIN-SUFFIX,entrust.net,闪电⚡'
    - 'DOMAIN-SUFFIX,eurekavpt.com,闪电⚡'
    - 'DOMAIN-SUFFIX,evernote.com,闪电⚡'
    - 'DOMAIN-SUFFIX,fabric.io,闪电⚡'
    - 'DOMAIN-SUFFIX,fast.com,闪电⚡'
    - 'DOMAIN-SUFFIX,fastly.net,闪电⚡'
    - 'DOMAIN-SUFFIX,fc2.com,闪电⚡'
    - 'DOMAIN-SUFFIX,feedburner.com,闪电⚡'
    - 'DOMAIN-SUFFIX,feedly.com,闪电⚡'
    - 'DOMAIN-SUFFIX,feedsportal.com,闪电⚡'
    - 'DOMAIN-SUFFIX,fiftythree.com,闪电⚡'
    - 'DOMAIN-SUFFIX,firebaseio.com,闪电⚡'
    - 'DOMAIN-SUFFIX,flexibits.com,闪电⚡'
    - 'DOMAIN-SUFFIX,flickr.com,闪电⚡'
    - 'DOMAIN-SUFFIX,flipboard.com,闪电⚡'
    - 'DOMAIN-SUFFIX,g.co,闪电⚡'
    - 'DOMAIN-SUFFIX,gabia.net,闪电⚡'
    - 'DOMAIN-SUFFIX,geni.us,闪电⚡'
    - 'DOMAIN-SUFFIX,gfx.ms,闪电⚡'
    - 'DOMAIN-SUFFIX,ggpht.com,闪电⚡'
    - 'DOMAIN-SUFFIX,ghostnoteapp.com,闪电⚡'
    - 'DOMAIN-SUFFIX,git.io,闪电⚡'
    - 'DOMAIN-KEYWORD,github,闪电⚡'
    - 'DOMAIN-SUFFIX,globalsign.com,闪电⚡'
    - 'DOMAIN-SUFFIX,gmodules.com,闪电⚡'
    - 'DOMAIN-SUFFIX,godaddy.com,闪电⚡'
    - 'DOMAIN-SUFFIX,golang.org,闪电⚡'
    - 'DOMAIN-SUFFIX,gongm.in,闪电⚡'
    - 'DOMAIN-SUFFIX,goo.gl,闪电⚡'
    - 'DOMAIN-SUFFIX,goodreaders.com,闪电⚡'
    - 'DOMAIN-SUFFIX,goodreads.com,闪电⚡'
    - 'DOMAIN-SUFFIX,gravatar.com,闪电⚡'
    - 'DOMAIN-SUFFIX,gstatic.com,闪电⚡'
    - 'DOMAIN-SUFFIX,gvt0.com,闪电⚡'
    - 'DOMAIN-SUFFIX,hockeyapp.net,闪电⚡'
    - 'DOMAIN-SUFFIX,hotmail.com,闪电⚡'
    - 'DOMAIN-SUFFIX,icons8.com,闪电⚡'
    - 'DOMAIN-SUFFIX,ifixit.com,闪电⚡'
    - 'DOMAIN-SUFFIX,ift.tt,闪电⚡'
    - 'DOMAIN-SUFFIX,ifttt.com,闪电⚡'
    - 'DOMAIN-SUFFIX,iherb.com,闪电⚡'
    - 'DOMAIN-SUFFIX,imageshack.us,闪电⚡'
    - 'DOMAIN-SUFFIX,img.ly,闪电⚡'
    - 'DOMAIN-SUFFIX,imgur.com,闪电⚡'
    - 'DOMAIN-SUFFIX,imore.com,闪电⚡'
    - 'DOMAIN-SUFFIX,instapaper.com,闪电⚡'
    - 'DOMAIN-SUFFIX,ipn.li,闪电⚡'
    - 'DOMAIN-SUFFIX,is.gd,闪电⚡'
    - 'DOMAIN-SUFFIX,issuu.com,闪电⚡'
    - 'DOMAIN-SUFFIX,itgonglun.com,闪电⚡'
    - 'DOMAIN-SUFFIX,itun.es,闪电⚡'
    - 'DOMAIN-SUFFIX,ixquick.com,闪电⚡'
    - 'DOMAIN-SUFFIX,j.mp,闪电⚡'
    - 'DOMAIN-SUFFIX,js.revsci.net,闪电⚡'
    - 'DOMAIN-SUFFIX,jshint.com,闪电⚡'
    - 'DOMAIN-SUFFIX,jtvnw.net,闪电⚡'
    - 'DOMAIN-SUFFIX,justgetflux.com,闪电⚡'
    - 'DOMAIN-SUFFIX,kat.cr,闪电⚡'
    - 'DOMAIN-SUFFIX,klip.me,闪电⚡'
    - 'DOMAIN-SUFFIX,libsyn.com,闪电⚡'
    - 'DOMAIN-SUFFIX,linkedin.com,闪电⚡'
    - 'DOMAIN-SUFFIX,line-apps.com,闪电⚡'
    - 'DOMAIN-SUFFIX,linode.com,闪电⚡'
    - 'DOMAIN-SUFFIX,lithium.com,闪电⚡'
    - 'DOMAIN-SUFFIX,littlehj.com,闪电⚡'
    - 'DOMAIN-SUFFIX,live.com,闪电⚡'
    - 'DOMAIN-SUFFIX,live.net,闪电⚡'
    - 'DOMAIN-SUFFIX,livefilestore.com,闪电⚡'
    - 'DOMAIN-SUFFIX,llnwd.net,闪电⚡'
    - 'DOMAIN-SUFFIX,macid.co,闪电⚡'
    - 'DOMAIN-SUFFIX,macromedia.com,闪电⚡'
    - 'DOMAIN-SUFFIX,macrumors.com,闪电⚡'
    - 'DOMAIN-SUFFIX,mashable.com,闪电⚡'
    - 'DOMAIN-SUFFIX,mathjax.org,闪电⚡'
    - 'DOMAIN-SUFFIX,medium.com,闪电⚡'
    - 'DOMAIN-SUFFIX,mega.co.nz,闪电⚡'
    - 'DOMAIN-SUFFIX,mega.nz,闪电⚡'
    - 'DOMAIN-SUFFIX,megaupload.com,闪电⚡'
    - 'DOMAIN-SUFFIX,microsofttranslator.com,闪电⚡'
    - 'DOMAIN-SUFFIX,mindnode.com,闪电⚡'
    - 'DOMAIN-SUFFIX,mobile01.com,闪电⚡'
    - 'DOMAIN-SUFFIX,modmyi.com,闪电⚡'
    - 'DOMAIN-SUFFIX,msedge.net,闪电⚡'
    - 'DOMAIN-SUFFIX,myfontastic.com,闪电⚡'
    - 'DOMAIN-SUFFIX,name.com,闪电⚡'
    - 'DOMAIN-SUFFIX,nextmedia.com,闪电⚡'
    - 'DOMAIN-SUFFIX,nsstatic.net,闪电⚡'
    - 'DOMAIN-SUFFIX,nssurge.com,闪电⚡'
    - 'DOMAIN-SUFFIX,nyt.com,闪电⚡'
    - 'DOMAIN-SUFFIX,nytimes.com,闪电⚡'
    - 'DOMAIN-SUFFIX,omnigroup.com,闪电⚡'
    - 'DOMAIN-SUFFIX,onedrive.com,闪电⚡'
    - 'DOMAIN-SUFFIX,onenote.com,闪电⚡'
    - 'DOMAIN-SUFFIX,ooyala.com,闪电⚡'
    - 'DOMAIN-SUFFIX,openvpn.net,闪电⚡'
    - 'DOMAIN-SUFFIX,openwrt.org,闪电⚡'
    - 'DOMAIN-SUFFIX,orkut.com,闪电⚡'
    - 'DOMAIN-SUFFIX,osxdaily.com,闪电⚡'
    - 'DOMAIN-SUFFIX,outlook.com,闪电⚡'
    - 'DOMAIN-SUFFIX,ow.ly,闪电⚡'
    - 'DOMAIN-SUFFIX,paddleapi.com,闪电⚡'
    - 'DOMAIN-SUFFIX,parallels.com,闪电⚡'
    - 'DOMAIN-SUFFIX,parse.com,闪电⚡'
    - 'DOMAIN-SUFFIX,pdfexpert.com,闪电⚡'
    - 'DOMAIN-SUFFIX,periscope.tv,闪电⚡'
    - 'DOMAIN-SUFFIX,pinboard.in,闪电⚡'
    - 'DOMAIN-SUFFIX,pinterest.com,闪电⚡'
    - 'DOMAIN-SUFFIX,pixelmator.com,闪电⚡'
    - 'DOMAIN-SUFFIX,pixiv.net,闪电⚡'
    - 'DOMAIN-SUFFIX,playpcesor.com,闪电⚡'
    - 'DOMAIN-SUFFIX,playstation.com,闪电⚡'
    - 'DOMAIN-SUFFIX,playstation.com.hk,闪电⚡'
    - 'DOMAIN-SUFFIX,playstation.net,闪电⚡'
    - 'DOMAIN-SUFFIX,playstationnetwork.com,闪电⚡'
    - 'DOMAIN-SUFFIX,pushwoosh.com,闪电⚡'
    - 'DOMAIN-SUFFIX,rime.im,闪电⚡'
    - 'DOMAIN-SUFFIX,servebom.com,闪电⚡'
    - 'DOMAIN-SUFFIX,sfx.ms,闪电⚡'
    - 'DOMAIN-SUFFIX,shadowsocks.org,闪电⚡'
    - 'DOMAIN-SUFFIX,sharethis.com,闪电⚡'
    - 'DOMAIN-SUFFIX,shazam.com,闪电⚡'
    - 'DOMAIN-SUFFIX,skype.com,闪电⚡'
    - 'DOMAIN-SUFFIX,smartdns闪电⚡.com,闪电⚡'
    - 'DOMAIN-SUFFIX,smartmailcloud.com,闪电⚡'
    - 'DOMAIN-SUFFIX,sndcdn.com,闪电⚡'
    - 'DOMAIN-SUFFIX,sony.com,闪电⚡'
    - 'DOMAIN-SUFFIX,soundcloud.com,闪电⚡'
    - 'DOMAIN-SUFFIX,sourceforge.net,闪电⚡'
    - 'DOMAIN-SUFFIX,spotify.com,闪电⚡'
    - 'DOMAIN-SUFFIX,squarespace.com,闪电⚡'
    - 'DOMAIN-SUFFIX,sstatic.net,闪电⚡'
    - 'DOMAIN-SUFFIX,st.luluku.pw,闪电⚡'
    - 'DOMAIN-SUFFIX,stackoverflow.com,闪电⚡'
    - 'DOMAIN-SUFFIX,startpage.com,闪电⚡'
    - 'DOMAIN-SUFFIX,staticflickr.com,闪电⚡'
    - 'DOMAIN-SUFFIX,steamcommunity.com,闪电⚡'
    - 'DOMAIN-SUFFIX,symauth.com,闪电⚡'
    - 'DOMAIN-SUFFIX,symcb.com,闪电⚡'
    - 'DOMAIN-SUFFIX,symcd.com,闪电⚡'
    - 'DOMAIN-SUFFIX,tapbots.com,闪电⚡'
    - 'DOMAIN-SUFFIX,tapbots.net,闪电⚡'
    - 'DOMAIN-SUFFIX,tdesktop.com,闪电⚡'
    - 'DOMAIN-SUFFIX,techcrunch.com,闪电⚡'
    - 'DOMAIN-SUFFIX,techsmith.com,闪电⚡'
    - 'DOMAIN-SUFFIX,thepiratebay.org,闪电⚡'
    - 'DOMAIN-SUFFIX,theverge.com,闪电⚡'
    - 'DOMAIN-SUFFIX,time.com,闪电⚡'
    - 'DOMAIN-SUFFIX,timeinc.net,闪电⚡'
    - 'DOMAIN-SUFFIX,tiny.cc,闪电⚡'
    - 'DOMAIN-SUFFIX,tinypic.com,闪电⚡'
    - 'DOMAIN-SUFFIX,tmblr.co,闪电⚡'
    - 'DOMAIN-SUFFIX,todoist.com,闪电⚡'
    - 'DOMAIN-SUFFIX,trello.com,闪电⚡'
    - 'DOMAIN-SUFFIX,trustasiassl.com,闪电⚡'
    - 'DOMAIN-SUFFIX,tumblr.co,闪电⚡'
    - 'DOMAIN-SUFFIX,tumblr.com,闪电⚡'
    - 'DOMAIN-SUFFIX,tweetdeck.com,闪电⚡'
    - 'DOMAIN-SUFFIX,tweetmarker.net,闪电⚡'
    - 'DOMAIN-SUFFIX,twitch.tv,闪电⚡'
    - 'DOMAIN-SUFFIX,txmblr.com,闪电⚡'
    - 'DOMAIN-SUFFIX,typekit.net,闪电⚡'
    - 'DOMAIN-SUFFIX,ubertags.com,闪电⚡'
    - 'DOMAIN-SUFFIX,ublock.org,闪电⚡'
    - 'DOMAIN-SUFFIX,ubnt.com,闪电⚡'
    - 'DOMAIN-SUFFIX,ulyssesapp.com,闪电⚡'
    - 'DOMAIN-SUFFIX,urchin.com,闪电⚡'
    - 'DOMAIN-SUFFIX,usertrust.com,闪电⚡'
    - 'DOMAIN-SUFFIX,v.gd,闪电⚡'
    - 'DOMAIN-SUFFIX,v2ex.com,闪电⚡'
    - 'DOMAIN-SUFFIX,vimeo.com,闪电⚡'
    - 'DOMAIN-SUFFIX,vimeocdn.com,闪电⚡'
    - 'DOMAIN-SUFFIX,vine.co,闪电⚡'
    - 'DOMAIN-SUFFIX,vivaldi.com,闪电⚡'
    - 'DOMAIN-SUFFIX,vox-cdn.com,闪电⚡'
    - 'DOMAIN-SUFFIX,vsco.co,闪电⚡'
    - 'DOMAIN-SUFFIX,vultr.com,闪电⚡'
    - 'DOMAIN-SUFFIX,w.org,闪电⚡'
    - 'DOMAIN-SUFFIX,w3schools.com,闪电⚡'
    - 'DOMAIN-SUFFIX,webtype.com,闪电⚡'
    - 'DOMAIN-SUFFIX,wikiwand.com,闪电⚡'
    - 'DOMAIN-SUFFIX,wikileaks.org,闪电⚡'
    - 'DOMAIN-SUFFIX,wikimedia.org,闪电⚡'
    - 'DOMAIN-SUFFIX,wikipedia.com,闪电⚡'
    - 'DOMAIN-SUFFIX,wikipedia.org,闪电⚡'
    - 'DOMAIN-SUFFIX,windows.com,闪电⚡'
    - 'DOMAIN-SUFFIX,windows.net,闪电⚡'
    - 'DOMAIN-SUFFIX,wire.com,闪电⚡'
    - 'DOMAIN-SUFFIX,wordpress.com,闪电⚡'
    - 'DOMAIN-SUFFIX,workflowy.com,闪电⚡'
    - 'DOMAIN-SUFFIX,wp.com,闪电⚡'
    - 'DOMAIN-SUFFIX,wsj.com,闪电⚡'
    - 'DOMAIN-SUFFIX,wsj.net,闪电⚡'
    - 'DOMAIN-SUFFIX,xda-developers.com,闪电⚡'
    - 'DOMAIN-SUFFIX,xeeno.com,闪电⚡'
    - 'DOMAIN-SUFFIX,xiti.com,闪电⚡'
    - 'DOMAIN-SUFFIX,yahoo.com,闪电⚡'
    - 'DOMAIN-SUFFIX,yimg.com,闪电⚡'
    - 'DOMAIN-SUFFIX,ying.com,闪电⚡'
    - 'DOMAIN-SUFFIX,yoyo.org,闪电⚡'
    - 'DOMAIN-SUFFIX,ytimg.com,闪电⚡'
    - 'DOMAIN-SUFFIX,telegra.ph,闪电⚡'
    - 'DOMAIN-SUFFIX,telegram.org,闪电⚡'
    - 'IP-CIDR,91.108.4.0/22,闪电⚡,no-resolve'
    - 'IP-CIDR,91.108.8.0/21,闪电⚡,no-resolve'
    - 'IP-CIDR,91.108.16.0/22,闪电⚡,no-resolve'
    - 'IP-CIDR,91.108.56.0/22,闪电⚡,no-resolve'
    - 'IP-CIDR,149.154.160.0/20,闪电⚡,no-resolve'
    - 'IP-CIDR6,2001:67c:4e8::/48,闪电⚡,no-resolve'
    - 'IP-CIDR6,2001:b28:f23d::/48,闪电⚡,no-resolve'
    - 'IP-CIDR6,2001:b28:f23f::/48,闪电⚡,no-resolve'
    - 'IP-CIDR,120.232.181.162/32,闪电⚡,no-resolve'
    - 'IP-CIDR,120.241.147.226/32,闪电⚡,no-resolve'
    - 'IP-CIDR,120.253.253.226/32,闪电⚡,no-resolve'
    - 'IP-CIDR,120.253.255.162/32,闪电⚡,no-resolve'
    - 'IP-CIDR,120.253.255.34/32,闪电⚡,no-resolve'
    - 'IP-CIDR,120.253.255.98/32,闪电⚡,no-resolve'
    - 'IP-CIDR,180.163.150.162/32,闪电⚡,no-resolve'
    - 'IP-CIDR,180.163.150.34/32,闪电⚡,no-resolve'
    - 'IP-CIDR,180.163.151.162/32,闪电⚡,no-resolve'
    - 'IP-CIDR,180.163.151.34/32,闪电⚡,no-resolve'
    - 'IP-CIDR,203.208.39.0/24,闪电⚡,no-resolve'
    - 'IP-CIDR,203.208.40.0/24,闪电⚡,no-resolve'
    - 'IP-CIDR,203.208.41.0/24,闪电⚡,no-resolve'
    - 'IP-CIDR,203.208.43.0/24,闪电⚡,no-resolve'
    - 'IP-CIDR,203.208.50.0/24,闪电⚡,no-resolve'
    - 'IP-CIDR,220.181.174.162/32,闪电⚡,no-resolve'
    - 'IP-CIDR,220.181.174.226/32,闪电⚡,no-resolve'
    - 'IP-CIDR,220.181.174.34/32,闪电⚡,no-resolve'
    - 'DOMAIN,injections.adguard.org,DIRECT'
    - 'DOMAIN,local.adguard.org,DIRECT'
    - 'DOMAIN-SUFFIX,local,DIRECT'
    - 'IP-CIDR,127.0.0.0/8,DIRECT'
    - 'IP-CIDR,172.16.0.0/12,DIRECT'
    - 'IP-CIDR,192.168.0.0/16,DIRECT'
    - 'IP-CIDR,10.0.0.0/8,DIRECT'
    - 'IP-CIDR,17.0.0.0/8,DIRECT'
    - 'IP-CIDR,100.64.0.0/10,DIRECT'
    - 'IP-CIDR,224.0.0.0/4,DIRECT'
    - 'IP-CIDR6,fe80::/10,DIRECT'
    - 'DOMAIN-SUFFIX,cn,DIRECT'
    - 'DOMAIN-KEYWORD,-cn,DIRECT'
    - 'GEOIP,CN,DIRECT'
    - 'MATCH,闪电⚡'

```

![](https://gitee.com/hxc8/images6/raw/master/img/202407182358054.jpg)

这里我把9090改成了9091

sudo ./clash -d . 

![](https://gitee.com/hxc8/images6/raw/master/img/202407182358037.jpg)

暂时告一段落，windows上装个clash

[https://docs.cfw.lbyczf.com/](https://docs.cfw.lbyczf.com/)

![](https://gitee.com/hxc8/images6/raw/master/img/202407182358221.jpg)

上图红色框默认是127.0.0.1 改成宿主机IP，然后虚拟机改成桥接模式，然后再打开windows打开共享文件夹和打印机，

![](https://gitee.com/hxc8/images6/raw/master/img/202407182358670.jpg)

不打开的话，宿主机能ping通虚拟机，但是虚拟机ping不同宿主机

![](https://gitee.com/hxc8/images6/raw/master/img/202407182358621.jpg)

![](https://gitee.com/hxc8/images6/raw/master/img/202407182358713.jpg)

然后把127.0.0.1改成宿主机IP，大概如下，然后拷贝到虚拟机的终端，然后执行脚本。

export https_proxy=[http://192.168.1.128:7890;export](http://192.168.1.128:7890;export) http_proxy=[http://192.168.1.128:7890;export](http://192.168.1.128:7890;export) all_proxy=socks5://192.168.1.128:7890

我目前执行的脚本里面需要下载一个叫cypress的，这里还可以看到，应该是代理上了

![](https://gitee.com/hxc8/images6/raw/master/img/202407182358632.jpg)

[https://www.jianshu.com/p/365bbb5d5c85](https://www.jianshu.com/p/365bbb5d5c85)

# Linux 系统-Ubuntu上配置Clash及设置开机自启教程

## 1.前言

最近要从github上clone一些Linux系统的项目，忍受不了乌龟般的下载速度，于是搭建隧道。按照下面的流程绝对能成功，并且帖主在朋友的CentOS系统上也成功配置！

## 2. Clash下载

进入：[[https://github.com/Dreamacro/clash/releases]](https://links.jianshu.com/go?to=https%3A%2F%2Fgithub.com%2FDreamacro%2Fclash%2Freleases%255D)下载适合你的版本，通常选择的是以clash-linux-amd64打头的版本，即在X86机器上运行的版本。

![](D:/download/youdaonote-pull-master/data/Technology/存储/Ceph/自己学ceph/images/WEBRESOURCE9edbb169936c32707c15b8de3b341f68stickPicture.png)

Clash版本选择.jpg

下载后解压并更改文件夹名为Clash，里面的可执行文件更名为clash，如下图第一个文件所示（后两个文件是配置文件，将在后文中介绍）。

![](https://gitee.com/hxc8/images6/raw/master/img/202407182358502.jpg)

clash目录下文件.jpg

然后给予clash执行权限：

```
sudo chmod 555 clash

```

## 3. Clash配置

#### 3.1 config.yaml文件

上图第二个文件是配置文件，可以先从Windows平台获得，也可以直接打开浏览器下载。

**先介绍第一种方式：**

打开clash for windows，点击Profiles，输入Clash订阅链接（从代理商那儿获得），再点击下载即可得到配置文件。然后右键配置文件就可以打开其所在文件夹，通常在c:\用户\**用户名**\.config\clash\profiles\目录下。

![](https://gitee.com/hxc8/images6/raw/master/img/202407182358469.jpg)

获得配置文件.jpg

**第二种方式：**

打开浏览器，直接输入订阅链接下载配置文件。

将得到的配置文件复制到Clash目录下，并**重命名为config.yaml，注意后缀名必须为.yaml**！。然后你需要**检查**配置文件中的一些重要内容：

```
port: 7890
socks-port: 7891
allow-lan: true
mode: Rule
log-level: info
# 外部控制入口，设置为0.0.0.0:9090，这样从外网和内网都能进入
external-controller: '0.0.0.0:9090'
# 外部控制入口密码，自行设置。不想设密码就把下面一行注释掉即可。
secret: "123456"

```

#### 3.2 Country.mmdb文件

还需要下载Country.mmdb文件：[https://cdn.jsdelivr.net/gh/Dreamacro/maxmind-geoip@release/Country.mmdb](https://cdn.jsdelivr.net/gh/Dreamacro/maxmind-geoip@release/Country.mmdb)，同样移到Clash目录下就OK。

到这一步时，Clash目录下就有了以下三个文件：

![](https://gitee.com/hxc8/images6/raw/master/img/202407182358458.jpg)

clash目录下文件.jpg

得到这三个文件后，在该目录下打开shell输入：

```
# '-d'指示配置文件目录，由于在同一目录，后面跟'.'就好
sudo ./clash -d .

```

若成功运行则会显示如下：

![](https://gitee.com/hxc8/images6/raw/master/img/202407182358269.jpg)

运行成功.jpg

#### 3.3 外部控制设置

使用Linux的浏览器打开[https://clash.razord.top/](https://links.jianshu.com/go?to=https%3A%2F%2Fclash.razord.top%2F)，会弹出登录界面（我的火狐浏览器不知道为啥打不开，建议用其他浏览器）：

![](https://gitee.com/hxc8/images6/raw/master/img/202407182358314.jpg)

浏览器外部控制设置.jpg

依次输入:

127.0.0.1

9090

123456（config.yaml文件中设置的密码,若没有密码空着就好）

若浏览器打不开或者登录不上，可以直接跳过这一步，因为有的代理会自动选择节点！

最后，还需要设置Linux系统网络代理，通常在右上角：

![](https://gitee.com/hxc8/images6/raw/master/img/202407182358344.jpg)

网络代理.jpg

到这一步就已经可以畅快上网了！

## 4. 开机自启

### 4.1 创建并编辑clash.service脚本

```shell
# 获取root权限
su
# 创建clash.service脚本
touch /etc/systemd/system/clash.service
# 编辑
vi /etc/systemd/system/clash.service

```

脚本内容如下：

```csharp
[Unit]
Description=Clash service
After=network.target
[Service]
Type=simple
User=root
Group = root
DynamicUser = true
# /home/cx/clash/clash是可执行文件的路径；
# '-d'用于指定配置文件目录，我的配置文件在同一个目录下，因此后面跟/home/cx/clash
ExecStart=/home/cx/clash/clash -d /home/cx/clash
Restart=on-failure
RestartPreventExitStatus=23
[Install]
WantedBy=multi-user.target

```

### 4.2 设置自动启动

```shell
# 重新加载systemctl daemon
systemctl daemon-reload
# 设置开机自启
systemctl enable clash.service
# 立即启动clash服务
systemctl start clash.service

```

## 5. 还是连不上网的原因总结

1. 系统代理是否设置了？

1. 浏览器外部控制是否正确选择了节点？

作者：cx天王盖地虎

链接：[https://www.jianshu.com/p/365bbb5d5c85](https://www.jianshu.com/p/365bbb5d5c85)

来源：简书

著作权归作者所有。商业转载请联系作者获得授权，非商业转载请注明出处。