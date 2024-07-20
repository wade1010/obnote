- 首页

- 我的主页

- 相册

- 广场

- 游戏

- 昵称搜索

- 消息

- 私信

- 模板

- 设置

- 退出

关注此空间

闲看云舒

天涯落拓谁知己，十年已过依初识。

2013-11-12 12:46

FileReader 的编码问题

今天用 FileReader 读取文件时乱码，于是查找原因

首先看下 FileReader 的实现方式：

|   |   |
| - | - |
| 1<br>2<br>3<br>4<br>5<br>6<br>7<br>8<br>9<br>10<br>11<br>12<br>13<br>14<br>15<br>16<br>17 | packagejava.io;<br>publicclassFileReader extendsInputStreamReader {<br>publicFileReader(String fileName) throwsFileNotFoundException {<br>super(newFileInputStream(fileName));<br>}<br>publicFileReader(File file) throwsFileNotFoundException {<br>super(newFileInputStream(file));<br>}<br>publicFileReader(FileDescriptor fd) {<br>super(newFileInputStream(fd));<br>}<br>} |


可见 FileReader 的实现还是非常简单的，其继承了 InputStreamReader，

我们再看看 InputStreamReader 的实现方式：

|   |   |
| - | - |
| 1<br>2<br>3<br>4<br>5<br>6<br>7<br>8<br>9<br>10<br>11<br>12<br>13<br>14<br>15<br>16<br>17<br>18<br>19<br>20<br>21<br>22<br>23<br>24<br>25<br>26<br>27<br>28<br>29<br>30<br>31<br>32<br>33<br>34<br>35<br>36<br>37<br>38<br>39<br>40<br>41<br>42<br>43<br>44<br>45<br>46<br>47<br>48<br>49<br>50<br>51<br>52<br>53<br>54<br>55<br>56<br>57<br>58<br>59<br>60<br>61<br>62<br>63 | packagejava.io;<br>importjava.nio.charset.Charset;<br>importjava.nio.charset.CharsetDecoder;<br>importsun.nio.cs.StreamDecoder;<br>publicclassInputStreamReader extendsReader {<br>privatefinalStreamDecoder sd;<br>publicInputStreamReader(InputStream in) {<br>super(in);<br>try{<br>sd = StreamDecoder.forInputStreamReader(in, this, (String)null);<br>} catch(UnsupportedEncodingException e) {<br>thrownewError(e);<br>}<br>}<br>publicInputStreamReader(InputStream in, String charsetName)<br>throwsUnsupportedEncodingException{<br>super(in);<br>if(charsetName == null)<br>thrownewNullPointerException("charsetName");<br>sd = StreamDecoder.forInputStreamReader(in, this, charsetName);<br>}<br>publicInputStreamReader(InputStream in, Charset cs) {<br>super(in);<br>if(cs == null)<br>thrownewNullPointerException("charset");<br>sd = StreamDecoder.forInputStreamReader(in, this, cs);<br>}<br>publicInputStreamReader(InputStream in, CharsetDecoder dec) {<br>super(in);<br>if(dec == null)<br>thrownewNullPointerException("charset decoder");<br>sd = StreamDecoder.forInputStreamReader(in, this, dec);<br>}<br>publicString getEncoding() {<br>returnsd.getEncoding();<br>}<br>publicintread() throwsIOException {<br>returnsd.read();<br>}<br>publicintread(charcbuf[], intoffset, intlength) throwsIOException {<br>returnsd.read(cbuf, offset, length);<br>}<br>publicbooleanready() throwsIOException {<br>returnsd.ready();<br>}<br>publicvoidclose() throwsIOException {<br>sd.close();<br>}<br>}<br>14036642.28905 |


对比这两份源码，即可看出问题原因：FileReader 继承了 InputStreamReader，但并没有实现父类中带字符集参数的构造函数，所以FileReader只能采用系统默认的编码方式。

我遇到的问题即为：系统编码是 UTF-8，文件编码是 GBK，所以读取时乱码。

原因找到了，针对其解决就好，如下两种解决方案：

一是重写 FileReader 方法，实现带字符集参数的构造函数，代码如下：

|   |   |
| - | - |
| 1<br>2<br>3<br>4<br>5<br>6<br>7<br>8<br>9<br>10<br>11<br>12 | importjava.io.FileInputStream;<br>importjava.io.FileNotFoundException;<br>importjava.io.InputStreamReader;<br>importjava.io.UnsupportedEncodingException;<br>publicclassFileReaderGBK extendsInputStreamReader {<br>publicFileReaderGBK(String fileName, String charSetName)<br>throwsFileNotFoundException, UnsupportedEncodingException {<br>super(newFileInputStream(fileName), charSetName);<br>}<br>} |


二是不用 FileReader ，直接使用 InputStreamReader 来读取文件，如下：

|   |   |
| - | - |
| 1<br>2 | BufferedReader reader = newBufferedReader(newInputStreamReader(<br>newFileInputStream(newFile(path)), "GBK")); |




