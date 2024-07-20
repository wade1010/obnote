进入prometheus根目录

```javascript
vi prometheus.yml
```



scrape_configs:

  # The job name is added as a label `job=<job_name>` to any timeseries scraped from this config.

  - job_name: "prometheus"



    # metrics_path defaults to '/metrics'

    # scheme defaults to 'http'.



    static_configs:

      - targets: ["localhost:9090"]

  - job_name: minio-job

    metrics_path: /minio/v2/metrics/cluster

    scheme: http

    static_configs:

    - targets: ['localhost:9000']

  - job_name: minio-node

    metrics_path: /minio/v2/metrics/node

    scheme: http

    static_configs:

    - targets: ['localhost:9000']



nohup ./prometheus --config.file=./prometheus.yml &



http://192.168.1.231:9090/targets



看到 up就表示可以了



![](https://gitee.com/hxc8/images6/raw/master/img/202407190005686.jpg)









export MINIO_PROMETHEUS_AUTH_TYPE="public"

export MINIO_PROMETHEUS_URL="http://127.0.0.1:9090"   

启动minio

/opt/oeos/minio server http://127.0.0.1/opt/oeos/data/disk\{1...4\} --console-address :9000 --address :9001



注意OEOS-HD是下面

```javascript
export OEOS_PROMETHEUS_AUTH_TYPE="public"
export OEOS_PROMETHEUS_URL="http://127.0.0.1:9090"
nohup /opt/oeos/oeos-hd server http://127.0.0.1/opt/oeos/oeosdata/disk\{1...4\} --console-address :19001 --address :19000 --secret xp02vDIsypsQyPOad8y2FUaHlA8mu734pgaqJAAaIqo= &
```







然后访问http://192.168.1.231:19001/tools/dashboard



就能看到

![](https://gitee.com/hxc8/images6/raw/master/img/202407190005978.jpg)

