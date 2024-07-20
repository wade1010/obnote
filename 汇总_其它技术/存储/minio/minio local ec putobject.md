1、确定分片大小，根据在线盘数初始化对应数量的writers

```javascript
shardFileSize := erasure.ShardFileSize(data.Size())
writers := make([]io.Writer, len(onlineDisks))
var inlineBuffers []*bytes.Buffer
if shardFileSize >= 0 {
   if !opts.Versioned && shardFileSize < smallFileThreshold {
      inlineBuffers = make([]*bytes.Buffer, len(onlineDisks))
   } else if shardFileSize < smallFileThreshold/8 {
      inlineBuffers = make([]*bytes.Buffer, len(onlineDisks))
   }
} else {
   // If compressed, use actual size to determine.
   if sz := erasure.ShardFileSize(data.ActualSize()); sz > 0 {
      if !opts.Versioned && sz < smallFileThreshold {
         inlineBuffers = make([]*bytes.Buffer, len(onlineDisks))
      } else if sz < smallFileThreshold/8 {
         inlineBuffers = make([]*bytes.Buffer, len(onlineDisks))
      }
   }
}
for i, disk := range onlineDisks {
   if disk == nil {
      continue
   }

   if len(inlineBuffers) > 0 {
      sz := shardFileSize
      if sz < 0 {
         sz = data.ActualSize()
      }
      inlineBuffers[i] = bytes.NewBuffer(make([]byte, 0, sz))
      writers[i] = newStreamingBitrotWriterBuffer(inlineBuffers[i], DefaultBitrotAlgorithm, erasure.ShardSize())
      continue
   }
   writers[i] = newBitrotWriter(disk, minioMetaTmpBucket, tempErasureObj, shardFileSize, DefaultBitrotAlgorithm, erasure.ShardSize())
}
```

如果没开启多版本且大小小于128KB 或 大小小于128/8KB 就是将数据存在xl.meta文件中。

其他情况这xl.meta只存元数据。





writers[i] = newBitrotWriter(disk, minioMetaTmpBucket, tempErasureObj, shardFileSize, DefaultBitrotAlgorithm, erasure.ShardSize())里面是调用newStreamingBitrotWriter() 内容如下

```javascript
// Returns streaming bitrot writer implementation.
func newStreamingBitrotWriter(disk StorageAPI, volume, filePath string, length int64, algo BitrotAlgorithm, shardSize int64) io.Writer {
   r, w := io.Pipe()
   h := algo.New()

   bw := &streamingBitrotWriter{iow: w, closeWithErr: w.CloseWithError, h: h, shardSize: shardSize, canClose: &sync.WaitGroup{}}
   bw.canClose.Add(1)
   go func() {
      totalFileSize := int64(-1) // For compressed objects length will be unknown (represented by length=-1)
      if length != -1 {
         bitrotSumsTotalSize := ceilFrac(length, shardSize) * int64(h.Size()) // Size used for storing bitrot checksums.
         totalFileSize = bitrotSumsTotalSize + length
      }
      r.CloseWithError(disk.CreateFile(context.TODO(), volume, filePath, totalFileSize, r))
      bw.canClose.Done()
   }()
   return bw
}
```

有个IO Pipe,然后开启个协程写文件





2、ec encode



n, erasureErr := erasure.Encode(ctx, data, writers, buffer, writeQuorum)，erasure.Encode对应方法内容如下



2.1、读出文件所有数据到buf中

