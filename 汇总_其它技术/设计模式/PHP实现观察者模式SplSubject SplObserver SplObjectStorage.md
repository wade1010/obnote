“观察者模式”的观察者三个字信息量很大。玩过很多网络游戏的童鞋们应该知道，即便是斗地主，除了玩家，还有一个角色叫“观察者"。在我们今天他谈论的模式设计中，观察者也是如此。首先，要有一个“主题”。只有有了一个主题，观察者才能搬着小板凳儿聚在一堆。其次，观察者还必须要有自己的操作。否则你聚在一堆儿没事做也没什么意义。

　　　从面向过程的角度来看，首先是观察者向主题注册，注册完之后，主题再通知观察者做出相应的操作，整个事情就完了。

　　　从面向对象的角度来看，主题提供注册和通知的接口，观察者提供自身操作的接口。（这些观察者拥有一个同一个接口。）观察者利用主题的接口向主题注册，而主题利用观察者接口通知观察者。耦合度相当之低。

         如何实现观察者注册？通过前面的注册者模式很容易给我们提供思路，把这些对象加到一棵注册树上就好了嘛。如何通知？这就更简单了，对注册树进行遍历，让每个对象实现其接口提供的操作



```javascript
<?php
class User implements SplSubject{
	public $loginNum;
	public $hobby;
	protected $observers=null;
	public function __construct($hobby){
		$this->loginNum=rand(1,10);
		$this->hobby=$hobby;
		$this->observers=new SplObjectStorage();
	}
	public function attach(SplObserver $observer)
	{
		$this->observers->attach($observer);
	}
	public function detach(SplObserver $observer)
	{
		$this->observers->dettach($observer);
		
	}
	public function notify()
	{
		$this->observers->rewind();
		while ($this->observers->valid()) {
			$observer=$this->observers->current();
			$observer->update($this);
			$this->observers->next();
		}
		
	}
	public function longin()
	{
		$this->notify();
	}
}

class Security  implements SplObserver
{
	public function update(SplSubject $subject)
	{
		if($subject->loginNum<3){
			echo "这是您第{$subject->loginNum}次安全登录!\n";
		}else{
			echo "这是您第{$subject->loginNum}次异常登录!\n";
		}
	}
}

class Ad implements SplObserver
{
	public function update(SplSubject $subject)
	{
		if($subject->hobby=='sport'){
			echo "篮球世锦赛门票预订\n";		
		}else{
			echo "好好学习天天向上\n";
		}
	}
}

class Student implements SplObserver{
	public function update(SplSubject $subject)
	{
		if($subject->hobby=='sport'){
			echo "这是您第{$subject->loginNum}次安全登录!\n";		
		}else{
			echo "好好学习天天向上\n";
		}
	}
}

#实施观察 

$user =  new User('sutudy');

$observer1 = new Security();
$observer2 = new Ad();

$user->attach($observer1);
$user->attach($observer2);

$user->longin();
```

