一个漂亮的PHP验证码来源：PHP100中文网   时间：2013-11-06 11:58:28   阅读数：112322

分享到：32

[导读] 自己导入字体，可以按照自己的额需要随便修改。

自己导入字体，可以按照自己的额需要随便修改。

![](https://gitee.com/hxc8/images8/raw/master/img/202407191110858.jpg)



<?php
	class Imagecode{
		private $width ;
		private $height;
		private $counts;
		private $distrubcode;
		private $fonturl;
		private $session;
		function __construct($width = 120,$height = 30,$counts = 5,$distrubcode="1235467890qwertyuipkjhgfdaszxcvbnm",$fonturl="C:\Windows\Fonts\TektonPro-BoldCond.otf"){
			$this->width=$width;
			$this->height=$height;
			$this->counts=$counts;
			$this->distrubcode=$distrubcode;
			$this->fonturl=$fonturl;
			$this->session=$this->sessioncode();
			session_start();
			$_SESSION['code']=$this->session;
		}
		
		 function imageout(){
			$im=$this->createimagesource();
			$this->setbackgroundcolor($im);
			$this->set_code($im);
			$this->setdistrubecode($im);
			ImageGIF($im);
			ImageDestroy($im); 
		}
		
		private function createimagesource(){
			return imagecreate($this->width,$this->height);
		}
		private function setbackgroundcolor($im){
			$bgcolor = ImageColorAllocate($im, rand(200,255),rand(200,255),rand(200,255));//±³¾°ÑÕÉ«
			imagefill($im,0,0,$bgcolor);
		}
		private function setdistrubecode($im){
			$count_h=$this->height;
			$cou=floor($count_h*2);
			for($i=0;$i<$cou;$i++){
				$x=rand(0,$this->width);
				$y=rand(0,$this->height);
				$jiaodu=rand(0,360);
				$fontsize=rand(8,15);
				$fonturl=$this->fonturl;
				$originalcode = $this->distrubcode;
				$countdistrub = strlen($originalcode);
				$dscode = $originalcode[rand(0,$countdistrub-1)];
				$color = ImageColorAllocate($im, rand(40,140),rand(40,140),rand(40,140));
				imagettftext($im,$fontsize,$jiaodu,$x,$y,$color,$fonturl,$dscode);
				
			}
		}
		private function set_code($im){
				$width=$this->width;
				$counts=$this->counts;
				$height=$this->height;
				$scode=$this->session;
				$y=floor($height/2)+floor($height/4);
				$fontsize=rand(30,35);
				$fonturl="C:\Windows\Fonts\AdobeGothicStd-Bold.otf";//$this->fonturl;
				
				$counts=$this->counts;
				for($i=0;$i<$counts;$i++){
					$char=$scode[$i];
					$x=floor($width/$counts)*$i+8;
					$jiaodu=rand(-20,30);
					$color = ImageColorAllocate($im,rand(0,50),rand(50,100),rand(100,140));
					imagettftext($im,$fontsize,$jiaodu,$x,$y,$color,$fonturl,$char);
				}
				
			
			
		}
		private function sessioncode(){
				$originalcode = $this->distrubcode;
				$countdistrub = strlen($originalcode);
				$_dscode = "";
				$counts=$this->counts;
				for($j=0;$j<$counts;$j++){
					$dscode = $originalcode[rand(0,$countdistrub-1)];
					$_dscode.=$dscode;
				}
				return $_dscode;
				
		}
	}
	Header("Content-type: image/GIF");
	$imagecode=new  Imagecode(160,50);
	$imagecode->imageout();





![](https://gitee.com/hxc8/images8/raw/master/img/202407191110002.jpg)

除非特别声明，PHP100新闻均为原创或投稿报道，转载请注明作者及原文链接

原文地址：http://www.php100.com/html/php/hanshu/2013/1106/6350.html

分享到：32

收藏

上一篇：开发者必备，超实用的PHP代码片段！

下一篇：PHP识别电脑还是手机访问网站

评论(7人参与，6条评论)

![](https://gitee.com/hxc8/images8/raw/master/img/202407191110034.jpg)

![](https://gitee.com/hxc8/images8/raw/master/img/202407191110166.jpg)

- 微博登录

- QQ登录

- 游客

- 最新评论

![](https://gitee.com/hxc8/images8/raw/master/img/202407191110336.jpg)

2015年9月11日 13:56曼陀罗[天津市网友]

楼主imagettftext($im, $fontsize, $jiaodu, $x, $y, $color, $fonturl, $dscode);报错咋办！

回复分享

![](https://gitee.com/hxc8/images8/raw/master/img/202407191110441.jpg)

2015年9月11日 13:55曼陀罗[天津市网友]

imagettftext

回复分享

![](https://gitee.com/hxc8/images8/raw/master/img/202407191110618.jpg)

2015年6月3日 18:29盛雪[北京市网友]

不错，换了字体文件就能显示了

回复分享

![](https://gitee.com/hxc8/images8/raw/master/img/202407191110586.jpg)

2015年4月14日 15:23志足意满[北京市网友]

不显示东西啊 只有背景图

1回复分享

![](https://gitee.com/hxc8/images8/raw/master/img/202407191110782.jpg)

2015年4月14日 15:00志足意满[北京市网友]

分享图片

![](https://gitee.com/hxc8/images8/raw/master/img/202407191110904.jpg)

回复分享

![](https://gitee.com/hxc8/images8/raw/master/img/202407191110739.jpg)

2015年4月10日 7:56恶魔

@

![](https://gitee.com/hxc8/images8/raw/master/img/202407191110415.jpg)

回复分享

PHP100正在使用畅言