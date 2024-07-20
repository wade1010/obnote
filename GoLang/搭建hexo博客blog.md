https://hexo.io/zh-cn/docs/







https://nodejs.org/en/download/

![](https://gitee.com/hxc8/images7/raw/master/img/202407190750087.jpg)



复制链接地址 然后wget 如下



wget https://nodejs.org/dist/v14.17.0/node-v14.17.0-linux-x64.tar.xz

tar -xf node-v14.17.0-linux-x64.tar.xz

mv node-v14.17.0-linux-x64 /usr/local/node

cd /usr/bin/

ln -s /usr/local/node/bin/node node

ln -s /usr/local/node/bin/npm npm

ln -s /usr/local/node/bin/npx npx

ln -s /usr/local/node/bin/hexo hexo



npm install hexo-cli -g





cd /opt/webroot



hexo init blog



cd blog



npm install



cd themes



git clone https://github.com/blinkfox/hexo-theme-matery.git



cd ..



【参考 https://github.com/blinkfox/hexo-theme-matery/blob/develop/README_CN.md】



切换主题

修改 Hexo 根目录下的 _config.yml 的 theme 的值：theme: hexo-theme-matery

_config.yml 文件的其它修改建议:

- 请修改 _config.yml 的 url 的值为你的网站主 URL（如：http://xxx.com）。

- 建议修改两个 per_page 的分页条数值为 6 的倍数，如：12、18 等，这样文章列表在各个屏幕下都能较好的显示。

- 如果你是中文用户，则建议修改 language 的值为 zh-CN。



```javascript
# Hexo Configuration
## Docs: https://hexo.io/docs/configuration.html
## Source: https://github.com/hexojs/hexo/

# Site
title: 'ProgramRecording'
subtitle: '程序录'
description: '记录程序人生，记录程序之路'
keywords: 'IT,程序'
author: Bob
language: zh-CN
timezone: 'Asia/Shanghai'

# URL
## Set your site url here. For example, if you use GitHub Page, set url as 'https://username.github.io/project'
url: http://cxlu.com
permalink: :year/:month/:day/:title/
permalink_defaults:
pretty_urls:
  trailing_index: true # Set to false to remove trailing 'index.html' from permalinks
  trailing_html: true # Set to false to remove trailing '.html' from permalinks

# Directory
source_dir: source
public_dir: public
tag_dir: tags
archive_dir: archives
category_dir: categories
code_dir: downloads/code
i18n_dir: :lang
skip_render:

# Writing
new_post_name: :title.md # File name of new posts
default_layout: post
titlecase: false # Transform title into titlecase
external_link:
  enable: true # Open external links in new tab
  field: site # Apply to the whole site
  exclude: ''
filename_case: 0
render_drafts: false
post_asset_folder: false
relative_link: false
future: true
highlight:
  enable: true
  line_number: true
  auto_detect: false
  tab_replace: ''
  wrap: true
  hljs: false
prismjs:
  enable: false
  preprocess: true
  line_number: true
  tab_replace: ''

# Home page setting
# path: Root path for your blogs index page. (default = '')
# per_page: Posts displayed per page. (0 = disable pagination)
# order_by: Posts order. (Order by date descending by default)
index_generator:
  path: ''
  per_page: 12
  order_by: -date

# Category & Tag
default_category: uncategorized
category_map:
tag_map:

# Metadata elements
## https://developer.mozilla.org/en-US/docs/Web/HTML/Element/meta
meta_generator: true

# Date / Time format
## Hexo uses Moment.js to parse and display date
## You can customize the date format as defined in
## http://momentjs.com/docs/#/displaying/format/
date_format: YYYY-MM-DD
time_format: HH:mm:ss
## updated_option supports 'mtime', 'date', 'empty'
updated_option: 'mtime'

# Pagination
## Set per_page to 0 to disable pagination
per_page: 12
pagination_dir: page

# Include / Exclude file(s)
## include:/exclude: options only apply to the 'source/' folder
include:
exclude:
ignore:

# Extensions
## Plugins: https://hexo.io/plugins/
## Themes: https://hexo.io/themes/
theme: hexo-theme-matery 

# Deployment
## Docs: https://hexo.io/docs/one-command-deployment
deploy:
  type: ''
search:
  path: search.xml
  field: post
permalink_pinyin:
  enable: true
  separator: '-' # default: '-'
postInfo:
  date: true
  update: false
  wordCount: true # 设置文章字数统计为 true.
  totalCount: true # 设置站点文章总字数统计为 true.
  min2read: true # 阅读时长.
  readCount: true # 阅读次数.
githubEmojis:
  enable: true
  className: github-emoji
  inject: true
  styles:
  customEmojis:
feed:
  type: atom
  path: atom.xml
  limit: 20
  hub:
  content:
  content_limit: 140
  content_limit_delim: ' '
  order_by: -date
```





新建分类 categories 页

categories 页是用来展示所有分类的页面，如果在你的博客 source 目录下还没有 categories/index.md 文件，那么你就需要新建一个，命令如下：

hexo new page "categories"

编辑你刚刚新建的页面文件 /source/categories/index.md，至少需要以下内容：

---
title: categories
date: 2018-09-30 17:25:30
type: "categories"
layout: "categories"
---





新建标签 tags 页

tags 页是用来展示所有标签的页面，如果在你的博客 source 目录下还没有 tags/index.md 文件，那么你就需要新建一个，命令如下：

hexo new page "tags"

编辑你刚刚新建的页面文件 /source/tags/index.md，至少需要以下内容：

---
title: tags
date: 2018-09-30 18:23:38
type: "tags"
layout: "tags"
---





新建关于我 about 页

about 页是用来展示关于我和我的博客信息的页面，如果在你的博客 source 目录下还没有 about/index.md 文件，那么你就需要新建一个，命令如下：

hexo new page "about"

编辑你刚刚新建的页面文件 /source/about/index.md，至少需要以下内容：

---
title: about
date: 2018-09-30 17:25:30
type: "about"
layout: "about"
---





新建留言板 contact 页（可选的）

contact 页是用来展示留言板信息的页面，如果在你的博客 source 目录下还没有 contact/index.md 文件，那么你就需要新建一个，命令如下：

hexo new page "contact"

编辑你刚刚新建的页面文件 /source/contact/index.md，至少需要以下内容：

---
title: contact
date: 2018-09-30 17:25:30
type: "contact"
layout: "contact"
---

注：本留言板功能依赖于第三方评论系统，请激活你的评论系统才有效果。并且在themes/hexo-theme-matery的 _config.yml 文件中，第 19 至 21 行的“菜单”配置，取消关于留言板的注释即可（默认是打开的，所以无需操作）





新建友情链接 friends 页（可选的）

friends 页是用来展示友情链接信息的页面，如果在你的博客 source 目录下还没有 friends/index.md 文件，那么你就需要新建一个，命令如下：

hexo new page "friends"

编辑你刚刚新建的页面文件 /source/friends/index.md，至少需要以下内容：

---
title: friends
date: 2018-12-12 21:25:30
type: "friends"
layout: "friends"
---

同时，在你的博客 source 目录下新建 _data 目录，在 _data 目录中新建 friends.json 文件，文件内容如下所示：

[{
    "avatar": "https://img2.woyaogexing.com/2021/05/29/b3c8b107a18e41348048f9ff56a445d6!400x400.png",
    "name": "百度",
    "introduction": "没事找度娘，有事找百度",
    "url": "http://www.baidu.com/",
    "title": "过去撩一下"
}]



mkdir _data && vim _data/friends.json





新建 404 页

如果在你的博客 source 目录下还没有 404.md 文件，那么你就需要新建一个

hexo new page 404

编辑你刚刚新建的页面文件 /source/404/index.md，至少需要以下内容：

---
title: 404
date: 2018-09-30 17:25:30
type: "404"
layout: "404"
description: "Oops～，我崩溃了！找不到你想要的页面 :("
---







搜索

本主题中还使用到了 hexo-generator-search 的 Hexo 插件来做内容搜索，安装命令如下：

npm install hexo-generator-search --save

在 Hexo 根目录下的 _config.yml 文件中，新增以下的配置项：

search:
  path: search.xml
  field: post





中文链接转拼音（建议安装）

如果你的文章名称是中文的，那么 Hexo 默认生成的永久链接也会有中文，这样不利于 SEO，且 gitment 评论对中文链接也不支持。我们可以用 hexo-permalink-pinyin Hexo 插件使在生成文章时生成中文拼音的永久链接。

安装命令如下：

npm i hexo-permalink-pinyin --save

在 Hexo 根目录下的 _config.yml 文件中，新增以下的配置项：

permalink_pinyin:
  enable: true
  separator: '-' # default: '-'

注：除了此插件外，hexo-abbrlink 插件也可以生成非中文的链接。

文章字数统计插件（建议安装）

如果你想要在文章中显示文章字数、阅读时长信息，可以安装 hexo-wordcount插件。

安装命令如下：

npm i --save hexo-wordcount

然后只需在本主题下的 _config.yml 文件中，将各个文章字数相关的配置激活即可：

postInfo:
  date: true
  update: false
  wordCount: false # 设置文章字数统计为 true.
  totalCount: false # 设置站点文章总字数统计为 true.
  min2read: false # 阅读时长.
  readCount: false # 阅读次数.

添加emoji表情支持（可选的）

本主题新增了对emoji表情的支持，使用到了 hexo-filter-github-emojis 的 Hexo 插件来支持 emoji表情的生成，把对应的markdown emoji语法（::,例如：:smile:）转变成会跳跃的emoji表情，安装命令如下：

npm install hexo-filter-github-emojis --save

在 Hexo 根目录下的 _config.yml 文件中，新增以下的配置项：

githubEmojis:
  enable: true
  className: github-emoji
  inject: true
  styles:
  customEmojis:

执行 hexo clean && hexo g 重新生成博客文件，然后就可以在文章中对应位置看到你用emoji语法写的表情了。

添加 RSS 订阅支持（可选的）

本主题中还使用到了 hexo-generator-feed 的 Hexo 插件来做 RSS，安装命令如下：

npm install hexo-generator-feed --save

在 Hexo 根目录下的 _config.yml 文件中，新增以下的配置项：

feed:
  type: atom
  path: atom.xml
  limit: 20
  hub:
  content:
  content_limit: 140
  content_limit_delim: ' '
  order_by: -date

执行 hexo clean && hexo g 重新生成博客文件，然后在 public 文件夹中即可看到 atom.xml 文件，说明你已经安装成功了。





![](https://gitee.com/hxc8/images7/raw/master/img/202407190750451.jpg)







