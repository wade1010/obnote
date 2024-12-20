by zhangxinxu from http://www.zhangxinxu.com

本文地址：http://www.zhangxinxu.com/wordpress/?p=4371

一、最近我在干嘛

最近产出较少，都干嘛去了呢！国庆前夕（节假日就是各个项目的截止日）火火火火忙了个把星期；国庆黄金周钓了一周的鱼，肌肉都练出来了；节后折腾自己的一个开源项目，蛮大蛮有前途的（自我感觉而已，呵呵~），现在进行了80%了，所以文章就没时间生产了。其实呢，是搬新家后，上下班时间长了，时间被打得太碎，加上回家还要时不时播种，腾不出整段时间弄文章。

以前孤家寡人、公司半里路，时间太好掌控了，所以有很多的时间折腾。现在嘛，只能说，人在江湖飘，哪有不挨刀。突然想起了facebook给女员工冰冻卵子的新闻，可以理解：安心卖命，少点有的没的的，用心工作。

今天周末，鱼钓过了、车子保养好了、超市转过了、菜市场跑过了、晚饭吃好了、锅碗刷好了，哦~今天是夫人刷的，我就说怎么时间多了点呢，新番也大致过了，终于，可以安安静静坐下敲键盘了。恩，争取周末把这篇搞出来。

二、响应式图片srcset属性

说起图片的srcset属性，估计有不少与时俱进的小伙伴会在心中不由自主念想道：“这个我知道的，可以根据屏幕密度现实对应尺寸图片，例如……”

上面代码对应demo轻戳这里。当然，我们也可以简写成：

由于我们都不是“别人家”的公司，因此，我们的办公PC显示器默认设备像素比都是1，因此，这些显示器呈现的图片默认都是128像素宽度的。下面问题来了，（不是挖掘机哪家强），如何让屌丝显示器模拟高设备像素比呢？

方法一：

Chrome浏览器，切换设备模式，如下截图(v38):

