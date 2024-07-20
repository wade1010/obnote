需要注意这里使用了nodejsv14.20

3  wget https://nodejs.org/dist/v14.20.0/node-v14.20.0-linux-x64.tar.gz

```
-- Configuring done
-- Generating done
CMake Warning:
  Manually-specified variables were not used by the project:

    BOOST_J


-- Build files have been written to: /home/bob/rpmbuild/SOURCES/ceph-16.2.9/build
+ set +x
done.
[bob@bogon ceph-16.2.9]$ cmake --build ./build/ --target mgr-dashboard-frontend-build
Scanning dependencies of target mgr-dashboard-frontend-deps
dashboard frontend dependencies are being installed
npm WARN using --force I sure hope you know what you are doing.

> fsevents@1.2.13 install /home/bob/rpmbuild/SOURCES/ceph-16.2.9/src/pybind/mgr/dashboard/frontend/node_modules/webpack-dev-server/node_modules/fsevents
> node install.js


Skipping 'fsevents' build as platform linux is not supported

> nice-napi@1.0.2 install /home/bob/rpmbuild/SOURCES/ceph-16.2.9/src/pybind/mgr/dashboard/frontend/node_modules/nice-napi
> node-gyp-build

events.js:174
      throw er; // Unhandled 'error' event
      ^

Error: spawn node-gyp EACCES
    at Process.ChildProcess._handle.onexit (internal/child_process.js:240:19)
    at onErrorNT (internal/child_process.js:415:16)
    at process._tickCallback (internal/process/next_tick.js:63:19)
Emitted 'error' event at:
    at Process.ChildProcess._handle.onexit (internal/child_process.js:246:12)
    at onErrorNT (internal/child_process.js:415:16)
    at process._tickCallback (internal/process/next_tick.js:63:19)

> esbuild@0.13.8 postinstall /home/bob/rpmbuild/SOURCES/ceph-16.2.9/src/pybind/mgr/dashboard/frontend/node_modules/@angular-devkit/build-angular/node_modules/esbuild
> node install.js

(node:1788634) UnhandledPromiseRejectionWarning: Error: Unsupported platform: linux sw64 LE
    at pkgAndSubpathForCurrentPlatform (/home/bob/rpmbuild/SOURCES/ceph-16.2.9/src/pybind/mgr/dashboard/frontend/node_modules/@angular-devkit/build-angular/node_modules/esbuild/install.js:74:11)
    at checkAndPreparePackage (/home/bob/rpmbuild/SOURCES/ceph-16.2.9/src/pybind/mgr/dashboard/frontend/node_modules/@angular-devkit/build-angular/node_modules/esbuild/install.js:210:28)
    at Object.<anonymous> (/home/bob/rpmbuild/SOURCES/ceph-16.2.9/src/pybind/mgr/dashboard/frontend/node_modules/@angular-devkit/build-angular/node_modules/esbuild/install.js:237:1)
    at Module._compile (internal/modules/cjs/loader.js:689:30)
    at Object.Module._extensions..js (internal/modules/cjs/loader.js:700:10)
    at Module.load (internal/modules/cjs/loader.js:599:32)
    at tryModuleLoad (internal/modules/cjs/loader.js:538:12)
    at Function.Module._load (internal/modules/cjs/loader.js:530:3)
    at Function.Module.runMain (internal/modules/cjs/loader.js:742:12)
    at startup (internal/bootstrap/node.js:283:19)
(node:1788634) UnhandledPromiseRejectionWarning: Unhandled promise rejection. This error originated either by throwing inside of an async function without a catch block, or by rejecting a promise which was not handled with .catch(). (rejection id: 2)
(node:1788634) [DEP0018] DeprecationWarning: Unhandled promise rejections are deprecated. In the future, promise rejections that are not handled will terminate the Node.js process with a non-zero exit code.

> core-js@3.16.0 postinstall /home/bob/rpmbuild/SOURCES/ceph-16.2.9/src/pybind/mgr/dashboard/frontend/node_modules/@angular-devkit/build-angular/node_modules/core-js
> node -e "try{require('./postinstall')}catch(e){}"

Thank you for using core-js ( https://github.com/zloirock/core-js ) for polyfilling JavaScript standard library!

The project needs your help! Please consider supporting of core-js:
> https://opencollective.com/core-js
> https://patreon.com/zloirock
> https://paypal.me/zloirock
> bitcoin: bc1qlea7544qtsmj2rayg0lthvza9fau63ux0fstcz

Also, the author of core-js ( https://github.com/zloirock ) is looking for a good job -)


> core-js-pure@3.20.2 postinstall /home/bob/rpmbuild/SOURCES/ceph-16.2.9/src/pybind/mgr/dashboard/frontend/node_modules/core-js-pure
> node -e "try{require('./postinstall')}catch(e){}"


> core-js@3.20.2 postinstall /home/bob/rpmbuild/SOURCES/ceph-16.2.9/src/pybind/mgr/dashboard/frontend/node_modules/core-js
> node -e "try{require('./postinstall')}catch(e){}"


> cypress@9.0.0 postinstall /home/bob/rpmbuild/SOURCES/ceph-16.2.9/src/pybind/mgr/dashboard/frontend/node_modules/cypress
> node index.js --exec install

Note: Overriding Cypress cache directory to: /home/bob/rpmbuild/SOURCES/ceph-16.2.9/build/src/pybind/mgr/dashboard/cypress

      Previous installs of Cypress may not be found.

(node:1788686) ExperimentalWarning: The fs.promises API is experimental
Installing Cypress (version: 9.0.0)

✔  Downloaded Cypress
✔  Unzipped Cypress
✔  Finished Installation /home/bob/rpmbuild/SOURCES/ceph-16.2.9/build/src/pybind/mgr/dashboard/cypress/9.0.0

You can now open Cypress by running: node_modules/.bin/cypress open

https://on.cypress.io/installing-cypress


> @compodoc/compodoc@1.1.15 postinstall /home/bob/rpmbuild/SOURCES/ceph-16.2.9/src/pybind/mgr/dashboard/frontend/node_modules/@compodoc/compodoc
> opencollective-postinstall || exit 0

Thank you for using @compodoc/compodoc!
If you rely on this package, please consider supporting our open collective:
> https://opencollective.com/compodoc/donate


> @angular/cli@12.2.13 postinstall /home/bob/rpmbuild/SOURCES/ceph-16.2.9/src/pybind/mgr/dashboard/frontend/node_modules/@angular/cli
> node ./bin/postinstall/script.js


> ceph-dashboard@0.0.0 postinstall /home/bob/rpmbuild/SOURCES/ceph-16.2.9/src/pybind/mgr/dashboard/frontend
> ngcc --properties es2015 browser module main --async false --first-only --tsconfig 'tsconfig.app.json'

(node:1787621) MaxListenersExceededWarning: Possible EventEmitter memory leak detected. 11 SIGINT listeners added. Use emitter.setMaxListeners() to increase limit
/home/bob/rpmbuild/SOURCES/ceph-16.2.9/src/pybind/mgr/dashboard/frontend/node_modules/yargs/node_modules/yargs-parser/build/index.cjs:1013
        throw Error(`yargs parser supports a minimum Node.js version of ${minNodeVersion}. Read our version support policy: https://github.com/yargs/yargs-parser#supported-nodejs-versions`);
        ^

