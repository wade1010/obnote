一、推荐方法 CURL获取

<?php

$c = curl_init();

$url = 'www.jb51.net';

curl_setopt($c, CURLOPT_URL, $url);

curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);

$data = curl_exec($c);

curl_close($c);

$pos = strpos($data,'utf-8');

if($pos===false){$data = iconv("gbk","utf-8",$data);}

preg_match("/<title>(.*)<\/title>/i",$data, $title);

echo $title[1];

?>

二、使用file()函数

<?php

$lines_array = file('http://www.jb51.net/');

$lines_string = implode('', $lines_array);

$pos = strpos($lines_string,'utf-8');

if($pos===false){$lines_string = iconv("gbk","utf-8",$lines_string);}

eregi("<title>(.*)</title>", $lines_string, $title);

echo $title[1];

?>

三、使用file_get_contents

<?php

$content=file_get_contents("http://www.jb51.net/");

$pos = strpos($content,'utf-8');

if($pos===false){$content = iconv("gbk","utf-8",$content);}

$postb=strpos($content,'<title>')+7;

$poste=strpos($content,'</title>');

$length=$poste-$postb;

echo substr($content,$postb,$length);

?>