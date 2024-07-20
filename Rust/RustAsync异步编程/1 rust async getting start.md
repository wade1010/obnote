[https://rust-lang.github.io/async-book/](https://rust-lang.github.io/async-book/)

![](https://gitee.com/hxc8/images4/raw/master/img/202407172254835.jpg)

![](https://gitee.com/hxc8/images4/raw/master/img/202407172254637.jpg)

![](https://gitee.com/hxc8/images4/raw/master/img/202407172254656.jpg)

![](https://gitee.com/hxc8/images4/raw/master/img/202407172254611.jpg)

![](images/WEBRESOURCE21c962b5e78ea9a7d0fbc770ad333a48截图.png)

![](https://gitee.com/hxc8/images4/raw/master/img/202407172254507.jpg)

![](https://gitee.com/hxc8/images4/raw/master/img/202407172254856.jpg)

第一个例子是创建两个线程，但是下载网页的任务实在是太小了，创建线程就是巨大的浪费。

而如果要下载成千上万的网页，再使用这种方式，那么这一块就会成为瓶颈

第二个例子就是使用rust async的写法。

这里面函数调用都是静态分配的，而且也没有堆内存的分配，最重要的是，它没有创建额外的线程。

具体怎么回事，以后再讲

![](images/WEBRESOURCE02e6c01d74f63ff30df11255b1612c0e截图.png)

![](https://gitee.com/hxc8/images4/raw/master/img/202407172254513.jpg)

![](https://gitee.com/hxc8/images4/raw/master/img/202407172254678.jpg)

上图的运行时是社区提供的

![](https://gitee.com/hxc8/images4/raw/master/img/202407172254038.jpg)

Tokio和async-std可能是目前比较流行的

![](https://gitee.com/hxc8/images4/raw/master/img/202407172254923.jpg)

![](https://gitee.com/hxc8/images4/raw/master/img/202407172254668.jpg)

![](https://gitee.com/hxc8/images4/raw/master/img/202407172254714.jpg)

![](images/WEBRESOURCE4fd0eaf1773a00e96f54b93058ca4e5e截图.png)

![](https://gitee.com/hxc8/images4/raw/master/img/202407172255353.jpg)

![](https://gitee.com/hxc8/images4/raw/master/img/202407172255007.jpg)

![](https://gitee.com/hxc8/images4/raw/master/img/202407172255275.jpg)

![](https://gitee.com/hxc8/images4/raw/master/img/202407172255212.jpg)

![](https://gitee.com/hxc8/images4/raw/master/img/202407172255202.jpg)

async/.await is Rust's built-in tool for writing asynchronous functions that look like synchronous code. async transforms a block of code into a state machine that implements a trait called Future. Whereas calling a blocking function in a synchronous method would block the whole thread, blocked Futures will yield control of the thread, allowing other Futures to run.

Let's add some dependencies to the Cargo.toml file:

```
[dependencies]
futures = "0.3"

```

To create an asynchronous function, you can use the async fn syntax:

```
async fn do_something() { /* ... */ }

```

The value returned by async fn is a Future. For anything to happen, the Future needs to be run on an executor.

```
// `block_on` blocks the current thread until the provided future has run to
// completion. Other executors provide more complex behavior, like scheduling
// multiple futures onto the same thread.
use futures::executor::block_on;

async fn hello_world() {
    println!("hello, world!");
}

fn main() {
    let future = hello_world(); // Nothing is printed
    block_on(future); // `future` is run and "hello, world!" is printed
}

```

Inside an async fn, you can use .await to wait for the completion of another type that implements the Future trait, such as the output of another async fn. Unlike block_on, .await doesn't block the current thread, but instead asynchronously waits for the future to complete, allowing other tasks to run if the future is currently unable to make progress.

For example, imagine that we have three async fn: learn_song, sing_song, and dance:

```
async fn learn_song() -> Song { /* ... */ }
async fn sing_song(song: Song) { /* ... */ }
async fn dance() { /* ... */ }

```

One way to do learn, sing, and dance would be to block on each of these individually:

```
fn main() {
    let song = block_on(learn_song());
    block_on(sing_song(song));
    block_on(dance());
}

```

However, we're not giving the best performance possible this way—we're only ever doing one thing at once! Clearly we have to learn the song before we can sing it, but it's possible to dance at the same time as learning and singing the song. To do this, we can create two separate async fn which can be run concurrently:

```
async fn learn_and_sing() {
    // Wait until the song has been learned before singing it.
    // We use `.await` here rather than `block_on` to prevent blocking the
    // thread, which makes it possible to `dance` at the same time.
    let song = learn_song().await;
    sing_song(song).await;
}

async fn async_main() {
    let f1 = learn_and_sing();
    let f2 = dance();

    // `join!` is like `.await` but can wait for multiple futures concurrently.
    // If we're temporarily blocked in the `learn_and_sing` future, the `dance`
    // future will take over the current thread. If `dance` becomes blocked,
    // `learn_and_sing` can take back over. If both futures are blocked, then
    // `async_main` is blocked and will yield to the executor.
    futures::join!(f1, f2);
}

fn main() {
    block_on(async_main());
}

```

In this example, learning the song must happen before singing the song, but both learning and singing can happen at the same time as dancing. If we used block_on(learn_song()) rather than learn_song().await in learn_and_sing, the thread wouldn't be able to do anything else while learn_song was running. This would make it impossible to dance at the same time. By .await-ing the learn_song future, we allow other tasks to take over the current thread if learn_song is blocked. This makes it possible to run multiple futures to completion concurrently on the same thread.