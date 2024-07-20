Python利用Matplotlib绘图的时候，无法显示坐标轴上面的中文和标题里面的中文

```
/home/yaya/miniconda3/envs/hikyuu/lib/python3.9/site-packages/IPython/core/events.py:89: UserWarning: Glyph 24179 (\N{CJK UNIFIED IDEOGRAPH-5E73}) missing from current font.  func(*args, **kwargs)

/home/yaya/miniconda3/envs/hikyuu/lib/python3.9/site-packages/IPython/core/events.py:89: UserWarning: Glyph 23433 (\N{CJK UNIFIED IDEOGRAPH-5B89}) missing from current font.  func(*args, **kwargs)

/home/yaya/miniconda3/envs/hikyuu/lib/python3.9/site-packages/IPython/core/events.py:89: UserWarning: Glyph 38134 (\N{CJK UNIFIED IDEOGRAPH-94F6}) missing from current font.  func(*args, **kwargs)

/home/yaya/miniconda3/envs/hikyuu/lib/python3.9/site-packages/IPython/core/events.py:89: UserWarning: Glyph 34892 (\N{CJK UNIFIED IDEOGRAPH-884C}) missing from current font.  func(*args, **kwargs)

/home/yaya/miniconda3/envs/hikyuu/lib/python3.9/site-packages/IPython/core/events.py:89: UserWarning: Glyph 65288 (\N{FULLWIDTH LEFT PARENTHESIS}) missing from current font.  func(*args, **kwargs)

/home/yaya/miniconda3/envs/hikyuu/lib/python3.9/site-packages/IPython/core/events.py:89: UserWarning: Glyph 26085 (\N{CJK UNIFIED IDEOGRAPH-65E5}) missing from current font.  func(*args, **kwargs)

/home/yaya/miniconda3/envs/hikyuu/lib/python3.9/site-packages/IPython/core/events.py:89: UserWarning: Glyph 32447 (\N{CJK UNIFIED IDEOGRAPH-7EBF}) missing from current font.  func(*args, **kwargs)

/home/yaya/miniconda3/envs/hikyuu/lib/python3.9/site-packages/IPython/core/events.py:89: UserWarning: Glyph 65289 (\N{FULLWIDTH RIGHT PARENTHESIS}) missing from current font.  func(*args, **kwargs)

/home/yaya/miniconda3/envs/hikyuu/lib/python3.9/site-packages/IPython/core/events.py:89: UserWarning: Glyph 24320 (\N{CJK UNIFIED IDEOGRAPH-5F00}) missing from current font.  func(*args, **kwargs)

/home/yaya/miniconda3/envs/hikyuu/lib/python3.9/site-packages/IPython/core/events.py:89: UserWarning: Glyph 39640 (\N{CJK UNIFIED IDEOGRAPH-9AD8}) missing from current font.  func(*args, **kwargs)

/home/yaya/miniconda3/envs/hikyuu/lib/python3.9/site-packages/IPython/core/events.py:89: UserWarning: Glyph 20302 (\N{CJK UNIFIED IDEOGRAPH-4F4E}) missing from current font.  func(*args, **kwargs)

/home/yaya/miniconda3/envs/hikyuu/lib/python3.9/site-packages/IPython/core/events.py:89: UserWarning: Glyph 25910 (\N{CJK UNIFIED IDEOGRAPH-6536}) missing from current font.  func(*args, **kwargs)

/home/yaya/miniconda3/envs/hikyuu/lib/python3.9/site-packages/IPython/core/events.py:89: UserWarning: Glyph 28072 (\N{CJK UNIFIED IDEOGRAPH-6DA8}) missing from current font.
...
/home/yaya/miniconda3/envs/hikyuu/lib/python3.9/site-packages/IPython/core/pylabtools.py:152: UserWarning: Glyph 28072 (\N{CJK UNIFIED IDEOGRAPH-6DA8}) missing from current font.  fig.canvas.print_figure(bytes_io, **kw)

/home/yaya/miniconda3/envs/hikyuu/lib/python3.9/site-packages/IPython/core/pylabtools.py:152: UserWarning: Glyph 24133 (\N{CJK UNIFIED IDEOGRAPH-5E45}) missing from current font.  fig.canvas.print_figure(bytes_io, **kw)
```

[https://hikyuu.readthedocs.io/zh_CN/latest/quickstart.html#matplotlib](https://hikyuu.readthedocs.io/zh_CN/latest/quickstart.html#matplotlib)

sudo find / -name 'matplotlibrc'

我也不知道具体用哪一个文件，干脆上面命令查找的结果我都按官方写的方法改一遍  （在另外一个ubuntu上面就找到一个/etc/matplotlibr）

主要改的是

```
interactive: True
axes.unicode_minus: True
font.sans-serif: Noto Sans CJK JP,DejaVu Sans, Bitstream Vera Sans, Computer Modern Sans Serif, Lucida Grande, Verdana, Geneva, Lucid, Arial, Helvetica, Avant Garde, sans-serif
```

rm -rf ~/.cache/matplotlib/fontlist-v330.json

> interactive: True 开启，非notebook里面一闪就消失


然后重启,按钮如下图

![](images/WEBRESOURCE324c319be30290c21517473e17531c18截图.png)

然后重新执行就可以正常显示中文了，如下图

![](https://gitee.com/hxc8/images5/raw/master/img/202407172332034.jpg)