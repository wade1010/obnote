

selenium + phantomjs





from selenium import webdriver

import time

dr = webdriver.Firefox(executable_path = '/Users/jinwenxin/desktop/pythonPractice/geckodriver')

time.sleep(5)

print 'Browser will close.'

dr.quit()

print 'Browser is close'

教大家一个方法，我用了在python下执行了help(webdriver.Firefox) 回车，这样就知道了传什么参数。

