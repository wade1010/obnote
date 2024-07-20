<?php

Encryption 和 Decrytion 默认使用AES-256 (rijndael-256-cbc)加密.

基本用法
//Create an instance
$crypt = new Phalcon\Crypt();
$key = 'le password';
$text = 'This is a secret text';
$encrypted = $crypt->encrypt($text, $key);
echo $crypt->decrypt($encrypted, $key);

你可以使用相同的方法多次加密:
//Create an instance
$crypt = new Phalcon\Crypt();
$texts = array(
    'my-key' => 'This is a secret text',
    'other-key' => 'This is a very secret'
);
foreach ($texts as $key => $text) {
    //Perform the encryption
    $encrypted = $crypt->encrypt($text, $key);
    //Now decrypt
    echo $crypt->decrypt($encrypted, $key);
}
-----------

加密参数
Name		Description
Cipher		The cipher is one of the encryption algorithms supported by libmcrypt. You can see a list here
Mode		One of the encryption modes supported by libmcrypt (ecb, cbc, cfb, ofb)
//Create an instance
$crypt = new Phalcon\Crypt();
//Use blowfish
$crypt->setCipher('blowfish');
$key = 'le password';
$text = 'This is a secret text';
echo $crypt->encrypt($text, $key);
------------

Base64 支持
为了正确的传输,可以通过BASE64编码
//Create an instance
$crypt = new Phalcon\Crypt();
$key = 'le password';
$text = 'This is a secret text';
$encrypt = $crypt->encryptBase64($text, $key);
echo $crypt->decryptBase64($text, $key);
------------

可以设置为DI
$di->set('crypt', function() {
    $crypt = new Phalcon\Crypt();
    //Set a global encryption key
    $crypt->setKey('%31.1e$i86e$f!8jz');
    return $crypt;
}, true);

