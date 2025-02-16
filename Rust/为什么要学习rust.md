一、rust的实现、优点

- 实现：明确/（零成本）抽象/赋能

- 优点：兼具高性能/安全性/表达力

明确：rust不像其他的语言，这些编程语言为了照顾初学者，它会把很多基本概念隐藏在基本语法之后，它给你营造一种假象，你可以不用了解基础知识，基本的概念，你也可以写好程序。如像python中，你 一直在做一些上层的东西，对底层，Python的对象时如何在堆和栈中分配的，这种完全不用理会。但是当你做到一些深度的项目的时候，尤其是提升Python的性能，和其他语言交互来做高性能处理的时候，你就会发现很多很多的概念需要使用，不得不去重新学习一些东西。

rust它会把这些细节完全屏蔽在语法之外，比如你在使用数据结构的时候，哪些数据时放在堆上哪些是放在栈上的，rust是很明确的，你只需要一开始把它学明白就行。

抽象：rust非常提倡零成本抽象，它的很多语言细节、库的实现、核心的组成部分，它都使用了非常漂亮的零成本抽象，比如Future这个对象，拥有零成本抽象的好处是我们可以享受到高性能的同时，还能在语法层面、在使用层面使用的感觉还可以像那些脚本语言类似。

再使用rust的过程中你会发现从系统层一直到web层，各个层级的开发任务都可以用rust来写

优点

高性能：就不必多说了，诞生之初就是对标的就是C语言和C++，所以rust无论在提供的能力上面还是在二进制的兼容方面，它都是跟C和C++是看齐的。Rust的代码也是非常容易的和C/C++互相操作的，因为他们共享相同的ABI。

rust直接编译成机器语言，你编译出来的代码没有一个运行时，比如golang，你的用户代码最终编译成二进制的时候，里面塞进去了一个golang的runtime，这个runtime在你不需要的时候，实际上它会影响性能的。而rust，没有这方面的问题，所以它的性能是非常的高。

安全性：是一门主打安全性的语言，它期望提供系统级开发的能力前提下，能够尽可能的让系统安全，C、C++虽然性能确实非常高，它们的安全性，尤其对初学者，不能很好的运用指针，不能很好的考虑并发场景下面如何共享数据的话，很容易写出不安全的代码。

表达力：如果你去尝试写过rust的话，你会发现写很多常规代码的时候，它的表达力和Python、lips这些比较高阶的语言是非常类似的

为什么安全性对软件系统很重要呢？

