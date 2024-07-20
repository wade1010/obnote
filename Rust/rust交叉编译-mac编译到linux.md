rust交叉编译-mac编译到linux

## 关于交叉编译

一般编程阶段用的是Windows或者Mac系统，部署平台是Linux，这种情况下就需要使用Cross-Compiler交叉编译，意思是可以在当前平台Host下编译出目标平台target的可执行文件，

尤其是做ARM平台开发的同学对这个更为熟悉。

Rust交叉编译在Github上有一个文档Rust核心员工Jorge Aparicio提供的一份文档[https://github.com/japaric/rust-cross](https://github.com/japaric/rust-cross)，推荐大家仔细的读一读。

如果要求比较简单，都是X86_64架构，从Mac上编译出x86_64-unknown-linux-musl就好。

mac上执行

```
rustup target add x86_64-unknown-linux-musl
```

安装musl-cross

```
brew install filosottile/musl-cross/musl-cross
```

## 配置和打包编译

配置config

vi ~/.cargo/config（没有新建即可，另外可在项目根目录下创建.cargo/config文件，只对当前项目生效）

添加内容如下：

```
[target.x86_64-unknown-linux-musl]
linker = "x86_64-linux-musl-gcc"
```

cargo new cross-compiling

cd cross-compiling

```
cargo build --release --target x86_64-unknown-linux-musl
```

程序简答，确实就是能直接交叉编译了。

但是有的项目用到了openssl，就有可能编译报错

error: failed to run custom build command for openssl-sys v0.9.76

```
error: failed to run custom build command for `openssl-sys v0.9.76`

Caused by:
  process didn't exit successfully: `/Users/xxxxx/target/release/build/openssl-sys-35f194a97bc2188b/build-script-main` (exit status: 101)
  --- stdout
  cargo:rustc-cfg=const_fn
  cargo:rustc-cfg=openssl
  cargo:rerun-if-env-changed=X86_64_UNKNOWN_LINUX_MUSL_OPENSSL_LIB_DIR
  X86_64_UNKNOWN_LINUX_MUSL_OPENSSL_LIB_DIR unset
  cargo:rerun-if-env-changed=OPENSSL_LIB_DIR
  OPENSSL_LIB_DIR unset
  cargo:rerun-if-env-changed=X86_64_UNKNOWN_LINUX_MUSL_OPENSSL_INCLUDE_DIR
  X86_64_UNKNOWN_LINUX_MUSL_OPENSSL_INCLUDE_DIR unset
  cargo:rerun-if-env-changed=OPENSSL_INCLUDE_DIR
  OPENSSL_INCLUDE_DIR unset
  cargo:rerun-if-env-changed=X86_64_UNKNOWN_LINUX_MUSL_OPENSSL_DIR
  X86_64_UNKNOWN_LINUX_MUSL_OPENSSL_DIR unset
  cargo:rerun-if-env-changed=OPENSSL_DIR
  OPENSSL_DIR unset
  cargo:rerun-if-env-changed=OPENSSL_NO_PKG_CONFIG
  cargo:rerun-if-env-changed=PKG_CONFIG_ALLOW_CROSS_x86_64-unknown-linux-musl
  cargo:rerun-if-env-changed=PKG_CONFIG_ALLOW_CROSS_x86_64_unknown_linux_musl
  cargo:rerun-if-env-changed=TARGET_PKG_CONFIG_ALLOW_CROSS
  cargo:rerun-if-env-changed=PKG_CONFIG_ALLOW_CROSS
  cargo:rerun-if-env-changed=PKG_CONFIG_x86_64-unknown-linux-musl
  cargo:rerun-if-env-changed=PKG_CONFIG_x86_64_unknown_linux_musl
  cargo:rerun-if-env-changed=TARGET_PKG_CONFIG
  cargo:rerun-if-env-changed=PKG_CONFIG
  cargo:rerun-if-env-changed=PKG_CONFIG_SYSROOT_DIR_x86_64-unknown-linux-musl
  cargo:rerun-if-env-changed=PKG_CONFIG_SYSROOT_DIR_x86_64_unknown_linux_musl
  cargo:rerun-if-env-changed=TARGET_PKG_CONFIG_SYSROOT_DIR
  cargo:rerun-if-env-changed=PKG_CONFIG_SYSROOT_DIR
  run pkg_config fail: "pkg-config has not been configured to support cross-compilation.\n\nInstall a sysroot for the target platform and configure it via\nPKG_CONFIG_SYSROOT_DIR and PKG_CONFIG_PATH, or install a\ncross-compiling wrapper for pkg-config and set it via\nPKG_CONFIG environment variable."

  --- stderr
  thread 'main' panicked at '

  Could not find directory of OpenSSL installation, and this `-sys` crate cannot
  proceed without this knowledge. If OpenSSL is installed and this crate had
  trouble finding it,  you can set the `OPENSSL_DIR` environment variable for the
  compilation process.

  Make sure you also have the development packages of openssl installed.
  For example, `libssl-dev` on Ubuntu or `openssl-devel` on Fedora.

  If you're in a situation where you think the directory *should* be found
  automatically, please open a bug at https://github.com/sfackler/rust-openssl
  and include information about your system as well as this message.

  $HOST = x86_64-apple-darwin
  $TARGET = x86_64-unknown-linux-musl
  openssl-sys = 0.9.76

  ', /Users/xxxx/.cargo/registry/src/mirrors.ustc.edu.cn-61ef6e0cd06fb9b8/openssl-sys-0.9.76/build/find_normal.rs:191:5
  note: run with `RUST_BACKTRACE=1` environment variable to display a backtrace
warning: build failed, waiting for other jobs to finish...
exit 101
```

解决

```
brew install openssl
brew install pkg-config
brew install perl
我也不知道是不是其中一个就行，我装了，后面也懒得卸载重新验证了
```

cargo.toml的[dependencies]添加如下

```
openssl-sys = "0.9"
openssl = { version = "0.10.33", features = ["vendored"] }
```

再次执行cargo build --release --target x86_64-unknown-linux-musl

发现还是报failed to run custom build command for openssl-sys v0.9.76这个错

需要添加一些内容

CROSS_COMPILE=x86_64-linux-musl- cargo build --release --target x86_64-unknown-linux-musl

这样就OK 了

参考

[https://www.andrew-thorburn.com/cross-compiling-a-simple-rust-web-app/](https://www.andrew-thorburn.com/cross-compiling-a-simple-rust-web-app/)

[http://t.zoukankan.com/007sx-p-15191400.html](http://t.zoukankan.com/007sx-p-15191400.html)

[https://stackoverflow.com/questions/69412947/error-failed-to-run-custom-build-command-for-openssl-sys-v0-9-67](https://stackoverflow.com/questions/69412947/error-failed-to-run-custom-build-command-for-openssl-sys-v0-9-67)