conda  create -n  django

conda activate django

pip install django

pip install mysqlclient

```
django-admin startproject mysite
```

```
mysite/
    manage.py
    mysite/
        __init__.py
        settings.py
        urls.py
        asgi.py
        wsgi.py
```

这些目录和文件的用处是：

- 最外层的 

mysite/ 根目录只是你项目的容器， 根目录名称对 Django 没有影响，你可以将它重命名为任何你喜欢的名称。

- manage.py

: 一个让你用各种方式管理 Django 项目的命令行工具。你可以阅读 [django-admin 和 manage.py](https://docs.djangoproject.com/zh-hans/4.2/ref/django-admin/) 获取所有 

manage.py 的细节。

- 里面一层的 

mysite/ 目录包含你的项目，它是一个纯 Python 包。它的名字就是当你引用它内部任何东西时需要用到的 Python 包名。 (比如 

mysite.urls).

- mysite/__init__.py

：一个空文件，告诉 Python 这个目录应该被认为是一个 Python 包。如果你是 Python 初学者，阅读官方文档中的 [更多关于包的知识](https://docs.python.org/3/tutorial/modules.html#tut-packages)。

- mysite/settings.py

：Django 项目的配置文件。如果你想知道这个文件是如何工作的，请查看 [Django 配置](https://docs.djangoproject.com/zh-hans/4.2/topics/settings/) 了解细节。

- mysite/urls.py

：Django 项目的 URL 声明，就像你网站的“目录”。阅读 [URL调度器](https://docs.djangoproject.com/zh-hans/4.2/topics/http/urls/) 文档来获取更多关于 URL 的内容。

- mysite/asgi.py

：作为你的项目的运行在 ASGI 兼容的 Web 服务器上的入口。阅读 [如何使用 ASGI 来部署](https://docs.djangoproject.com/zh-hans/4.2/howto/deployment/asgi/) 了解更多细节。

- mysite/wsgi.py

：作为你的项目的运行在 WSGI 兼容的Web服务器上的入口。阅读 [如何使用 WSGI 进行部署](https://docs.djangoproject.com/zh-hans/4.2/howto/deployment/wsgi/) 了解更多细节。

```
python manage.py runserver
python manage.py runserver 8080
python manage.py runserver 0.0.0.0:8000
```

创建投票应用

现在你的开发环境——这个“项目” ——已经配置好了，你可以开始干活了。

在 Django 中，每一个应用都是一个 Python 包，并且遵循着相同的约定。Django 自带一个工具，可以帮你生成应用的基础目录结构，这样你就能专心写代码，而不是创建目录了。

项目 VS 应用

项目和应用有什么区别？应用是一个专门做某件事的网络应用程序——比如博客系统，或者公共记录的数据库，或者小型的投票程序。项目则是一个网站使用的配置和应用的集合。项目可以包含很多个应用。应用可以被很多个项目使用。

何时使用 [**include()**](https://docs.djangoproject.com/zh-hans/4.2/ref/urls/#django.urls.include)

当包括其它 URL 模式时你应该总是使用 **include()** ， **admin.site.urls** 是唯一例外。

```
python manage.py runserver
```

[http://localhost:8000/polls/](http://localhost:8000/polls/)

设置中文时区

```
LANGUAGE_CODE = 'zh-hans'

TIME_ZONE = 'Asia/Shanghai'
```

如果LANGUAGE_CODE是zh-cn则会报错  

```
raise OSError(
OSError: No translation files found for default language zh-cn.
```

sudo docker rundocker pull mysql:latest

sudo docker run -itd --name mysql-test -p 13306:3306 -e MYSQL_ROOT_PASSWORD=123456 mysql

sudo docker exec -it mysql-test bash

mysql -u root -p

create database chaogushe;

配置数据库

```
DATABASES = {
    'default': {
        'ENGINE': 'django.db.backends.mysql',
        'NAME': 'xxxx',
        'USER': 'root',
        'PASSWORD': '123456',
        'HOST': '192.168.1.118',
        'PORT': '13306',
    }
}

```

python manage.py migrate

```
>python manage.py migrate
Operations to perform:
  Apply all migrations: admin, auth, contenttypes, sessions
Running migrations:
  Applying contenttypes.0001_initial... OK
  Applying auth.0001_initial... OK
  Applying admin.0001_initial... OK
  Applying admin.0002_logentry_remove_auto_add... OK
  Applying admin.0003_logentry_add_action_flag_choices... OK
  Applying contenttypes.0002_remove_content_type_name... OK
  Applying auth.0002_alter_permission_name_max_length... OK
  Applying auth.0003_alter_user_email_max_length... OK
  Applying auth.0004_alter_user_username_opts... OK
  Applying auth.0005_alter_user_last_login_null... OK
  Applying auth.0006_require_contenttypes_0002... OK
  Applying auth.0007_alter_validators_add_error_messages... OK
  Applying auth.0008_alter_user_username_max_length... OK
  Applying auth.0009_alter_user_last_name_max_length... OK
  Applying auth.0010_alter_group_name_max_length... OK
  Applying auth.0011_update_proxy_permissions... OK
  Applying auth.0012_alter_user_first_name_max_length... OK
  Applying sessions.0001_initial... OK
```

编辑 **polls/models.py** 文件：

polls/models.py[](https://docs.djangoproject.com/zh-hans/4.2/intro/tutorial02/#id2)

```
from django.db import models


class Question(models.Model):
    question_text = models.CharField(max_length=200)
    pub_date = models.DateTimeField("date published")


class Choice(models.Model):
    question = models.ForeignKey(Question, on_delete=models.CASCADE)
    choice_text = models.CharField(max_length=200)
    votes = models.IntegerField(default=0)

```

每个模型被表示为 [**django.db.models.Model**](https://docs.djangoproject.com/zh-hans/4.2/ref/models/instances/#django.db.models.Model) 类的子类。每个模型有许多类变量，它们都表示模型里的一个数据库字段。

每个字段都是 [**Field**](https://docs.djangoproject.com/zh-hans/4.2/ref/models/fields/#django.db.models.Field) 类的实例 - 比如，字符字段被表示为 [**CharField**](https://docs.djangoproject.com/zh-hans/4.2/ref/models/fields/#django.db.models.CharField) ，日期时间字段被表示为 [**DateTimeField**](https://docs.djangoproject.com/zh-hans/4.2/ref/models/fields/#django.db.models.DateTimeField) 。这将告诉 Django 每个字段要处理的数据类型。

每个 [**Field**](https://docs.djangoproject.com/zh-hans/4.2/ref/models/fields/#django.db.models.Field) 类实例变量的名字（例如 **question_text** 或 **pub_date** ）也是字段名，所以最好使用对机器友好的格式。你将会在 Python 代码里使用它们，而数据库会将它们作为列名。

你可以使用可选的选项来为 [**Field**](https://docs.djangoproject.com/zh-hans/4.2/ref/models/fields/#django.db.models.Field) 定义一个人类可读的名字。这个功能在很多 Django 内部组成部分中都被使用了，而且作为文档的一部分。如果某个字段没有提供此名称，Django 将会使用对机器友好的名称，也就是变量名。在上面的例子中，我们只为 **Question.pub_date** 定义了对人类友好的名字。对于模型内的其它字段，它们的机器友好名也会被作为人类友好名使用。

定义某些 [**Field**](https://docs.djangoproject.com/zh-hans/4.2/ref/models/fields/#django.db.models.Field) 类实例需要参数。例如 [**CharField**](https://docs.djangoproject.com/zh-hans/4.2/ref/models/fields/#django.db.models.CharField) 需要一个 [**max_length**](https://docs.djangoproject.com/zh-hans/4.2/ref/models/fields/#django.db.models.CharField.max_length) 参数。这个参数的用处不止于用来定义数据库结构，也用于验证数据，我们稍后将会看到这方面的内容。

[**Field**](https://docs.djangoproject.com/zh-hans/4.2/ref/models/fields/#django.db.models.Field) 也能够接收多个可选参数；在上面的例子中：我们将 **votes** 的 [**default**](https://docs.djangoproject.com/zh-hans/4.2/ref/models/fields/#django.db.models.Field.default) 也就是默认值，设为0。

注意在最后，我们使用 [**ForeignKey**](https://docs.djangoproject.com/zh-hans/4.2/ref/models/fields/#django.db.models.ForeignKey) 定义了一个关系。这将告诉 Django，每个 **Choice** 对象都关联到一个 **Question** 对象。Django 支持所有常用的数据库关系：多对一、多对多和一对一。

需要在配置类 [**INSTALLED_APPS**](https://docs.djangoproject.com/zh-hans/4.2/ref/settings/#std-setting-INSTALLED_APPS) 中添加设置。因为 **PollsConfig** 类写在文件 **polls/apps.py** 中，所以它的点式路径是 **'polls.apps.PollsConfig'**。在文件 **mysite/settings.py** 中 [**INSTALLED_APPS**](https://docs.djangoproject.com/zh-hans/4.2/ref/settings/#std-setting-INSTALLED_APPS) 子项添加点式路径后，它看起来像这样：

mysite/settings.py[¶](https://docs.djangoproject.com/zh-hans/4.2/intro/tutorial02/#id3)

```
INSTALLED_APPS = [
    "polls.apps.PollsConfig",
    "django.contrib.admin",
    "django.contrib.auth",
    "django.contrib.contenttypes",
    "django.contrib.sessions",
    "django.contrib.messages",
    "django.contrib.staticfiles",
]

```

现在你的 Django 项目会包含 **polls** 应用。接着运行下面的命令：

/ 



```
$ python manage.py makemigrations polls

```

你将会看到类似于下面这样的输出：

> python manage.py makemigrations pollsMigrations for 'polls':polls\migrations\0001_initial.py- Create model Question- Create model Choice


[**sqlmigrate**](https://docs.djangoproject.com/zh-hans/4.2/ref/django-admin/#django-admin-sqlmigrate) 命令接收一个迁移的名称，然后返回对应的 SQL：

/ 



```
$ python manage.py sqlmigrate polls 0001

```

你将会看到类似下面这样的输出（我把输出重组成了人类可读的格式）：

```
BEGIN;
--
-- Create model Question
--
CREATE TABLE "polls_question" (
    "id" bigint NOT NULL PRIMARY KEY GENERATED BY DEFAULT AS IDENTITY,
    "question_text" varchar(200) NOT NULL,
    "pub_date" timestamp with time zone NOT NULL
);
--
-- Create model Choice
--
CREATE TABLE "polls_choice" (
    "id" bigint NOT NULL PRIMARY KEY GENERATED BY DEFAULT AS IDENTITY,
    "choice_text" varchar(200) NOT NULL,
    "votes" integer NOT NULL,
    "question_id" bigint NOT NULL
);
ALTER TABLE "polls_choice"
  ADD CONSTRAINT "polls_choice_question_id_c5b4b260_fk_polls_question_id"
    FOREIGN KEY ("question_id")
    REFERENCES "polls_question" ("id")
    DEFERRABLE INITIALLY DEFERRED;
CREATE INDEX "polls_choice_question_id_c5b4b260" ON "polls_choice" ("question_id");

COMMIT;
```

再次运行 [**migrate**](https://docs.djangoproject.com/zh-hans/4.2/ref/django-admin/#django-admin-migrate) 命令，在数据库里创建新定义的模型的数据表：

python manage.py migrate

> python manage.py migrateOperations to perform:Apply all migrations: admin, auth, contenttypes, polls, sessionsRunning migrations:Applying polls.0001_initial... OK


改变模型需要这三步：

- 编辑 models.py 文件，改变模型。

- 运行 

- 运行 

创建一个管理员账号[¶](https://docs.djangoproject.com/zh-hans/4.2/intro/tutorial02/#creating-an-admin-user)

首先，我们得创建一个能登录管理页面的用户。请运行下面的命令：

```
$ python manage.py createsuperuser
```

### 启动开发服务器

Django 的管理界面默认就是启用的。让我们启动开发服务器，看看它到底是什么样的。

如果开发服务器未启动，用以下命令启动它：

```
$ python manage.py runserver
```

[http://127.0.0.1:8000/admin/](http://127.0.0.1:8000/admin/)

### 向管理页面中加入投票应用

但是我们的投票应用在哪呢？它没在索引页面里显示。

只需要再做一件事：我们得告诉管理，问题 **Question** 对象需要一个后台接口。打开 **polls/admin.py** 文件，把它编辑成下面这样：

polls/admin.py[](https://docs.djangoproject.com/zh-hans/4.2/intro/tutorial02/#id6)

```
from django.contrib import admin

from .models import Question

admin.site.register(Question)
```

### 创建一个测试来暴露这个 bug

我们刚刚在 [**shell**](https://docs.djangoproject.com/zh-hans/4.2/ref/django-admin/#django-admin-shell) 里做的测试也就是自动化测试应该做的工作。所以我们来把它改写成自动化的吧。

按照惯例，Django 应用的测试应该写在应用的 **tests.py** 文件里。测试系统会自动的在所有以 **tests** 开头的文件里寻找并执行测试代码。

将下面的代码写入 **polls** 应用里的 **tests.py** 文件内：

polls/tests.py[](https://docs.djangoproject.com/zh-hans/4.2/intro/tutorial05/#id1)

```
import datetime

from django.test import TestCase
from django.utils import timezone

from .models import Question


class QuestionModelTests(TestCase):
    def test_was_published_recently_with_future_question(self):
        """
        was_published_recently() returns False for questions whose pub_date
        is in the future.
        """
        time = timezone.now() + datetime.timedelta(days=30)
        future_question = Question(pub_date=time)
        self.assertIs(future_question.was_published_recently(), False)

```

我们创建了一个 [**django.test.TestCase**](https://docs.djangoproject.com/zh-hans/4.2/topics/testing/tools/#django.test.TestCase) 的子类，并添加了一个方法，此方法创建一个 **pub_date** 时未来某天的 **Question** 实例。然后检查它的 **was_published_recently()** 方法的返回值——它 **应该** 是 False。

### 运行测试

在终端中，我们通过输入以下代码运行测试:

```
$ python manage.py test polls
```

### 发生了什么呢？以下是自动化测试的运行过程：

python manage.py test polls 将会寻找 polls 应用里的测试代码

它找到了 django.test.TestCase 的一个子类

它创建一个特殊的数据库供测试使用

它在类中寻找测试方法——以 test 开头的方法。

在 test_was_published_recently_with_future_question 方法中，它创建了一个 pub_date 值为 30 天后的 Question 实例。

接着使用 assertls() 方法，发现 was_published_recently() 返回了 True，而我们期望它返回 False。

测试系统通知我们哪些测试样例失败了，和造成测试失败的代码所在的行号。