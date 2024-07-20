创建个对象，然后做删除bucket





下面代码 短时间内调用多次 tikv 查询同一个key

```javascript
if err := migrateV27ToV28MinioSys(objAPI); err != nil {
   return err
}
if err := migrateV28ToV29MinioSys(objAPI); err != nil {
   return err
}
if err := migrateV29ToV30MinioSys(objAPI); err != nil {
   return err
}
if err := migrateV30ToV31MinioSys(objAPI); err != nil {
   return err
}
if err := migrateV31ToV32MinioSys(objAPI); err != nil {
   return err
}
```

