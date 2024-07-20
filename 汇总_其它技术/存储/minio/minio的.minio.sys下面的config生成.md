

```javascript
if osIsNotExist(err) || osIsPermission(err) {
   // Initialize the server config, if no config exists.
   return newSrvConfig(objAPI)
}


func newSrvConfig(objAPI ObjectLayer) error {
   // Initialize server config.
   srvCfg := newServerConfig()

   // hold the mutex lock before a new config is assigned.
   globalServerConfigMu.Lock()
   globalServerConfig = srvCfg
   globalServerConfigMu.Unlock()

   // Save config into file.
   return saveServerConfig(GlobalContext, objAPI, globalServerConfig)
}



```

 

