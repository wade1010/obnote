

mysql> show create table index_user;

+------------+---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+

| Table      | Create Table                                                                                                                                                                                                                                |

+------------+---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+

| index_user | CREATE TABLE `index_user` (

  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,

  `uuid` varchar(32) NOT NULL DEFAULT '',

  `name` varchar(25) NOT NULL DEFAULT '',

  PRIMARY KEY (`id`)

) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 |

+------------+---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+



mysql> select * from index_user;

+----+----------------------------------+------+

| id | uuid                             | name |

+----+----------------------------------+------+

|  1 | 8f677691f9dda9bb4c72bc8297e17fa6 | cxh  |

+----+----------------------------------+------+

1 row in set (0.00 sec)



mysql> insert index_user set uuid=md5('fadfa'),name='xxx';

Query OK, 1 row affected (0.01 sec)



mysql> select * from index_user;

+----+----------------------------------+------+

| id | uuid                             | name |

+----+----------------------------------+------+

|  1 | 8f677691f9dda9bb4c72bc8297e17fa6 | cxh  |

|  2 | 8d1e252b161e85c737a844cd5cca25d5 | xxx  |

+----+----------------------------------+------+