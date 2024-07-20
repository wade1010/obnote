AH00558: httpd: Could not reliably determine the server's fully qualified domain name, using 127.0.0.1. Set the 'ServerName' directive globally to suppress this message





Make sure you're editing the right httpd.conf file, then the error about unreliable server's domain name should be gone (this is the most common mistake).



To locate your httpd.conf Apache configuration file, run:



apachectl -t -D DUMP_INCLUDES           （查看httpd.conf的具体目录，mac上重装Apache，不知道为什么用的不是2.4下面的httpd.conf）

Then edit the file and uncomment or change ServerName line into:



ServerName localhost

Then restart your apache by: sudo apachectl restart