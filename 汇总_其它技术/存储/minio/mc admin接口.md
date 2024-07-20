设置header的User-Agent

/Users/bob/workspace/goworkspace/pkg/mod/github.com/minio/minio@v0.0.0-20210407151610-0a1db6d41be0/pkg/madmin/api.go

477行  adm.setUserAgent(req)





设置header的X-Amz-Content-Sha256

/Users/bob/workspace/goworkspace/pkg/mod/github.com/minio/minio@v0.0.0-20210407151610-0a1db6d41be0/pkg/madmin/api.go

485行  req.Header.Set("X-Amz-Content-Sha256", hex.EncodeToString(sum[:]))







SignV4

/Users/bob/workspace/goworkspace/pkg/mod/github.com/minio/minio@v0.0.0-20210407151610-0a1db6d41be0/pkg/madmin/api.go

488行





设置header的X-Amz-Content-Sha256

/Users/bob/workspace/goworkspace/pkg/mod/github.com/minio/minio-go/v7@v7.0.11-0.20210319012211-5a0d16291a2e/pkg/signer/request-signature-v4.go

267行  req.Header.Set("X-Amz-Date", t.Format(iso8601DateFormat))



规范headers  

/Users/bob/workspace/goworkspace/pkg/mod/github.com/minio/minio-go/v7@v7.0.11-0.20210319012211-5a0d16291a2e/pkg/signer/request-signature-v4.go

178行 getCanonicalHeaders(req, ignoredHeaders)





# 用户相关



密码需要单独拿出来

/Users/bob/workspace/golangworkspace/minio-mc/cmd/admin-user-add.go

mc admin user add myminio user4 a1234567

PUT /minio/admin/v3/add-user?accessKey=user5

Proto: HTTP/1.1

Host: 192.168.199.249:9000

Authorization: AWS4-HMAC-SHA256 Credential=adminadminadmin/20210414//s3/aws4_request, SignedHeaders=host;x-amz-content-sha256;x-amz-date, Signature=4842ed4492e8e066afc71578e845b98c4b5410bcdc008d8612ec325fb46da778

Content-Length: 100

User-Agent: MinIO (darwin; amd64) madmin-go/0.0.1 ___go_build_github_com_minio_mc/DEVELOPMENT.GOGET

X-Amz-Content-Sha256: 38cce0a0ed0687d001e9e31d200b0018b5ba7e131294b8eccb11c49b5dca999a

X-Amz-Date: 20210414T112605Z



---

mc admin user list myminio



GET /minio/admin/v3/list-users

Proto: HTTP/1.1

Host: 192.168.199.249:9000

Authorization: AWS4-HMAC-SHA256 Credential=adminadminadmin/20210414//s3/aws4_request, SignedHeaders=host;x-amz-content-sha256;x-amz-date, Signature=7e1194f09c5d8aa7b00e24a05f11f85265500effc9a29ea1ddee82345b099f85

Content-Length: 0

User-Agent: MinIO (darwin; amd64) madmin-go/0.0.1 ___go_build_github_com_minio_mc/DEVELOPMENT.GOGET

X-Amz-Content-Sha256: e3b0c44298fc1c149afbf4c8996fb92427ae41e4649b934ca495991b7852b855

X-Amz-Date: 20210414T104701Z

---



mc admin disable myminio user1



PUT /minio/admin/v3/set-user-status?accessKey=user1&status=disabled

Proto: HTTP/1.1

Host: 192.168.199.249:9000

Authorization: AWS4-HMAC-SHA256 Credential=adminadminadmin/20210415//s3/aws4_request, SignedHeaders=host;x-amz-content-sha256;x-amz-date, Signature=b9b13aa9f6c600342d50c1059ea5c9bf27782ccd5cff5f5414f19f552b4afe17

Transfer-Encoding: chunked

User-Agent: MinIO (darwin; amd64) madmin-go/0.0.1 mc/RELEASE.2021-02-19T05-34-40Z

X-Amz-Content-Sha256: e3b0c44298fc1c149afbf4c8996fb92427ae41e4649b934ca495991b7852b855

X-Amz-Date: 20210415T022706Z

---

mc admin enable myminio user1

