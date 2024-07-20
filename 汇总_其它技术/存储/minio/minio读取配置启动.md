

![](https://gitee.com/hxc8/images6/raw/master/img/202407190007523.jpg)





```javascript
cfg, err := goconfig.LoadConfigFile("node.ini")
if err == nil {
   nodeId, err := cfg.GetValue("default", "node_id")
   if err != nil {
      panic("file node.ini,config err!")
   }
   tikvHost, err := cfg.GetValue("default", "tikv")
   if err != nil {
      panic("file node.ini,config err!")
   }
   globalTikvAddr = tikvHost
   // initTikvStore must before other initial
   err = initTikvStore()
   if err != nil {
      panic(fmt.Sprintf("failed to init tikv: %v", err))
   }
   //bob先模拟设置了minio启动配置,后期会有其他程序设置，这边只负责读
   var launchConf = LaunchConf{}
   launchConf.ID = "S001"
   launchConf.Name = "MINIO"
   launchConf.Description = "MINIO 启动配置"
   launchConf.Version = 1
   launchConf.LaunchType = 0
   launchConf.LaunchParams = []string{".fsv2data", "--console-address", ":9001"}
   confData, _ := json.Marshal(launchConf)
   ctx := context.Background()
   confKey := []byte("Node/" + nodeId + "/App/SysService/SS002/Conf")
   err = saveKeyTikv(ctx, confKey, confData)
   if err != nil {
      panic(fmt.Sprintf("failed to save key to tikv: %v", err))
   }
   data, err := readKeyTikv(ctx, confKey)
   if err != nil {
      panic(fmt.Sprintf("failed to read key from tikv: %v", err))
   }
   var readLaunchConf = LaunchConf{}
   err = json.Unmarshal(data, &readLaunchConf)
   if err != nil {
      panic(fmt.Sprintf("failed to unmarshal data from tikv: %v", err))
   }
   args = append(args, readLaunchConf.LaunchParams...)
}
```

