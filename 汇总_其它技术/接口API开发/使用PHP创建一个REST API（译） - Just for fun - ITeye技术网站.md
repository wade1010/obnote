最近API在网络领域有些风靡，明确的说是REST的影响力。这实在没什么好惊讶的，因为在任何编程语言中，消费REST API都是非常的容易。构建它也非常的简单，因为本质上你不会用到任何那些已存在很久的HTTP细则。由于Rails对REST做出的深思熟虑的支持，包括提供和消费这些API（这已经被所有那些和我共事的Rails狂热者阐述过），我要赞美Rails，这样的事情并不常发生。

说真的，如果你从未使用过REST，但是使用过（或者更糟糕的，构建过）SOAP API，或仅仅开过一个WSDL并且将你报价单的头部分解过，伙计，我能有好消息告诉你吗。

那么，REST到底是什么？为什么你应该关注它？

在开始写代码前，我想要确保每个人对REST是什么以及它如何对API有利已经有了较好的认识。首先，从技术上来说，REST并不仅仅针对API，它更像一个通用的概念。然而明显的是，在这篇文章中，我们将在API的语境下谈论REST。所以，让我们来看看API的基本需求以及REST如何作用它们。

请求

所有的API都需要接受请求。有代表性的，对于一个RESTful API，你会拥有一种定义良好的URL模式。让我们假设你想要在你的网站上为用户提供一个API（我知道，我总是为我的例子使用“用户”的概念）。好的，你的URL结构可能类似于“api/users”和“api/users/[id]”这样，这取决于针对你的API被请求的操作类型。你还需要考虑要如何接受数据。目前大多数人正在使用JSON或XML，我个人更倾向于JSON，因为它与JavaScript配合使用更好些，而且PHP拥有简单的功能来编码和解码JSON。如果过去你希望你的API真正的稳健，你能够接受两者通过嗅探出请求的内容类型（例如：application/json 或 application/xml），但是更让人接受的是将内容类型限制成一种。真见鬼，如果你愿意你甚至可以使用简单的键/值对。

请求的另一块内容是它实际上做了什么，比如加载，保存等等。一般的，你不得不想出某种体系架构来定义请求者（消费者）请求的是什么动作，但是REST简化了这些。通过使用HTTP请求方法或动词，我们不需要定义任何东西。我们能够仅仅使用GET,POST,PUT和DELETE方法，这些包含了任何我们需要的请求。你可以将这些动词等价于标准的CRUD风格的东西：GET=加载/检索，POST=创建，PUT=更新，DELETE=delete。注意到这些动词不可以直接对应CRUD是重要的，但是这是一种理解它们的好方法。重新回到上面URL的例子，让我们看一看一些可能的请求意味着什么：

- GET request to /api/users – 列出所有用户

- GET request to /api/users/1 – 列出ID为1的用户信息

- POST request to /api/users – 创建一个新用户

- PUT request to /api/users/1 – 更新ID为1的用户信息

- DELETE request to /api/users/1 – 删除ID为1的用户信息

正如你希望看到的一样，REST通过一些简单，易于理解的标准和协议已经处理了很多在构建API时的主要的棘手问题，但对于一个良好的API还有另一块内容。

响应

因此，REST处理请求非常的容易，但它也容易生成响应。类似于请求，一个RESTful响应有两个主要的部件：响应主体和状态码。响应主体非常容易去处理。像请求一样，大多数REST上的响应或者是JSON文件或者是XML文件（可能在POST情况下仅仅是一个平面文件，这个我们将在之后提到）。同样的，像请求一样，通过另一部分HTTP请求细则—“Accept”，用户能够指定他们想要的响应类型。如果用户希望得到一个XML响应，他们可以仅仅发送一个Accept头信息“Accept: application/xml”作为请求的一部分。无可否认，这个方法没有被广泛的采用，所以你也能在URL中使用扩展的概念。例如，“/api/users.xml”意味着用户想要XML作为响应，类似的，“ /api/users.json”意味着用户要响应JSON（“/api/users/1.json/xml”同理）。无论你选择哪种方式，你都应该选择一种默认的响应类型，因为大多数情况人们甚至都不会告诉你他们想要的。再次声明，我会说选择JSON。如此，没有Accept头或扩展（例如：/api/users）也不应该失败，它应该仅仅以默认的响应类型做“容错”处理。

