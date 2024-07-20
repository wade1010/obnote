原文： http://www.oschina.net/translate/creating-an-api-centric-web-application



|   |   |
| - | - |
| 正计划着要开始搞一个新的网络应用？在这篇教程中，我们将讨论如何创建以API为中心的网络应用，还会解释在今天的多平台世界，这类应用为什么是重要的。<br>引言<br>API？<br>对于还不甚熟悉这个术语的朋友，API是Application Programming Interface（应用编程接口）的简称。根据维基百科：<br>API是以源代码为基础的约定，它用于软件组件之间相互通信的接口。API可能包含函数、数据结构、对象类、以及变量等的约定。<br>API可视化<br>图片蒙惠http://blog.zoho.com<br>简单地讲，API指的是一组应用中的函数，它们能够被其它应用（或者这些函数所属应用自己，下文中我们将会看到）用来与应用进行交互。API是一种很棒的向外部应用安全和妥善地表明其功能的方式，因为这些外部应用所能利用的所有功能仅限于API中所表现出的功能。 | stoneyang<br>翻译于 2年前<br>12人顶<br>顶 翻译的不错哦! |


![](https://note.youdao.com/yws/res/935/388369B27CCD42AF9466DE12CE1EB0F0)

![](https://note.youdao.com/yws/res/945/0A1C1CF1B21B409D8EBD86DB862B9939)

|   |   |
| - | - |
| 什么是&quot;以API为中心的”网络应用？<br>以API为中心的网络应用就是基本上通过API调用执行大多数甚或所有功能的一类网络应用。<br>以API为中心的网络应用就是基本上通过API调用执行大多数甚或所有功能的一类网络应用。举个例子，如果你正要登录一个用户，你应当将其认证信息发送给API，然后API会向你返回一个结果，说明该用户是否提供了正确的用户名-密码组合。<br>以API为中心的网络应用的另外一个特征就是API一直是无状态的，这意味着这种应用无法辨别由会话发起的API调用。由于API调用通常由后端代码构成，实现对会话的掌控将比较困难，因为这其中通常没有cookies介入。这种局限事实上是好事——它&quot;迫使”开发者建造不基于当前用户状态工作的API，但是相应地在功能上，它使测试易于进行，因为用户的当前状态无需被重建。 | stoneyang<br>翻译于 2年前<br>6人顶<br>顶 翻译的不错哦! |


![](https://note.youdao.com/yws/res/945/0A1C1CF1B21B409D8EBD86DB862B9939)

|   |   |
| - | - |
| 为什么要经历这些麻烦？<br>作为Web开发者，我们已经亲眼目睹了技术的进步。有一个常识是，当代的人们不会只通过浏览器来使用应用，还会通过其它诸如移动电话和平板电脑之类的设备使用。举个例子，这篇发表在Mashable上的名为&quot;用户在移动应用上花的时间比在网络上的多”的写道：<br>一项新近的报告表明，用户花在移动应用上的时间首次超过了花在网络上的时间。 <br><br>Flurry对比了其移动数据与来自comScore和Alexa的统计数据，发现在六月，用户每天花费81分钟使用移动应用，而只花74分钟用于网上冲浪。<br>这里还有一篇来自ReadWriteWeb的更新的文章&quot;在移动设备上浏览网络的人多于使用IE6和IE7的人数总和”：<br>来自Sitepoint的 浏览器趋势的最新数据表明，在智能手机上浏览Web的人比使用IE6和IE7浏览的人更多。这两件难有起色的老古董多年来一直是Web开发者的噩梦，它们需要各网站尽可能妥当地降格到至少常用浏览器所能支持的水平。但是现在时代不同了；2011年十一月中，6.95%的Web活动在移动浏览器上发生，而发生在IE6或IE7上的则只有6.49%。<br>正如我们所见，越来越多的人正通过其它途径获得讯息，特别是移动设备。 | stoneyang<br>翻译于 2年前<br>3人顶<br>顶 翻译的不错哦! |


![](https://note.youdao.com/yws/res/945/0A1C1CF1B21B409D8EBD86DB862B9939)

|   |   |
| - | - |
| 这与我创建以API为中心的网络应用有何关系？<br>这必将会使我们的应用更加有用，因为它可以用在任何你需要的地方。<br>创建以API为中心的网络应用的主要优势之一便是它帮助你建立可以用于任何设备的功能，浏览器、移动电话、甚至是桌面应用。你所需要做的就是创建的API能够使所有这些设备利用它完成通信，然后，瞧！你将能够建造一个集中式应用，它能够接受来自用户所使用的任何设备的输入并执行相应的功能。<br>以API为中心的应用的框图 | stoneyang<br>翻译于 2年前<br>4人顶<br>顶 翻译的不错哦! |


![](https://note.youdao.com/yws/res/934/BE27E027A6074F65B2CC6F0463DB21A5)

![](https://note.youdao.com/yws/res/945/0A1C1CF1B21B409D8EBD86DB862B9939)

|   |   |
| - | - |
| 通过以这种方式创建应用，我们能够从容地利用不同的人使用不同的媒介这一优势。这必将使应用更加有用，因为它能用在用户需要的任何地方。<br>为了证明我们的观点，这里有一篇关于Twitter的重新设计的网站的文章，文章告诉我们他们现在如何利用他们的API来驱动Twitter.com的，实质上是使其以API为中心：<br>最重要的架构改动之一就是Twitter.com现在是我们自己API的客户。它从终端提取数据，此终端与移动网站，我们为iPhone、iPad、Android，以及所有第三方应用所用端点相同。这一转变使我们能向API团队分配更多的资源，同时生成了40多个补丁。在初始页面负载和来自客户端的每个调用上，所有的数据现在都是从一个高度优化的JSON段缓存中获取的。<br>在本篇教程中，我们将创建一个简单的TODO列表应用，该应用以API为中心；还要创建一个浏览器上的前端客户端，该客户端与我们的TODO列表应用进行交互。文末，你就能了解一个以API为中心的应用的有机组成部分，同时，还能了解怎样使应用和客户端两者之间的安全通信变得容易。记住这些，我们开始吧！ | stoneyang<br>翻译于 2年前<br>4人顶<br>顶 翻译的不错哦! |


![](https://note.youdao.com/yws/res/945/0A1C1CF1B21B409D8EBD86DB862B9939)

|   |   |
| - | - |
| 步骤 1: 规划该应用的功能<br>本教程中我们将要构建的这个 TODO 应用将会有下面几个基本的CRUD功能:<br>创建 TODO 条目<br>读取 TODO 条目<br>更新 TODO 条目 (重命名，标记为完成，标记为未完成)<br>删除 TODO 条目<br>每一个 TODO 条目将拥有:<br>一个标题 Title<br>一个截止日期 Date Due<br>一个描述 Description<br>一个判断 TODO 条目是否完成的标志 Is Done<br>让我们模拟一下该应用，使我们考虑该应用以后会是什么样子时，能有有一个直观的参考:<br> <br><br>简单的TODO 模拟示例 | lwei<br>翻译于 2年前<br>5人顶<br>顶 翻译的不错哦! |


![](https://note.youdao.com/yws/res/939/0F6D3AC56B234FB0A4C93D873EE663AE)

![](https://note.youdao.com/yws/res/943/716DC4F5D5BB4AD2869FA084C99E0084)

|   |   |
| - | - |
| 步骤 2: 创建API服务器<br>既然我们是在开发一个以API为中心的应用，我们将创建两个&quot;项目”: API 服务器，和前端客户端。 我们首先从创建API服务器开始。<br>在你的web server文件夹，创建一个文件夹，命名为simpletodo\_api，然后创建一个index.php文件。这个index.php文件将作为一个访问API的前端控制器，所以，所有访问API服务器的请求都会由该文件产生。打开它并往里输入下列代码:<br>?<br>实质上，这里我们创建的是一个简单的前端控制器，它实现了下列功能:<br>接受一次拥有任意个参数的API调用<br>为本次API调用抽取出Controller和Action<br>进行必要的检查确保Controller和Action都存在<br>执行API调用<br>捕获异常，如果有的话<br>返回一个结果给调用者 | lwei<br>翻译于 2年前<br>5人顶<br>顶 翻译的不错哦! |


|   |   |
| - | - |
| 1<br>2<br>3<br>4<br>5<br>6<br>7<br>8<br>9<br>10<br>11<br>12<br>13<br>14<br>15<br>16<br>17<br>18<br>19<br>20<br>21<br>22<br>23<br>24<br>25<br>26<br>27<br>28<br>29<br>30<br>31<br>32<br>33<br>34<br>35<br>36<br>37<br>38<br>39<br>40<br>41<br>42<br>43<br>44<br>45<br>46<br>47 | &lt;?php<br>// 定义数据目录的路径<br>define('DATA\_PATH', realpath(dirname(\_\_FILE\_\_).'/data'));<br> <br>//引入我们的models<br>include\_once 'models/TodoItem.php';<br> <br>//在一个try-catch块中包含所有代码，来捕获所有可能的异常!<br>try {<br>    //获得在POST/GET request中的所有参数<br>    $params = $\_REQUEST;<br>     <br>    //获取controller并把它正确的格式化使得第一个字母总是大写的<br>    $controller = ucfirst(strtolower($params['controller']));<br>     <br>    //获取action并把它正确的格式化，使它所有的字母都是小写的，并追加一个'Action'<br>    $action = strtolower($params['action']).'Action';<br> <br>    //检查controller是否存在。如果不存在，抛出异常<br>    if( file\_exists("controllers/{$controller}.php") ) {<br>        include\_once "controllers/{$controller}.php";<br>    } else {<br>        throw new Exception('Controller is invalid.');<br>    }<br>     <br>    //创建一个新的controller实例，并把从request中获取的参数传给它<br>    $controller = new $controller($params);<br>     <br>    //检查controller中是否存在action。如果不存在，抛出异常。<br>    if( method\_exists($controller, $action) === false ) {<br>        throw new Exception('Action is invalid.');<br>    }<br>     <br>    //执行action<br>    $result['data'] = $controller-&gt;$action();<br>    $result['success'] = true;<br>     <br>} catch( Exception $e ) {<br>    //捕获任何一次样并且报告问题<br>    $result = array();<br>    $result['success'] = false;<br>    $result['errormsg'] = $e-&gt;getMessage();<br>}<br> <br>//回显调用API的结果<br>echo json\_encode($result);<br>exit(); |


![](https://note.youdao.com/yws/res/943/716DC4F5D5BB4AD2869FA084C99E0084)

|   |   |
| - | - |
| 除了需要创建index.php外你还需要创建三个文件夹:  controllers, models 和  data.<br>controllers  文件夹存放的是所有我们API服务器将会用到的的控制器。我们用MVC架构来使API服务器结构更清楚合理。<br>models 文件夹存放所有API服务器要用到的数据模型。<br>data 文件夹将会用来保存API服务器的任何数据。<br>在controllers文件夹下创建一个叫Todo.php的文件。这将是任何TODO列表有关任务的控制器。按照TODO应用所需提供的功能，向Todo控制器里面添加必要的方法：<br>?<br>现在为每个action中添加必要的功能实现。我将会提供createAction()方法的源码，其他方法将留作作业。如果你觉得毫无头绪，你也可以下载示例的源码，从那里拷贝。<br>? | bigtiger02<br>翻译于 2年前<br>4人顶<br>顶 翻译的不错哦! |


![](https://note.youdao.com/yws/res/941/811C09BC3E5C497FA9F4E3ADEA98CC1D)

|   |   |
| - | - |
| 1<br>2<br>3<br>4<br>5<br>6<br>7<br>8<br>9<br>10<br>11<br>12<br>13<br>14<br>15<br>16<br>17<br>18<br>19<br>20<br>21<br>22<br>23<br>24<br>25<br>26<br>27<br>28<br>29<br>30 | &lt;?php<br>class Todo<br>{<br>    private $\_params;<br>     <br>    public function \_\_construct($params)<br>    {<br>        $this-&gt;\_params = $params;<br>    }<br>     <br>    public function createAction()<br>    {<br>        //create a new todo item<br>    }<br>     <br>    public function readAction()<br>    {<br>        //read all the todo items<br>    }<br>     <br>    public function updateAction()<br>    {<br>        //update a todo item<br>    }<br>     <br>    public function deleteAction()<br>    {<br>        //delete a todo item<br>    }<br>} |


|   |   |
| - | - |
| 1<br>2<br>3<br>4<br>5<br>6<br>7<br>8<br>9<br>10<br>11<br>12<br>13<br>14<br>15 | public function createAction()<br>{<br>    //create a new todo item<br>    $todo = new TodoItem();<br>    $todo-&gt;title = $this-&gt;\_params['title'];<br>    $todo-&gt;description = $this-&gt;\_params['description'];<br>    $todo-&gt;due\_date = $this-&gt;\_params['due\_date'];<br>    $todo-&gt;is\_done = 'false';<br>     <br>    //pass the user's username and password to authenticate the user<br>    $todo-&gt;save($this-&gt;\_params['username'], $this-&gt;\_params['userpass']);<br>     <br>    //return the todo item in array format<br>    return $todo-&gt;toArray();<br>} |


![](https://note.youdao.com/yws/res/944/F9E2FA5D8CF94FA59ACA06F11E33D62A)

|   |   |
| - | - |
| 在文件夹models下创建TodoItem.php，这样我们就可以创建&quot;条目添加”的代码了。注意：我并没有和数据库进行连接，相反我将信息保存到文件中，虽然这可以用任何数据库来实现,但是 这样做相对来说要容易些。<br>?<br>createAction方法使用到TodoItem模型里面两个方法：<br>save() – 该方法将TodoItem保存到一个文件中，如有必要，需要设置todo\_id。<br>toArray() – 该方法返回一个以变量为索引的数组Todo条目。<br>由于API需要通过HTTP请求调用，在浏览器输入如下地址测试API：<br>http://localhost/simpletodo\_api/?controller=todo&amp;action=create&amp;title=test%20title&amp;description=test%20description&amp;due\_date=12/08/2011&amp;username=nikko&amp;userpass=test1234<br>如果没有错，你应该在data文件夹下看到一个新的文件夹，在该文件夹里面有一个文件，文件内容如下：<br> <br><br>createAction()结果<br>恭喜！您已经成功创建了一个的API服务器和API调用！ | bigtiger02<br>翻译于 2年前<br>5人顶<br>顶 翻译的不错哦! |


|   |   |
| - | - |
| 1<br>2<br>3<br>4<br>5<br>6<br>7<br>8<br>9<br>10<br>11<br>12<br>13<br>14<br>15<br>16<br>17<br>18<br>19<br>20<br>21<br>22<br>23<br>24<br>25<br>26<br>27<br>28<br>29<br>30<br>31<br>32<br>33<br>34<br>35<br>36<br>37<br>38<br>39<br>40<br>41<br>42<br>43<br>44<br>45<br>46<br>47<br>48<br>49<br>50 | &lt;?php<br>class TodoItem<br>{<br>    public $todo\_id;<br>    public $title;<br>    public $description;<br>    public $due\_date;<br>    public $is\_done;<br>     <br>    public function save($username, $userpass)<br>    {<br>        //get the username/password hash<br>        $userhash = sha1("{$username}\_{$userpass}");<br>        if( is\_dir(DATA\_PATH."/{$userhash}") === false ) {<br>            mkdir(DATA\_PATH."/{$userhash}");<br>        }<br>         <br>        //if the $todo\_id isn't set yet, it means we need to create a new todo item<br>        if( is\_null($this-&gt;todo\_id) || !is\_numeric($this-&gt;todo\_id) ) {<br>            //the todo id is the current time<br>            $this-&gt;todo\_id = time();<br>        }<br>         <br>        //get the array version of this todo item<br>        $todo\_item\_array = $this-&gt;toArray();<br>         <br>        //save the serialized array version into a file<br>        $success = file\_put\_contents(DATA\_PATH."/{$userhash}/{$this-&gt;todo\_id}.txt", serialize($todo\_item\_array));<br>         <br>        //if saving was not successful, throw an exception<br>        if( $success === false ) {<br>            throw new Exception('Failed to save todo item');<br>        }<br>         <br>        //return the array version<br>        return $todo\_item\_array;<br>    }<br>     <br>    public function toArray()<br>    {<br>        //return an array version of the todo item<br>        return array(<br>            'todo\_id' =&gt; $this-&gt;todo\_id,<br>            'title' =&gt; $this-&gt;title,<br>            'description' =&gt; $this-&gt;description,<br>            'due\_date' =&gt; $this-&gt;due\_date,<br>            'is\_done' =&gt; $this-&gt;is\_done<br>        );<br>    }<br>} |


![](https://note.youdao.com/yws/res/933/59D7F389DE0E40B6A10A12107E6C061C)

![](https://note.youdao.com/yws/res/944/F9E2FA5D8CF94FA59ACA06F11E33D62A)

|   |   |
| - | - |
| 步骤3：确保API服务器具有APP ID和APP SECRET<br>目前，API服务器被设置为接受全部API请求。我们将需要将之限制在我们自己的应用上，以确保只有我们自己的前端客户端能够完成API请求。另外，你实际上也可以创建一个系统，其中的用户可以创建他们自己的应用，而那些应用也用用对你的API服务器的访问权，这与Facebook和Twitter的应用的的工作原理类似。<br>我们从为使用API服务器的用户创建一组id-密码对开始。由于这只是一个Demo，我们可以使用任何随机的、32位字符串。对于APP ID，我们将其设定为APP001。<br>再次打开index.php文件，然后用下列代码更新之：<br>? | stoneyang<br>翻译于 2年前<br>5人顶<br>顶 翻译的不错哦! |


|   |   |
| - | - |
| 1<br>2<br>3<br>4<br>5<br>6<br>7<br>8<br>9<br>10<br>11<br>12<br>13<br>14<br>15<br>16<br>17<br>18<br>19<br>20<br>21<br>22<br>23<br>24<br>25<br>26<br>27<br>28<br>29<br>30<br>31<br>32<br>33<br>34<br>35<br>36<br>37<br>38 | &lt;?php<br>// Define path to data folder<br>define('DATA\_PATH', realpath(dirname(\_\_FILE\_\_).'/data'));<br> <br>//Define our id-key pairs<br>$applications = array(<br>    'APP001' =&gt; '28e336ac6c9423d946ba02d19c6a2632', //randomly generated app key<br>);<br>//include our models<br>include\_once 'models/TodoItem.php';<br> <br>//wrap the whole thing in a try-catch block to catch any wayward exceptions!<br>try {<br>    //\*UPDATED\*<br>    //get the encrypted request<br>    $enc\_request = $\_REQUEST['enc\_request'];<br>     <br>    //get the provided app id<br>    $app\_id = $\_REQUEST['app\_id'];<br>     <br>    //check first if the app id exists in the list of applications<br>    if( !isset($applications[$app\_id]) ) {<br>        throw new Exception('Application does not exist!');<br>    }<br>     <br>    //decrypt the request<br>    $params = json\_decode(trim(mcrypt\_decrypt(MCRYPT\_RIJNDAEL\_256, $applications[$app\_id], base64\_decode($enc\_request), MCRYPT\_MODE\_ECB)));<br>     <br>    //check if the request is valid by checking if it's an array and looking for the controller and action<br>    if( $params == false || isset($params-&gt;controller) == false || isset($params-&gt;action) == false ) {<br>        throw new Exception('Request is not valid');<br>    }<br>     <br>    //cast it into an array<br>    $params = (array) $params;<br>    ...<br>    ...<br>    ... |


![](https://note.youdao.com/yws/res/945/0A1C1CF1B21B409D8EBD86DB862B9939)

|   |   |
| - | - |
| 我们在这里已经完成的实际上是实现一个非常简单的认证我们的前端客户端的方法，它利用了与公共-私有密钥认证相似的系统。基本上，这里给出的就是认证过程的步骤分解：<br>公钥加密<br>完成一个API调用，其中提供了$app\_id和$enc\_request<br>$enc\_request的值是API调用的参数，利用APP KEY进行加密。APP KEY绝对不会被发送到服务器，它只是被用来散列请求。此外，该请求只能利用APP KEY被解密<br>一旦API调用到达API服务器，它会查验它自己的应用列表是否与APP ID所提供的一致<br>当调用被发现，API服务器尝试利用与APP ID发送的密钥相匹配的密钥进行解密<br>如果请求被解密成功，那么继续执行程序<br>既然API服务器已经确保具有APP ID和APP SECRET，那么我们就可以开始编写使用API服务器的前端客户端了。 | stoneyang<br>翻译于 2年前<br>3人顶<br>顶 翻译的不错哦! |


![](https://note.youdao.com/yws/res/937/9BBB26D40B17412D8C8777A18D01585A)

![](https://note.youdao.com/yws/res/945/0A1C1CF1B21B409D8EBD86DB862B9939)

|   |   |
| - | - |
| 步骤4：创建浏览器客户端<br>我们从为前端客户端设定新建文件夹开始。在你的Web服务器上的文件夹中创建一个名为simpletodo\_client\_browser的文件夹。完成后，创建一个index.php文件，并将下列代码写进去：<br>?<br>这段代码的运行结果看起来就像这样：<br>SimpleTODO的登录页<br>需要注意的是我在这里已经包含了两个JavaScript文件和两个CSS文件：<br>reset.css是你的标准CSS重置脚本。我使用了meyerweb.com css reset.<br>bootstrap.min.css是Twitter Bootstrap<br>jquery.min.js是最新版的jQuery library<br>jquery-ui-1.8.16.custom.min.js是最新版的jQuery UI library<br>接下来，我们创建login.php文件来存储客户端会话中的用户名和密码。<br>?<br>这里，我们简单地为用户开启一次会话，所依据的是用户所提供的用户名和密码组合。这充当了简单的组合密钥，它允许用户访问某个特定用户名和密码组合所存储的TODO项。然后我们重定向至todo.php，那里是我们开始与API服务器交互的地方。然而在我们开始编写todo.php文件之前，先创建一个 ApiCaller类，它将封装我们所需的全部API调用方法，包括请求的加密。<br>创建apicaller.php，并把下面的代码写进去：<br>? | stoneyang<br>翻译于 2年前<br>1人顶<br>顶 翻译的不错哦! |


|   |   |
| - | - |
| 1<br>2<br>3<br>4<br>5<br>6<br>7<br>8<br>9<br>10<br>11<br>12<br>13<br>14<br>15<br>16<br>17<br>18<br>19<br>20<br>21<br>22<br>23<br>24<br>25<br>26<br>27<br>28<br>29<br>30<br>31<br>32<br>33<br>34<br>35<br>36<br>37<br>38<br>39<br>40<br>41<br>42<br>43<br>44<br>45<br>46<br>47<br>48 | &lt;!DOCTYPE html&gt;<br>&lt;html&gt;<br>&lt;head&gt;<br>    &lt;title&gt;SimpleTODO&lt;/title&gt;<br>     <br>    &lt;link rel="stylesheet" href="css/reset.css" type="text/css" /&gt;<br>    &lt;link rel="stylesheet" href="css/bootstrap.min.css" type="text/css" /&gt;<br>     <br>    &lt;script src="js/jquery.min.js"&gt;&lt;/script&gt;<br>    &lt;script src="js/jquery-ui-1.8.16.custom.min.js"&gt;&lt;/script&gt;<br>     <br>    &lt;style&gt;<br>    body {<br>        padding-top: 40px;<br>    }<br>    \#main {<br>        margin-top: 80px;<br>        text-align: center;<br>    }<br>    &lt;/style&gt;<br>&lt;/head&gt;<br>&lt;body&gt;<br>    &lt;div class="topbar"&gt;<br>        &lt;div class="fill"&gt;<br>            &lt;div class="container"&gt;<br>                &lt;a class="brand" href="index.php"&gt;SimpleTODO&lt;/a&gt;<br>            &lt;/div&gt;<br>        &lt;/div&gt;<br>    &lt;/div&gt;<br>    &lt;div id="main" class="container"&gt;<br>        &lt;form class="form-stacked" method="POST" action="login.php"&gt;<br>            &lt;div class="row"&gt;<br>                &lt;div class="span5 offset5"&gt;<br>                    &lt;label for="login\_username"&gt;Username:&lt;/label&gt;<br>                    &lt;input type="text" id="login\_username" name="login\_username" placeholder="username" /&gt;<br>                 <br>                    &lt;label for="login\_password"&gt;Password:&lt;/label&gt;<br>                    &lt;input type="password" id="login\_password" name="login\_password" placeholder="password" /&gt;<br>                     <br>                &lt;/div&gt;<br>            &lt;/div&gt;<br>            &lt;div class="actions"&gt;<br>                &lt;button type="submit" name="login\_submit" class="btn primary large"&gt;Login or Register&lt;/button&gt;<br>            &lt;/div&gt;<br>        &lt;/form&gt;<br>    &lt;/div&gt;<br>&lt;/body&gt;<br>&lt;/html&gt; |


![](https://note.youdao.com/yws/res/940/2E677C5E50E64226A0FE4EF718A891E4)

|   |   |
| - | - |
| 1<br>2<br>3<br>4<br>5<br>6<br>7<br>8<br>9 | &lt;?php<br>//get the form values<br>$username = $\_POST['login\_username'];<br>$userpass = $\_POST['login\_password'];<br>session\_start();<br>$\_SESSION['username'] = $username;<br>$\_SESSION['userpass'] = $userpass;<br>header('Location: todo.php');<br>exit(); |


|   |   |
| - | - |
| 1<br>2<br>3<br>4<br>5<br>6<br>7<br>8<br>9<br>10<br>11<br>12<br>13<br>14<br>15<br>16<br>17<br>18<br>19<br>20<br>21<br>22<br>23<br>24<br>25<br>26<br>27<br>28<br>29<br>30<br>31<br>32<br>33<br>34<br>35<br>36<br>37<br>38<br>39<br>40<br>41<br>42<br>43<br>44<br>45<br>46<br>47<br>48<br>49<br>50<br>51<br>52<br>53<br>54<br>55<br>56<br>57<br>58 | &lt;?php<br>class ApiCaller<br>{<br>    //some variables for the object<br>    private $\_app\_id;<br>    private $\_app\_key;<br>    private $\_api\_url;<br>     <br>    //construct an ApiCaller object, taking an<br>    //APP ID, APP KEY and API URL parameter<br>    public function \_\_construct($app\_id, $app\_key, $api\_url)<br>    {<br>        $this-&gt;\_app\_id = $app\_id;<br>        $this-&gt;\_app\_key = $app\_key;<br>        $this-&gt;\_api\_url = $api\_url;<br>    }<br>     <br>    //send the request to the API server<br>    //also encrypts the request, then checks<br>    //if the results are valid<br>    public function sendRequest($request\_params)<br>    {<br>        //encrypt the request parameters<br>        $enc\_request = base64\_encode(mcrypt\_encrypt(MCRYPT\_RIJNDAEL\_256, $this-&gt;\_app\_key, json\_encode($request\_params), MCRYPT\_MODE\_ECB));<br>         <br>        //create the params array, which will<br>        //be the POST parameters<br>        $params = array();<br>        $params['enc\_request'] = $enc\_request;<br>        $params['app\_id'] = $this-&gt;\_app\_id;<br>         <br>        //initialize and setup the curl handler<br>        $ch = curl\_init();<br>        curl\_setopt($ch, CURLOPT\_URL, $this-&gt;\_api\_url);<br>        curl\_setopt($ch, CURLOPT\_RETURNTRANSFER, 1);<br>        curl\_setopt($ch, CURLOPT\_POST, count($params));<br>        curl\_setopt($ch, CURLOPT\_POSTFIELDS, $params);<br> <br>        //execute the request<br>        $result = curl\_exec($ch);<br>         <br>        //json\_decode the result<br>        $result = @json\_decode($result);<br>         <br>        //check if we're able to json\_decode the result correctly<br>        if( $result == false || isset($result['success']) == false ) {<br>            throw new Exception('Request was not correct');<br>        }<br>         <br>        //if there was an error in the request, throw an exception<br>        if( $result['success'] == false ) {<br>            throw new Exception($result['errormsg']);<br>        }<br>         <br>        //if everything went great, return the data<br>        return $result['data'];<br>    }<br>} |


![](https://note.youdao.com/yws/res/945/0A1C1CF1B21B409D8EBD86DB862B9939)

|   |   |
| - | - |
| 我们将利用ApiCaller类向我们的API服务器发送请求。这样，所有必需的加密和cURL初始化代码将会写在一个地方，我们就不用重复代码了。<br>\_\_construct函数接受三个参数：<br>$app\_id——客户端的APP ID（浏览器端是APP001）<br>$app\_key——客户端的APP KEY（浏览器端是28e336ac6c9423d946ba02d19c6a2632）<br>$api\_url——API服务器的URL，此处为http://localhost/simpletodo\_api/<br>sendRequest()函数：<br>利用mcrypt库以API服务对其解密的同样方式来对请求参数进行加密<br>生成发往API服务器的$\_POST参数<br>通过cURL执行API调用<br>查验API调用的结果是否正确<br>当一切都按计划进行的时候返回数据<br>现在，我们开始写todo.php。首先，我们创建一些代码来为密码为test1234的用户nikko（这是我们先前用来测试API服务器的那个用户名/密码组合）获取当前的todo项。<br>?<br>打开index.php，以nikko/tes1234登录，然后你应该看到我们先前创建的TODO项的avar\_dump()。<br>恭喜，你已经成功地做好了一个向API服务器的API调用！在这段代码中，我们已经：<br>开启会话，使我们拥有了对$\_SESSION中的username以及userpass的访问权<br>实例化了一个新的ApiCaller类，为其提供了APP ID，APP KEY，以及API服务器的URL<br>通过sendRequest()方法发送了一个请求 | stoneyang<br>翻译于 2年前<br>3人顶<br>顶 翻译的不错哦! |


|   |   |
| - | - |
| 1<br>2<br>3<br>4<br>5<br>6<br>7<br>8<br>9<br>10<br>11<br>12<br>13<br>14<br>15 | &lt;?php<br>session\_start();<br>include\_once 'apicaller.php';<br> <br>$apicaller = new ApiCaller('APP001', '28e336ac6c9423d946ba02d19c6a2632', 'http://localhost/simpletodo\_api/');<br> <br>$todo\_items = $apicaller-&gt;sendRequest(array(<br>    'controller' =&gt; 'todo',<br>    'action' =&gt; 'read',<br>    'username' =&gt; $\_SESSION['username'],<br>    'userpass' =&gt; $\_SESSION['userpass']<br>));<br> <br>echo '';<br>var\_dump($todo\_items); |


![](https://note.youdao.com/yws/res/942/49587F1AAFFA4B81B6540B6FFAC6892E)

![](https://note.youdao.com/yws/res/945/0A1C1CF1B21B409D8EBD86DB862B9939)

|   |   |
| - | - |
| 现在，我们来重新格式化一下数据，让它们开起来更好看些。向todo.php中添加下列HTML。别忘了移去var\_dump()！<br>?<br>这段代码的运行结果如下：<br>很酷哈？但它目前啥也干不了，那么让我们开始添加一些功能吧。我将为new\_todo.php提供代码，它们调用todo/createAPI调用来创建新的TODO项。创建其他页（update\_todo.php和delete\_todo.php）应该与此十分相似，因此我把它们留给你。打开new\_todo.php，然后把下面的代码添进去：<br>?<br>正如你所看到的，new\_todo.php页再次使用了ApiCaller调用来简化向API服务器所发送的 todo/create请求。这主要完成了与之前相同的事情：<br>开启一个会话，以使其获得对存储于$\_SESSION中的$username和$userpass的访问权<br>实例化一个新的ApiCaller类，为它提供APP ID， APP KEY，以及API服务器的URL<br>通过sendRequest()方法发送请求<br>重定向回todo.php<br>恭喜，它好用了！你已经成功地创建了一个以API为中心的应用！ | stoneyang<br>翻译于 2年前<br>3人顶<br>顶 翻译的不错哦! |


|   |   |
| - | - |
| 1<br>2<br>3<br>4<br>5<br>6<br>7<br>8<br>9<br>10<br>11<br>12<br>13<br>14<br>15<br>16<br>17<br>18<br>19<br>20<br>21<br>22<br>23<br>24<br>25<br>26<br>27<br>28<br>29<br>30<br>31<br>32<br>33<br>34<br>35<br>36<br>37<br>38<br>39<br>40<br>41<br>42<br>43<br>44<br>45<br>46<br>47<br>48<br>49<br>50<br>51<br>52<br>53<br>54<br>55<br>56<br>57<br>58<br>59<br>60<br>61<br>62<br>63<br>64<br>65<br>66<br>67<br>68<br>69<br>70<br>71<br>72<br>73<br>74<br>75<br>76<br>77<br>78<br>79<br>80<br>81<br>82<br>83<br>84<br>85<br>86<br>87<br>88<br>89<br>90<br>91<br>92<br>93<br>94<br>95<br>96<br>97<br>98<br>99<br>100<br>101<br>102<br>103<br>104 | &lt;!DOCTYPE html&gt;<br>&lt;html&gt;<br>&lt;head&gt;<br>    &lt;title&gt;SimpleTODO&lt;/title&gt;<br>     <br>    &lt;link rel="stylesheet" href="css/reset.css" type="text/css" /&gt;<br>    &lt;link rel="stylesheet" href="css/bootstrap.min.css" type="text/css" /&gt;<br>    &lt;link rel="stylesheet" href="css/flick/jquery-ui-1.8.16.custom.css" type="text/css" /&gt;<br>     <br>    &lt;script src="js/jquery.min.js"&gt;&lt;/script&gt;<br>    &lt;script src="js/jquery-ui-1.8.16.custom.min.js"&gt;&lt;/script&gt;<br>     <br>    &lt;style&gt;<br>    body {<br>        padding-top: 40px;<br>    }<br>    \#main {<br>        margin-top: 80px;<br>    }<br>     <br>    .textalignright {<br>        text-align: right;<br>    }<br>     <br>    .marginbottom10 {<br>        margin-bottom: 10px;<br>    }<br>    \#newtodo\_window {<br>        text-align: left;<br>        display: none;<br>    }<br>    &lt;/style&gt;<br>     <br>    &lt;script&gt;<br>    $(document).ready(function() {<br>        $("\#todolist").accordion({<br>            collapsible: true<br>        });<br>        $(".datepicker").datepicker();<br>        $('\#newtodo\_window').dialog({<br>            autoOpen: false,<br>            height: 'auto',<br>            width: 'auto',<br>            modal: true<br>        });<br>        $('\#newtodo').click(function() {<br>            $('\#newtodo\_window').dialog('open');<br>        });<br>    });<br>    &lt;/script&gt;<br>&lt;/head&gt;<br>&lt;body&gt;<br>    &lt;div class="topbar"&gt;<br>        &lt;div class="fill"&gt;<br>            &lt;div class="container"&gt;<br>                &lt;a class="brand" href="index.php"&gt;SimpleTODO&lt;/a&gt;<br>            &lt;/div&gt;<br>        &lt;/div&gt;<br>    &lt;/div&gt;<br>    &lt;div id="main" class="container"&gt;<br>        &lt;div class="textalignright marginbottom10"&gt;<br>            &lt;span id="newtodo" class="btn info"&gt;Create a new TODO item&lt;/span&gt;<br>            &lt;div id="newtodo\_window" title="Create a new TODO item"&gt;<br>                &lt;form method="POST" action="new\_todo.php"&gt;<br>                    &lt;p&gt;Title:&lt;br /&gt;&lt;input type="text" class="title" name="title" placeholder="TODO title" /&gt;&lt;/p&gt;<br>                    &lt;p&gt;Date Due:&lt;br /&gt;&lt;input type="text" class="datepicker" name="due\_date" placeholder="MM/DD/YYYY" /&gt;&lt;/p&gt;<br>                    &lt;p&gt;Description:&lt;br /&gt;&lt;textarea class="description" name="description"&gt;&lt;/textarea&gt;&lt;/p&gt;<br>                    &lt;div class="actions"&gt;<br>                        &lt;input type="submit" value="Create" name="new\_submit" class="btn primary" /&gt;<br>                    &lt;/div&gt;<br>                &lt;/form&gt;<br>            &lt;/div&gt;<br>        &lt;/div&gt;<br>        &lt;div id="todolist"&gt;<br>            &lt;?php foreach($todo\_items as $todo): ?&gt;<br>            &lt;h3&gt;&lt;a href="\#"&gt;&lt;?php echo $todo-&gt;title; ?&gt;&lt;/a&gt;&lt;/h3&gt;<br>            &lt;div&gt;<br>                &lt;form method="POST" action="update\_todo.php"&gt;<br>                &lt;div class="textalignright"&gt;<br>                    &lt;a href="delete\_todo.php?todo\_id=&lt;?php echo $todo-&gt;todo\_id; ?&gt;"&gt;Delete&lt;/a&gt;<br>                &lt;/div&gt;<br>                &lt;div&gt;<br>                    &lt;p&gt;Date Due:&lt;br /&gt;&lt;input type="text" id="datepicker\_&lt;?php echo $todo-&gt;todo\_id; ?&gt;" class="datepicker" name="due\_date" value="12/09/2011" /&gt;&lt;/p&gt;<br>                    &lt;p&gt;Description:&lt;br /&gt;&lt;textarea class="span8" id="description\_&lt;?php echo $todo-&gt;todo\_id; ?&gt;" class="description" name="description"&gt;&lt;?php echo $todo-&gt;description; ?&gt;&lt;/textarea&gt;&lt;/p&gt;<br>                &lt;/div&gt;<br>                &lt;div class="textalignright"&gt;<br>                    &lt;?php if( $todo-&gt;is\_done == 'false' ): ?&gt;<br>                    &lt;input type="hidden" value="false" name="is\_done" /&gt;<br>                    &lt;input type="submit" class="btn" value="Mark as Done?" name="markasdone\_button" /&gt;<br>                    &lt;?php else: ?&gt;<br>                    &lt;input type="hidden" value="true" name="is\_done" /&gt;<br>                    &lt;input type="button" class="btn success" value="Done!" name="done\_button" /&gt;<br>                    &lt;?php endif; ?&gt;<br>                    &lt;input type="hidden" value="&lt;?php echo $todo-&gt;todo\_id; ?&gt;" name="todo\_id" /&gt;<br>                    &lt;input type="hidden" value="&lt;?php echo $todo-&gt;title; ?&gt;" name="title" /&gt;<br>                    &lt;input type="submit" class="btn primary" value="Save Changes" name="update\_button" /&gt;<br>                &lt;/div&gt;<br>                &lt;/form&gt;<br>            &lt;/div&gt;<br>            &lt;?php endforeach; ?&gt;<br>        &lt;/div&gt;<br>    &lt;/div&gt;<br>&lt;/body&gt;<br>&lt;/html&gt; |


![](https://note.youdao.com/yws/res/938/706D3F827C1147629608ABD17704D11A)

|   |   |
| - | - |
| 1<br>2<br>3<br>4<br>5<br>6<br>7<br>8<br>9<br>10<br>11<br>12<br>13<br>14<br>15<br>16<br>17<br>18<br>19 | &lt;?php<br>session\_start();<br>include\_once 'apicaller.php';<br> <br>$apicaller = new ApiCaller('APP001', '28e336ac6c9423d946ba02d19c6a2632', 'http://localhost/simpletodo\_api/');<br> <br>$new\_item = $apicaller-&gt;sendRequest(array(<br>    'controller' =&gt; 'todo',<br>    'action' =&gt; 'create',<br>    'title' =&gt; $\_POST['title'],<br>    'due\_date' =&gt; $\_POST['due\_date'],<br>    'description' =&gt; $\_POST['description'],<br>    'username' =&gt; $\_SESSION['username'],<br>    'userpass' =&gt; $\_SESSION['userpass']<br>));<br> <br>header('Location: todo.php');<br>exit();<br>?&gt; |


![](https://note.youdao.com/yws/res/936/C4AFF12A03F4467F9B8CC083F4CB67AC)

![](https://note.youdao.com/yws/res/945/0A1C1CF1B21B409D8EBD86DB862B9939)

|   |
| - |
| 结论<br>围绕API创建并开发应用具有如此之多的优势。想创建一个Android版的SimpleTODO？你需要的所有功能都已经在API服务器上了，所以你所要做的就是创建客户端！想重构或者优化某些类？没问题——只要确保输出相同即可。想添加更多的功能？你可以在不影响任何客户端代码的前提下做到！<br>尽管存在着某些像是更长的开发时间或者更加复杂，但是以这种方式开发网络应用的优势却远比其劣势更重要。今天的这种开发由我们自己权衡取舍，从而使我们能够在将来获益。<br>你是准备使用一台API服务器作为你的下一个Web应用，还是已经在过去的项目中使用了相同的技术？请在评论中告知！ |
