[https://github.com/minio/warp/releases](https://github.com/minio/warp/releases)

下载最新的版本，这里是

```
./warp -v
warp version 0.5.5 - 1baadbc
```

这里4个节点都要转这个warp

一、启动client

./warp client      默认是7761端口

二、server

/warp get --duration=1m --obj.size 4KiB --concurrent=128 --warp-client=172.16.1.23{1...4} --host=172.16.1.23{1...4}:9000 --access-key=oeosadmin --secret-key=oeosadmin --noclear

执行输出内容如下：

```
warp: Running benchmark on all clients...  
warp: Benchmark data written to "warp-remote-2022-05-31[160351]-yxom.csv.zst"                                           

----------------------------------------
Operation: PUT
Skipping PUT too few samples. Longer benchmark run required for reliable results.

----------------------------------------
Operation: GET
* Average: 163.73 MiB/s, 41914.41 obj/s

Throughput by host:
 * http://172.16.1.231:9000: Avg: 44.85 MiB/s, 11480.91 obj/s
 * http://172.16.1.232:9000: Avg: 35.97 MiB/s, 9207.93 obj/s
 * http://172.16.1.233:9000: Avg: 43.95 MiB/s, 11252.24 obj/s
 * http://172.16.1.234:9000: Avg: 39.13 MiB/s, 10017.67 obj/s

Throughput, split into 59 x 1s:
 * Fastest: 193.8MiB/s, 49623.89 obj/s
 * 50% Median: 167.2MiB/s, 42811.12 obj/s
 * Slowest: 98.2MiB/s, 25140.59 obj/s
warp: Cleanup done.                                 
```

```
NAME:
  warp get - benchmark get objects

USAGE:
  warp get [FLAGS]
  -> see https://github.com/minio/warp#get

FLAGS:
  --no-color             disable color theme
  --debug                enable debug output
  --insecure             disable TLS certificate verification
  --autocompletion       install auto-completion for your shell
  --host value           host. Multiple hosts can be specified as a comma separated list. (default: "127.0.0.1:9000") [$WARP_HOST]
  --access-key value     Specify access key [$WARP_ACCESS_KEY]
  --secret-key value     Specify secret key [$WARP_SECRET_KEY]
  --tls                  Use TLS (HTTPS) for transport [$WARP_TLS]
  --region value         Specify a custom region [$WARP_REGION]
  --encrypt              encrypt/decrypt objects (using server-side encryption with random keys)
  --bucket value         Bucket to use for benchmark data. ALL DATA WILL BE DELETED IN BUCKET! (default: "warp-benchmark-bucket")
  --host-select value    Host selection algorithm. Can be "weighed" or "roundrobin" (default: "weighed")
  --concurrent value     Run this many concurrent operations (default: 20)
  --noprefix             Do not use separate prefix for each thread
  --prefix value         Use a custom prefix for each thread
  --disable-multipart    disable multipart uploads
  --md5                  Add MD5 sum to uploads
  --storage-class value  Specify custom storage class, for instance 'STANDARD' or 'REDUCED_REDUNDANCY'.
  --objects value        Number of objects to upload. (default: 2500)
  --obj.size value       Size of each generated object. Can be a number or 10KiB/MiB/GiB. All sizes are base 2 binary. (default: "10MiB")
  --range                Do ranged get operations. Will request with random offset and length.
  --versions value       Number of versions to upload. If more than 1, versioned listing will be benchmarked (default: 1)
  --obj.generator value  Use specific data generator (default: "random")
  --obj.randsize         Randomize size of objects so they will be up to the specified size
  --benchdata value      Output benchmark+profile data to this file. By default unique filename is generated.
  --serverprof value     Run MinIO server profiling during benchmark; possible values are 'cpu', 'mem', 'block', 'mutex' and 'trace'.
  --duration value       Duration to run the benchmark. Use 's' and 'm' to specify seconds and minutes. (default: 5m0s)
  --autoterm             Auto terminate when benchmark is considered stable.
  --autoterm.dur value   Minimum duration where output must have been stable to allow automatic termination. (default: 10s)
  --autoterm.pct value   The percentage the last 6/25 time blocks must be within current speed to auto terminate. (default: 7.5)
  --noclear              Do not clear bucket before or after running benchmarks. Use when running multiple clients.
  --syncstart value      Specify a benchmark start time. Time format is 'hh:mm' where hours are specified in 24h format, server TZ.
  --warp-client value    Connect to warp clients and run benchmarks there.
  --analyze.dur value    Split analysis into durations of this length. Can be '1s', '5s', '1m', etc.
  --analyze.out value    Output aggregated data as to file
  --analyze.op value     Only output for this op. Can be GET/PUT/DELETE, etc.
  --analyze.host value   Only output for this host.
  --analyze.skip value   Additional duration to skip when analyzing data. (default: 0s)
  --analyze.v            Display additional analysis data.
  --serve value          When running benchmarks open a webserver to fetch results remotely, eg: localhost:7762
  --help, -h             show help
```

测试一个节点关机，这里才用卸载万兆网卡的方式模拟单机

ip link set ens102f0 down

/warp put --warp-client=172.16.1.23{1...4} --host=172.16.1.23{1...4}:9000 --access-key=oeosadmin --secret-key=oeosadmin --bucket=testbobsmallfiles --concurrent=850 --noprefix --obj.size=2MiB --analyze.out=2MiB-850-bobThreads.csv --noclear --duration=30s

/warp put --warp-client=172.16.1.23{1...4} --host=172.16.1.23{1...4}:29000 --access-key=oeosadmin --secret-key=oeosadmin --bucket=testbobsmallfiles --concurrent=850 --noprefix --obj.size=2MiB --analyze.out=2MiB-850-bobThreads.csv --noclear --duration=30s

/warp put --warp-client=172.16.1.23{1...4} --host=172.16.1.23{1...4}:29000 --access-key=oeosadmin --secret-key=oeosadmin --bucket=testbobsmallfiles --concurrent=850 --noprefix --obj.size=2MiB --analyze.out=2MiB-850-bobThreads.csv --noclear --duration=30s

/oeos  server --node-conf /etc/oeos/system.config --address :29000 --object-log OL --sys-event GE --log-path /var/log/oeos/default/S001/s3-access.log --log-max-size 50 --log-max-num 1000 --tenant-id 01 --service-mode s3-online