正则匹配到html得到想要的内容，再用多线程保存html到数据库

[parseHtml.py_](attachments/CAE2301695714C7F9D425DBC36BC9B82parseHtml.py_)



[parseHtml.py2](attachments/273B8E814661443098BD93F98D521D11parseHtml.py2)

匹配到名字成为字典，这里是组成两个字典

resumePreUrl={'url': '1', 'name': '1'}

再加到list里面  list.append（resumePreUrl）

resumePreUrl={'url': '2', 'name': '2'}

再加到list里面  list.append（resumePreUrl）

这样 list里面就有两个字典，转成json输出

import json

encodedjson = json.dumps(list)

print encodedjson