![](https://gitee.com/hxc8/images5/raw/master/img/202407172327886.jpg)

![](https://gitee.com/hxc8/images5/raw/master/img/202407172327027.jpg)

![](https://gitee.com/hxc8/images5/raw/master/img/202407172327813.jpg)

初学者对语法不太熟悉，资深工程师在使用的不熟悉的语法的时候，可能会导致语法缺陷，目前大部分编程语言都会在你敲入代码的时候作比较详尽的提示。rust有rust-language-server和rust-analyzer.

类型安全缺陷：这种缺陷就需要语言本身的类型系统来帮助我们把这类缺陷找出来，大部分非类型语言就没有办法了，比如向Python PHP，函数期望的是类型A，使用的时候是类型B，这种错误只有在运行时才能被检查出来。所以目前越来越多的脚本语言也倾向于让开发人员尽可能的多写一些type annotation，但是这个type annotation不是语言原生的一部分。并且不是强制的，所以在写脚本语言的时候特别注意类型安全的问题。有些语言就逐渐进化出来，比如JavaScript进化成typeScript，拥有强大的类型系统之后，它可以最大程度的避免类型安全缺陷。

内存和资源安全缺陷：几乎所有的语言里面都会有类似的问题，那对于有自动内存管理的语言，它可以帮你解决绝大多数内存方面的问题，不会出现内存使用了没有释放，使用已经释放了的内存，使用悬停的指针，这些问题在Python JavaScript ruby这些脚本语言里面就不会存在，因为它整个语言的结构决定了内存安全的问题被他语言的运行时给解决了，虽然解决的方式大家都不一样。但是这里面只是大部分的内存安全问题被解决了。比如你的内存泄露，如果你是人为的或者说在使用过程中不小心造成的内存泄露，一旦你对内存的引用存在的话，那这块内存一直存在于程序运行的生命周期里。所以这块问题，没有语言能完全解决。内存泄露的问题，只能说尽可能的去解决。

资源安全的问题，也是大部分都会有的问题，即便语言本身有自动内存管理，有garbage collector机制去保证内存资源的释放，但是像其他资源，文件、socket、还有操作系统要的资源，如果没有很好的释放的话，那么就会带来资源的泄露，这种问题是很多变成语言都会存在的，尤其是你在做异常处理，非正常流程的处理的时候，很容易遗忘对资源的释放。

rust基本上解决了内存安全和资源的问题，它通过所有权、借用检查、生命周期来保证内存和资源一旦被分配，那在它的生命周期结束的时候，这些内存和资源都会被释放。

并发安全：往往是发生在支持多线程的语言之中，比如在两个线程之间同时访问一个变量，没有做合适的临界区的保护的话，那么就很容易发生并发安全缺陷。rust是通过它的所有权/借用检查/生命周期这套系统加上类型系统来解决并发安全的问题。rust有send和sync两个trait，这两个trait就是去连带所有权/借用检查/声明周期这套机制来解决并发安全的问题。这个问题，也是一个语言单纯拥有GC是很难去解决的，像很多高级的语言，在解决并发安全的时候，它干脆就把整个线程概念给开发者屏蔽了，只允许使用我提供的运行时来保证并发安全，比如golang，只能使用这个channel和goroutine来做这个并发处理，让你没法接触到线程本身，来解决并发安全的问题。这样带来的问题就是，在并发场景下，你需要很多不同的手段，比如有些情况下，你用共享内存来解决并发问题效率比较高，在某些情况下你使用类似像golang这样的channel这样的机制，通过channel来synchronize，这样来处理会更高效，有些情况下你可能使用操作系统提供的信号量会有更高效，总之处理并发有很多很多的手段，但是大部分语言为了并发安全，它把绝大部分手段都给屏蔽掉了，开发者没办法使用这些手段。而rust所有的这些手段，依旧提供给你，但是他提供了一个良好的并发安全的保障。

错误处理：错误处理是非常重要的，对rust来讲，相对那些采用了exception（像java C++）非常不同的一个路径，它也尝试使用类型来解决这个错误，它提供了Result这种类型，这个和Haskell这种语言是非常类似的，并且在这个类型上它强制要求你需要使用类型里面的值，否则的话编译器就会告警，这就极大程度避免了开发者得到一个结果，但是它没有处理这个结果里面所涵盖的错误，这样的问题。但是错误处理，尤其是相关的逻辑缺陷，这是编译器无法帮助你的。

二、rust目前的生态

大量高质量的顶级开源项目，一线公司纷纷才用。

应用场景广泛，从嵌入式系统到web应用。

![](https://gitee.com/hxc8/images5/raw/master/img/202407172327838.jpg)

目前主流的区块链公司和区块链都是用rust开发。

三、rust的现在和未来

国内外主要rust置为方向

- 后端高性能网络服务开发

- 数据库、存储

- 跨平台基础类库、SDK

- 可性计算、数据与网络安全、隐私

- 边缘计算

- 区块链

大厂使用情况及未来潜力

- 字节：飞书 

- pingcap：tikv

- Dropbox、Figma：文件同步引擎

- Cloudflare：边缘计算

- Facebook:  区块链，SCM工具

- Amazon:AWS(e.g firecracker VMM)

- Discord:状态服务

Discord 是用rust改写了golang的状态服务，虽然golang的性能，网络I/O的性能足够满足他们的需求，但是当用户的状态，Discord是实时聊天的工具，用户用他们这个工具的过程中，会经常遇到一些，就是当大量用户同时访问的时候，这个状态服务经常会有一些不太好的情况，为什么会不好能，因为golang的GC，当GC开始工作的时候，就会让服务停顿一下，导致大量用户响应时间在那一刻不好，

四、使用rust的感悟

- 对大部分应用场景，rust的生态足够成熟

- rust让重构代码变得轻松

- rust体现了软件开发的诸多最佳实践，学习rust让你成为更好的程序员

- rust可以帮助提升其他语言的能力

- 思维转换

- 抓住现象背后的本质

- 在实践中学习

![](https://gitee.com/hxc8/images5/raw/master/img/202407172327136.jpg)

当栈结束的时候，内存就会自动被释放，堆上的内存分配出来，如果没有人给它释放的话，这块内存就会一直在那。所以堆上面的所有内存的这个或者说值的声明周期和process一样长的。

rust对这种行为做了限制，就是能不能将堆上的内存的生命周期和栈上的内存的生命周期绑定起来呢。如果绑定起来会产生什么样的结果，它能应用在什么样的场景下。如果它能应用在80%的场景，那么还有20%的场景该怎么样去处理，rust围绕这种方式去构建了所有权和生命周期这套体系，让rust可以在没有一个运行时，不需要GC，不需要Arc这样的额外的负担的情况下，还能帮助开发者去自动管理内存，让开发者不用担心内存安全问题。

rust和golang对比

golang它考虑如何适应新时代并发，所以它使用了一个运行时，这个运行时里面有个调度器，这个调度器来调度各种各样的 goroutine,golang里面内存是不要释放的，所以它还有个GC也在这个运行时里面来帮助开发者管理内存，另外它不支持泛型，可能是目前最被大家诟病的一方面，当你系统复杂到一定程度，你可能需要良好的泛型支持，让这个语言的扩展性更强，否则每种就行就得有一种实现，rust它的设计思路跟golang是相反的，比如golang语言相对小巧，而rust语言本身，很多方面借鉴haskell，所以它的语言本身是相对复杂的。它有完整的类型系统来支持泛型。这也就是rust在编译的时候比golang慢很多，因为所有支持泛型，一旦你要对这个泛型函数要做单体化的话，那么你的编译时间必定极大的增加，未来golang2如果支持泛型的话，也会出现这个问题。

另外一方面rust是面向系统级开发，go虽然说它想做一个更加modern的C，但是它的使用场景并不是应用在系统级开发，更多的是应用程序的开发和服务的开发，因为它有大的运行时在那里放着，就使得它不适合做底层开发

rust开始的目标是替换C和C++做一个更好的系统层面的开发工具，所以它设计之初就要求不能有运行时，所有你看到的运行时，比如tokio、第三方这种类似go的运行时这样的工具，它都是在第三方库，不是在语言核心内。你需要用的时候就用这个运行时，你不需要的时候你可以不用这个运行时，把这种自由度完全给了开发者。

另外在异常处理或者错误处理方面，go和rust也走了完全不同的路，go的错误处理也是这门语言被诟病的一个原因，rust的错误处理，它用类型系统来做错误处理，并且使用一些语法方面的技巧，让错误处理非常的惊艳并且非常的简单，

做客户端socket开发的话，可以用标准库tcpstream和tcplistener

做上层web开发的话，warp /actix /rocket  这些库往往会集成了websocket，并且rust对http2 https3也有良好的支持

rust grpc库tonic

如果在做系统，系统对并发和延迟都有很高的要求的话，rust就是很好的语言。看这个系统responsetime的时候，不看它的avg responsetime，这个值没有意义，有可能你的avg response time是2ms，但是你的第99个百分位它的延时可能到了2秒或者是几秒，往往关注性能，最终关注的是p90 p95 p99这样的百分位下面的延时，告诉我们的是对于最慢的用户，他们的延时是什么样的，然后你要解决的是针对这部分用户。

往往p99高延时很可能导致它的一个原因，如果你使用的是GC语言，像golang、java。很可能这种p99产生延时的问题都是由GC引起的，如果你想消除这种延迟带来的问题的话，最好的选择就是用rust来重写。

C++和rust:

rust学习了很多语言的东西。比如生命周期是从phacon（读音对）

智能指针向C++学习

类型系统是想hascell学习的

rust指针和引用他们的行为是更加受控的，你可以认为，当你去写unsafe rust的时候你就是在写C++，在写非unsafe代码的时候，是在C++基础上增加了一大堆规则去写代码。

然后就是对于编译环境，第三方库，大家的使用感官是有写不同，在这些方面rust是比C++要简单的多，比如说在rust下面集成第三方的crate，这个感觉跟Python、JavaScript、nodejs是非常类似的

rust也难，rust难在语法特性，c++难在内容太多