但是，错误和另外一些重要的与请求相关联的状态信息怎么办呢？这简单，使用HTTP状态码！这已经超过了我对于构建RESTful API的兴趣。通过使用HTTP状态码，你不需要为你的API想出一种“错误/成功”处理模式，这已经被实现了。例如，如果一个用户用POST方法发送“ /api/users”的请求，并且你想要返回一个成功的产物，简单的发送一个201状态码（201=被创造）就可以了。如果失败，发送500状态码（500=内部服务器错误），或者如果搞砸了发送400状态码（400=错误请求）。可能用户尝试用一些不被接受的post去攻击API端点，发送一个501状态码（不被执行）。或许你的MySQL服务器宕机了，因此你的API会被临时性的冻结，发送一个503状态码（服务器不可用）。希望你理解了这个思路。如果你想要阅读更多关于状态码的内容，在wikipedia上查阅它们：List of HTTP Status Codes。

我希望你了解了通过使用REST的概念构建你的API的所有优势。这真的非常的酷，这没有在PHP社区被广泛的讨论是一件耻辱的事（至少就我所讨论到的）。我认为很大部分原因是缺乏关于如何去处理GET或POST以外，也就是PUT和DELETE方法请求的好的文档。不可否认，处理它们确实有些蠢，但是这肯定不困难。我非常确认一些流行的框架里面很可能存在某种形式的REST实现，但我并不是一个狂热的框架迷（基于很多的原因以致于我不想加入），即使有人已经为你实现了解决方案，知道这些对你也是有好处的。



如果你仍然不确信这是一种有用的API范型，看看REST为RoR做了些什么。主要被标榜的一条是构建API将会如何的简单（通过一些RoR狂热者，我确信），事实上也确实如此。对于RoR我知之甚少，但是办公室周围的那些RoR迷多次的给我指出这点。但是，好吧我离题了，让我们写一些代码！

开始使用REST和PHP

一条最终的免责声明：我们将要看到的代码是不可能作为一种稳健的解决方案的例子的。在这里，我的主要目的是向你展示如何在PHP中处理REST的独立部件，将构建最终解决方案的权利留给你。



让我们向深处挖掘！我认为对于我们需要创建一个REST API这件事最好的做一些实际有用的事就是创建一个类，这个类将提供所有的工具函数。我们也会创建一个小类用来储存我们的数据。你也可以拿走它扩展它和在自己的需求中使用它。让我们贴一些代码：

Php代码

![](D:/download/youdaonote-pull-master/data/Technology/接口API开发/images/263A583B989B4B9BAFFBF75FD87E05B3icon_star.png)

1. class RestUtils  

1. {  

1. publicstaticfunction processRequest()  

1.     {  

1.     }  

1. publicstaticfunction sendResponse($status = 200, $body = '', $content_type = 'text/html')  

1.     {  

1.     }  

1. publicstaticfunction getStatusCodeMessage($status)  

1.     {  

1. // these could be stored in a .ini file and loaded

1. // via parse_ini_file()... however, this will suffice

1. // for an example

1. $codes = Array(  

1.             100 => 'Continue',  

1.             101 => 'Switching Protocols',  

1.             200 => 'OK',  

1.             201 => 'Created',  

1.             202 => 'Accepted',  

1.             203 => 'Non-Authoritative Information',  

1.             204 => 'No Content',  

1.             205 => 'Reset Content',  

1.             206 => 'Partial Content',  

1.             300 => 'Multiple Choices',  

1.             301 => 'Moved Permanently',  

1.             302 => 'Found',  

1.             303 => 'See Other',  

1.             304 => 'Not Modified',  

1.             305 => 'Use Proxy',  

1.             306 => '(Unused)',  

1.             307 => 'Temporary Redirect',  

1.             400 => 'Bad Request',  

1.             401 => 'Unauthorized',  

1.             402 => 'Payment Required',  

1.             403 => 'Forbidden',  

1.             404 => 'Not Found',  

1.             405 => 'Method Not Allowed',  

1.             406 => 'Not Acceptable',  

1.             407 => 'Proxy Authentication Required',  

1.             408 => 'Request Timeout',  

1.             409 => 'Conflict',  

1.             410 => 'Gone',  

1.             411 => 'Length Required',  

1.             412 => 'Precondition Failed',  

1.             413 => 'Request Entity Too Large',  

1.             414 => 'Request-URI Too Long',  

1.             415 => 'Unsupported Media Type',  

1.             416 => 'Requested Range Not Satisfiable',  

1.             417 => 'Expectation Failed',  

1.             500 => 'Internal Server Error',  

1.             501 => 'Not Implemented',  

1.             502 => 'Bad Gateway',  

1.             503 => 'Service Unavailable',  

1.             504 => 'Gateway Timeout',  

1.             505 => 'HTTP Version Not Supported'

1.         );  

1. return (isset($codes[$status])) ? $codes[$status] : '';  

1.     }  

1. }  

