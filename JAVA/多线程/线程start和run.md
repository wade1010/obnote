之所以出现线程，就是为了更好的利用CPU，让她更加“精明”的干活。



通过调用Thread类的start()方法来启动一个线程，

这时此线程是处于就绪状态，

并没有运行。

然后通过此Thread类调用方法run()来完成其运行操作的，

这里方法run()称为线程体，

它包含了要执行的这个线程的内容，

Run方法运行结束，

此线程终止，

而CPU再运行其它线程，



而如果直接用Run方法，

这只是调用一个方法而已，

程序中依然只有主线程--这一个线程，

其程序执行路径还是只有一条，

这样就没有达到写线程的目的。



记住：线程就是为了更好地利用CPU，

提高程序运行速率的！



public class TestThread1{

 public static void main(String[] args){

  Runner1 r=new Runner1();

  //r.run();//这是方法调用，而不是开启一个线程

  Thread t=new Thread(r);//调用了Thread(Runnable target)方法。且父类对象变量指向子类对象。

  t.start();

  

  for(int i=0;i<100;i++){

   System.out.println("进入Main Thread运行状态");

   System.out.println(i);

  }

 }

}

class Runner1 implements Runnable{ //实现了这个接口，jdk就知道这个类是一个线程

 public void run(){

  

  for(int i=0;i<100;i++){

   System.out.println("进入Runner1运行状态");

   System.out.println(i);

  }

 }

}