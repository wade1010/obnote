```javascript
#!/bin/bash
export MINIO_ACCESS_KEY=minioadmin
export MINIO_SECRET_KEY=minioadmin
/root/minio server \
--address ":9029" \
http://172.16.1.231/opt/drbd0/minio/data1 http://172.16.1.231/opt/drbd0/minio/data2 \
http://172.16.1.232/opt/drbd0/minio/data1 http://172.16.1.232/opt/drbd0/minio/data2 \
http://172.16.1.233/opt/drbd0/minio/data1 http://172.16.1.233/opt/drbd0/minio/data2
```







