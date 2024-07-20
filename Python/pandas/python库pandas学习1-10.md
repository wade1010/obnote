[https://pandas.pydata.org/](https://pandas.pydata.org/)

![](D:/download/youdaonote-pull-master/data/Technology/Python/pandas/images/WEBRESOURCEc3eedf0a1712413c73f09bf4f3e0b6d3截图.png)

![](D:/download/youdaonote-pull-master/data/Technology/Python/pandas/images/WEBRESOURCE572d4141b8ddc7f9b5e4dc23f1c333ad截图.png)

![](D:/download/youdaonote-pull-master/data/Technology/Python/pandas/images/WEBRESOURCEc3fc8fb842161922b40b5769bf075a77截图.png)

![](D:/download/youdaonote-pull-master/data/Technology/Python/pandas/images/WEBRESOURCEee5ad3e07cb2d73909ab68bede49fc19截图.png)

```
import pandas as pd
fpath = 'XXXX'
ratings = pd.read_csv(fpath)
#查看前几行
ratings.head()
#查看数据的形状
ratings.shape
#查看列名列表
ratings.columns
#查看索引列
ratings.index
#查看每列的数据类型
ratings.dtypes

#1.2读取txt，指定分隔符,列名
fpath = 'xxx'
pvuv = pd.read_csv(fpath,sep="\t",header=None,names=['pdate','pv','uv'])
pvuv

#2、读取Excel
fpath = 'xxx.xlsx'
pvuv = pd.read_excel(fpath)
pvuv

#3、读取MySQL
import pandas as pd
from sqlalchemy import create_engine
#import pymysql  #新版本pandas使用下面方法创建
#conn = pymysql.connect(host='127.0.0.1',user='root',password='',database='chaogushe',charset='utf8')

#pip3  install sqlalchemy 
conn = create_engine('mysql+pymysql://%s:%s@%s:%s/%s?charset=utf8' % ('root', '', '127.0.0.1', '3306', 'chaogushe'))

ret = pd.read_sql("select * from stocks limit 10",con=conn)
ret
```

![](D:/download/youdaonote-pull-master/data/Technology/Python/pandas/images/WEBRESOURCEd9ee8398ece086eb6e0ccc98b226a47a截图.png)

![](D:/download/youdaonote-pull-master/data/Technology/Python/pandas/images/WEBRESOURCEc3d5904aaac75ac23aedf6b2118d8ae8截图.png)

![](D:/download/youdaonote-pull-master/data/Technology/Python/pandas/images/WEBRESOURCE69c1c29ffc0135cda0e55793336ae00b截图.png)

如果查询出来的结果是一列或者一行就会变成Series

![](D:/download/youdaonote-pull-master/data/Technology/Python/pandas/images/WEBRESOURCE40e306ed6e07de9df19c8bbfa8055b94截图.png)

![](D:/download/youdaonote-pull-master/data/Technology/Python/pandas/images/WEBRESOURCE49a724c69dec7cb73a4ada5d44356e3c截图.png)

![](D:/download/youdaonote-pull-master/data/Technology/Python/pandas/images/WEBRESOURCEcbd57d2b34ec572c23ee7c2d28ab80f8截图.png)

![](D:/download/youdaonote-pull-master/data/Technology/Python/pandas/images/WEBRESOURCE47924e6f7de9c4552cc86dfb1a2f5097截图.png)

![](D:/download/youdaonote-pull-master/data/Technology/Python/pandas/images/WEBRESOURCE611e81b72f0054e63309c60a327928db截图.png)

![](D:/download/youdaonote-pull-master/data/Technology/Python/pandas/images/WEBRESOURCEadc8b731aa5ff3aa14501ac92d67ff26截图.png)

![](D:/download/youdaonote-pull-master/data/Technology/Python/pandas/images/WEBRESOURCEdf1616e40435fbca30dfe16204e2ab75截图.png)

![](D:/download/youdaonote-pull-master/data/Technology/Python/pandas/images/WEBRESOURCE3638bb6fae52975436f549cd96ae7cb2截图.png)

![](D:/download/youdaonote-pull-master/data/Technology/Python/pandas/images/WEBRESOURCEc566410f0fea0ab9b56a7da30138257c截图.png)

```
import pandas as pd

#0读取数据
df = pd.read_csv('xxxxx')
df.head

```

![](D:/download/youdaonote-pull-master/data/Technology/Python/pandas/images/WEBRESOURCE1e1d67c82f13a2a3991a34fb36ad5004截图.png)

```
# 设定索引为日期，方便按日期筛选
df.set_index('ymd',inplace=True)

df.index
```

![](D:/download/youdaonote-pull-master/data/Technology/Python/pandas/images/WEBRESOURCE7fac270e813189bcfb875c678ff4f3e8截图.png)

```
df.head()
```

![](D:/download/youdaonote-pull-master/data/Technology/Python/pandas/images/WEBRESOURCE1e2d20713af747baddc91e89b4c7f30c截图.png)

去掉温度的C

```
#替换掉温度的后缀°C
df.loc[:,'bWendu'] = df['bWendu'].str.replace('°C','').astype('int32')
df.loc[:,'yWendu'] = df['yWendu'].str.replace('°C','').astype('int32')
df.dtypes
```

![](D:/download/youdaonote-pull-master/data/Technology/Python/pandas/images/WEBRESOURCE7598de62b91c451475e43efb000ecbf3截图.png)

![](D:/download/youdaonote-pull-master/data/Technology/Python/pandas/images/WEBRESOURCEd7a48870eb8349fcbaa3a62c90ab356f截图.png)

```
# 1、使用单个label值查询数据
# 行或者列，都可以只传入单个值，实现精确匹配
#得到单个值
df.loc['2018-01-03','bWendu']
```

![](D:/download/youdaonote-pull-master/data/Technology/Python/pandas/images/WEBRESOURCE774cbe58cbe87f9eb1a3467dc668ce7d截图.png)

```
#得到一个Series
df.loc['2018-01-03',['bWendu','yWendu']]
```

![](D:/download/youdaonote-pull-master/data/Technology/Python/pandas/images/WEBRESOURCEfd9040af4dde2e754aefae0851265d23截图.png)

```
#2、使用值列表批量查询
#得到Series
df.loc[['2018-01-03','2018-01-04','2018-01-05'],'bWendu']
 
```

![](D:/download/youdaonote-pull-master/data/Technology/Python/pandas/images/WEBRESOURCEb58ce62fafe60ab3221d054134d57d61截图.png)

```
# 得到DataFrame
df.loc[['2018-01-03'，'2018-01-04','2018-01-05'],['bWendu','yWendu']]
```

![](D:/download/youdaonote-pull-master/data/Technology/Python/pandas/images/WEBRESOURCE72f5d735b566499273faf69e884a1cb4截图.png)

```
# 3使用数值区间进行范围查询
# 注意：区间既包含开始也包含结束

# 行index按区间
df.loc['2018-01-03':'2018-01-05','bWendu']

```

![](D:/download/youdaonote-pull-master/data/Technology/Python/pandas/images/WEBRESOURCEd5839809a611f0aff2bf213c2bc85845截图.png)

```
# 列index按区间
df.loc['2018-01-03','bWendu':'fengxiang']

```

![](D:/download/youdaonote-pull-master/data/Technology/Python/pandas/images/WEBRESOURCEd12958f8e7abfbf525c83230abeaca28截图.png)

```
# 列和行都按区间查询
df.loc['2018-01-03':'2018-01-05','bWendu':'fengxiang']
```

![](D:/download/youdaonote-pull-master/data/Technology/Python/pandas/images/WEBRESOURCEa9f6b8f8cc1035d6aa3941b47cbb97b3截图.png)

4、最为强大的方法

```
# 4使用条件表达式查询
#  bool列表的长度得等于行数或者列数
#  简单条件查询，最低温度低于-10度的列表
df['yWendu']<-10
```

![](D:/download/youdaonote-pull-master/data/Technology/Python/pandas/images/WEBRESOURCEb69e942eaa2a7bb87217f91e7d4106ca截图.png)

复杂条件查询，查一下我心中的完美天气

注意，组合条件用&符号合并，每个条件都得带括号

```
## 查询最高温度小于30度，且最低温度大于15度，且是晴天，且天气为优的数据 。  (列取所有)
df.loc[(df['bWendu']<30) & (df['yWendu']>15) & (df['tianqi']=='晴') & (df['aqiLevel']==1),:]
```

![](D:/download/youdaonote-pull-master/data/Technology/Python/pandas/images/WEBRESOURCEaeea88297ea8b5fcabfb969b7789b874截图.png)

 5、调用函数查询

```
#直接写lambda表达式
df.loc[lambda df:(df['bWendu']<=30>) & (df['yWendu']>=15),:]
```

![](D:/download/youdaonote-pull-master/data/Technology/Python/pandas/images/WEBRESOURCEfdf17b615c2f4320ccce68f5549ec36c截图.png)

  

```
# 编写自己的函数，查询9月份，空气质量好的数据】
def query_my_data(df):
    return df.index.str.startwith('2018-09') & df['aqiLevel']==1
    
df.loc[query_my_data,:]
```

![](D:/download/youdaonote-pull-master/data/Technology/Python/pandas/images/WEBRESOURCEef5ea90d5033c09fbcac1aa0c8008618截图.png)

![](D:/download/youdaonote-pull-master/data/Technology/Python/pandas/images/WEBRESOURCE386ceabbe31ddedc164a5b70c1ab82de截图.png)

![](D:/download/youdaonote-pull-master/data/Technology/Python/pandas/images/WEBRESOURCEad7e2731a765821d9d1d942ab6814cb6截图.png)

```
import pandas as pd
fpath = 'xxxxx'
df = pd.read_csv(fptah)
df.head()
```

![](D:/download/youdaonote-pull-master/data/Technology/Python/pandas/images/WEBRESOURCEc1aa320f3fcba393b24ca1e4fa3f2012截图.png)

1、直接赋值

实例：清理温度列，变成数字类型

```
 # 替换掉温度的后缀°C
 df.loc[:,'bWendu'] = df['bWendu'].str.replace('°C','').astype('int32')
 df.loc[:,'yWendu'] = df['yWendu'].str.replace('°C','').astype('int32')
```

实例：计算温差

![](D:/download/youdaonote-pull-master/data/Technology/Python/pandas/images/WEBRESOURCE6441f25b7f7c0534a1d01ca1f13578e1截图.png)

2、df.apply方法

![](D:/download/youdaonote-pull-master/data/Technology/Python/pandas/images/WEBRESOURCE15a615276115d64204f28f9aaf7c61f0截图.png)

 

![](D:/download/youdaonote-pull-master/data/Technology/Python/pandas/images/WEBRESOURCEd83b1e7e1aaef0adf4d84ec379dd4cb4截图.png)

![](D:/download/youdaonote-pull-master/data/Technology/Python/pandas/images/WEBRESOURCE1f8943f1eefae3f6232e608eb174ffeb截图.png)

对于axis等于0和1的理解，如下图。

0是沿着竖轴，一列一列的迭代

1是沿着横轴，一行一行的迭代

![](D:/download/youdaonote-pull-master/data/Technology/Python/pandas/images/WEBRESOURCE3d58b8813692fda727e48eb156e89753截图.png)

 

![](D:/download/youdaonote-pull-master/data/Technology/Python/pandas/images/WEBRESOURCE3f70ab9b06a897f4d36841df11c3c500截图.png)

assign默认就是传横轴

![](D:/download/youdaonote-pull-master/data/Technology/Python/pandas/images/WEBRESOURCEf22947c3e66755dc897d750b738832c0截图.png)

![](D:/download/youdaonote-pull-master/data/Technology/Python/pandas/images/WEBRESOURCEc3843f29d0266a6d96d12b549d159c7c截图.png)

 

![](D:/download/youdaonote-pull-master/data/Technology/Python/pandas/images/WEBRESOURCE7a133702f30c616f031b9badee4357fb截图.png)

![](D:/download/youdaonote-pull-master/data/Technology/Python/pandas/images/WEBRESOURCEc87c50ccdbc32eb1f6e6521e988a284b截图.png)

 

![](D:/download/youdaonote-pull-master/data/Technology/Python/pandas/images/WEBRESOURCE3f56350ebece59e63f71be13f31be602截图.png)

![](D:/download/youdaonote-pull-master/data/Technology/Python/pandas/images/WEBRESOURCE22e8278095ef69aaef544d084c323ab4截图.png)

![](D:/download/youdaonote-pull-master/data/Technology/Python/pandas/images/WEBRESOURCE3c1812ec1d9ee6743441f4f4c6f24020截图.png)

![](D:/download/youdaonote-pull-master/data/Technology/Python/pandas/images/WEBRESOURCEaf3530566d401d1372732c810aaca37e截图.png)

![](D:/download/youdaonote-pull-master/data/Technology/Python/pandas/images/WEBRESOURCE8b2d73d18d7a1e5cdec1465a28e7702f截图.png)

![](D:/download/youdaonote-pull-master/data/Technology/Python/pandas/images/WEBRESOURCEa01528867551eed5ae77b15fd5e1cac4截图.png)

![](D:/download/youdaonote-pull-master/data/Technology/Python/pandas/images/WEBRESOURCE6bec630d735b010713a48c2cc7192b4b截图.png)

![](D:/download/youdaonote-pull-master/data/Technology/Python/pandas/images/WEBRESOURCEcdfae750292682505f340865b930157c截图.png)

![](D:/download/youdaonote-pull-master/data/Technology/Python/pandas/images/WEBRESOURCE0bcc34fa42d6b88b7379571f06bec268截图.png)

![](D:/download/youdaonote-pull-master/data/Technology/Python/pandas/images/WEBRESOURCEf843abd35261a7ebb808ecdd0c152aab截图.png)

![](D:/download/youdaonote-pull-master/data/Technology/Python/pandas/images/WEBRESOURCE27a41145b406ab746307d9c6baab9162截图.png)

原始Excel内容

![](D:/download/youdaonote-pull-master/data/Technology/Python/pandas/images/WEBRESOURCE2801e2b5943b55f22f46db8dcd102e1e截图.png)

清洗后的内容

![](D:/download/youdaonote-pull-master/data/Technology/Python/pandas/images/WEBRESOURCE010f06b03e47c66ff36e847fff9520b4截图.png)

![](D:/download/youdaonote-pull-master/data/Technology/Python/pandas/images/WEBRESOURCE853a0205109f2f6494c88b2252c151e6截图.png)

![](D:/download/youdaonote-pull-master/data/Technology/Python/pandas/images/WEBRESOURCEf08889e8c19b34002a3ab4a9440b4ec8截图.png)

![](D:/download/youdaonote-pull-master/data/Technology/Python/pandas/images/WEBRESOURCE4f85182d0082c97b8b0f7ed39584d60d截图.png)

![](D:/download/youdaonote-pull-master/data/Technology/Python/pandas/images/WEBRESOURCE64773b9b0b76add89053be5a0c5f390a截图.png)

![](D:/download/youdaonote-pull-master/data/Technology/Python/pandas/images/WEBRESOURCE1f150dce8948641efc8920582532330a截图.png)

![](D:/download/youdaonote-pull-master/data/Technology/Python/pandas/images/WEBRESOURCE8179a04106ee2e0e28f45b0bbc1b5fec截图.png)

![](D:/download/youdaonote-pull-master/data/Technology/Python/pandas/images/WEBRESOURCE02480d45f29495febf5a47d95748b69f截图.png)

![](D:/download/youdaonote-pull-master/data/Technology/Python/pandas/images/WEBRESOURCEb526d63b7b58fe0e7342d47d587e7188截图.png)

![](D:/download/youdaonote-pull-master/data/Technology/Python/pandas/images/WEBRESOURCE0c6b47feb56e1420771b9a4000f44b17截图.png)

![](D:/download/youdaonote-pull-master/data/Technology/Python/pandas/images/WEBRESOURCE95c7ce18695ae5b59c5838dee79b36fe截图.png)

![](D:/download/youdaonote-pull-master/data/Technology/Python/pandas/images/WEBRESOURCE89db6109bb7682a7d89a29d556ca1d31截图.png)

![](D:/download/youdaonote-pull-master/data/Technology/Python/pandas/images/WEBRESOURCEfb5763ae21700ec3cb0f7c010391fdb8截图.png)

![](D:/download/youdaonote-pull-master/data/Technology/Python/pandas/images/WEBRESOURCE16820084eaa0349dba952b108bc975d1截图.png)

![](D:/download/youdaonote-pull-master/data/Technology/Python/pandas/images/WEBRESOURCE8dc76d354ca8349f7b7113d241a98d9a截图.png)

![](D:/download/youdaonote-pull-master/data/Technology/Python/pandas/images/WEBRESOURCE7e2bb85cc0b08124d5b1d33efa6d56ff截图.png)

![](D:/download/youdaonote-pull-master/data/Technology/Python/pandas/images/WEBRESOURCE5f2d6fa18f0de9b9648c76eb21ad00cd截图.png)

index = False 不保存dataframe索引

![](D:/download/youdaonote-pull-master/data/Technology/Python/pandas/images/WEBRESOURCEd1fbdaba43854ee0901fbdbf1e10e880截图.png)

![](D:/download/youdaonote-pull-master/data/Technology/Python/pandas/images/WEBRESOURCEb2ac217a8c029e8169f00df4837fcbea截图.png)

![](D:/download/youdaonote-pull-master/data/Technology/Python/pandas/images/WEBRESOURCE5530c231a6d185c704dcce146e129976截图.png)

![](D:/download/youdaonote-pull-master/data/Technology/Python/pandas/images/WEBRESOURCEb1b457b520a8ea7c5d0cd7ca439d0080截图.png)

这里有时候会修改成功有时候会修改失败

![](D:/download/youdaonote-pull-master/data/Technology/Python/pandas/images/WEBRESOURCE64223de1a502ae8190d5fe5d11808961截图.png)

![](D:/download/youdaonote-pull-master/data/Technology/Python/pandas/images/WEBRESOURCE2fa7b6d1cf9aeb9fa72e09eed40e43ee截图.png)

![](D:/download/youdaonote-pull-master/data/Technology/Python/pandas/images/WEBRESOURCEbce486a83a591b4b3a07a1f6b0fa7b62截图.png)

![](D:/download/youdaonote-pull-master/data/Technology/Python/pandas/images/WEBRESOURCE2e5dfaf4df3582f32f931e42e31b4409截图.png)

![](D:/download/youdaonote-pull-master/data/Technology/Python/pandas/images/WEBRESOURCE68a29b85a35f07e660bd49f70c4ca645截图.png)

![](D:/download/youdaonote-pull-master/data/Technology/Python/pandas/images/WEBRESOURCE8496f7b990b42688432e6e2883ffaf82截图.png)

![](D:/download/youdaonote-pull-master/data/Technology/Python/pandas/images/WEBRESOURCE581dc1cfa3661fc57966baa46654a48f截图.png)

![](D:/download/youdaonote-pull-master/data/Technology/Python/pandas/images/WEBRESOURCEd2e0fe0791c77d78310e96661ca1392c截图.png)

![](D:/download/youdaonote-pull-master/data/Technology/Python/pandas/images/WEBRESOURCE7a7c9f07cbbe3005180c2595e4f361f6截图.png)

![](D:/download/youdaonote-pull-master/data/Technology/Python/pandas/images/WEBRESOURCE6c58852303ebbd64299fdf04400d1d20截图.png)

![](D:/download/youdaonote-pull-master/data/Technology/Python/pandas/images/WEBRESOURCEc4a92fc0c8527f3d2324f75521b3aefc截图.png)

![](D:/download/youdaonote-pull-master/data/Technology/Python/pandas/images/WEBRESOURCEa4bd6ae4daafc114550b968b01f63794截图.png)

![](D:/download/youdaonote-pull-master/data/Technology/Python/pandas/images/WEBRESOURCEee7aa61869f74fe0b185b4ae2c7697a7截图.png)

 

![](D:/download/youdaonote-pull-master/data/Technology/Python/pandas/images/WEBRESOURCEc5a38da31b4a2193cfd006fa964e9245截图.png)

 

![](D:/download/youdaonote-pull-master/data/Technology/Python/pandas/images/WEBRESOURCE2f67e4ef1e56cbef645946ddf3482515截图.png)

  

![](D:/download/youdaonote-pull-master/data/Technology/Python/pandas/images/WEBRESOURCE209eedf5f85eb776988ce18dabfae892截图.png)

![](D:/download/youdaonote-pull-master/data/Technology/Python/pandas/images/WEBRESOURCE79ff5fde37db80681469b78be902a80d截图.png)