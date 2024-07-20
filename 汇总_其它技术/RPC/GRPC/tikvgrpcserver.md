

![](D:/download/youdaonote-pull-master/data/Technology/RPC/GRPC/images/13AB041D83264515BE28661157AD220Fimage.png)



.proto文件内容如下

```javascript
syntax = "proto3";
option go_package = "tikv-rpc/proto";
package  tikvrpc;


service TikvTxnService {
    rpc Begin(NullMsg) returns(TxnIdResult) {}
    rpc Commit(TxnIdResult) returns(ResponseResult) {}
    rpc Rollback(TxnIdResult) returns(ResponseResult) {}
    rpc Exist(GetRequest) returns(BoolResult) {}
    rpc Get(GetRequest) returns(KVResult) {}
    rpc BatchGet(BatchGetRequest) returns(BatchResult) {}
    rpc Put(PutRequest) returns(ResponseResult) {}
    rpc BatchPut(BatchPutRequest) returns(ResponseResult) {}
    rpc Scan(ScanRequest) returns(BatchResult) {}
    rpc Delete(DeleteRequest) returns(ResponseResult) {}
    rpc BatchDelete(BatchDeleteRequest) returns(ResponseResult) {}
}
message NullMsg {

}
message BoolResult {
    bool exist = 1;
}
message TxnIdResult {
    uint64 TxnId = 1;
}
message ResponseResult {
    int32 status = 1;
    string error = 2;
}

message GetRequest {
    uint64 startTS = 1;
    bytes key = 2;
}

message BatchGetRequest {
    uint64 startTS = 1;
    repeated bytes keys = 2;
}
message KVResult {
    bytes key = 1;
    bytes value = 2;
}
message PutRequest {
    uint64 startTS = 1;
    KVResult kv = 2;
}

message BatchPutRequest {
    uint64 startTS = 1;
    repeated KVResult kvs = 2;
}
message ScanRequest {
    uint64 startTS = 1;
    bytes startKey = 2;
    bytes endKey = 3;
    uint32 limit = 4;
    bool keyOnly = 5;
}
message BatchResult {
    repeated KVResult kvs = 1;
}

message DeleteRequest {
    bytes key = 1;
}
message BatchDeleteRequest {
    repeated bytes keys = 1;
}
```



cd /proto

protoc --go_out=. --go_opt=paths=source_relative --go-grpc_out=. --go-grpc_opt=paths=source_relative  tikv_txn.proto



