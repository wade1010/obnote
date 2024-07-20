



dataUsageCrawlInterval = 12 * time.Hour



等待io比较低的时候

```javascript
if err := fs.waitForLowActiveIO(); err != nil {
   return filepath.SkipDir
}

func (fs *FSObjects) waitForLowActiveIO() error {
   t := time.NewTicker(lowActiveIOWaitTick)
   defer t.Stop()
   for {
      if atomic.LoadInt64(&fs.activeIOCount) >= fs.maxActiveIOCount {
         select {
         case <-GlobalServiceDoneCh:
            return errors.New("forced exit")
         case <-t.C:
            continue
         }
      }
      break
   }

   return nil

}
```

