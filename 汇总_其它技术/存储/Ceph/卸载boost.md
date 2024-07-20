```
root@user-PC:~/boost1.78# dpkg -l | grep libboost
ii  libboost-atomic-dev:sw_64              1.67.0.1                                 sw_64        atomic data types, operations, and memory ordering constraints (default version)
ii  libboost-atomic1.67-dev:sw_64          1.67.0-13+deb10u1+0eagle1                sw_64        atomic data types, operations, and memory ordering constraints
ii  libboost-atomic1.67.0:sw_64            1.67.0-13+deb10u1+0eagle1                sw_64        atomic data types, operations, and memory ordering constraints
ii  libboost-chrono-dev:sw_64              1.67.0.1                                 sw_64        C++ representation of time duration, time point, and clocks (default version)
ii  libboost-chrono1.67-dev:sw_64          1.67.0-13+deb10u1+0eagle1                sw_64        C++ representation of time duration, time point, and clocks
。。。。。。。。。。。。。。。。。。。。。。。
```

卸载

apt remove libboost*