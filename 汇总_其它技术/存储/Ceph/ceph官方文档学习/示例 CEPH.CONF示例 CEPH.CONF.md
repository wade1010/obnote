```
[global]
fsid = {cluster-id}
mon_initial_members = {hostname}[, {hostname}]
mon_host = {ip-address}[, {ip-address}]

#All clusters have a front-side public network.
#If you have two network interfaces, you can configure a private / cluster 
#network for RADOS object replication, heartbeats, backfill,
#recovery, etc.
public_network = {network}[, {network}]
#cluster_network = {network}[, {network}] 

#Clusters require authentication by default.
auth_cluster_required = cephx
auth_service_required = cephx
auth_client_required = cephx

#Choose reasonable numbers for journals, number of replicas
#and placement groups.
osd_journal_size = {n}
osd_pool_default_size = {n}  # Write an object n times.
osd_pool_default_min_size = {n} # Allow writing n copy in a degraded state.
osd_pool_default_pg_num = {n}
osd_pool_default_pgp_num = {n}

#Choose a reasonable crush leaf type.
#0 for a 1-node cluster.
#1 for a multi node cluster in a single rack
#2 for a multi node, multi chassis cluster with multiple hosts in a chassis
#3 for a multi node cluster with hosts across racks, etc.
osd_crush_chooseleaf_type = {n}
```

#Choose a reasonable crush leaf type.

#0 for a 1-node cluster.

#1 for a multi node cluster in a single rack

#2 for a multi node, multi chassis cluster with multiple hosts in a chassis

#3 for a multi node cluster with hosts across racks, etc.

osd_crush_chooseleaf_type = {n}

[https://docs.ceph.com/en/latest/rados/configuration/msgr2/](https://docs.ceph.com/en/latest/rados/configuration/msgr2/)

```
# ceph config assimilate-conf < /etc/ceph/ceph.conf
# ceph config generate-minimal-config > /etc/ceph/ceph.conf.new
# cat /etc/ceph/ceph.conf.new
# minimal ceph.conf for 0e5a806b-0ce5-4bc6-b949-aa6f68f5c2a3
[global]
        fsid = 0e5a806b-0ce5-4bc6-b949-aa6f68f5c2a3
        mon_host = [v2:10.0.0.1:3300/0,v1:10.0.0.1:6789/0]
# mv /etc/ceph/ceph.conf.new /etc/ceph/ceph.conf
```

```
[global]

	# By default, Ceph makes 3 replicas of RADOS objects. If you want to maintain four
	# copies of an object the default value--a primary copy and three replica
	# copies--reset the default values as shown in 'osd_pool_default_size'.
	# If you want to allow Ceph to accept an I/O operation to a degraded PG,
	# set 'osd_pool_default_min_size' to a number less than the
	# 'osd_pool_default_size' value.

	osd_pool_default_size = 3  # Write an object 3 times.
	osd_pool_default_min_size = 2 # Accept an I/O operation to a PG that has two copies of an object.

	# Ensure you have a realistic number of placement groups. We recommend
	# approximately 100 per OSD. E.g., total number of OSDs multiplied by 100
	# divided by the number of replicas (i.e., osd pool default size). So for
	# 10 OSDs and osd pool default size = 4, we'd recommend approximately
	# (100 * 10) / 4 = 250.
        # always use the nearest power of 2

	osd_pool_default_pg_num = 256
	osd_pool_default_pgp_num = 256
```