[www] brew install php71                                                                                               

==> Installing dependencies for php@7.1: glib, gmp, icu4c, libpq, libzip, tidy-html5, libtiff and webp

==> Installing php@7.1 dependency: glib

==> Downloading https://homebrew.bintray.com/bottles/glib-2.60.3.high_sierra.bottle.tar.gz

==> Downloading from https://akamai.bintray.com/ac/ac219eb8b2b0b0e61535f24b5c2e01542946bb92bdf2a1c212eb4a0a2758a9aa?__gd

######################################################################## 100.0%

==> Pouring glib-2.60.3.high_sierra.bottle.tar.gz

🍺  /usr/local/Cellar/glib/2.60.3: 429 files, 15.3MB

==> Installing php@7.1 dependency: gmp

==> Downloading https://homebrew.bintray.com/bottles/gmp-6.1.2_2.high_sierra.bottle.1.tar.gz

==> Downloading from https://akamai.bintray.com/a5/a536c51149806b73b2e1178be94300832b6b151455006bc7f2a32b9dc493c7a3?__gd



curl: (52) Empty reply from server

Error: Failed to download resource "gmp"

Download failed: https://homebrew.bintray.com/bottles/gmp-6.1.2_2.high_sierra.bottle.1.tar.gz

Warning: Bottle installation failed: building from source.

==> Downloading https://gmplib.org/download/gmp/gmp-6.1.2.tar.xz

######################################################################## 100.0%

==> ./configure --prefix=/usr/local/Cellar/gmp/6.1.2_2 --enable-cxx --with-pic --build=core2-apple-darwin17

==> make

==> make check

==> make install

🍺  /usr/local/Cellar/gmp/6.1.2_2: 18 files, 3.1MB, built in 9 minutes 31 seconds

==> Installing php@7.1 dependency: icu4c

==> Downloading https://homebrew.bintray.com/bottles/icu4c-64.2.high_sierra.bottle.tar.gz

==> Downloading from https://akamai.bintray.com/02/02afdd6a472c31351e46a3b2a38c1c77354f0cc9199c3dbd7e06bc171b3638a2?__gd

######################################################################## 100.0%

==> Pouring icu4c-64.2.high_sierra.bottle.tar.gz

==> Caveats

icu4c is keg-only, which means it was not symlinked into /usr/local,

because macOS provides libicucore.dylib (but nothing else).



If you need to have icu4c first in your PATH run:

  echo 'export PATH="/usr/local/opt/icu4c/bin:$PATH"' >> ~/.zshrc

  echo 'export PATH="/usr/local/opt/icu4c/sbin:$PATH"' >> ~/.zshrc



For compilers to find icu4c you may need to set:

  export LDFLAGS="-L/usr/local/opt/icu4c/lib"

  export CPPFLAGS="-I/usr/local/opt/icu4c/include"



For pkg-config to find icu4c you may need to set:

  export PKG_CONFIG_PATH="/usr/local/opt/icu4c/lib/pkgconfig"



==> Summary

🍺  /usr/local/Cellar/icu4c/64.2: 257 files, 69.5MB

==> Installing php@7.1 dependency: libpq

==> Downloading https://homebrew.bintray.com/bottles/libpq-11.3.high_sierra.bottle.tar.gz

==> Downloading from https://akamai.bintray.com/ba/ba645036b965e3e777d3c2c3b73078e67e40d4a215db26caff3e27536c7381a3?__gd

######################################################################## 100.0%

==> Pouring libpq-11.3.high_sierra.bottle.tar.gz

==> Caveats

libpq is keg-only, which means it was not symlinked into /usr/local,

because conflicts with postgres formula.



If you need to have libpq first in your PATH run:

  echo 'export PATH="/usr/local/opt/libpq/bin:$PATH"' >> ~/.zshrc



For compilers to find libpq you may need to set:

  export LDFLAGS="-L/usr/local/opt/libpq/lib"

  export CPPFLAGS="-I/usr/local/opt/libpq/include"



For pkg-config to find libpq you may need to set:

  export PKG_CONFIG_PATH="/usr/local/opt/libpq/lib/pkgconfig"



==> Summary

🍺  /usr/local/Cellar/libpq/11.3: 2,205 files, 22.4MB

==> Installing php@7.1 dependency: libzip

==> Downloading https://homebrew.bintray.com/bottles/libzip-1.5.2.high_sierra.bottle.tar.gz

######################################################################## 100.0%

==> Pouring libzip-1.5.2.high_sierra.bottle.tar.gz

🍺  /usr/local/Cellar/libzip/1.5.2: 134 files, 579.6KB

==> Installing php@7.1 dependency: tidy-html5

==> Downloading https://homebrew.bintray.com/bottles/tidy-html5-5.6.0.high_sierra.bottle.tar.gz

==> Downloading from https://akamai.bintray.com/af/af9633f1578980fe3d4351c3d71b4b83cc79f814d87310e4b7d05830c53c9621?__gd

######################################################################## 100.0%

==> Pouring tidy-html5-5.6.0.high_sierra.bottle.tar.gz

🍺  /usr/local/Cellar/tidy-html5/5.6.0: 14 files, 2.6MB

==> Installing php@7.1 dependency: libtiff

==> Downloading https://homebrew.bintray.com/bottles/libtiff-4.0.10_1.high_sierra.bottle.tar.gz

==> Downloading from https://akamai.bintray.com/f0/f05323c49236328f4a63e0acb9ff340baf37e589cf5699f334d1e98928f87fd4?__gd

######################################################################## 100.0%

==> Pouring libtiff-4.0.10_1.high_sierra.bottle.tar.gz

🍺  /usr/local/Cellar/libtiff/4.0.10_1: 246 files, 3.5MB

