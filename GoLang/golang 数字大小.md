	MaxInt8   = 1<<7 - 1

	MinInt8   = -1 << 7

	MaxInt16  = 1<<15 - 1

	MinInt16  = -1 << 15

	MaxInt32  = 1<<31 - 1

	MinInt32  = -1 << 31

	MaxInt64  = 1<<63 - 1

	MinInt64  = -1 << 63

	MaxUint8  = 1<<8 - 1

	MaxUint16 = 1<<16 - 1

	MaxUint32 = 1<<32 - 1

	MaxUint64 = 1<<64 - 1





  int类型的大小为 8 字节

  int8类型大小为 1 字节

  int16类型大小为 2 字节

  int32类型大小为 4 字节

  int64类型大小为 8 字节







通过上述可以看到，int和int64运行的结果一样。int64是有符号 64 位整型，而在64位操作系统中int的大小也是64位（8字节）。



直接看一下官方文档：

int is a signed integer type that is at least 32 bits in size. It is a distinct type, however, and not an alias for, say, int32.



翻译一下，就是说这个整形最少占32位，int和int32是两码事。



uint is a variable sized type, on your 64 bit computer uint is 64 bits wide.

uint和uint8等都属于无符号int类型

uint类型长度取决于 CPU，如果是32位CPU就是4个字节，如果是64位就是8个字节。





