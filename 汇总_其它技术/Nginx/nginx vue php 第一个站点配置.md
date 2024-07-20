单独使用nginx
```
server {
        listen       8080;
        server_name  nlocal.ijisq.com;
        root /Users/xhcheng/ghworkspace/ijisq-front/dist;
        location / {
            try_files $uri $uri/ /index.html;
        }
        location /api {
            rewrite ^/api/(.*)$ /$1 break;
            proxy_pass http://127.0.0.1:8899;
        }
        location /pay {
            rewrite ^/pay/(.*)$ /paysdk/$1 break;
            #rewrite ^/pay/(.*)$ /paysdk/$1 permanent;
            proxy_pass http://127.0.0.1:8899;
        }
}
server {
        listen 8899;
        server_name 127.0.0.1;
        root /Users/xhcheng/bobworkspace/ijisq-api/public;
        location / {
            rewrite ^/(.*)$ /index.php/$1 last;
            break;
        }
        location /paysdk/ {
            alias /Users/xhcheng/bobworkspace/ijisq-api/thirdparty/paysdk/;
            index index.php;
        }
        location ~ ^/paysdk/.+\.php$ {
            root /Users/xhcheng/bobworkspace/ijisq-api/thirdparty/;
            rewrite /paysdk/(.*\.php?) /$1 break;
            include fastcgi.conf;
            fastcgi_pass 127.0.0.1:9000;
            fastcgi_index index.php;
            fastcgi_param SCRIPT_FILENAME /Users/xhcheng/bobworkspace/ijisq-api/thirdparty/paysdk$fastcgi_script_name;
        }
        location ~ .+\.php($|/) {
            set $script $uri;
            set $path_info "/";
            if ($uri ~ "^(.+\.php)(/.+)") {
               set $script $1;
               set $path_info $2;
            }

            fastcgi_pass 127.0.0.1:9000;
            fastcgi_index index.php?IF_REWRITE=1;
            include fastcgi.conf;
        }
}

```

使用nginx+apache
```
server {
        listen       8080;
        server_name  nlocal.ijisq.com;
        root /Users/xhcheng/ghworkspace/ijisq-front/dist;
        location / {
            try_files $uri $uri/ /index.html;
        }
        location /api {
            rewrite ^/api/(.*)$ /$1 break;
            proxy_pass http://local.ijisq-api.com;
        }
        location /pay {
            rewrite ^/pay/(.*)$ /paysdk/$1 permanent;
            proxy_pass http://local.ijisq-api.com;
        }
}
```