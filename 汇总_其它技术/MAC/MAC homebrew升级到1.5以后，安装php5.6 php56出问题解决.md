homebrew/php

Homebrew 1.5



https://stackoverflow.com/questions/49649693/install-php-extension-for-php-5-6-on-osx-with-deprecated-homebrew-php



First install exolnet/homebrew-deprecated

$ brew tap exolnet/homebrew-deprecated

After it install deprecated package

$ brew install php@5.6





最后一步时间还挺久的







To enable PHP in Apache add the following to httpd.conf and restart Apache:

    LoadModule php5_module /usr/local/opt/php@5.6/lib/httpd/modules/libphp5.so



    <FilesMatch \.php$>

        SetHandler application/x-httpd-php

    </FilesMatch>



Finally, check DirectoryIndex includes index.php

    DirectoryIndex index.php index.html



The php.ini and php-fpm.ini file can be found in:

    /usr/local/etc/php/5.6/



php@5.6 is keg-only, which means it was not symlinked into /usr/local,

because this is an alternate version of another formula.



If you need to have php@5.6 first in your PATH run:

  echo 'export PATH="/usr/local/opt/php@5.6/bin:$PATH"' >> ~/.zshrc

  echo 'export PATH="/usr/local/opt/php@5.6/sbin:$PATH"' >> ~/.zshrc



For compilers to find php@5.6 you may need to set:

  export LDFLAGS="-L/usr/local/opt/php@5.6/lib"

  export CPPFLAGS="-I/usr/local/opt/php@5.6/include"





To have launchd start exolnet/deprecated/php@5.6 now and restart at login:

  brew services start exolnet/deprecated/php@5.6

Or, if you don't want/need a background service you can just run:

  php-fpm

==> Summary

🍺  /usr/local/Cellar/php@5.6/5.6.40: 493 files, 60.6MB, built in 5 minutes 46 seconds