PUT /minio/admin/v3/set-user-status?accessKey=user1&status=enabled

Proto: HTTP/1.1

Host: 192.168.199.249:9000

Authorization: AWS4-HMAC-SHA256 Credential=adminadminadmin/20210415//s3/aws4_request, SignedHeaders=host;x-amz-content-sha256;x-amz-date, Signature=b030037ecf0f794fe9b82ab869dec8f0445537a95740da5cdc8357592791ce7b

Transfer-Encoding: chunked

User-Agent: MinIO (darwin; amd64) madmin-go/0.0.1 mc/RELEASE.2021-02-19T05-34-40Z

X-Amz-Content-Sha256: e3b0c44298fc1c149afbf4c8996fb92427ae41e4649b934ca495991b7852b855

X-Amz-Date: 20210415T023006Z



---

mc admin remove myminio user5



DELETE /minio/admin/v3/remove-user?accessKey=user5

Proto: HTTP/1.1

Host: 192.168.199.249:9000

User-Agent: MinIO (darwin; amd64) madmin-go/0.0.1 mc/RELEASE.2021-02-19T05-34-40Z

X-Amz-Content-Sha256: e3b0c44298fc1c149afbf4c8996fb92427ae41e4649b934ca495991b7852b855

X-Amz-Date: 20210415T023205Z

Authorization: AWS4-HMAC-SHA256 Credential=adminadminadmin/20210415//s3/aws4_request, SignedHeaders=host;x-amz-content-sha256;x-amz-date, Signature=5f2a97c32e0fc15c195bffbfff6a8de399e75fec85ee21e33207982d7b1abcb1

Content-Length: 0

---

 mc admin user info myminio user5



GET /minio/admin/v3/user-info?accessKey=user5

Proto: HTTP/1.1

Host: 192.168.199.249:9000

Authorization: AWS4-HMAC-SHA256 Credential=adminadminadmin/20210415//s3/aws4_request, SignedHeaders=host;x-amz-content-sha256;x-amz-date, Signature=c8edc71bf7092d302c31183428ad251e5ffa8fedb3c78678b7d19a887df246a7

Content-Length: 0

User-Agent: MinIO (darwin; amd64) madmin-go/0.0.1 mc/RELEASE.2021-02-19T05-34-40Z

X-Amz-Content-Sha256: e3b0c44298fc1c149afbf4c8996fb92427ae41e4649b934ca495991b7852b855

X-Amz-Date: 20210415T023715Z

---





# 策略相关



mc admin policy list myminio



GET /minio/admin/v3/list-canned-policies

Proto: HTTP/1.1

Host: 192.168.199.249:9000

X-Amz-Date: 20210415T023911Z

Authorization: AWS4-HMAC-SHA256 Credential=adminadminadmin/20210415//s3/aws4_request, SignedHeaders=host;x-amz-content-sha256;x-amz-date, Signature=d9aa3685d36ea4718f7e1011bbc1dabd805d0e4f8d2a995fa1d5f309b01bf212

Content-Length: 0

User-Agent: MinIO (darwin; amd64) madmin-go/0.0.1 mc/RELEASE.2021-02-19T05-34-40Z

X-Amz-Content-Sha256: e3b0c44298fc1c149afbf4c8996fb92427ae41e4649b934ca495991b7852b855

---



添加策略和编辑策略都用这个

mc admin policy add myminio writeonly /tmp/writeonly.json

```javascript
{
 "Version": "2012-10-17",
 "Statement": [
  {
   "Effect": "Allow",
   "Action": [
    "s3:*"
   ],
   "Resource": [
    "arn:aws:s3:::*"
   ]
  }
 ]
}
```



PUT /minio/admin/v3/add-canned-policy?name=testpolicy

Proto: HTTP/1.1

Host: 192.168.199.249:9000

Content-Length: 105

User-Agent: MinIO (darwin; amd64) madmin-go/0.0.1 mc/RELEASE.2021-02-19T05-34-40Z

X-Amz-Content-Sha256: f6b14fc2b73e2bafede45ab60ab5e8308913d4ed970e46b84dad9c8d35afe616

X-Amz-Date: 20210415T024330Z

