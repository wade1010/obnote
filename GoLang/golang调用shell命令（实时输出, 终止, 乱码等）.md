背景

是这样的，最近在研究一个定时任务系统的改造，可能有点像jenkins做到的那种吧。



可以输入shell命令，也可以执行py脚本等等，相比之前来说，也要能够及时停止！



但是遇到了这么个问题，golang执行py脚本的时候获取不到脚本的输出。

首先来看看go里面怎么运行shell脚本吧，我比较喜欢执行全部命令。

普通用法（一次性获取所有输出）

package main



import (

    "fmt"

    "os/exec"

)



func main() {

    Command("ls")

}



// 这里为了简化，我省去了stderr和其他信息

func Command(cmd string) error {

    c := exec.Command("bash", "-c", cmd)

    // 此处是windows版本

    // c := exec.Command("cmd", "/C", cmd)

    output, err := c.CombinedOutput()

    fmt.Println(string(output))

    return err

}



可以看到，当前命令执行的是输出当前目录下的文件/文件夹



image.png

实时显示

效果图:



image.png

package main



import (

    "bufio"

    "fmt"

    "io"

    "os/exec"

    "sync"

)



func main() {

    // 执行ping baidu的命令, 命令不会结束

    Command("ping www.baidu.com")



}



func Command(cmd string) error {

    //c := exec.Command("cmd", "/C", cmd)   // windows

    c := exec.Command("bash", "-c", cmd)  // mac or linux

    stdout, err := c.StdoutPipe()

    if err != nil {

        return err

    }

    var wg sync.WaitGroup

    wg.Add(1)

    go func() {

        defer wg.Done()

        reader := bufio.NewReader(stdout)

        for {

            readString, err := reader.ReadString('\n')

            if err != nil || err == io.EOF {

                return

            }

            fmt.Print(readString)

        }

    }()

    err = c.Start()

    wg.Wait()

    return err

}

可关闭+实时输出

package main



import (

    "bufio"

    "context"

    "fmt"

    "io"

    "os/exec"

    "sync"

    "time"

)



func main() {

    ctx, cancel := context.WithCancel(context.Background())

    go func(cancelFunc context.CancelFunc) {

        time.Sleep(3 * time.Second)

        cancelFunc()

    }(cancel)

    Command(ctx, "ping www.baidu.com")

}



func Command(ctx context.Context, cmd string) error {

    // c := exec.CommandContext(ctx, "cmd", "/C", cmd)

    c := exec.CommandContext(ctx, "bash", "-c", cmd) // mac linux

    stdout, err := c.StdoutPipe()

    if err != nil {

        return err

    }

    var wg sync.WaitGroup

    wg.Add(1)

    go func(wg *sync.WaitGroup) {

        defer wg.Done()

        reader := bufio.NewReader(stdout)

        for {

            // 其实这段去掉程序也会正常运行，只是我们就不知道到底什么时候Command被停止了，而且如果我们需要实时给web端展示输出的话，这里可以作为依据 取消展示

            select {

            // 检测到ctx.Done()之后停止读取

            case <-ctx.Done():

                if ctx.Err() != nil {

                    fmt.Printf("程序出现错误: %q", ctx.Err())

                } else {

                    fmt.Println("程序被终止")

                }

                return

            default:

                readString, err := reader.ReadString('\n')

                if err != nil || err == io.EOF {

                    return

                }

                fmt.Print(readString)

            }

        }

    }(&wg)

    err = c.Start()

    wg.Wait()

    return err

}



效果图:



image.png

可以看到输出了3次（1秒1次）之后程序就被终止了，确切的说是读取输出流的循环结束了。



执行Python脚本(阻塞)

其实很简单，只要python -u xxx.py这样执行就可以了, -u参数



简单的说就是python的输出是有缓存的，-u会强制往标准流输出，当Python脚本阻塞的时候



也不会拿不到输出！

其他

"bash" 和"-c"，据我的观察，这2个参数代表在当前cmd窗口执行，而不加这2个参数，直接上shell的话，会启动一个新窗口，目前观察是stdout拿不到数据。



仍有缺陷

上面的命令可以解决大部分问题，但是获取不到stderr的信息，所以我们需要改造一下。



下面是输出和错误一并输出的实时读取，类似于jenkins那种。

package main



import (

    "bufio"

    "context"

    "fmt"

    "io"

    "os/exec"

    "sync"

    "time"

)



func main() {

    ctx, cancel := context.WithCancel(context.Background())

    go func(cancelFunc context.CancelFunc) {

        time.Sleep(3 * time.Second)

        cancelFunc()

    }(cancel)

    Command(ctx, "ping www.baidu.com")

}



func read(ctx context.Context, wg *sync.WaitGroup, std io.ReadCloser) {

    reader := bufio.NewReader(std)

    defer wg.Done()

    for {

        select {

        case <-ctx.Done():

            return

        default:

            readString, err := reader.ReadString('\n')

            if err != nil || err == io.EOF {

                return

            }

            fmt.Print(readString)

        }

    }

}



func Command(ctx context.Context, cmd string) error {

    //c := exec.CommandContext(ctx, "cmd", "/C", cmd) // windows

    c := exec.CommandContext(ctx, "bash", "-c", cmd) // mac linux

    stdout, err := c.StdoutPipe()

    if err != nil {

        return err

    }

    stderr, err := c.StderrPipe()

    if err != nil {

        return err

    }

    var wg sync.WaitGroup

    // 因为有2个任务, 一个需要读取stderr 另一个需要读取stdout

    wg.Add(2)

    go read(ctx, &wg, stderr)

    go read(ctx, &wg, stdout)

    // 这里一定要用start,而不是run 详情请看下面的图

    err = c.Start()

    // 等待任务结束

    wg.Wait()

    return err

}



image.png

windows输出乱码问题

参考资料： https://blog.csdn.net/rznice/article/details/88122923



最后给一个解决windows乱码的完整案例

 需要下载golang.org/x/text/encoding/simplifiedchinese

package main



import (

    "bufio"

    "fmt"

    "io"

    "os/exec"

    "sync"

    "golang.org/x/text/encoding/simplifiedchinese"

)



type Charset string



const (

    UTF8    = Charset("UTF-8")

    GB18030 = Charset("GB18030")

)



func main() {

    // 执行ping baidu的命令, 命令不会结束

    Command("ping www.baidu.com")



}



func Command(cmd string) error {

    //c := exec.Command("cmd", "/C", cmd)   // windows

    c := exec.Command("bash", "-c", cmd)  // mac or linux

    stdout, err := c.StdoutPipe()

    if err != nil {

        return err

    }

    var wg sync.WaitGroup

    wg.Add(1)

    go func() {

        defer wg.Done()

        reader := bufio.NewReader(stdout)

        for {

            readString, err := reader.ReadString('\n')

            if err != nil || err == io.EOF {

                return

            }

            byte2String := ConvertByte2String([]byte(readString), "GB18030")

            fmt.Print(byte2String)

        }

    }()

    err = c.Start()

    wg.Wait()

    return err

}



func ConvertByte2String(byte []byte, charset Charset) string {

    var str string

    switch charset {

    case GB18030:

        var decodeBytes, _ = simplifiedchinese.GB18030.NewDecoder().Bytes(byte)

        str = string(decodeBytes)

    case UTF8:

        fallthrough

    default:

        str = string(byte)

    }

    return str

}



0人点赞

Golang





作者：小克klose

链接：https://www.jianshu.com/p/03bfbcd10283

来源：简书

著作权归作者所有。商业转载请联系作者获得授权，非商业转载请注明出处。