![](https://gitee.com/hxc8/images7/raw/master/img/202407190803414.jpg)

然后，选择对应的设备，例如iPhone6 Plus的设备像素比就是3.

![](https://gitee.com/hxc8/images7/raw/master/img/202407190803621.jpg)

此时，刷新页面，加载的就是大尺寸图片，也就是256像素宽度那张。

方法二：

Chrome浏览器，Ctrl+✚快捷键，或者ctrl + 鼠标滚轮，放大页面的比例，例如，试试放大到150%：

![](https://gitee.com/hxc8/images7/raw/master/img/202407190803808.jpg)

此时设备像素比window.devicePixelRatio为1.5，因此加载的就是256像素宽度的图片。有图有真相：

![](https://gitee.com/hxc8/images7/raw/master/img/202407190803837.jpg)

不同的2x显示策略

还有些时候，使用同尺寸的高清图片作为2x对应图片，虽然两者图片大小差不多，但个人觉得还是2倍尺寸优化大图更好一点，为什么呢？

![](https://gitee.com/hxc8/images7/raw/master/img/202407190803898.jpg)

srcset当初设计的用意是为了高密度屏幕上图片更好的显示，如果世界上就只有“不同设备密度”这一个戏剧冲突的话，2x图片是高清图还是2倍尺寸图其实都无伤大雅。然而，事实上，生活无处不戏剧，现代web布局中，有种布局不可忽略，那就是「响应式布局」，剧本往往会这样，PC浏览器上显示大图，Mobile浏览器上显示小图。发现没，同样是“大小图的要求”，和设备像素比有类似的戏剧冲突。

于是，如果我们2x图片使用的是高清图，结合响应式布局，我们可能需要4张图片资源，即：小图、小图高清和大图、大图高清。但是，2x图片走的是2倍尺寸图片，我们只需要3张图片资源，即：小图、中图以及中图、大图。

在老一代srcset规范成型过程中，其实已经考虑到与响应式布局的纠葛，出现了w描述符，例如，走高清路线的：

走2倍尺寸路线的：

注意啊注意：千万不要去关心上面的w描述符的含义，因为新的srcset属性中w描述符含义与之完全不同，为了避免理解冲突，心中跟我默念3遍：忘掉它、忘掉它、忘掉它，无视它、无视它、无视它。大家可以把精力放在下面，新的srcset规范以及新的sizes属性语法含义等。

三、恰如其分的时间点

我们去caniuse上查看srcset属性的浏览器支持情况，会发现有黄黄绿绿的颜色：

![](https://gitee.com/hxc8/images7/raw/master/img/202407190803923.jpg)

可以看到，Chrome浏览器下，从版本38开始是绿色的，而之前是黄绿色。黄绿色表示仅支持旧的srcset规范，绿色表示支持全新的srcset规范，包括sizes属性，w描述符。

而Chrome 38稳定版就在上周发布（腾讯软件管家显示10月16号也就是前几天发布的），于是，我们就有条件实践srcset新规范特性，有利于快速学习。

因此，我才选择在这个时间点写这篇文章，推产品也是如此，需要一个合适的时间点，太早的死在沙滩上，太晚的落后跟不上。

因此，若要学习本文内容，看看您的Chrome浏览器是否是38+.

于此同时，苹果刚发布了4x的显示器，如果使用传统srcset，需要1x, 2x, 3x, 4x, 我勒个去，HTML立马变成了臃肿的大胖子。与时俱进势在必行！

四、全新的智能srcset、sizes属性, w描述符

旧的srcset是人主导，而现在新的srcset是浏览器主导，你主需要提供图片资源、以及断点，其他都交给浏览器智能解决，什么响应宽度啊、设备像素比啊，你都不要关心了，浏览器会自动匹配最佳显示图片。你的表情现在是不是这样子啊：

![](https://gitee.com/hxc8/images7/raw/master/img/202407190803009.jpg)

如下HTML示例：

srcset="mm-width-128px.jpg 128w, mm-width-256px.jpg 256w, mm-width-512px.jpg 512w"     sizes="(max-width: 360px) 340px, 128px">

其中1：

srcset用来指向提供的图片资源，注意，仅仅是资源指向，没有以前的1x, 2x什么的，这个都交给浏览器了，我们不需要关心！例如这里，指向了3个尺寸图片，分别实际尺寸128像素，256像素和512像素。

其中2：

sizes用来表示尺寸临界点，主要跟响应式布局打交道。语法如下：

sizes="[media query] [length], [media query] [length] ... etc"

例如上述代码中，size = "(max-width: 360px) 340px, 128px"表示当视区宽度不大于360像素时候，图片的宽度限制为340像素，其他情况下，使用128像素（对应下面demo页面第1张图）。

如果sizes="128px", 则尺寸就一直是128像素，图片只会根据设备像素比发生变化。

注意，这里所有的值都是指宽度值，且单位任意，em, px, cm, vw, ...都是可以的，甚至可以CSS3的calc计算（对应下面demo页面第2张图），例如：

sizes="(max-width: 360px) calc(100vw - 20px), 128px"

表示当视区宽度不大于360像素时候，图片宽度为整个视区宽度减去20像素的大小。

OK，上面2个属性具体如何起作用的呢？首先，你需要狠狠地点击这里：srcset与sizes新释义w描述符示意demo

1. 设备像素比的对应显示

按照Part2的提示，F12点击切换设备模式，我们先看下大宽度尺寸下的显示，例如，我们选择个iPad 1：

![](https://gitee.com/hxc8/images7/raw/master/img/202407190803965.jpg)

其中fit前面的钩钩去掉，以更清晰的显示。此时，大家可以看到，图片显示的是128像素宽度的。为什么呢？

还记得咱们的sizes属性值吗？最后光秃秃没有媒体查询的值就是128px, 目前视图宽度是1024像素，远远大于360像素这个临界宽度值，因此，图片占据宽度是128像素。同时，最最重要的是，这里的设备像素比是1.

![](https://gitee.com/hxc8/images7/raw/master/img/202407190803920.jpg)

因此，我们可以正儿八经显示实际宽度是128像素的那张图了。最终显示如下：

![](https://gitee.com/hxc8/images7/raw/master/img/202407190803856.jpg)

//zxx: 上下两张图上面已经提到过，代码差异仅仅是sizes的一个临界宽度值不一样，上面的是340px，下面的是calc(100vw - 20px)，有助于大家深入理解w描述符，后面有大量戏份，当然，这里，大家可暂时不用关心。

OK，保持宽度不变，我们变一个小魔术，下面，把设备像素比1改成2然后刷新页面：

![](https://gitee.com/hxc8/images7/raw/master/img/202407190803929.jpg)

于是，当当当当，见证奇迹的时刻到了，图片256了：

![](https://gitee.com/hxc8/images7/raw/master/img/202407190803880.jpg)

同样的，我们修改设备像素比是3再刷新页面，会发现图片加载的是512宽度那种：

![](https://gitee.com/hxc8/images7/raw/master/img/202407190803947.jpg)

如果改成4+，还是上面这种512像素宽度的图片，因为这是我们给浏览器提供的最大w对应的图片了。

大家注意到木有，我们完全没有指定浏览器那个设备像素比使用哪个图片，浏览器非常smart帮我们加载了最佳显示的图片。这就是最新的srcset的释义，更智能更强大。

2. sizes的媒体查询

下面我们深入理解下sizes属性的作用机制。

首先，我们修改设备为Google Nexus5, 此时视区宽度为360像素，正好是sizes设置的临界宽度值，修改设备像素比为1（去除其他测试干扰因素），刷新页面，会发现页面加载的是512这张图：

![](D:/download/youdaonote-pull-master/data/Technology/页面样式/images/D7A0B45AF9574AF386A5F9AC8F1282292014-10-19_192428.png)

为什么加载是512呢？

依次看来，首先第一张图，对应值是(max-width: 360px) 340px, 意思通俗易懂，视区宽度不大于360像素时候，图片实际尺寸340像素。此demo两侧各有10像素空白，因此，正好图片满屏显示的感觉。下面问题来了，为什么加载的是512这种图，而不是256？

大家还记不记得srcset中每张图片资源后面的w描述符的值大小？没错，分别是128w, 256w, 512w. 正好这里解释下w描述符的意思，根据我个人的理解（注意，是个人理解，仅供参考），w用来描述文件的宽度，我们可以形象理解为规格，就像手机一样，有大小不一样的尺寸与规格。根据我浅薄的经验，我们可以直接等同于像素去理解。可能你会疑问，那为何不直接256px而是256w, 因为，请注意，这里的256w并不是指图片的宽度，而是图片的宽度规格，例如，一张图片实际宽度是256像素，但是，这种图片是png24无损图片，或100%质量JPG图片，则，我们可以使用512w表示这张图片，质量好规格就高，不难理解吧！如果是512px显然就有问题了，明明图片256像素，搞个512px，歧义到王母娘娘哪儿去了！因此，需要一个没人见过的w表示，同时，需要意识到，w不是单位，而是一个描述符(descriptor)。

回到主线，图片340像素，可以看出340规格，340w, 于是256w这张图片显示就规格不够，简称“不够格”；512w > 340w，满足最优显示准则，于是，加载的就是512px这张图。

如果你希望视图360像素宽度时候加载的是256这张图片，很简单，修改其规格256w为340w, done!

OK, 注意注意，前方高能！！

下面，不是下饺子，修改宽度为一个吉利的数字 – 250, 然后刷一下（注意设备像素比要为1）~~会发现，上下两张图的差异来了，如下截图：

![](https://gitee.com/hxc8/images7/raw/master/img/202407190803661.jpg)

虽然图片都是100%父容器显示，但加载的图片却不一样。咦？为何会不一样呢？

![](https://gitee.com/hxc8/images7/raw/master/img/202407190803722.jpg)

其实很好理解，前者是340px, 后者是calc(100vw - 20px)，一个是定值，一个动态的。前者永远是340w规格，而后者在尺寸是250像素时候，实际像素是230px, 可以看成230w，比256w小，于是就加载了256这种图片啦！

因此，如果大家希望图片的加载进一步智能，建议使用动态单位。

![](https://gitee.com/hxc8/images7/raw/master/img/202407190803842.jpg)

五、结束语

该说些什么的，好像没什么特别想吐槽的，就这样~

感谢阅读，欢迎纠错，欢迎交流。

![](https://gitee.com/hxc8/images7/raw/master/img/202407190803918.jpg)

本文为原创前沿文章，技术发展变幻莫测，一定会更新知识点以及修正一些错误，因此转载请保留原出处，方便溯源，避免陈旧错误知识对新人的误导，同时有更好的阅读体验。

本文地址：http://www.zhangxinxu.com/wordpress/?p=4371

（本篇完）

如果您觉得本文的内容对您的学习有所帮助，您可以支付宝(左)或微信(右)：

![](https://gitee.com/hxc8/images7/raw/master/img/202407190803990.jpg)

![](https://gitee.com/hxc8/images7/raw/master/img/202407190803307.jpg)

相关文章

- HTML5响应式图片技术中文图解 (0.574)

- 热门：响应图片(Responsive Images)技术简介 (0.402)

- 应运而生的web页面响应布局 (0.115)

- 翻译：web响应设计，乏味！ (0.115)

- 伪类+js实现CSS3 media queries跨界准确判断 (0.115)

- IE6下png背景不透明问题的综合拓展 (0.024)

- jQuery之图片关联伸缩效果 (0.024)

- 搜狐白社会似iphone短信对话框效果的优化 (0.024)

- JavaScript实现图片幻灯片滚动播放动画效果 (0.024)

- 告别图片—使用字符实现兼容性的圆角尖角效果beta版 (0.024)

- 页面可用性之img标签longdesc属性与HTML5 (RANDOM - 0.024)

分享到： 2

标签： sizes, srcset, w描述符, 响应图片, 响应布局, 图片

这篇文章发布于 2014年10月19日，星期日，20:10，归类于 css相关。 阅读 18744 次, 今日 53 次