Error: yargs parser supports a minimum Node.js version of 12. Read our version support policy: https://github.com/yargs/yargs-parser#supported-nodejs-versions
    at Object.<anonymous> (/home/bob/rpmbuild/SOURCES/ceph-16.2.9/src/pybind/mgr/dashboard/frontend/node_modules/yargs/node_modules/yargs-parser/build/index.cjs:1013:15)
    at Module._compile (internal/modules/cjs/loader.js:689:30)
    at Object.Module._extensions..js (internal/modules/cjs/loader.js:700:10)
    at Module.load (internal/modules/cjs/loader.js:599:32)
    at tryModuleLoad (internal/modules/cjs/loader.js:538:12)
    at Function.Module._load (internal/modules/cjs/loader.js:530:3)
    at Module.require (internal/modules/cjs/loader.js:637:17)
    at require (internal/modules/cjs/helpers.js:22:18)
    at Object.<anonymous> (/home/bob/rpmbuild/SOURCES/ceph-16.2.9/src/pybind/mgr/dashboard/frontend/node_modules/yargs/build/index.cjs:1:58090)
    at Module._compile (internal/modules/cjs/loader.js:689:30)
added 2947 packages in 90.042s
Built target mgr-dashboard-frontend-deps
Scanning dependencies of target mgr-dashboard-frontend-build
dashboard frontend is being created

> ceph-dashboard@0.0.0 build:localize /home/bob/rpmbuild/SOURCES/ceph-16.2.9/src/pybind/mgr/dashboard/frontend
> node cd --env --pre && ng build --localize "--prod" "--progress=false"

[cd.js] './angular.json' was copied to './angular.backup.json'
[cd.js] Preparing build of EN and "".
[cd.js] 'src/environments/environment.tpl.ts' was copied to 'src/environments/environment.ts'
[cd.js] 'src/environments/environment.tpl.ts' was copied to 'src/environments/environment.prod.ts'
[cd.js] Writing to ./angular.json
[cd.js] Placeholders were replace in 'src/environments/environment.prod.ts'
[cd.js] Placeholders were replace in 'src/environments/environment.ts'
Node.js version v10.15.2 detected.
The Angular CLI requires a minimum Node.js version of either v12.14 or v14.15.

Please update your Node.js version or visit https://nodejs.org/ for additional instructions.

npm ERR! code ELIFECYCLE
npm ERR! errno 3
npm ERR! ceph-dashboard@0.0.0 build:localize: `node cd --env --pre && ng build --localize "--prod" "--progress=false"`
npm ERR! Exit status 3
npm ERR!
npm ERR! Failed at the ceph-dashboard@0.0.0 build:localize script.
npm ERR! This is probably not a problem with npm. There is likely additional logging output above.

npm ERR! A complete log of this run can be found in:
npm ERR!     /home/bob/.npm/_logs/2023-04-14T02_59_56_329Z-debug.log
gmake[3]: *** [src/pybind/mgr/dashboard/CMakeFiles/mgr-dashboard-frontend-build.dir/build.make:954：../src/pybind/mgr/dashboard/frontend/dist] 错误 3
gmake[2]: *** [CMakeFiles/Makefile2:5372：src/pybind/mgr/dashboard/CMakeFiles/mgr-dashboard-frontend-build.dir/all] 错误 2
gmake[1]: *** [CMakeFiles/Makefile2:5379：src/pybind/mgr/dashboard/CMakeFiles/mgr-dashboard-frontend-build.dir/rule] 错误 2
gmake: *** [Makefile:1703：mgr-dashboard-frontend-build] 错误 2
[bob@bogon ceph-16.2.9]$ npm -v
6.4.1

```

后面编译了申威的node，成功