 闲话休提，直接开始。

原始图像

![](D:/download/youdaonote-pull-master/data/Technology/Python/python杂乱/images/D32B3CD31AF84813B9DD55AC1C02D4361357042391_5395.jpg.jpeg)

![](D:/download/youdaonote-pull-master/data/Technology/Python/python杂乱/images/B8CBBE35555A448B8210153C9552183E1357042406_2548.jpg.jpeg)

![](D:/download/youdaonote-pull-master/data/Technology/Python/python杂乱/images/FE9AB58622DD4BC0BAD1149C6A36CBF01357042413_4257.jpg.jpeg)

![](D:/download/youdaonote-pull-master/data/Technology/Python/python杂乱/images/08AB05ED56DF4D6D8A1EE86749AA72201357042419_3300.jpg.jpeg)

Step 1 打开图像吧。

[python] view plain copy

1. im = Image.open('temp1.jpg')  

Step 2 把彩色图像转化为灰度图像。彩色图像转化为灰度图像的方法很多，这里采用RBG转化到HSI彩色空间，采用I分量。

[python] view plain copy

1. imgry = im.convert('L')  

灰度看起来是这样的

![](D:/download/youdaonote-pull-master/data/Technology/Python/python杂乱/images/002E9D6AB62943CE99CA0A78B1274BB91357042694_8797.jpg.jpeg)

![](D:/download/youdaonote-pull-master/data/Technology/Python/python杂乱/images/D242C12B6B444173884051D3CD90258A1357042708_1134.jpg.jpeg)

![](D:/download/youdaonote-pull-master/data/Technology/Python/python杂乱/images/FA4B52AC81FB4FF5A980E9F2F32C29BE1357042715_4994.jpg.jpeg)

![](D:/download/youdaonote-pull-master/data/Technology/Python/python杂乱/images/358136AE63754A9B9CF9847C9DEC161F1357042721_1723.jpg.jpeg)



Step 3 需要把图像中的噪声去除掉。这里的图像比较简单，直接阈值化就行了。我们把大于阈值threshold的像素置为1，其他的置为0。对此，先生成一张查找表，映射过程让库函数帮我们做。

[python] view plain copy

1. threshold = 140

1. table = []  

1. for i in range(256):  

1. if i < threshold:  

1.         table.append(0)  

1. else:  

1.         table.append(1)  



阈值为什么是140呢？试出来的，或者参考直方图。

映射过程为

[python] view plain copy

1. out = imgry.point(table,'1')  

此时图像看起来是这样的

![](D:/download/youdaonote-pull-master/data/Technology/Python/python杂乱/images/13B416CA13BF449384D9686D5788723F1357042771_1086.jpg.jpeg)

![](D:/download/youdaonote-pull-master/data/Technology/Python/python杂乱/images/3DFED7C0F98C4B1097FD5024BC7770241357042777_9526.jpg.jpeg)

![](D:/download/youdaonote-pull-master/data/Technology/Python/python杂乱/images/BCE06546EED745D094B0F09C78862FA21357042781_3934.jpg.jpeg)

![](D:/download/youdaonote-pull-master/data/Technology/Python/python杂乱/images/19DAD567138B45CE9D5B25F6495638291357042785_1072.jpg.jpeg)

Step 4 把图片中的字符转化为文本。采用pytesser 中的image_to_string函数

[python] view plain copy

1. text = image_to_string(out)  



Step 5 优化。根据观察，验证码中只有数字，并且上面的文字识别程序经常把8识别为S。因此，对于识别结果，在进行一些替换操作。

[python] view plain copy

1. #由于都是数字

1. #对于识别成字母的 采用该表进行修正

1. rep={'O':'0',  

1. 'I':'1','L':'1',  

1. 'Z':'2',  

1. 'S':'8'

1.     };  

[python] view plain copy

1. for r in rep:  

1.     text = text.replace(r,rep[r])  



好了，text中为最终结果。

7025

0195

7039

6716



程序需要 PIL库和 pytesser库支持。

最后，整个程序看起来是这样的

[python] view plain copy

1. import Image  

1. import ImageEnhance  

1. import ImageFilter  

1. import sys  

1. from pytesser import *  

1. # 二值化

1. threshold = 140

1. table = []  

1. for i in range(256):  

1. if i < threshold:  

1.         table.append(0)  

1. else:  

1.         table.append(1)  

1. #由于都是数字

1. #对于识别成字母的 采用该表进行修正

1. rep={'O':'0',  

1. 'I':'1','L':'1',  

1. 'Z':'2',  

1. 'S':'8'

1.     };  

1. def  getverify1(name):  

1. #打开图片

1.     im = Image.open(name)  

1. #转化到亮度

1.     imgry = im.convert('L')  

1.     imgry.save('g'+name)  

1. #二值化

1.     out = imgry.point(table,'1')  

1.     out.save('b'+name)  

1. #识别

1.     text = image_to_string(out)  

1. #识别对吗

1.     text = text.strip()  

1.     text = text.upper();  

1. for r in rep:  

1.         text = text.replace(r,rep[r])  

1. #out.save(text+'.jpg')

1. print text  

1. return text  

1. getverify1('v1.jpg')  

1. getverify1('v2.jpg')  

1. getverify1('v3.jpg')  

1. getverify1('v4.jpg')  

程序以及测试数据在这里