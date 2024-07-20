       文件操作一直是Web程序员头疼的地方，而文件操作在CMS这样的系统中又是必须的。如今，PHP文件操作的函数内容已经非常强大，文件这部分也是学习PHP非常重要的一部分，希望大家不要忽略。这篇文章会简单介绍一下PHP的几个基本文件操作，最后附有PHP文件函数汇总，供大家参考和学习。



1. 打开文件：fopen("文件名","模式");

模式参数：

r：只读，将文件指针指向文件头。

r+：读/写，将文件指针指向文件头。

w：只写，打开并清空文件的内容。如果文件不存在，则创建文件。

w+：读/写，打开并清空文件的内容。如果文件不存在，则创建文件。

a：追加，打开并向文件的末端进行写操作。如果文件不存在，则创建新文件。 

a+：读/追加，通过向文件末端写内容，来保持文件内容。





2. 关闭文件：fclose();

使用文件完毕，要显式的告诉PHP已经使用完文件，例如：

$file=fopen("test.txt","r");  //关闭一个已打开的文件指针

//some code be executed  

fclose=($file);  





3. 检测是否已达到文件的末端：feof();

例如：if(feof($file)){ echo "end of file";}





4. 逐行读取文件：fgets();

例如：

$file=fopen("test.txt","r");  

while(!feof($file)){  

echo fgets($file)."< br/>";  

}  

fclose($file); 





5. 逐字符读取文件：fgetc()





6. 读取任意二进制数据：fread()





7. 判断文件读取的状态

每个文件句柄都有一个文件指针，根据fopen函数的mode参数，文件指针最初位于文件的开头，或者文件的末尾。feof()可以判断文件是否已经到末尾；filesize()函数返回文件的大小。





8. 写入文件和权限判断

fwrite() 函数执行文件写入

is_readable()//判断文件是否可读

is_writeable()//判断文件是否可写

is_writable()//判断文件是否可写

file_exists()//是否存在这个文件



代码举例：

$filename = 'test.txt';  

$somecontent;

// 首先我们要确定文件存在并且可写 

if (is_writable($filename)) {  

// 在这个例子里，我们将使用添加模式打开$filename，  因此，文件指针将会在文件的开头，那就是当我们使用fwrite()的时候，$somecontent将要写入的地方。  



    if (!$handle = fopen($filename, 'a')) {  

       echo "不能打开文件 $filename";  

       exit;  

    }  

      // 将$somecontent写入到我们打开的文件中。  

        if (fwrite($handle, $somecontent) === FALSE) {  

              echo "不能写入到文件 $filename";  

              exit;  

       }  



        echo "成功地将 $somecontent 写入到文件$filename";  

        fclose($handle);  

} 



else{  

      echo "文件 $filename 不可写"; 

}





9. 将文件读取到一个数组：$array=file("text.txt"),$array[0]就是第一行文本，依次类推。如果要翻转整个数组，例如：

$arr=array_reverse($array);

则最后一行文本就是$arr[0]



10. 访问目录

目录访问建议使用前向斜线"/"，兼容windows和unix系统。主要函数包括：

basename()//返回不包括路径信息的文件名

dirname()//返回文件名的目录部分

realpath()//接受相对路径，返回文件的绝对路径

pathinfo()//提取给定路径的目录名，基本文件名和扩展名

opendir()//打开目录，返回资源句柄

readdir()//读取目录项

rewinddir()//将读取指针返回开头

closedir()//关闭读取句柄

chdir()//改变当前脚本执行期间的当前工作目录

mkdir()//创建目录

rmdir()删除目录



附：PHP文件函数大全 

basename — 返回路径中的文件名部分 

chgrp — 改变文件所属的组 

chmod — 改变文件模式 

chown — 改变文件的所有者 

clearstatcache — 清除文件状态缓存 

copy — 拷贝文件 

delete — 参见 unlink() 或 unset() 

dirname — 返回路径中的目录部分 

disk_free_space — 返回目录中的可用空间 

disk_total_space — 返回一个目录的磁盘总大小 

diskfreespace — disk_free_space()的别名 

fclose — 关闭一个已打开的文件指针 

feof — 测试文件指针是否到了文件结束的位置 

fflush — 将缓冲内容输出到文件 

fgetc — 从文件指针中读取字符 

fgetcsv — 从文件指针中读入一行并解析 CSV 字段 

fgets — 从文件指针中读取一行 

fgetss — 从文件指针中读取一行并过滤掉 HTML 标记 

file_exists — 检查文件或目录是否存在 

file_get_contents — 将整个文件读入一个字符串 

file_put_contents — 将一个字符串写入文件 

file — 把整个文件读入一个数组中 

fileatime — 取得文件的上次访问时间 

filectime — 这个PHP文件函数取得文件的 inode 修改时间 

filegroup — 取得文件的组 

fileinode — 取得文件的 inode 

filemtime — 取得文件修改时间 

fileowner — 取得文件的所有者 

fileperms — 取得文件的权限 

filesize — 取得文件大小 

filetype — 取得文件类型 

flock — 轻便的咨询文件锁定 

fnmatch — 用模式匹配文件名 

fopen — 打开文件或者 URL 

fpassthru — 输出文件指针处的所有剩余数据 

fputcsv — 将行格式化为 CSV 并写入文件指针 

fputs — fwrite()的别名 

fread — 读取文件（可安全用于二进制文件） 

fscanf — 从文件中格式化输入 

fseek — 在文件指针中定位 

fstat — 通过已打开的文件指针取得文件信息 

ftell — 返回文件指针读/写的位置 

ftruncate — 将文件截断到给定的长度 

fwrite — 写入文件（可安全用于二进制文件） 

glob — 寻找与模式匹配的文件路径 

is_dir — 该PHP文件函数判断给定文件名是否是一个目录 

is_executable — 判断给定文件名是否可执行 

is_file — 判断给定文件名是否为一个正常的文件 

is_link — 判断给定文件名是否为一个符号连接 

is_readable — 判断给定文件名是否可读 

is_uploaded_file — 判断文件是否是通过 HTTP POST 上传的 

is_writable — 判断给定的文件名是否可写 

is_writeable — is_writable()的别名 

link — 建立一个硬连接 

linkinfo — 获取一个连接的信息 

lstat — 给出一个文件或符号连接的信息 

mkdir — 新建目录 

move_uploaded_file — 将上传的文件移动到新位置 

parse_ini_file — 解析一个配置文件 

pathinfo — 返回文件路径的信息 

pclose — 关闭进程文件指针 

popen — 打开进程文件指针 

readfile — 输出一个文件 

readlink — 返回符号连接指向的目标 

realpath — 返回规范化的绝对路径名 

rename — 重命名一个文件或目录 

rewind — 倒回文件指针的位置 

rmdir — 删除目录 

set_file_buffer — stream_set_write_buffer()的别名 

stat — 给出文件的信息 

symlink — 建立符号连接 

tempnam — 建立一个具有唯一文件名的文件 

tmpfile — 建立一个临时文件 

touch — 设定文件的访问和修改时间 

umask — 改变当前的 umask 

unlink — 删除文件