```javascript
// Encode reads from the reader, erasure-encodes the data and writes to the writers.
func (e *Erasure) Encode(ctx context.Context, src io.Reader, writers []io.Writer, buf []byte, quorum int) (total int64, err error) {
   writer := &parallelWriter{
      writers:     writers,
      writeQuorum: quorum,
      errs:        make([]error, len(writers)),
   }

   for {
      var blocks [][]byte
      n, err := io.ReadFull(src, buf)
      if err != nil && err != io.EOF && err != io.ErrUnexpectedEOF {
         logger.LogIf(ctx, err)
         return 0, err
      }
      eof := err == io.EOF || err == io.ErrUnexpectedEOF
      if n == 0 && total != 0 {
         // Reached EOF, nothing more to be done.
         break
      }
      // We take care of the situation where if n == 0 and total == 0 by creating empty data and parity files.
      blocks, err = e.EncodeData(ctx, buf[:n])
      if err != nil {
         logger.LogIf(ctx, err)
         return 0, err
      }

      if err = writer.Write(ctx, blocks); err != nil {
         logger.LogIf(ctx, err)
         return 0, err
      }
      total += int64(n)
      if eof {
         break
      }
   }
   return total, nil
}
```



2.2、上面blocks, err = e.EncodeData(ctx, buf[:n])内容如下

```javascript
// EncodeData encodes the given data and returns the erasure-coded data.
// It returns an error if the erasure coding failed.
func (e *Erasure) EncodeData(ctx context.Context, data []byte) ([][]byte, error) {
   if len(data) == 0 {
      return make([][]byte, e.dataBlocks+e.parityBlocks), nil
   }
   encoded, err := e.encoder().Split(data)
   if err != nil {
      logger.LogIf(ctx, err)
      return nil, err
   }
   if err = e.encoder().Encode(encoded); err != nil {
      logger.LogIf(ctx, err)
      return nil, err
   }
   return encoded, nil
}
```



2.3、上面encoded, err := e.encoder().Split(data)内容如下

```javascript
// Split a data slice into the number of shards given to the encoder,
// and create empty parity shards if necessary.
//
// The data will be split into equally sized shards.
// If the data size isn't divisible by the number of shards,
// the last shard will contain extra zeros.
//
// There must be at least 1 byte otherwise ErrShortData will be
// returned.
//
// The data will not be copied, except for the last shard, so you
// should not modify the data of the input slice afterwards.
func (r *reedSolomon) Split(data []byte) ([][]byte, error) {
   if len(data) == 0 {
      return nil, ErrShortData
   }
   // Calculate number of bytes per data shard.
   perShard := (len(data) + r.DataShards - 1) / r.DataShards

   if cap(data) > len(data) {
      data = data[:cap(data)]
   }

   // Only allocate memory if necessary
   var padding []byte
   if len(data) < (r.Shards * perShard) {
      // calculate maximum number of full shards in `data` slice
      fullShards := len(data) / perShard
      padding = make([]byte, r.Shards*perShard-perShard*fullShards)
      copy(padding, data[perShard*fullShards:])
      data = data[0 : perShard*fullShards]
   }

   // Split into equal-length shards.
   dst := make([][]byte, r.Shards)
   i := 0
   for ; i < len(dst) && len(data) >= perShard; i++ {
      dst[i] = data[:perShard:perShard]
      data = data[perShard:]
   }

   for j := 0; i+j < len(dst); j++ {
      dst[i+j] = padding[:perShard:perShard]
      padding = padding[perShard:]
   }

   return dst, nil
}
```