Authorization: AWS4-HMAC-SHA256 Credential=adminadminadmin/20210415//s3/aws4_request, SignedHeaders=host;x-amz-content-sha256;x-amz-date, Signature=f63425e167ce98883c687eee2241ef87e49e5f48fe82378069173114b1d7f82f

{"Version":"2012-10-17","Statement":[{"Effect":"Allow","Action":["s3:*"],"Resource":["arn:aws:s3:::*"]}]}



```javascript
sum := sha256.Sum256(reqData.content)
req.Header.Set("X-Amz-Content-Sha256", hex.EncodeToString(sum[:]))
req.Body = ioutil.NopCloser(bytes.NewReader(reqData.content))
```



---

mc admin policy remove myminio testpolicy



DELETE /minio/admin/v3/remove-canned-policy?name=testpolicy

Proto: HTTP/1.1

Host: 192.168.199.249:9000

User-Agent: MinIO (darwin; amd64) madmin-go/0.0.1 mc/RELEASE.2021-02-19T05-34-40Z

X-Amz-Content-Sha256: e3b0c44298fc1c149afbf4c8996fb92427ae41e4649b934ca495991b7852b855

X-Amz-Date: 20210415T041211Z

Authorization: AWS4-HMAC-SHA256 Credential=adminadminadmin/20210415//s3/aws4_request, SignedHeaders=host;x-amz-content-sha256;x-amz-date, Signature=6ff08885583ef785c25e9c16dc1655cc793161204ce01abeb0b0612efb5c842f

Content-Length: 0

---





mc admin policy info myminio readwrite



GET /minio/admin/v3/list-canned-policies

Proto: HTTP/1.1

Host: 192.168.199.249:9000

Authorization: AWS4-HMAC-SHA256 Credential=adminadminadmin/20210415//s3/aws4_request, SignedHeaders=host;x-amz-content-sha256;x-amz-date, Signature=6cfc9326e7c524f83ace6f2128e947ed890e368d1700430b90c652427984022a

Content-Length: 0

User-Agent: MinIO (darwin; amd64) madmin-go/0.0.1 mc/RELEASE.2021-02-19T05-34-40Z

X-Amz-Content-Sha256: e3b0c44298fc1c149afbf4c8996fb92427ae41e4649b934ca495991b7852b855

X-Amz-Date: 20210415T041304Z

---



用户

mc admin policy set myminio readwrite user=user2



PUT /minio/admin/v3/set-user-or-group-policy?isGroup=false&policyName=readwrite&userOrGroup=user2

Proto: HTTP/1.1

Host: 192.168.199.249:9000

Transfer-Encoding: chunked

User-Agent: MinIO (darwin; amd64) madmin-go/0.0.1 mc/RELEASE.2021-02-19T05-34-40Z

X-Amz-Content-Sha256: e3b0c44298fc1c149afbf4c8996fb92427ae41e4649b934ca495991b7852b855

X-Amz-Date: 20210415T041545Z

Authorization: AWS4-HMAC-SHA256 Credential=adminadminadmin/20210415//s3/aws4_request, SignedHeaders=host;x-amz-content-sha256;x-amz-date, Signature=59aabc51cb95a7067ae5fcc49b0494379c6ab41fe40abb0f00942c5e04cac6f0



组

mc admin policy set myminio readwrite group=group1



PUT /minio/admin/v3/set-user-or-group-policy?isGroup=true&policyName=readwrite&userOrGroup=group1

Proto: HTTP/1.1

Host: 192.168.199.249:9000

X-Amz-Content-Sha256: e3b0c44298fc1c149afbf4c8996fb92427ae41e4649b934ca495991b7852b855

X-Amz-Date: 20210415T041655Z

Authorization: AWS4-HMAC-SHA256 Credential=adminadminadmin/20210415//s3/aws4_request, SignedHeaders=host;x-amz-content-sha256;x-amz-date, Signature=95342387340777a6c2a2cfeeda5cad3377db215bc4be9b8e71db616d57e1f333

Transfer-Encoding: chunked

User-Agent: MinIO (darwin; amd64) madmin-go/0.0.1 mc/RELEASE.2021-02-19T05-34-40Z



















