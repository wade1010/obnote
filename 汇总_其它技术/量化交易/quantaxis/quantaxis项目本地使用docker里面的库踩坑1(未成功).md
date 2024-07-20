问题：

使用本地代码的时候，我checkout的分支是1.X版本，但是requirement.txt里面的配置是>=，所以你pip install -r requirement.txt的时候就是安装的最新版本。这样就看你会有问题，比如我遇到的问题：

pymongo这个库，3.X版本是有count这个方法的，但是4.X可能就没有了。[https://www.jianshu.com/p/a71f6825d248](https://www.jianshu.com/p/a71f6825d248)

解决：

安装好docker版本的quantaxis

进入docker

docker exec -it qacommunity-rust bash

pip list

查看对应的版本

```
(base) root@89300a30efaf:~# pip list
Package                                  Version
---------------------------------------- -------------------
absl-py                                  0.11.0
aioch                                    0.0.2
aiohttp                                  3.6.3
alabaster                                0.7.12
alembic                                  1.4.3
...................省略.......................
astropy                                  4.0.2
astunparse                               1.6.3
async-generator                          1.10
async-timeout                            3.0.1
atomicwrites                             1.4.0
```

然后找到quantaxis项目里面的requirement.txt。

去掉 demjson 这个，安装会失败，而且也没用到

将>=改成==

将后面的版本改成上面pip list出来的版本

有的没有版本限制，自己也加上==

![](https://gitee.com/hxc8/images5/raw/master/img/202407172348201.jpg)

改成如下

![](https://gitee.com/hxc8/images5/raw/master/img/202407172348043.jpg)

安装完执行启动quantaxis/QUANTAXIS/**main**.py

然后输入 save stock_block

发现报错如下：

![](https://gitee.com/hxc8/images5/raw/master/img/202407172348054.jpg)

这个是requirement.txt里面没有的，所以自己添加下

到docker 环境 pip list看下

```
(base) root@89300a30efaf:~# pip list|grep xlrd
xlrd                                     1.2.0
```

然后在requirement.txt里面添加一行

xlrd==1.2.0

也可以单独执行 pip install xlrd==1.2.0

附：

docker里面 pip list

文件如下：

[requirement.txt](attachments/WEBRESOURCE9dc0d7671a970b90b7f97df74d745bddrequirement.txt)

文本如下：

```
absl-py==0.11.0
aioch==0.0.2
aiohttp==3.6.3
alabaster==0.7.12
alembic==1.4.3
amqp==5.0.5
anaconda-client==1.7.2
anaconda-navigator==1.10.0
anaconda-project==0.8.3
apache-airflow==2.0.0
apache-airflow-providers-cncf-kubernetes==1.0.0
apache-airflow-providers-ftp==1.0.0
apache-airflow-providers-http==1.0.0
apache-airflow-providers-imap==1.0.0
apache-airflow-providers-sqlite==1.0.0
apispec==3.3.2
appdirs==1.4.4
APScheduler==3.6.3
argcomplete==1.12.2
argh==0.26.2
argon2-cffi==20.1.0
asn1crypto==1.4.0
astroid==2.4.2
astropy==4.0.2
astunparse==1.6.3
async-generator==1.10
async-timeout==3.0.1
atomicwrites==1.4.0
attrs==20.3.0
autopep8==1.5.4
Babel==2.8.1
backcall==0.2.0
backports.functools-lru-cache==1.6.1
backports.shutil-get-terminal-size==1.0.0
backports.tempfile==1.0
backports.weakref==1.0.post1
beautifulsoup4==4.9.3
billiard==3.6.3.0
bitarray==1.6.1
bkcharts==0.2
bleach==3.2.1
blinker==1.4
bokeh==2.2.3
boto==2.49.0
Bottleneck==1.3.2
brotlipy==0.7.0
bs4==0.0.1
cached-property==1.5.2
cachetools==4.2.1
cattrs==1.1.2
celery==5.0.5
certifi==2020.6.20
cffi==1.14.3
chardet==3.0.4
click==7.1.2
click-didyoumean==0.0.3
click-plugins==1.1.1
click-repl==0.1.6
clickclick==20.10.2
clickhouse-driver==0.2.0
cloudpickle==1.6.0
clyent==1.2.2
colorama==0.4.4
colorlog==4.0.2
commonmark==0.9.1
conda==4.9.2
conda-build==3.20.5
conda-package-handling==1.7.2
conda-verify==3.4.2
connexion==2.7.0
contextlib2==0.6.0.post1
croniter==0.3.36
cryptography==3.1.1
cycler==0.10.0
Cython==0.29.21
cytoolz==0.11.0
dag-factory==0.7.0
dask==2.30.0
decorator==4.4.2
defusedxml==0.6.0
delegator.py==0.1.1
demjson==2.2.4
diff-match-patch==20200713
dill==0.3.3
distributed==2.30.1
dnspython==1.16.0
docutils==0.16
email-validator==1.1.2
entrypoints==0.3
et-xmlfile==1.0.1
eventlet==0.30.1
fastcache==1.1.0
filelock==3.0.12
flake8==3.8.4
Flask==1.1.2
Flask-AppBuilder==3.1.1
Flask-Babel==1.0.0
Flask-Caching==1.9.0
Flask-JWT-Extended==3.25.0
Flask-Login==0.4.1
Flask-OpenID==1.2.5
Flask-SQLAlchemy==2.4.4
flask-swagger==0.2.13
Flask-WTF==0.14.3
fsspec==0.8.3
funcsigs==1.0.2
future==0.18.2
gast==0.3.3
gevent==20.9.0
gevent-websocket==0.10.1
glob2==0.7
gmpy2==2.0.8
google-auth==1.26.1
google-auth-oauthlib==0.4.2
google-pasta==0.2.0
graphviz==0.15
greenlet==0.4.17
grpcio==1.31.0
gunicorn==19.10.0
h5py==2.10.0
HeapDict==1.0.1
html5lib==1.1
idna==2.10
imageio==2.9.0
imagesize==1.2.0
importlib-metadata==1.7.0
importlib-resources==1.5.0
inflection==0.5.1
iniconfig==1.1.1
intervaltree==3.1.0
ipykernel==5.3.4
ipython==7.19.0
ipython-genutils==0.2.0
ipywidgets==7.5.1
iso8601==0.1.13
isort==5.6.4
itsdangerous==1.1.0
janus==0.4.0
jdcal==1.4.1
jedi==0.17.1
jeepney==0.5.0
Jinja2==2.11.2
joblib==0.17.0
json-merge-patch==0.2
json5==0.9.5
jsonschema==3.2.0
jupyter==1.0.0
jupyter-client==6.1.7
jupyter-console==6.2.0
jupyter-core==4.6.3
jupyterlab==2.2.6
jupyterlab-pygments==0.1.2
jupyterlab-server==1.2.0
Keras==2.4.3
Keras-Preprocessing==1.1.2
keyring==21.4.0
kiwisolver==1.3.0
kombu==5.0.2
kubernetes==11.0.0
lazy-object-proxy==1.4.3
libarchive-c==2.9
llvmlite==0.34.0
locket==0.2.0
lockfile==0.12.2
lxml==4.6.1
Mako==1.1.3
Markdown==3.3.3
MarkupSafe==1.1.1
marshmallow==3.10.0
marshmallow-enum==1.5.1
marshmallow-oneofschema==2.1.0
marshmallow-sqlalchemy==0.23.1
matplotlib==3.3.2
mccabe==0.6.1
mistune==0.8.4
mkl-fft==1.2.0
mkl-random==1.1.1
mkl-service==2.3.0
mock==4.0.2
more-itertools==8.6.0
motor==2.3.0
mpmath==1.1.0
msgpack==1.0.0
multidict==4.7.6
multipledispatch==0.6.0
natsort==7.1.0
navigator-updater==0.2.1
nbclient==0.5.1
nbconvert==6.0.7
nbformat==5.0.8
nest-asyncio==1.4.2
networkx==2.5
nltk==3.5
nose==1.3.7
notebook==6.1.4
numba==0.51.2
numexpr==2.7.1
numpy==1.19.2
numpydoc==1.1.0
oauthlib==3.1.0
olefile==0.46
openapi-spec-validator==0.2.9
openpyxl==3.0.5
opt-einsum==3.1.0
packaging==20.4
pandarallel==1.5.1
pandas==1.1.5
pandocfilters==1.4.3
parso==0.7.0
partd==1.1.0
path==15.0.0
pathlib2==2.3.5
pathtools==0.1.2
patsy==0.5.1
PeakUtils==1.3.3
pendulum==2.1.2
pep8==1.7.1
pexpect==4.8.0
pickleshare==0.7.5
pika==1.0.0b1
Pillow==8.0.1
pip==20.2.4
pkginfo==1.6.1
pluggy==0.13.1
ply==3.11
prettytable==2.0.0
prison==0.1.3
prometheus-client==0.8.0
prompt-toolkit==3.0.8
protobuf==3.14.0
psutil==5.7.2
ptyprocess==0.6.0
py==1.9.0
pyasn1==0.4.8
pyasn1-modules==0.2.8
pycodestyle==2.6.0
pyconvert==0.6.3
pycosat==0.6.3
pycparser==2.20
pycurl==7.43.0.6
pydocstyle==5.1.1
pyecharts==1.9.0
pyecharts-snapshot==0.2.0
pyee==7.0.4
pyflakes==2.2.0
Pygments==2.7.2
PyJWT==1.7.1
pylint==2.6.0
pymongo==3.11.2
pyodbc==4.0.0-unsupported
pyOpenSSL==19.1.0
pyparsing==2.4.7
pyppeteer==0.2.2
pyrsistent==0.17.3
PySocks==1.7.1
pytdx==1.72.post1
pytesseract==0.3.7
pytest==0.0.0
python-daemon==2.2.4
python-dateutil==2.8.1
python-editor==1.0.4
python-jsonrpc-server==0.4.0
python-language-server==0.35.1
python-nvd3==0.15.0
python-slugify==4.0.1
python3-openid==3.2.0
pytz==2020.1
pytzdata==2020.1
PyWavelets==1.1.1
pyxdg==0.27
PyYAML==5.3.1
pyzmq==19.0.2
qaenv==0.0.2
QAStrategy==0.0.25
qavifiserver==0.0.2
QDarkStyle==2.8.1
qgrid==1.3.1
qifiaccount==1.14.0
qifimanager==1.2.3
QtAwesome==1.0.1
qtconsole==4.7.7
QtPy==1.9.0
quantaxis==1.10.19.post3
quantaxis-pubsub==1.11
quantaxis-run==1.9
quantaxis-servicedetect==0.0.3
quantaxis-webserver==1.8.2
redis==3.5.3
regex==2020.10.15
requests==2.24.0
requests-oauthlib==1.3.0
retrying==1.3.3
rich==9.2.0
rope==0.18.0
rsa==4.7
Rtree==0.9.4
ruamel-yaml==0.15.87
scikit-image==0.17.2
scikit-learn==0.23.2
scipy==1.5.2
seaborn==0.11.1
SecretStorage==3.1.2
Send2Trash==1.5.0
setproctitle==1.2.1
setuptools==50.3.1.post20201107
simplegeneric==0.8.1
simplejson==3.17.2
singledispatch==3.4.0.3
sip==4.19.13
six==1.15.0
snowballstemmer==2.0.0
sortedcollections==1.2.1
sortedcontainers==2.2.2
soupsieve==2.0.1
Sphinx==3.2.1
sphinxcontrib-applehelp==1.0.2
sphinxcontrib-devhelp==1.0.2
sphinxcontrib-htmlhelp==1.0.3
sphinxcontrib-jsmath==1.0.1
sphinxcontrib-qthelp==1.0.3
sphinxcontrib-serializinghtml==1.1.4
sphinxcontrib-websupport==1.2.4
spyder==4.1.5
spyder-kernels==1.9.4
SQLAlchemy==1.3.20
SQLAlchemy-JSONField==1.0.0
SQLAlchemy-Utils==0.36.8
statsmodels==0.12.1
swagger-ui-bundle==0.0.8
sympy==1.6.2
tables==3.6.1
tabulate==0.8.7
tblib==1.7.0
tenacity==6.2.0
tensorboard==2.3.0
tensorboard-plugin-wit==1.6.0
tensorflow==2.2.0
tensorflow-estimator==2.2.0
termcolor==1.1.0
terminado==0.9.1
testpath==0.4.4
text-unidecode==1.3
threadpoolctl==2.1.0
thrift==0.13.0
tifffile==2020.10.1
toml==0.10.1
toolz==0.11.1
tornado==5.1.1
tornado-http2==0.0.1
tqdm==4.50.2
traitlets==5.0.5
tushare==1.2.62
typing-extensions==3.7.4.3
tzlocal==2.1
ujson==4.0.1
unicodecsv==0.14.1
urllib3==1.25.11
vine==5.0.0
watchdog==0.10.3
wcwidth==0.2.5
webencodings==0.5.1
websocket-client==0.57.0
websockets==8.1
Werkzeug==1.0.1
wheel==0.35.1
widgetsnbextension==3.5.1
wrapt==1.11.2
WTForms==2.3.3
wurlitzer==2.0.1
xlrd==1.2.0
XlsxWriter==1.3.7
xlwt==1.3.0
xmltodict==0.12.0
yapf==0.30.0
yarl==1.6.3
zenlog==1.1
zict==2.0.0
zipp==3.4.0
zope.event==4.5.0
zope.interface==5.1.2
```