==> Installing php@7.1 dependency: webp

==> Downloading https://homebrew.bintray.com/bottles/webp-1.0.2.high_sierra.bottle.1.tar.gz

==> Downloading from https://akamai.bintray.com/b3/b324a2a6eeb5c7c916a903f7249b6233334f99e7394b9927784319f086e21f8e?__gd

######################################################################## 100.0%

==> Pouring webp-1.0.2.high_sierra.bottle.1.tar.gz

🍺  /usr/local/Cellar/webp/1.0.2: 39 files, 2.1MB

==> Installing php@7.1

==> Downloading https://homebrew.bintray.com/bottles/php@7.1-7.1.29.high_sierra.bottle.1.tar.gz

==> Downloading from https://akamai.bintray.com/5e/5e26cd9483db4c357a7a9ab52e1f65654a37abbb0f0868c18c488ad5f56d4910?__gd

######################################################################## 100.0%

==> Pouring php@7.1-7.1.29.high_sierra.bottle.1.tar.gz

==> /usr/local/Cellar/php@7.1/7.1.29/bin/pear config-set php_ini /usr/local/etc/php/7.1/php.ini system

Last 15 lines from /Users/xhcheng/Library/Logs/Homebrew/php@7.1/post_install.01.pear:

2019-06-01 13:12:37 +0800



/usr/local/Cellar/php@7.1/7.1.29/bin/pear

config-set

php_ini

/usr/local/etc/php/7.1/php.ini

system



dyld: Library not loaded: /usr/local/opt/jpeg/lib/libjpeg.9.dylib

  Referenced from: /usr/local/Cellar/php@7.1/7.1.29/bin/php

  Reason: image not found

Warning: The post-install step did not complete successfully

You can try again using `brew postinstall php@7.1`

==> Caveats

To enable PHP in Apache add the following to httpd.conf and restart Apache:

    LoadModule php7_module /usr/local/opt/php@7.1/lib/httpd/modules/libphp7.so



    <FilesMatch \.php$>

        SetHandler application/x-httpd-php

    </FilesMatch>



Finally, check DirectoryIndex includes index.php

    DirectoryIndex index.php index.html



The php.ini and php-fpm.ini file can be found in:

    /usr/local/etc/php/7.1/



php@7.1 is keg-only, which means it was not symlinked into /usr/local,

because this is an alternate version of another formula.



If you need to have php@7.1 first in your PATH run:

  echo 'export PATH="/usr/local/opt/php@7.1/bin:$PATH"' >> ~/.zshrc

  echo 'export PATH="/usr/local/opt/php@7.1/sbin:$PATH"' >> ~/.zshrc



For compilers to find php@7.1 you may need to set:

  export LDFLAGS="-L/usr/local/opt/php@7.1/lib"

  export CPPFLAGS="-I/usr/local/opt/php@7.1/include"





To have launchd start php@7.1 now and restart at login:

  brew services start php@7.1

Or, if you don't want/need a background service you can just run:

  php-fpm

==> Summary

🍺  /usr/local/Cellar/php@7.1/7.1.29: 514 files, 63.2MB

==> Caveats

==> icu4c

icu4c is keg-only, which means it was not symlinked into /usr/local,

because macOS provides libicucore.dylib (but nothing else).



If you need to have icu4c first in your PATH run:

  echo 'export PATH="/usr/local/opt/icu4c/bin:$PATH"' >> ~/.zshrc

  echo 'export PATH="/usr/local/opt/icu4c/sbin:$PATH"' >> ~/.zshrc



For compilers to find icu4c you may need to set:

  export LDFLAGS="-L/usr/local/opt/icu4c/lib"

  export CPPFLAGS="-I/usr/local/opt/icu4c/include"



For pkg-config to find icu4c you may need to set:

  export PKG_CONFIG_PATH="/usr/local/opt/icu4c/lib/pkgconfig"



==> libpq

libpq is keg-only, which means it was not symlinked into /usr/local,

because conflicts with postgres formula.



If you need to have libpq first in your PATH run:

  echo 'export PATH="/usr/local/opt/libpq/bin:$PATH"' >> ~/.zshrc



For compilers to find libpq you may need to set:

  export LDFLAGS="-L/usr/local/opt/libpq/lib"

  export CPPFLAGS="-I/usr/local/opt/libpq/include"



For pkg-config to find libpq you may need to set:

  export PKG_CONFIG_PATH="/usr/local/opt/libpq/lib/pkgconfig"



==> php@7.1

To enable PHP in Apache add the following to httpd.conf and restart Apache:

    LoadModule php7_module /usr/local/opt/php@7.1/lib/httpd/modules/libphp7.so



    <FilesMatch \.php$>

        SetHandler application/x-httpd-php

    </FilesMatch>



Finally, check DirectoryIndex includes index.php

    DirectoryIndex index.php index.html



The php.ini and php-fpm.ini file can be found in:

    /usr/local/etc/php/7.1/



php@7.1 is keg-only, which means it was not symlinked into /usr/local,

because this is an alternate version of another formula.



If you need to have php@7.1 first in your PATH run:

  echo 'export PATH="/usr/local/opt/php@7.1/bin:$PATH"' >> ~/.zshrc

  echo 'export PATH="/usr/local/opt/php@7.1/sbin:$PATH"' >> ~/.zshrc



For compilers to find php@7.1 you may need to set:

  export LDFLAGS="-L/usr/local/opt/php@7.1/lib"

  export CPPFLAGS="-I/usr/local/opt/php@7.1/include"





To have launchd start php@7.1 now and restart at login:

  brew services start php@7.1

Or, if you don't want/need a background service you can just run:

  php-fpm

