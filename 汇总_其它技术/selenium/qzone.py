# coding = utf-8
import  time

from selenium import webdriver
driver = webdriver.Firefox(executable_path = '/Users/xhcheng/ghworkspace/geckodriver')
driver.set_window_position(20, 40)
driver.set_window_size(1100,700)
driver.get('https://qzone.qq.com/')
driver.switch_to_frame('login_frame')
driver.find_element_by_id('switcher_plogin').click()
driver.find_element_by_id('u').clear()
driver.find_element_by_id('u').send_keys('6666612')
driver.find_element_by_id('p').clear()
driver.find_element_by_id('p').send_keys('xhc1010')
driver.find_element_by_id('login_button').click()
driver.find_element_by_id('login_button').click()
cookie= driver.get_cookies()
print cookie
