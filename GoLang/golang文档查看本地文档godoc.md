只是查看某个方法



go doc 包名



如 go doc fmt







```javascript
godoc --help                                    15:12.22 三 12 23 2020 >>> 
usage: godoc -http=localhost:6060
  -analysis string
    	comma-separated list of analyses to perform when in GOPATH mode (supported: type, pointer). See https://golang.org/lib/godoc/analysis/help.html
  -goroot string
    	Go root directory (default "/usr/local/Cellar/go/1.15.2/libexec")
  -http string
    	HTTP service address (default "localhost:6060")
  -index
    	enable search index
  -index_files string
    	glob pattern specifying index files; if not empty, the index is read from these files in sorted order
  -index_interval duration
    	interval of indexing; 0 for default (5m), negative to only index once at startup
  -index_throttle float
    	index throttle value; 0.0 = no time allocated, 1.0 = full throttle (default 0.75)
  -links
    	link identifiers to their declarations (default true)
  -maxresults int
    	maximum number of full text search results shown (default 10000)
  -notes string
    	regular expression matching note markers to show (default "BUG")
  -play
    	enable playground
  -templates string
    	load templates/JS/CSS from disk in this directory
  -timestamps
    	show timestamps with directory listings
  -url string
    	print HTML for named URL
  -v	verbose mode
  -write_index
    	write index to a file; the file name must be specified with -index_files
  -zip string
    	zip file providing the file system to serve; disabled if empty
```





直接运行godoc 



然后再浏览器输入 localhost:6060



就可以看到文档了









也可以指定端口



godoc -http=:9001 