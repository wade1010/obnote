linux通过lsof判断文件是否正在被读被写

```
package main

import (
   "os"
   "time"
)

func main() {
   os.Open("/opt/chaosblade/logs/1.txt")  //O_RDONLY
   time.Sleep(time.Second * 1000)
}

```

```
package main

import (
   "fmt"
   "os"
   "strconv"
   "time"
)

func main() {

   f, err := os.OpenFile("/opt/chaosblade/logs/1.txt", os.O_WRONLY, 0)
   fmt.Println(f.Name(), err)
   i := 1
   for {
      str := strconv.Itoa(i)
      f.Write([]byte(str))
      i++
      time.Sleep(time.Second * 2)
      fmt.Println("tick")
   }

}

```

lsof -f -- /opt/chaosblade/logs/1.txt 

COMMAND   PID USER   FD   TYPE DEVICE SIZE/OFF      NODE NAME

main    40064 root    3w   REG  253,0      101 375776545 /opt/chaosblade/logs/1.txt

hello   43226 root    3r   REG  253,0      101 375776545 /opt/chaosblade/logs/1.txt

w表示write

r表示read

但是不能看到vim  /opt/chaosblade/logs/1.txt   这个进程

c# program is not able to detect if file is open in notepad or notepad++