1. class RestRequest  

1. {  

1. private$request_vars;  

1. private$data;  

1. private$http_accept;  

1. private$method;  

1. publicfunction __construct()  

1.     {  

1. $this->request_vars      = array();  

1. $this->data              = '';  

1. $this->http_accept       = (strpos($_SERVER['HTTP_ACCEPT'], 'json')) ? 'json' : 'xml';  

1. $this->method            = 'get';  

1.     }  

1. publicfunction setData($data)  

1.     {  

1. $this->data = $data;  

1.     }  

1. publicfunction setMethod($method)  

1.     {  

1. $this->method = $method;  

1.     }  

1. publicfunction setRequestVars($request_vars)  

1.     {  

1. $this->request_vars = $request_vars;  

1.     }  

1. publicfunction getData()  

1.     {  

1. return$this->data;  

1.     }  

1. publicfunction getMethod()  

1.     {  

1. return$this->method;  

1.     }  

1. publicfunction getHttpAccept()  

1.     {  

1. return$this->http_accept;  

1.     }  

1. publicfunction getRequestVars()  

1.     {  

1. return$this->request_vars;  

1.     }  

1. }  

好的，我们已经得到了一个用来保存一些关于请求（REST请求）信息的简单类，我们可以利用这个类中的一些静态方法去处理请求和响应。正如你看到的，我们确实仅有两个方法要写。这是整件事情最美的地方！好的，让我们继续。

处理请求

处理请求是相当直接的，但是在这里我们能遇到一些猎物（即：PUT和DELETE等，多数是PUT）。我们将在某个时刻重温他们，但现在让我们检查下RestRequest类。如果你注意到了构造函数，你就会看到我们已经解释了HTTP_ACCEPT头部，如果没被提供，将默认为JSON。有了这样的方式，我们只需要处理传入的数据。

我们有很多的方式去做这个，但是假设我们总是会在请求中得到一个键值对：‘数据’=> 实际的数据。同样假设实际的数据是JSON。在我之前对REST的解释中，你能够看到请求的内容类型和或者JSON或者XML的处理方式，但是现在我们应该尽量让其简单。因此，我们处理请求的方法最终看起来像这样：

Php代码

![](D:/download/youdaonote-pull-master/data/Technology/接口API开发/images/11A13FA5EFD24F26A6F842712360FDD6icon_star.png)

1. publicstaticfunction processRequest()  

