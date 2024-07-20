#### 1 安装crypto-js
npm install crypto-js

#### 2 js端
```JavaScript
import CryptoJS from 'crypto-js'
export default {
     decrypt(word) {
        let hash = CryptoJS.MD5(constConfig.AES_KEY_STR).toString();
        return JSON.parse(CryptoJS.AES.decrypt(word, hash).toString(CryptoJS.enc.Utf8));
    },
    encrypt(word) {
        word = JSON.stringify(word);
        let hash = CryptoJS.MD5(constConfig.AES_KEY_STR).toString();
        return CryptoJS.AES.encrypt(word, hash).toString();
    },
    
    
---------------------------------------------------------

    decrypt2(word) {//或者  这个包含参数iv
        let hash = CryptoJS.MD5(YOUR_AES_KEY_STR).toString();
        let iv = CryptoJS.MD5(YOUR_AES_KEY_STR.split("").reverse().join("")).toString();
        return JSON.parse(CryptoJS.AES.decrypt(word, hash, {
            iv: iv,
            mode: CryptoJS.mode.CBC,
            padding: CryptoJS.pad.Pkcs7
        }).toString(CryptoJS.enc.Utf8));


    },
    encrypt2(word) {//或者  这个包含参数iv
        word = JSON.stringify(word);
        let hash = CryptoJS.MD5(YOUR_AES_KEY_STR).toString();
        return CryptoJS.AES.encrypt(word, hash).toString();

        let iv = CryptoJS.MD5(YOUR_AES_KEY_STR.split("").reverse().join("")).toString();
        return CryptoJS.AES.encrypt(word, hash, {
            iv: iv,
            mode: CryptoJS.mode.CBC,
            padding: CryptoJS.pad.Pkcs7
        }).toString();
    }
}
```

#### 3 php端
我使用json传参数 格式如 {'cipherText':字符串(加密后的参数)}
```
$json = file_get_contents("php://input");
if (!empty($json)) {
    $params = json_decode($json, true);
    if ($cipherText = array_get($params, 'cipherText')) {
        $params = json_decode(EncryptUtil::decrypt($cipherText), true);//下面附上EncryptUtil类
    }
    ......code 渠道参数就可以做操作了......
}
```

##### EncryptUtil
```
<?php

class EncryptUtil
{
    const UID_AES_KEY = 'YOUR_AES_KEY_STR';

    public static function decrypt($data, $key = self::UID_AES_KEY)
    {
        $data = base64_decode($data);
        $hash = md5($key);
        $cipherText = substr($data, 16);
        $salt = substr($data, 8, 8);
        $rounds = 3;
        $hashSalt = $hash . $salt;
        $md5Hash[] = md5($hashSalt, true);
        $result = $md5Hash[0];
        for ($i = 1; $i < $rounds; $i++) {
            $md5Hash[$i] = md5($md5Hash[$i - 1] . $hashSalt, true);
            $result .= $md5Hash[$i];
        }
        $key = substr($result, 0, 32);
        $iv = substr($result, 32, 16);
        return openssl_decrypt($cipherText, 'aes-256-cbc', $key, true, $iv);
    }

    public static function encrypt($data, $key = self::UID_AES_KEY)
    {
        $hash = md5($key);
        $salt = openssl_random_pseudo_bytes(8);
        $salted = '';
        $dx = '';
        while (strlen($salted) < 48) {
            $dx = md5($dx . $hash . $salt, true);
            $salted .= $dx;
        }
        $key = substr($salted, 0, 32);
        $iv = substr($salted, 32, 16);
        $encryptedData = openssl_encrypt($data, 'aes-256-cbc', $key, OPENSSL_RAW_DATA, $iv);
        return base64_encode('Salted__' . $salt . $encryptedData);
    }
}
```


后端也可以是使用EncryptUtil里面的encrypt对返回的数据加密，前端再用上述js端里面的decrypt解密，从而达到前后端机密传输