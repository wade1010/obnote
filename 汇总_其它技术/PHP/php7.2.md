To enable PHP in Apache add the following to httpd.conf and restart Apache:

    LoadModule php7_module /usr/local/opt/php@7.2/lib/httpd/modules/libphp7.so



    <FilesMatch \.php$>

        SetHandler application/x-httpd-php

    </FilesMatch>



Finally, check DirectoryIndex includes index.php

    DirectoryIndex index.php index.html



The php.ini and php-fpm.ini file can be found in:

    /usr/local/etc/php/7.2/



php@7.2 is keg-only, which means it was not symlinked into /usr/local,

because this is an alternate version of another formula.



If you need to have php@7.2 first in your PATH run:

  echo 'export PATH="/usr/local/opt/php@7.2/bin:$PATH"' >> ~/.zshrc

  echo 'export PATH="/usr/local/opt/php@7.2/sbin:$PATH"' >> ~/.zshrc



For compilers to find php@7.2 you may need to set:

  export LDFLAGS="-L/usr/local/opt/php@7.2/lib"

  export CPPFLAGS="-I/usr/local/opt/php@7.2/include"





To have launchd start php@7.2 now and restart at login:

  brew services start php@7.2

Or, if you don't want/need a background service you can just run:

  php-fpm

