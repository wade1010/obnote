![](https://gitee.com/hxc8/images3/raw/master/img/202407172250644.jpg)

![](https://gitee.com/hxc8/images3/raw/master/img/202407172250824.jpg)

![](https://gitee.com/hxc8/images3/raw/master/img/202407172250838.jpg)

![](https://gitee.com/hxc8/images3/raw/master/img/202407172250170.jpg)

![](https://gitee.com/hxc8/images3/raw/master/img/202407172250213.jpg)

![](https://gitee.com/hxc8/images3/raw/master/img/202407172250214.jpg)

同步调用

![](https://gitee.com/hxc8/images3/raw/master/img/202407172250902.jpg)

![](images/WEBRESOURCEf9805222e879628541ac40dc25a3dec1截图.png)

多线程调用

![](https://gitee.com/hxc8/images3/raw/master/img/202407172250179.jpg)

![](https://gitee.com/hxc8/images3/raw/master/img/202407172250116.jpg)

异步写法

```
use std::time::Duration;

#[tokio::main]
async fn main() {
    println!("Hello before reading file!");
    let h1 = tokio::spawn(async {
        let _file_contents = read_from_file1().await;
    });

    let h2 = tokio::spawn(async {
        let _file2_contents = read_from_file2().await;
    });

    let _ = tokio::join!(h1, h2);
}
async fn read_from_file1() -> String {
    tokio::time::sleep(Duration::new(4, 0)).await;
    println!("{:?}", "Processing file 1...");
    String::from("content 1")
}

async fn read_from_file2() -> String {
    tokio::time::sleep(Duration::new(2, 0)).await;
    println!("{:?}", "Processing file 2...");
    String::from(String::from("content 2"))
}

```

![](https://gitee.com/hxc8/images3/raw/master/img/202407172251364.jpg)

![](https://gitee.com/hxc8/images3/raw/master/img/202407172251341.jpg)

![](https://gitee.com/hxc8/images3/raw/master/img/202407172251505.jpg)

![](https://gitee.com/hxc8/images3/raw/master/img/202407172251440.jpg)

![](https://gitee.com/hxc8/images3/raw/master/img/202407172251054.jpg)

![](https://gitee.com/hxc8/images3/raw/master/img/202407172251891.jpg)

```
use std::future::Future;
use std::pin::Pin;
use std::task::{Context, Poll};
use std::time::Duration;

struct ReadFileFuture {}
impl Future for ReadFileFuture {
    type Output = String;
    /*
    这里需要用到Pin，是因为Future会被异步运行时反复poll,所以把这个future给固定到内存的某一特定位置
    这对异步代码的安全性来说，是必要的，
    Pin是比较高级的内容，这里方法签名是固定的。可以不去理解它，按着要求做就行

    poll会被tokio运行的执行器所调用，执行器会尝试解析future来得到最终的结果，本例中最终的结果就是String类型
    如果值不可用，也就是pending，那么当前这个任务就会被注册到Waker这个组件，以便当future里面的值变得可用时，
    waker组件就可以通过运行时再次调用future里面的这个poll方法
    */
    fn poll(self: Pin<&mut Self>, cx: &mut Context<'_>) -> Poll<Self::Output> {
        println!("Tokio! Stop polling me");
        Poll::Pending
    }
}
#[tokio::main]
async fn main() {
    println!("Hello before reading file...");
    let h1 = tokio::spawn(async {
        let future1 = ReadFileFuture {};
        future1.await
    });
    let h2 = tokio::spawn(async {
        let file2_contents = read_from_file2().await;
        println!("{:?}", file2_contents);
    });
    let _ = tokio::join!(h1, h2);
}

fn read_from_file2() -> impl Future<Output = String> {
    async {
        tokio::time::sleep(Duration::new(2, 0)).await;
        println!("{:?}", "Processing file 2");
        String::from("hello,there from file 2")
    }
}

执行结果
Hello before reading file...
Tokio! Stop polling me
"Processing file 2"
"hello,there from file 2"


然后程序就会hung住
```

修改下代码 

![](https://gitee.com/hxc8/images3/raw/master/img/202407172251918.jpg)

上例子修改部分代码如下

```
impl Future for ReadFileFuture {
    type Output = String;
    /*
    这里需要用到Pin，是因为Future会被异步运行时反复poll,所以把这个future给固定到内存的某一特定位置
    这对异步代码的安全性来说，是必要的，
    Pin是比较高级的内容，这里方法签名是固定的。可以不去理解它，按着要求做就行

    poll会被tokio运行的执行器所调用，执行器会尝试解析future来得到最终的结果，本例中最终的结果就是String类型
    如果值不可用，也就是pending，那么当前这个任务就会被注册到Waker这个组件，以便当future里面的值变得可用时，
    waker组件就可以通过运行时再次调用future里面的这个poll方法
    */
    fn poll(self: Pin<&mut Self>, cx: &mut Context<'_>) -> Poll<Self::Output> {
        println!("Tokio! Stop polling me");
        cx.waker().wake_by_ref(); //这样就会通知tokio运行时，异步任务已经准备好了，可以被安排再次执行了，但是本例中，结果仍是pending
        Poll::Pending
    }
}
```

再次执行结果，就会不断打印stop polling me 

![](https://gitee.com/hxc8/images3/raw/master/img/202407172251168.jpg)

![](https://gitee.com/hxc8/images3/raw/master/img/202407172251432.jpg)

![](images/WEBRESOURCEc5d69fc363b7e43823c6cca146749a76截图.png)

![](https://gitee.com/hxc8/images3/raw/master/img/202407172251836.jpg)

![](https://gitee.com/hxc8/images3/raw/master/img/202407172251954.jpg)

```
impl Future for ReadFileFuture {
    type Output = String;
    /*
    这里需要用到Pin，是因为Future会被异步运行时反复poll,所以把这个future给固定到内存的某一特定位置
    这对异步代码的安全性来说，是必要的，
    Pin是比较高级的内容，这里方法签名是固定的。可以不去理解它，按着要求做就行

    poll会被tokio运行的执行器所调用，执行器会尝试解析future来得到最终的结果，本例中最终的结果就是String类型
    如果值不可用，也就是pending，那么当前这个任务就会被注册到Waker这个组件，以便当future里面的值变得可用时，
    waker组件就可以通过运行时再次调用future里面的这个poll方法
    */
    fn poll(self: Pin<&mut Self>, cx: &mut Context<'_>) -> Poll<Self::Output> {
        println!("Tokio! Stop polling me");
        //添加下面这行就会通知tokio运行时，异步任务已经准备好了，可以被安排再次执行了，但是本例中，结果仍是pending
        cx.waker().wake_by_ref();
        // Poll::Pending 修改成如下代码
        Poll::Ready(String::from("Hello,there from file 1"))
    }
}
```

Hello before reading file...

Tokio! Stop polling me

"Processing file 2"

"hello,there from file 2"

- 终端将被任务重用，按任意键关闭。

程序可以正常结束

![](https://gitee.com/hxc8/images3/raw/master/img/202407172251343.jpg)

![](https://gitee.com/hxc8/images3/raw/master/img/202407172251526.jpg)

![](https://gitee.com/hxc8/images3/raw/master/img/202407172251917.jpg)

![](https://gitee.com/hxc8/images3/raw/master/img/202407172251742.jpg)

![](https://gitee.com/hxc8/images3/raw/master/img/202407172251993.jpg)

```
use std::future::Future;
use std::pin::Pin;
use std::task::{Context, Poll};
use std::time::{Duration, Instant};

struct AsyncTimer {
    expiration_time: Instant,
}
impl Future for AsyncTimer {
    type Output = String;

    fn poll(self: Pin<&mut Self>, cx: &mut Context<'_>) -> Poll<Self::Output> {
        if Instant::now() >= self.expiration_time {
            println!("hello, it is time for future 1");
            Poll::Ready(String::from("Future 1 has completed"))
        } else {
            println!("hello, it is not yet time for future 1.Going to sleep");
            let waker = cx.waker().clone();
            let expiration_time = self.expiration_time;
            std::thread::spawn(move || {
                let current_time = Instant::now();
                if current_time < expiration_time {
                    std::thread::sleep(expiration_time - current_time);
                }
                waker.wake();
            });
            Poll::Pending
        }
    }
}

#[tokio::main]
async fn main() {
    let h1 = tokio::spawn(async {
        let future1 = AsyncTimer {
            expiration_time: Instant::now() + Duration::from_secs(4),
        };
        println!("{:?}", future1.await);
    });
    let h2 = tokio::spawn(async {
        let file2_contents = read_from_file2().await;
        println!("{:?}", file2_contents);
    });

    let _ = tokio::join!(h1, h2);
}

fn read_from_file2() -> impl Future<Output = String> {
    async {
        tokio::time::sleep(Duration::from_secs(2)).await;
        // std::thread::sleep(Duration::from_secs(2));
        String::from("Future 2 hash completed")
    }
}

```

hello, it is not yet time for future 1.Going to sleep

"Future 2 hash completed"

hello, it is time for future 1

"Future 1 has completed"