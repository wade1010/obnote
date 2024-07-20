openssl genrsa -out private.key 2048

openssl req -new -x509 -days 3650 -key private.key -out public.crt -subj "/C=CN/ST=bj/L=oct/O=com/CN=192.168.1.10"



上面IP是本机IP



# 生成自签名证书





https://github.com/minio/minio/tree/master/docs/tls





生成后启动minio



minio server http://127.0.0.1/Users/bob/miniocluster/disk{1...4} --certs-dir /Users/bob/Desktop/tls  --console-address :19001 --address :19000



可以访问minio页面，但是登录不上



登录接口报错







![](https://gitee.com/hxc8/images6/raw/master/img/202407190004076.jpg)



```json
{
"code":500,
"detailedMessage":"Post "https://192.168.1.10:19000/": x509: certificate relies on legacy Common Name field, use SANs instead",
"message":"invalid Login"
}
```



发现是GO1.15   X509 被砍了（不能用了） ，需要用到SAN证书



看了下minion官方



https://github.com/minio/minio/tree/master/docs/tls#31-use-certgen-to-generate-a-certificate



发现这个可以生成SAN证书



https://github.com/minio/certgen/releases





## Example (server)

```javascript
certgen -host "127.0.0.1,localhost"

Created a new certificate 'public.crt', 'private.key' valid for the following names 📜
 - "127.0.0.1"
 - "localhost"
```

## Example (client)

```javascript
certgen -client -host "localhost"

Created a new certificate 'client.crt', 'client.key' valid for the following names 📜
 - "localhost"
```



wget https://github.com/minio/certgen/releases/download/v1.2.0/certgen-darwin-amd64



mv certgen-darwin-amd64 certgen



记得删掉之前的证书



./certgen -host "127.0.0.1,192.168.1.10"     (这里指定回环IP和本机IP)



```json
./certgen -host "127.0.0.1,192.168.1.10"
Created a new certificate 'public.crt', 'private.key' valid for the following names 📜
 - "127.0.0.1"
 - "192.168.1.10"
```



生成的证书未添加到根证书，https请求未携带数字证书会导致在SDK请求的时候出现如下错误。

# x509: certificate signed by unknown authority



sudo security add-trusted-cert -d -r trustRoot -k /Library/Keychains/System.keychain ./public.crt



ps 删掉现有证书:

sudo security delete-certificate -c "<现有证书的名称>"



其他系统添加证书到根证书请看

https://manuals.gfi.com/en/kerio/connect/content/server-configuration/ssl-certificates/adding-trusted-root-certificates-to-the-server-1605.html









certgen -client -host "127.0.0.1,192.168.1.10"









再次启动minio

minio server http://127.0.0.1/Users/bob/miniocluster/disk{1...4} --certs-dir /Users/bob/Desktop/tls  --console-address :19001 --address :19000







![](https://gitee.com/hxc8/images6/raw/master/img/202407190004123.jpg)



测试OEOS



./oeos server --node-conf local_dev/system.config --address :9000 --mock --certs-dir /Users/bob/Desktop/tls





![](https://gitee.com/hxc8/images6/raw/master/img/202407190004997.jpg)