1.     {  

1. // get our verb

1. $request_method = strtolower($_SERVER['REQUEST_METHOD']);  

1. $return_obj     = new RestRequest();  

1. // we'll store our data here

1. $data           = array();  

1. switch ($request_method)  

1.         {  

1. // gets are easy...

1. case'get':  

1. $data = $_GET;  

1. break;  

1. // so are posts

1. case'post':  

1. $data = $_POST;  

1. break;  

1. // here's the tricky bit...

1. case'put':  

1. // basically, we read a string from PHP's special input location,

1. // and then parse it out into an array via parse_str... per the PHP docs:

1. // Parses str  as if it were the query string passed via a URL and sets

1. // variables in the current scope.

1. parse_str(file_get_contents('php://input'), $put_vars);  

1. $data = $put_vars;  

1. break;  

1.         }  

1. // store the method

1. $return_obj->setMethod($request_method);  

1. // set the raw data, so we can access it if needed (there may be

1. // other pieces to your requests)

1. $return_obj->setRequestVars($data);  

1. if(isset($data['data']))  

1.         {  

1. // translate the JSON to an Object for use however you want

1. $return_obj->setData(json_decode($data['data']));  

1.         }  

1. return$return_obj;  

1.     }  

就像我说的，非常的直接。然而，有些事情要注意。首先，特别的对于DELETE请求不可以接受数据，因此我们在switch中没有对应的case。第二点，你会注意到我们储存了请求变量和解析过的JSON数据这两者。随着你可能有另外的东西作为你的请求的一部分（一个API键或其他什么东西）时这非常有用，这些东西本身并不是真实的数据（像用户的名字，邮箱，等等）。



那么，我们如何使用这个呢？让我们回到用户例子。假设你已经为用户将你的请求路由到正确的控制器了，我们会有一些这样的代码：

Php代码

![](D:/download/youdaonote-pull-master/data/Technology/接口API开发/images/D5751FFCFE4C44D4B32BD2A2E425E57Aicon_star.png)

1. $data = RestUtils::processRequest();  

1. switch($data->getMethod)  

1. {  

1. case'get':  

1. // retrieve a list of users

1. break;  

1. case'post':  

1. $user = new User();  

1. $user->setFirstName($data->getData()->first_name);  // just for example, this should be done cleaner

1. // and so on...

1. $user->save();  

1. break;  

1. // etc, etc, etc...

1. }  

请不要在真正的应用程序中这样做，这只是一个应急的例子。你会想把这个封装在一个任何东西都已被合适抽象的很好的控制结构里，但是这个应该帮助你理解了如何使用这个素材。好吧，我离题了，让我们进入到发送响应部分。

发送响应

现在我们能解释请求了，让我们往前到发送响应部分。我们已经知道实际需要做的是发送正确状态码，可能有一些主体（例如，如果是GET请求），但是会有一个重要的捕获对于那些没有主体的响应。假如某人用一个不存在的用户请求攻击我们简单地用户API（如：api/user/123）。在这种情况下，发送404状态码是合适的，但是简单地在头部里发送状态码是不够的。如果在你的浏览器中查看这个页面，你将会看到空白页。这是因为Apache（或者其它运行着的Web服务器）没有发送状态码，所以没有状态页面。我们需要考虑这些当构建我们的方法的时候。记住这些，下面是大致的代码：

Php代码

![](D:/download/youdaonote-pull-master/data/Technology/接口API开发/images/5908A3C226CE46C5990FD550B8097243icon_star.png)

1. publicstaticfunction sendResponse($status = 200, $body = '', $content_type = 'text/html')  

