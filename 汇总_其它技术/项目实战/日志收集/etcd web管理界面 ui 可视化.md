docker pull evildecay/etcdkeeper



使用

仅仅查看key: 通过设置参数--keys-only=true

查看拥有某个前缀的key 通过设置参数--prefix

查看所有的key

etcdctl --endpoints="https://10.39.47.35:2379"  --prefix --keys-only=true get /

查看拥有某个前缀的keys

etcdctl --endpoints="https://10.39.47.35:2379"  --prefix --keys-only=true get /registry

查看某个具体的key的值

etcdctl --endpoints="https://10.39.47.35:2379" --prefix --keys-only=false get /registry/pods/monitoring/node-exporter-bkdwx
