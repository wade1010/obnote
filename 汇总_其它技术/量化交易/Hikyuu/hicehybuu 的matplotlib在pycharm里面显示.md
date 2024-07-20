from hikyuu.interactive import *

import matplotlib.pyplot as plt

s = sm['sh000001']

k = s.get_kdata(Query(-200))

# 创建两个显示坐标轴的窗口ax1, ax2 = create_figure(2)

# 在第一个坐标轴中绘制K线和EMAk.plot(axes=ax1)

plt.draw()

plt.show()