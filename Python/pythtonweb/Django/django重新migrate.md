第一次migrate后，

![](D:/download/youdaonote-pull-master/data/Technology/Python/pythtonweb/Django/images/WEBRESOURCE8e7cff3b416753fd4ffd46b56f587f14截图.png)

![](D:/download/youdaonote-pull-master/data/Technology/Python/pythtonweb/Django/images/WEBRESOURCEbaaf6abb500a7ab4862b63fbdaf2e7f5截图.png)

假如需删除上图的cgs_limitupreason,

需要删除django_migrations里面的cgs的相关记录

然后重新python manage.py makemigrations cgs

python manage.py migrate cgs

就可以了