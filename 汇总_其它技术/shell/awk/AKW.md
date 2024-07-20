![](https://gitee.com/hxc8/images7/raw/master/img/202407190748771.jpg)







![](https://gitee.com/hxc8/images7/raw/master/img/202407190748973.jpg)







awk 'NR==FNR{a[$1]=$2;}NR!=FNR{if($2 in a) print "update file set object_id="$1" where name=\x27"a[$2]"\x27;"}' 1.txt 2.txt

或者

awk 'NR==FNR{a[$1]=$2;}NR!=FNR{if($2 in a) print "update file set object_id="$1" where name=""'\''"a[$2]"'\''"";"}' 1.txt 2.txt





awk输出单引号  awk 'BEGIN{print "\x27"}'  或者 "'\''"



update file set object_id=12 where name='9673.jpg';

update file set object_id=13 where name='9680.jpg';

update file set object_id=14 where name='9724.jpg';

update file set object_id=15 where name='9731.jpg';

update file set object_id=16 where name='9735.jpg';

update file set object_id=17 where name='9755.jpg';

update file set object_id=18 where name='9764.jpg';

update file set object_id=19 where name='9769.jpg';

update file set object_id=20 where name='9775.jpg';

update file set object_id=21 where name='9794.jpg';

update file set object_id=22 where name='9795.jpg';

update file set object_id=25 where name='9816.jpg';

update file set object_id=26 where name='9817.jpg';

update file set object_id=27 where name='9827.jpg';

update file set object_id=28 where name='9828.jpg';

update file set object_id=29 where name='9836.jpg';

update file set object_id=30 where name='9841.jpg';

update file set object_id=31 where name='9865.jpg';

update file set object_id=32 where name='9866.jpg';

update file set object_id=33 where name='9870.jpg';

update file set object_id=34 where name='9873.jpg';

update file set object_id=35 where name='9882.jpg';

update file set object_id=36 where name='9897.jpg';

update file set object_id=37 where name='9981.png';

update file set object_id=38 where name='9986.jpg';

update file set object_id=39 where name='9991.jpg';

update file set object_id=40 where name='9992.jpg';

update file set object_id=41 where name='9995.jpg';

update file set object_id=42 where name='10002.jpg';

update file set object_id=43 where name='10009.jpg';

update file set object_id=44 where name='10016.jpg';

update file set object_id=45 where name='10032.jpg';

update file set object_id=46 where name='10043.jpg';

update file set object_id=47 where name='10046.jpg';

update file set object_id=48 where name='10051.jpg';

update file set object_id=49 where name='10062.jpg';

update file set object_id=50 where name='10074.jpg';

update file set object_id=51 where name='10086.jpg';

update file set object_id=52 where name='10095.jpg';

update file set object_id=53 where name='10102.jpg';

update file set object_id=54 where name='10117.jpg';

update file set object_id=55 where name='10140.jpg';

update file set object_id=56 where name='10193.jpg';

update file set object_id=57 where name='10197.jpg';

update file set object_id=58 where name='10203.jpg';

update file set object_id=59 where name='10204.jpg';

update file set object_id=60 where name='10206.jpg';

update file set object_id=1 where name='10294.jpg';

update file set object_id=2 where name='10298.jpg';









