A CA file has been bootstrapped using certificates from the system

keychain. To add additional certificates, place .pem files in

  /usr/local/etc/openssl@1.1/certs



and run

  /usr/local/opt/openssl@1.1/bin/c_rehash



openssl@1.1 is keg-only, which means it was not symlinked into /usr/local,

because openssl/libressl is provided by macOS so don't link an incompatible version.



If you need to have openssl@1.1 first in your PATH run:

  echo 'export PATH="/usr/local/opt/openssl@1.1/bin:$PATH"' >> ~/.zshrc



For compilers to find openssl@1.1 you may need to set:

  export LDFLAGS="-L/usr/local/opt/openssl@1.1/lib"

  export CPPFLAGS="-I/usr/local/opt/openssl@1.1/include"



For pkg-config to find openssl@1.1 you may need to set:

  export PKG_CONFIG_PATH="/usr/local/opt/openssl@1.1/lib/pkgconfig"



==> nginx

Docroot is: /usr/local/var/www



The default port has been set in /usr/local/etc/nginx/nginx.conf to 8080 so that

nginx can run without sudo.



nginx will load all files in /usr/local/etc/nginx/servers/.



To have launchd start nginx now and restart at login:

  brew services start nginx

Or, if you don't want/need a background service you can just run:

  nginx