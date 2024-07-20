![](https://gitee.com/hxc8/images4/raw/master/img/202407172255600.jpg)

![](https://gitee.com/hxc8/images4/raw/master/img/202407172255159.jpg)

![](https://gitee.com/hxc8/images4/raw/master/img/202407172255028.jpg)

![](https://gitee.com/hxc8/images4/raw/master/img/202407172255836.jpg)

tcpserver

```
use std::{
    io::{Read, Write},
    net::TcpListener,
};
fn main() {
    let listener = TcpListener::bind("127.0.0.1:3000").unwrap();
    println!("Running on port 3000...");
    for stream in listener.incoming() {
        let mut stream = stream.unwrap();

        println!("Connection established");
        let mut buffer = [0; 1024];
        stream.read(&mut buffer).unwrap();
        stream.write(&mut buffer).unwrap();
    }
}

```

tcpclient

```
use std::{
    io::{Read, Write},
    net::TcpStream,
    str,
};

fn main() {
    let mut stream = TcpStream::connect("127.0.0.1:3000").unwrap();
    stream.write("Hello".as_bytes()).unwrap();
    let mut buffer = [0; 5];
    stream.read(&mut buffer).unwrap();
    println!(
        "Response from server:{:?}",
        str::from_utf8(&buffer).unwrap()
    );
}

```

![](https://gitee.com/hxc8/images4/raw/master/img/202407172255572.jpg)

![](https://gitee.com/hxc8/images4/raw/master/img/202407172256656.jpg)

![](https://gitee.com/hxc8/images4/raw/master/img/202407172256311.jpg)

![](https://gitee.com/hxc8/images4/raw/master/img/202407172256779.jpg)

![](https://gitee.com/hxc8/images4/raw/master/img/202407172256819.jpg)

![](https://gitee.com/hxc8/images4/raw/master/img/202407172256010.jpg)

![](https://gitee.com/hxc8/images4/raw/master/img/202407172256805.jpg)

![](https://gitee.com/hxc8/images4/raw/master/img/202407172256178.jpg)

![](https://gitee.com/hxc8/images4/raw/master/img/202407172256373.jpg)

![](https://gitee.com/hxc8/images4/raw/master/img/202407172256624.jpg)

![](https://gitee.com/hxc8/images4/raw/master/img/202407172256553.jpg)

![](https://gitee.com/hxc8/images4/raw/master/img/202407172256867.jpg)

![](https://gitee.com/hxc8/images4/raw/master/img/202407172256096.jpg)

![](https://gitee.com/hxc8/images4/raw/master/img/202407172256428.jpg)

![](https://gitee.com/hxc8/images4/raw/master/img/202407172256542.jpg)

![](https://gitee.com/hxc8/images4/raw/master/img/202407172256220.jpg)

![](https://gitee.com/hxc8/images4/raw/master/img/202407172256958.jpg)

![](https://gitee.com/hxc8/images4/raw/master/img/202407172256877.jpg)

![](https://gitee.com/hxc8/images4/raw/master/img/202407172256239.jpg)

curl -X POST localhost:3000/courses/ -H "Content-Type: application/json" -d '{"teacher_id":1,"name":"First course"}'

![](https://gitee.com/hxc8/images4/raw/master/img/202407172256370.jpg)

![](https://gitee.com/hxc8/images4/raw/master/img/202407172256287.jpg)

docker run  --name=postgres -p 5432:5432 -e POSTGRES_PASSWORD=123456 postgres:11

账号和初始库postgres

密码  123456

create table course

(

id serial primary key,

teacher_id INT not null,

name  varchar(140) not null,

![](https://gitee.com/hxc8/images4/raw/master/img/202407172256146.jpg)

time TIMESTAMP default now()

)

insert into course(id,teacher_id,name,time)VALUES(1,1,'First course','2022-09-07 15:49:31');

insert into course(id,teacher_id,name,time)VALUES(2,1,'First course','2022-09-07 15:50:31');

![](https://gitee.com/hxc8/images4/raw/master/img/202407172256989.jpg)

![](https://gitee.com/hxc8/images4/raw/master/img/202407172256534.jpg)

![](https://gitee.com/hxc8/images4/raw/master/img/202407172256506.jpg)

![](https://gitee.com/hxc8/images4/raw/master/img/202407172256961.jpg)

![](images/WEBRESOURCE1b86e94491c6565629d2fa0f0555c349截图.png)

![](https://gitee.com/hxc8/images4/raw/master/img/202407172256544.jpg)

![](https://gitee.com/hxc8/images4/raw/master/img/202407172256440.jpg)

![](https://gitee.com/hxc8/images4/raw/master/img/202407172257826.jpg)

![](https://gitee.com/hxc8/images4/raw/master/img/202407172257126.jpg)

![](https://gitee.com/hxc8/images4/raw/master/img/202407172257809.jpg)

![](https://gitee.com/hxc8/images4/raw/master/img/202407172257088.jpg)

![](https://gitee.com/hxc8/images4/raw/master/img/202407172257166.jpg)

![](https://gitee.com/hxc8/images4/raw/master/img/202407172257490.jpg)

![](https://gitee.com/hxc8/images4/raw/master/img/202407172257901.jpg)

![](https://gitee.com/hxc8/images4/raw/master/img/202407172257939.jpg)

![](https://gitee.com/hxc8/images4/raw/master/img/202407172257887.jpg)

![](https://gitee.com/hxc8/images4/raw/master/img/202407172257944.jpg)