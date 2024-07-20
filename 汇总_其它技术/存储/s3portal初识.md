 git clone [https://github.com/inwinstack/s3-portal-ui.git](https://github.com/inwinstack/s3-portal-ui.git)

cd s3-portal-api

```
cp config.example.js config.js
vim config.js
```

```
module.exports = {
  NODE_ENV: process.env.NODE_ENV || 'development',
  SERVER_HOST: 'http://local.s3portal.com',
};
```

```
npm install
```

然后配置后端

git clone [https://github.com/inwinstack/s3-portal-api.git](https://github.com/inwinstack/s3-portal-api.git)

cp .env.example .env

vim .env

```
APP_ENV=local
APP_DEBUG=true
APP_KEY=SomeRandomString

DB_HOST=127.0.0.1
DB_DATABASE=s3portal
DB_USERNAME=root
DB_PASSWORD=

CACHE_DRIVER=file
SESSION_DRIVER=file
QUEUE_DRIVER=sync

REDIS_HOST=localhost
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_DRIVER=smtp
MAIL_HOST=mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null

S3_ACCESS_KEY=minioadmin
S3_SECERT_KEY=minioadmin
REGION=Canada
S3_URL=http://127.0.0.1
S3_ADMIN_ENRTYPOINT=http://127.0.0.1:19001
S3_PORT=19000
CEPH_REST_API_URL=<CEPH_REST_API_URL>
CEPH_REST_API_PORT=5000
USER_DEFAULT_CAPACITY_KB=-1

```

composer install

配置Apache vhots

```
<VirtualHost *:80>
    DocumentRoot "/Users/bob/workspace/bobanworkspace/s3-portal-api/public"
    ServerName local.s3portal.com
    ErrorLog "/private/var/log/apache2/local.s3portal-error_log"
    CustomLog "/private/var/log/apache2/local.s3portal-access_log" common
    <Directory /Users/bob/workspace/bobanworkspace/s3-portal-api/public>
	Options Indexes FollowSymLinks
        AllowOverride All
        Order allow,deny
        Allow from all
        Require all granted
    </Directory>
</VirtualHost>
```

配置 hosts

127.0.0.1  local.s3portal.com

创建数据库 s3portal

执行 php artisan migrate  创建数据库

然后启动前端项目

npm start

靠，最终还是运行不起来，估计是本地PHP版本高了，他这个好像是php5.几