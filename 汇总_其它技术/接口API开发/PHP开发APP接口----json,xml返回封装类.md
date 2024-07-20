php以json或者xml 形式返回给app。明白这点就很好说了，就是把数据包装成json或者xml，返回给APP

定义抽象APP基类：

<?php/**
 * 定义API抽象类
*/abstractclassApi {const JSON = 'Json';
const XML = 'Xml';
const ARR = 'Array';
/**
* 定义工厂方法
* param string $type 返回数据类型
*/publicstaticfunctionfactory($type = self::JSON) {$type = isset($_GET['format']) ? $_GET['format'] : $type;
$resultClass = ucwords($type);
require_once('./Response/' . $type . '.php');
returnnew$resultClass();
}

abstractfunctionresponse($code, $message, $data);
}

以xml形式返回给APP：

<?phpclassXmlextendsApi {publicfunctionresponse($code, $message = '', $data = array()) {if(!is_numeric($code)) {
return'';
}
$result = array(
'code' => $code,
'message' => $message,
'data' => $data);
header('Content-Type:text/xml');
$xml = "<?xml version='1.0' encoding='UTF-8'?>\n";
$xml .= "<root>";
$xml .= self::xmlToEncode($result);
$xml .= "</root>";
echo$xml;
}
publicstaticfunctionxmlToEncode($result) {$xml = $attr = '';
foreach($resultas$key => $value) {
                    //判断键值对，如果是数字键值不允许if(is_numeric($key)) {
$attr = " id='" . $key . "'";
$key = "item";
}
$xml .= "<{$key}{$attr}>";
//以递归形式返回，主要是因为数组在xml中显示是array，必须显示出来具体键值对$xml .= is_array($value) ? self::xmlToEncode($value) : $value;
$xml .= "</{$key}>\n";
}
return$xml;
}
}


以json格式返回数据：

<?php/**
 * 按xml方式输出通信数据
*/classJsonextendsApi {publicfunctionresponse($code, $message = '', $data = array()) {if(!(is_numeric($code))) {
return'';
}

$result = array(
'code' => $code,
'message' => $message,
'data' => $data);

echo json_encode($result);
exit;
}
}

也可以采用这种方式组装返回数据：

<?phpclassResponse {const JSON = "json";
/**
* 按综合方式输出通信数据
* @param integer $code 状态码
* @param string $message 提示信息
* @param array $data 数据
* @param string $type 数据类型
* return string
*/publicstaticfunctionshow($code, $message = '', $data = array(), $type = self::JSON) {if(!is_numeric($code)) {
return'';
}

$type = isset($_GET['format']) ? $_GET['format'] : self::JSON;

$result = array(
'code' => $code,
'message' => $message,
'data' => $data,
);

if($type == 'json') {
self::json($code, $message, $data);
exit;
} elseif($type == 'array') { //适合调试代码var_dump($result);
} elseif($type == 'xml') {
self::xmlEncode($code, $message, $data);
exit;
} else {
// TODO}
}
/**
* 按json方式输出通信数据
* @param integer $code 状态码
* @param string $message 提示信息
* @param array $data 数据
* return string
*/publicstaticfunctionjson($code, $message = '', $data = array()) {if(!is_numeric($code)) {
return'';
}

$result = array(
'code' => $code,
'message' => $message,
'data' => $data);

echo json_encode($result);
exit;
}

/**
* 按xml方式输出通信数据
* @param integer $code 状态码
* @param string $message 提示信息
* @param array $data 数据
* return string
*/publicstaticfunctionxmlEncode($code, $message, $data = array()) {if(!is_numeric($code)) {
return'';
}

$result = array(
'code' => $code,
'message' => $message,
'data' => $data,
);

header("Content-Type:text/xml");
$xml = "<?xml version='1.0' encoding='UTF-8'?>\n";
$xml .= "<root>\n";

$xml .= self::xmlToEncode($result);

$xml .= "</root>";
echo$xml;
}

publicstaticfunctionxmlToEncode($data) {$xml = $attr = "";
foreach($dataas$key => $value) {
if(is_numeric($key)) {
$attr = " id='{$key}'";
$key = "item";
}
$xml .= "<{$key}{$attr}>";
$xml .= is_array($value) ? self::xmlToEncode($value) : $value;
$xml .= "</{$key}>\n";
}
return$xml;
}

}