1.     {  

1. $status_header = 'HTTP/1.1 ' . $status . ' ' . RestUtils::getStatusCodeMessage($status);  

1. // set the status

1.         header($status_header);  

1. // set the content type

1.         header('Content-type: ' . $content_type);  

1. // pages with body are easy

1. if($body != '')  

1.         {  

1. // send the body

1. echo$body;  

1. exit;  

1.         }  

1. // we need to create the body if none is passed

1. else

1.         {  

1. // create some body messages

1. $message = '';  

1. // this is purely optional, but makes the pages a little nicer to read

1. // for your users.  Since you won't likely send a lot of different status codes,

1. // this also shouldn't be too ponderous to maintain

1. switch($status)  

1.             {  

1. case 401:  

1. $message = 'You must be authorized to view this page.';  

1. break;  

1. case 404:  

1. $message = 'The requested URL ' . $_SERVER['REQUEST_URI'] . ' was not found.';  

1. break;  

1. case 500:  

1. $message = 'The server encountered an error processing your request.';  

1. break;  

1. case 501:  

1. $message = 'The requested method is not implemented.';  

1. break;  

1.             }  

1. // servers don't always have a signature turned on (this is an apache directive "ServerSignature On")

1. $signature = ($_SERVER['SERVER_SIGNATURE'] == '') ? $_SERVER['SERVER_SOFTWARE'] . ' Server at ' . $_SERVER['SERVER_NAME'] . ' Port ' . $_SERVER['SERVER_PORT'] : $_SERVER['SERVER_SIGNATURE'];  

1. // this should be templatized in a real-world solution

1. $body = '"-//W3C//DTD HTML 4.01//EN""http://www.w3.org/TR/html4/strict.dtd">  

1.                           

1.                               

1.                                  "Content-Type"  content="text/html; charset=iso-8859-1">  

1.                                 <span style='font-size:12px;font-style:normal;font-weight:normal;font-family:Monaco, 'DejaVu Sans Mono', 'Bitstream Vera Sans Mono', Consolas, 'Courier New', monospace;color:rgb(0, 0, 255);'>' . $status . '</span><span style='font-size:12px;font-style:normal;font-weight:normal;font-family:Monaco, 'DejaVu Sans Mono', 'Bitstream Vera Sans Mono', Consolas, 'Courier New', monospace;color:rgb(0, 0, 0);'></span><span style='font-size:12px;font-style:normal;font-weight:normal;font-family:Monaco, 'DejaVu Sans Mono', 'Bitstream Vera Sans Mono', Consolas, 'Courier New', monospace;color:rgb(0, 0, 255);'>' . RestUtils::getStatusCodeMessage($status) . '</span><span style='font-size:12px;font-style:normal;font-weight:normal;font-family:Monaco, 'DejaVu Sans Mono', 'Bitstream Vera Sans Mono', Consolas, 'Courier New', monospace;color:rgb(0, 0, 0);'></span>  

1.                               

1.                               

1.                                 

' . RestUtils::getStatusCodeMessage($status) . '

  

1.                                 

' . $message . '

  

1.                                    

1.                                 

' . $signature . '

  

1.                             

  

1.                         

';  

1. echo$body;  

1. exit;  

1.         }  

1.     }  

这就是了！在技术上我们有了处理请求和发送响应的一切需要的东西。让我们更多的谈谈为什么我们需要一个标准的或自定义的响应主体。对于GET请求来说，这是非常明显的，我们需要发送“XML/JSON”内容取代状态页面（只要请求是有效的）。然而，还有POST要处理。在你的应用程序里，当你创建一个新实体，你可能会通过类似mysql_insert_id()函数这样的方法来获取新实体的ID。如果一个用户向你的API发送一个POST请求，他们同样想要一个新ID。我通常处理这种情况的方法是简单的发送一个作为主体的新的ID（伴随一个201状态码），但是如果你愿意你可以将他们封装在XML或JSON中。



让我们稍微来扩展一下我们的简单应用：

Php代码

![](D:/download/youdaonote-pull-master/data/Technology/接口API开发/images/0805D8CCF4184FF5B82F0968CB12406Eicon_star.png)

1. switch($data->getMethod)  

1. {  

1. // this is a request for all users, not one in particular

1. case'get':  

1. $user_list = getUserList(); // assume this returns an array

1. if($data->getHttpAccept == 'json')  

1.         {  

1.             RestUtils::sendResponse(200, json_encode($user_list), 'application/json');  

1.         }  

1. elseif ($data->getHttpAccept == 'xml')  

1.         {  

1. // using the XML_SERIALIZER Pear Package

1. $options = array

1.             (  

1. 'indent' => '     ',  

1. 'addDecl' => false,  

1. 'rootName' => $fc->getAction(),  

1.                 XML_SERIALIZER_OPTION_RETURN_RESULT => true  

1.             );  

1. $serializer = new XML_Serializer($options);  

1.             RestUtils::sendResponse(200, $serializer->serialize($user_list), 'application/xml');  

1.         }  

1. break;  

1. // new user create

1. case'post':  

1. $user = new User();  

1. $user->setFirstName($data->getData()->first_name);  // just for example, this should be done cleaner

1. // and so on...

1. $user->save();  

1. // just send the new ID as the body

1.         RestUtils::sendResponse(201, $user->getId());  

1. break;  

1. }  

