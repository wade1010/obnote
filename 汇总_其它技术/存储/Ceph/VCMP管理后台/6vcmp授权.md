### 方式1：直接插入数据库

这个授权是1年，到期时间为2024-6-13

```
mysql -u root -p
```

密码是

```
vClusters@2019
```

```
delete from vcfs.vclusters_license where id =1;
```

```
INSERT INTO vcfs.vclusters_license
(id, activate_date, lic_expired_date, trial_deadline, activate_status, adv_features, sn)
VALUES(1, NULL, 'o/jT1FUVfcTE8lSgs7zX6a2KI0zXN/MXfSJaltYS5lbexaLuSpSnS3fmTfZCumywOyNMMuXCf0R1
vDyNC6LPdJdz9R6R2ZcZOTnBnW6lyLOnXS7VaTALarfzDkX8jsqR5xdJf2ctnVT5HyTHE32HQeEM
P9e+W86XJ5JhXJaS2qXnmOIEK/Min/OLzJKShXjmCAdQMSyHC/f+QC8eGnVWg3CSX8wGARUR9oQ2
Nzv1VZvPFd0+a+fEZ+uIuFC32eO/3ynJoc2hCSFYQFsP14krKMXAKouQfjcK6c3FihMoBElTXh62
Lc8gpg8K+JcQ0el/KrYRZUuNVYurEeuAEjtR5g==
', '', 'PJVk4PCOUz6zRasEJ6L3ysiwhpm0ANws0aSZJTqf4inbLlt43ZEZCO8Nttn24EBXDw/2NasWmXDw
2QOo7dKBRaUB+bRYZ4Inze9fwAhNA5Oxp5YypQ1D4LbQ2c/FhYIFxTw8eu+rtAFo6dRGJ2hrLCVU
b6mrtqlXKfG5f36WRmyIpOYUtBDIA5MJzSfGbz4XdsCCB2kVOE97EqrlM88KkACoP64IdkW+U+R5
7rFDG4VmdaBeBFQzdgirEap6RPtxlGO1X0LqHhZX9EX8tnFlVzIwuNE4wVdZl+TODul+mt9e9/Ld
dZMApu+Hj5OKW9Vx2BZBlZOPAPyoqSVlGHoxCQ==
', '', 'ZTllYmVlMTgzYTIxMjQzZWU5Y2M4ODc1MzRlYTIzOTggOTY4OGZkZTQ1NDE5NGMzNTg4NGNkNTk1
YjYxMGFiODk=
');

```

### 方式2：生成授权码

vcmp/web/vclusters/controllers/license.py

在showLic这个方法里，打开注释代码，或者添加

```
        deadline = datetime.datetime.now() + datetime.timedelta(days=365)
        # generate a license SN
        import uuid
        sn = codecs.encode(bytes(vclmp_crypto.PUBLIC_KEY_MD5 + ' ' + uuid.uuid4().hex, 'utf-8'), 'base64')
        cipher = vclmp_crypto.RSACipher()
        trial_deadline = cipher.encrypt_rsacipher(bytes(str(deadline).split(' ')[0], 'utf-8'))
        # activate_status = cipher.encrypt_rsacipher(b'0') # 试用
        activate_status = cipher.encrypt_rsacipher(b'1') # 非试用
        License.objects.create(lic_expired_date=trial_deadline, sn=sn, activate_status=activate_status)
```

然后刷新页面就行了，然后回退上面的操作，重启web server（页面调用的接口是/api/license/show/）