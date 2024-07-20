有时我们想通过list命令来查看源文件时，gdb会提示"No source file named XXX"

原因一：产生这个问题的原因很可能是编译的时候没有添加-g选项

readelf --debug-dump=info [elf文件]   （可以查看文件中的.debug_info段，这个段记录了调试需要的信息，包括每个源文件的绝对路径信息，如果绝对路径下没有源文件则list [file:line_nume]会失败）

readelf --debug-dump=line [elf文件]   （可以查看文件中的.debug_line段，这个段记录了指令和源文件的行数对应的信息）

原因二：debug信息中的路径和源文件在文件系统中的路径不同，参考链接

[https://www.cnblogs.com/rickyk/p/4184860.html](https://www.cnblogs.com/rickyk/p/4184860.html)

————————————————

版权声明：本文为CSDN博主「kh815」的原创文章，遵循CC 4.0 BY-SA版权协议，转载请附上原文出处链接及本声明。

原文链接：[https://blog.csdn.net/kh815/article/details/102498643](https://blog.csdn.net/kh815/article/details/102498643)

  curl [https://api.openai.com/v1/completions](https://api.openai.com/v1/completions) \

    -H "Content-Type: application/json" \

    -H "Authorization: Bearer lS9HhwiYbN9y9HX6t66nT3BlbkFJtFfAdz2UFRwXVpzc9JT8" \

    -d '{

    "model": "text-davinci-003",

    "prompt": "Can I make a request?\n\n",

    "temperature": 0.7,

    "max_tokens": 256,

    "top_p": 1,

    "frequency_penalty": 0,

    "presence_penalty": 0

  }'