2.4、2.2中 if err = e.encoder().Encode(encoded); err != nil {内容如下

```javascript
// Encodes parity for a set of data shards.
// An array 'shards' containing data shards followed by parity shards.
// The number of shards must match the number given to New.
// Each shard is a byte array, and they must all be the same size.
// The parity shards will always be overwritten and the data shards
// will remain the same.
func (r *reedSolomon) Encode(shards [][]byte) error {
   if len(shards) != r.Shards {
      return ErrTooFewShards
   }

   err := checkShards(shards, false)
   if err != nil {
      return err
   }

   // Get the slice of output buffers.
   output := shards[r.DataShards:]

   // Do the coding.
   r.codeSomeShards(r.parity, shards[0:r.DataShards], output, r.ParityShards, len(shards[0]))
   return nil
}
```



2.5、上面r.codeSomeShards(r.parity, shards[0:r.DataShards], output, r.ParityShards, len(shards[0]))内容如下

```javascript
// Multiplies a subset of rows from a coding matrix by a full set of
// input shards to produce some output shards.
// 'matrixRows' is The rows from the matrix to use.
// 'inputs' An array of byte arrays, each of which is one input shard.
// The number of inputs used is determined by the length of each matrix row.
// outputs Byte arrays where the computed shards are stored.
// The number of outputs computed, and the
// number of matrix rows used, is determined by
// outputCount, which is the number of outputs to compute.
func (r *reedSolomon) codeSomeShards(matrixRows, inputs, outputs [][]byte, outputCount, byteCount int) {
   if len(outputs) == 0 {
      return
   }
   switch {
   case r.o.useAVX512 && r.o.maxGoroutines > 1 && byteCount > r.o.minSplitSize && len(inputs) >= 4 && len(outputs) >= 2:
      r.codeSomeShardsAvx512P(matrixRows, inputs, outputs, outputCount, byteCount)
      return
   case r.o.useAVX512 && len(inputs) >= 4 && len(outputs) >= 2:
      r.codeSomeShardsAvx512(matrixRows, inputs, outputs, outputCount, byteCount)
      return
   case r.o.maxGoroutines > 1 && byteCount > r.o.minSplitSize:
      r.codeSomeShardsP(matrixRows, inputs, outputs, outputCount, byteCount)
      return
   }

   // Process using no goroutines
   start, end := 0, r.o.perRound
   if end > len(inputs[0]) {
      end = len(inputs[0])
   }
   if avx2CodeGen && r.o.useAVX2 && byteCount >= 32 && len(inputs)+len(outputs) >= 4 && len(inputs) <= maxAvx2Inputs && len(outputs) <= maxAvx2Outputs {
      m := genAvx2Matrix(matrixRows, len(inputs), len(outputs), r.mPool.Get().([]byte))
      start += galMulSlicesAvx2(m, inputs, outputs, 0, byteCount)
      r.mPool.Put(m)
      end = len(inputs[0])
   }

   for start < len(inputs[0]) {
      for c := 0; c < r.DataShards; c++ {
         in := inputs[c][start:end]
         for iRow := 0; iRow < outputCount; iRow++ {
            if c == 0 {
               galMulSlice(matrixRows[iRow][c], in, outputs[iRow][start:end], &r.o)
            } else {
               galMulSliceXor(matrixRows[iRow][c], in, outputs[iRow][start:end], &r.o)
            }
         }
      }
      start = end
      end += r.o.perRound
      if end > len(inputs[0]) {
         end = len(inputs[0])
      }
   }
}
```



2.1中if err = writer.Write(ctx, blocks); err != nil { 内容如下

```javascript
// Write writes data to writers in parallel.
func (p *parallelWriter) Write(ctx context.Context, blocks [][]byte) error {
   var wg sync.WaitGroup

   for i := range p.writers {
      if p.writers[i] == nil {
         p.errs[i] = errDiskNotFound
         continue
      }
      if p.errs[i] != nil {
         continue
      }
      wg.Add(1)
      go func(i int) {
         defer wg.Done()
         var n int
         n, p.errs[i] = p.writers[i].Write(blocks[i])
         if p.errs[i] == nil {
            if n != len(blocks[i]) {
               p.errs[i] = io.ErrShortWrite
            }
         }
      }(i)
   }
   wg.Wait()

   // If nilCount >= p.writeQuorum, we return nil. This is because HealFile() uses
   // CreateFile with p.writeQuorum=1 to accommodate healing of single disk.
   // i.e if we do no return here in such a case, reduceWriteQuorumErrs() would
   // return a quorum error to HealFile().
   nilCount := countErrs(p.errs, nil)
   if nilCount >= p.writeQuorum {
      return nil
   }
   return reduceWriteQuorumErrs(ctx, p.errs, objectOpIgnoredErrs, p.writeQuorum)
}
```