重申一下，这仅仅是一个例子，但是它展示了（我认为，至少是这样的）实现RESTful所要做出的努力。

总结

这就是了！我非常自信已经把一些观点易于理解的指了出来，因此我愿意接受你如何更进一步的领悟这个材料，而且或许可以正确的实现它。



在现实的MVC应用程序中，你或许想做的是为你的加载个别API控制器的API设置一个控制器。例如，使用上面的原型，我们可能创建一个包含get(),put(),post()和delete()方法的UserRestController控制器。这些方法将会使用工具来处理请求，智能的做一些需要做的事，然后使用工具发送响应。



你也可以更进一步，抽象出你的API控制器和数据模型。不用在你的应用程序中为每个数据模型创建一个控制器，你可以添加一些逻辑到你的API控制器并且首先寻找一个显示定义的控制器，如果没找到，则尝试去寻找一个存在的模型。例如，URL“api/user/1”将会首先查找一个“user”的rest控制器，如果没找到，再在你的应用程序中寻找一个叫做“user”的模型。如果找到了一个，你可以对这些模型写一些自动化巫师程序来自动化处理所有的请求。



更进一步，你可以创建一个一般的“list-all”方法，其工作原理类似于先前段落的例子。假设你的url是“api/users”。API控制器将会首先检查“users”rest控制器，如果没找到，识别用户被多元化，解除多元化，然后查找“user”模型。如果发现一个，加载一个列表的用户列表并发出。



最后，你可以同样简单的为你的API加上摘要式身份验证。假设你只想要合适认证的用户有访问你API的权限，你可以向这样在你的处理请求的功能在加入一些代码（借用我的现有应用，有一些常量和变量引用没有被定义在这个片段中）。

Php代码

![](D:/download/youdaonote-pull-master/data/Technology/接口API开发/images/57AC5D6085B448A98B253995BB8C35B8icon_star.png)

1. // figure out if we need to challenge the user

1. if(emptyempty($_SERVER['PHP_AUTH_DIGEST']))  

1.             {  

1.                 header('HTTP/1.1 401 Unauthorized');  

1.                 header('WWW-Authenticate: Digest realm="' . AUTH_REALM . '",qop="auth",nonce="' . uniqid() . '",opaque="' . md5(AUTH_REALM) . '"');  

1. // show the error if they hit cancel

1. die(RestControllerLib::error(401, true));  

1.             }  

1. // now, analayze the PHP_AUTH_DIGEST var

1. if(!($data = http_digest_parse($_SERVER['PHP_AUTH_DIGEST'])) || $auth_username != $data['username'])  

1.             {  

1. // show the error due to bad auth

1. die(RestUtils::sendResponse(401));  

1.             }  

1. // so far, everything's good, let's now check the response a bit more...

1. $A1 = md5($data['username'] . ':' . AUTH_REALM . ':' . $auth_pass);  

1. $A2 = md5($_SERVER['REQUEST_METHOD'] . ':' . $data['uri']);  

1. $valid_response = md5($A1 . ':' . $data['nonce'] . ':' . $data['nc'] . ':' . $data['cnonce'] . ':' . $data['qop'] . ':' . $A2);  

1. // last check..

1. if($data['response'] != $valid_response)  

1.             {  

1. die(RestUtils::sendResponse(401));  

1.             }  

非常酷的原型，是吗？通过一点点代码和一些聪明的逻辑，你可以非常快捷的在你的应用程序中加入一个全功能的REST API。我并不仅仅是在鼓励这个概念，我花了半天时间在我的个人框架中实现了它，又花了另一个半天在里面加入了各式各样的魔法。如果你对我的最终实现感兴趣，在评论中注明，我将非常高兴地与你分享。如果你有任何愿意分享的酷的点子，同样也请在评论中写下它们。如果我足够喜欢它，我甚至乐意让您自己创作关于这一主题的文章。



下次再见。

原文链接：Create a REST API with PHP

（完）