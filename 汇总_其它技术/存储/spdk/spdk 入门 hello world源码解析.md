hello_world.c  main方法源码如下

```javascript
int main(int argc, char **argv)
{
   int rc;
   struct spdk_env_opts opts;

   /*
    * SPDK relies on an abstraction around the local environment
    * named env that handles memory allocation and PCI device operations.
    * This library must be initialized first.
    *
    */
   spdk_env_opts_init(&opts);
   rc = parse_args(argc, argv, &opts);
   if (rc != 0) {
      return rc;
   }

   opts.name = "hello_world";
   if (spdk_env_init(&opts) < 0) {
      fprintf(stderr, "Unable to initialize SPDK env\n");
      return 1;
   }

   printf("Initializing NVMe Controllers\n");

   if (g_vmd && spdk_vmd_init()) {
      fprintf(stderr, "Failed to initialize VMD."
         " Some NVMe devices can be unavailable.\n");
   }

   /*
    * Start the SPDK NVMe enumeration process.  probe_cb will be called
    *  for each NVMe controller found, giving our application a choice on
    *  whether to attach to each controller.  attach_cb will then be
    *  called for each controller after the SPDK NVMe driver has completed
    *  initializing the controller we chose to attach.
    */
   rc = spdk_nvme_probe(&g_trid, NULL, probe_cb, attach_cb, NULL);
   if (rc != 0) {
      fprintf(stderr, "spdk_nvme_probe() failed\n");
      rc = 1;
      goto exit;
   }

   if (TAILQ_EMPTY(&g_controllers)) {
      fprintf(stderr, "no NVMe controllers found\n");
      rc = 1;
      goto exit;
   }

   printf("Initialization complete.\n");
   hello_world();
   cleanup();
   if (g_vmd) {
      spdk_vmd_fini();
   }

exit:
   cleanup();
   spdk_env_fini();
   return rc;
}
```





main()的处理流程为：



1. 源码317行,上文12行     spdk_env_opts_init(&opts);

1. 源码320行,上文19行     spdk_env_init(&opts);

1. 源码331行,上文38行     rc = spdk_nvme_probe(NULL, NULL, probe_cb, attach_cb, NULL);

1. 源码345行,上文52行     hello_world();

1. 源码346行,上文59行     cleanup();





步骤1-步骤2是spdk运行环境初始化



步骤3是调用函数spdk_nvme_probe()主动发现NVMe SSDs设备



步骤4是调用hello_world()，做简单的读写操作



步骤5调用cleanup()释放内存资源，detach NVMe SSD设备等





接下来分析下关键函数spdk_nvme_probe()



分析之前先搞清楚下面两个问题：

1、每一块NVMe SSD里都有一个控制器(Controller),那么发现的所有NVMe SSD（也就是NVMe Controllers）以什么方式组织在一起？

2、每一块NVMe SSD都可以划分为多个namespace（类似逻辑分区的概念），那么这些namespace以什么方式组织在一起呢？



答案：

链表结构，看下hello_world.c代码

```javascript
struct ctrlr_entry {
   struct spdk_nvme_ctrlr    *ctrlr;
   TAILQ_ENTRY(ctrlr_entry)   link;
   char            name[1024];
};

struct ns_entry {
   struct spdk_nvme_ctrlr *ctrlr;
   struct spdk_nvme_ns    *ns;
   TAILQ_ENTRY(ns_entry)  link;
   struct spdk_nvme_qpair *qpair;
};

static TAILQ_HEAD(, ctrlr_entry) g_controllers = TAILQ_HEAD_INITIALIZER(g_controllers);
static TAILQ_HEAD(, ns_entry) g_namespaces = TAILQ_HEAD_INITIALIZER(g_namespaces);
```



上面的

g_controllers是管理所有的nvme ssd的全局链表头

g_namespaces是管理所有的namespace的全局链表头



所以main函数里面的

```javascript
if (TAILQ_EMPTY(&g_controllers)) {
   fprintf(stderr, "no NVMe controllers found\n");
   rc = 1;
   goto exit;
}
```

就是因为g_controllers为空，就是因为没有找到nvme sdd







接下来看下main函数里面是如何使用spdk_nvme_probe()的

```javascript
rc = spdk_nvme_probe(&g_trid, NULL, probe_cb, attach_cb, NULL);
```



如上 probe_cb 和 attach_cb是两个回调函数（其实还有个remove_cb，只是上面未使用，传了个NULL）

probe_cb：当发现一个nvme设备的时候被调用

attach_cb：当一个nvme设备已经被attach到一个用户态的nvme驱动的时候被调用





probe_cb, attach_cb以及remove_cb的相关源码信息在spdk/include/spdk/nvme.h

```javascript
/**
 * Enumerate the bus indicated by the transport ID and attach the userspace NVMe
 * driver to each device found if desired.
 *
 * This function is not thread safe and should only be called from one thread at
 * a time while no other threads are actively using any NVMe devices.
 *
 * If called from a secondary process, only devices that have been attached to
 * the userspace driver in the primary process will be probed.
 *
 * If called more than once, only devices that are not already attached to the
 * SPDK NVMe driver will be reported.
 *
 * To stop using the the controller and release its associated resources,
 * call spdk_nvme_detach() with the spdk_nvme_ctrlr instance from the attach_cb()
 * function.
 *
 * \param trid The transport ID indicating which bus to enumerate. If the trtype
 * is PCIe or trid is NULL, this will scan the local PCIe bus. If the trtype is
 * RDMA, the traddr and trsvcid must point at the location of an NVMe-oF discovery
 * service.
 * \param cb_ctx Opaque value which will be passed back in cb_ctx parameter of
 * the callbacks.
 * \param probe_cb will be called once per NVMe device found in the system.
 * \param attach_cb will be called for devices for which probe_cb returned true
 * once that NVMe controller has been attached to the userspace driver.
 * \param remove_cb will be called for devices that were attached in a previous
 * spdk_nvme_probe() call but are no longer attached to the system. Optional;
 * specify NULL if removal notices are not desired.
 *
 * \return 0 on success, -1 on failure.
 */
int spdk_nvme_probe(const struct spdk_nvme_transport_id *trid,
          void *cb_ctx,
          spdk_nvme_probe_cb probe_cb,
          spdk_nvme_attach_cb attach_cb,
          spdk_nvme_remove_cb remove_cb);
          
          
/**
 * Callback for spdk_nvme_probe() enumeration.
 *
 * \param cb_ctx Opaque value passed to spdk_nvme_probe().
 * \param trid NVMe transport identifier.
 * \param opts NVMe controller initialization options. This structure will be
 * populated with the default values on entry, and the user callback may update
 * any options to request a different value. The controller may not support all
 * requested parameters, so the final values will be provided during the attach
 * callback.
 *
 * \return true to attach to this device.
 */
typedef bool (*spdk_nvme_probe_cb)(void *cb_ctx, const struct spdk_nvme_transport_id *trid,
               struct spdk_nvme_ctrlr_opts *opts);

/**
 * Callback for spdk_nvme_attach() to report a device that has been attached to
 * the userspace NVMe driver.
 *
 * \param cb_ctx Opaque value passed to spdk_nvme_attach_cb().
 * \param trid NVMe transport identifier.
 * \param ctrlr Opaque handle to NVMe controller.
 * \param opts NVMe controller initialization options that were actually used.
 * Options may differ from the requested options from the attach call depending
 * on what the controller supports.
 */
typedef void (*spdk_nvme_attach_cb)(void *cb_ctx, const struct spdk_nvme_transport_id *trid,
                struct spdk_nvme_ctrlr *ctrlr,
                const struct spdk_nvme_ctrlr_opts *opts);

/**
 * Callback for spdk_nvme_remove() to report that a device attached to the userspace
 * NVMe driver has been removed from the system.
 *
 * The controller will remain in a failed state (any new I/O submitted will fail).
 *
 * The controller must be detached from the userspace driver by calling spdk_nvme_detach()
 * once the controller is no longer in use. It is up to the library user to ensure
 * that no other threads are using the controller before calling spdk_nvme_detach().
 *
 * \param cb_ctx Opaque value passed to spdk_nvme_remove_cb().
 * \param ctrlr NVMe controller instance that was removed.
 */
typedef void (*spdk_nvme_remove_cb)(void *cb_ctx, struct spdk_nvme_ctrlr *ctrlr);
```





接下来看下struct spdk_nvme_transport_id



```javascript
/**
 * NVMe transport identifier.
 *
 * This identifies a unique endpoint on an NVMe fabric.
 *
 * A string representation of a transport ID may be converted to this type using
 * spdk_nvme_transport_id_parse().
 */
struct spdk_nvme_transport_id {
   /**
    * NVMe transport string.
    */
   char trstring[SPDK_NVMF_TRSTRING_MAX_LEN + 1];

   /**
    * NVMe transport type.
    */
   enum spdk_nvme_transport_type trtype;

   /**
    * Address family of the transport address.
    *
    * For PCIe, this value is ignored.
    */
   enum spdk_nvmf_adrfam adrfam;

   /**
    * Transport address of the NVMe-oF endpoint. For transports which use IP
    * addressing (e.g. RDMA), this should be an IP address. For PCIe, this
    * can either be a zero length string (the whole bus) or a PCI address
    * in the format DDDD:BB:DD.FF or DDDD.BB.DD.FF. For FC the string is
    * formatted as: nn-0xWWNN:pn-0xWWPN” where WWNN is the Node_Name of the
    * target NVMe_Port and WWPN is the N_Port_Name of the target NVMe_Port.
    */
   char traddr[SPDK_NVMF_TRADDR_MAX_LEN + 1];

   /**
    * Transport service id of the NVMe-oF endpoint.  For transports which use
    * IP addressing (e.g. RDMA), this field should be the port number. For PCIe,
    * and FC this is always a zero length string.
    */
   char trsvcid[SPDK_NVMF_TRSVCID_MAX_LEN + 1];

   /**
    * Subsystem NQN of the NVMe over Fabrics endpoint. May be a zero length string.
    */
   char subnqn[SPDK_NVMF_NQN_MAX_LEN + 1];

   /**
    * The Transport connection priority of the NVMe-oF endpoint. Currently this is
    * only supported by posix based sock implementation on Kernel TCP stack. More
    * information of this field can be found from the socket(7) man page.
    */
   int priority;
};
```



对于nvme over PCIe，我们只需要关注   spdk_nvme_transport_type 这一项

```javascript
/**
 * NVMe transport type.
 */
enum spdk_nvme_transport_type trtype;
```





目前支持的传输类型如下

```javascript
/**
 * NVMe library transports
 *
 * NOTE: These are mapped directly to the NVMe over Fabrics TRTYPE values, except for PCIe,
 * which is a special case since NVMe over Fabrics does not define a TRTYPE for local PCIe.
 *
 * Currently, this uses 256 for PCIe which is intentionally outside of the 8-bit range of TRTYPE.
 * If the NVMe-oF specification ever defines a PCIe TRTYPE, this should be updated.
 */
enum spdk_nvme_transport_type {
   /**
    * PCIe Transport (locally attached devices)
    */
   SPDK_NVME_TRANSPORT_PCIE = 256,

   /**
    * RDMA Transport (RoCE, iWARP, etc.)
    */
   SPDK_NVME_TRANSPORT_RDMA = SPDK_NVMF_TRTYPE_RDMA,

   /**
    * Fibre Channel (FC) Transport
    */
   SPDK_NVME_TRANSPORT_FC = SPDK_NVMF_TRTYPE_FC,

   /**
    * TCP Transport
    */
   SPDK_NVME_TRANSPORT_TCP = SPDK_NVMF_TRTYPE_TCP,

   /**
    * Custom VFIO User Transport (Not spec defined)
    */
   SPDK_NVME_TRANSPORT_VFIOUSER = 1024,

   /**
    * Custom Transport (Not spec defined)
    */
   SPDK_NVME_TRANSPORT_CUSTOM = 4096,
};
```





接下来看看函数spdk_nvme_probe()的代码

```javascript
int
spdk_nvme_probe(const struct spdk_nvme_transport_id *trid, void *cb_ctx,
      spdk_nvme_probe_cb probe_cb, spdk_nvme_attach_cb attach_cb,
      spdk_nvme_remove_cb remove_cb)
{
   struct spdk_nvme_transport_id trid_pcie;
   struct spdk_nvme_probe_ctx *probe_ctx;

   if (trid == NULL) {
      memset(&trid_pcie, 0, sizeof(trid_pcie));
      spdk_nvme_trid_populate_transport(&trid_pcie, SPDK_NVME_TRANSPORT_PCIE);
      trid = &trid_pcie;
   }

   probe_ctx = spdk_nvme_probe_async(trid, cb_ctx, probe_cb,
                 attach_cb, remove_cb);
   if (!probe_ctx) {
      SPDK_ERRLOG("Create probe context failed\n");
      return -1;
   }

   /*
    * Keep going even if one or more nvme_attach() calls failed,
    *  but maintain the value of rc to signal errors when we return.
    */
   return nvme_init_controllers(probe_ctx);
}
```





1、set trid if it is NULL,  spdk_nvme_trid_populate_transport 

2、spdk_nvme_probe_async

- 	rc = nvme_driver_init();

-	probe_ctx = calloc(1, sizeof(*probe_ctx));

- 	nvme_probe_ctx_init(probe_ctx, trid, cb_ctx, probe_cb, attach_cb, remove_cb);

-	rc = nvme_probe_internal(probe_ctx, false);

3、return nvme_init_controllers(probe_ctx);



来看看 nvme_probe_internal(probe_ctx,false)



```javascript
static int
nvme_probe_internal(struct spdk_nvme_probe_ctx *probe_ctx,
                  bool direct_connect)
{
       int rc;
       struct spdk_nvme_ctrlr *ctrlr, *ctrlr_tmp;

       spdk_nvme_trid_populate_transport(&probe_ctx->trid, probe_ctx->trid.trtype);
       if (!spdk_nvme_transport_available_by_name(probe_ctx->trid.trstring)) {
              SPDK_ERRLOG("NVMe trtype %u not available\n", probe_ctx->trid.trtype);
              return -1;
       }

       nvme_robust_mutex_lock(&g_spdk_nvme_driver->lock);

       rc = nvme_transport_ctrlr_scan(probe_ctx, direct_connect);
       if (rc != 0) {
              SPDK_ERRLOG("NVMe ctrlr scan failed\n");
              TAILQ_FOREACH_SAFE(ctrlr, &probe_ctx->init_ctrlrs, tailq, ctrlr_tmp) {
                     TAILQ_REMOVE(&probe_ctx->init_ctrlrs, ctrlr, tailq);
                     nvme_transport_ctrlr_destruct(ctrlr);
              }
              nvme_robust_mutex_unlock(&g_spdk_nvme_driver->lock);
              return -1;
       }

       /*
        * Probe controllers on the shared_attached_ctrlrs list
        */
       if (!spdk_process_is_primary() && (probe_ctx->trid.trtype == SPDK_NVME_TRANSPORT_PCIE)) {
              TAILQ_FOREACH(ctrlr, &g_spdk_nvme_driver->shared_attached_ctrlrs, tailq) {
                     /* Do not attach other ctrlrs if user specify a valid trid */
                     if ((strlen(probe_ctx->trid.traddr) != 0) &&
                         (spdk_nvme_transport_id_compare(&probe_ctx->trid, &ctrlr->trid))) {
                            continue;
                     }

                     /* Do not attach if we failed to initialize it in this process */
                     if (nvme_ctrlr_get_current_process(ctrlr) == NULL) {
                            continue;
                     }

                     nvme_ctrlr_proc_get_ref(ctrlr);

                     /*
                      * Unlock while calling attach_cb() so the user can call other functions
                      *  that may take the driver lock, like nvme_detach().
                      */
                     if (probe_ctx->attach_cb) {
                            nvme_robust_mutex_unlock(&g_spdk_nvme_driver->lock);
                            probe_ctx->attach_cb(probe_ctx->cb_ctx, &ctrlr->trid, ctrlr, &ctrlr->opts);
                            nvme_robust_mutex_lock(&g_spdk_nvme_driver->lock);
                     }
              }
       }

       nvme_robust_mutex_unlock(&g_spdk_nvme_driver->lock);

       return 0;
}
```



关注重点16行rc = nvme_transport_ctrlr_scan(probe_ctx, direct_connect);



lib/nvme/nvme_transport.c



```javascript
int
nvme_transport_ctrlr_scan(struct spdk_nvme_probe_ctx *probe_ctx,
                       bool direct_connect)
{
       const struct spdk_nvme_transport *transport = nvme_get_transport(probe_ctx->trid.trstring);

       if (transport == NULL) {
              SPDK_ERRLOG("Transport %s doesn't exist.", probe_ctx->trid.trstring);
              return -ENOENT;
       }

       return transport->ops.ctrlr_scan(probe_ctx, direct_connect);
}
```



于是， nvme_transport_ctrlr_scan()被转化为nvme_pcie_ctrlr_scan()调用（对NVMe over PCIe）来说，



```javascript
static int
nvme_pcie_ctrlr_scan(struct spdk_nvme_probe_ctx *probe_ctx,
                   bool direct_connect)
{
       struct nvme_pcie_enum_ctx enum_ctx = {};

       enum_ctx.probe_ctx = probe_ctx;

       if (strlen(probe_ctx->trid.traddr) != 0) {
              if (spdk_pci_addr_parse(&enum_ctx.pci_addr, probe_ctx->trid.traddr)) {
                     return -1;
              }
              enum_ctx.has_pci_addr = true;
       }

       /* Only the primary process can monitor hotplug. */
       if (spdk_process_is_primary()) {
              _nvme_pcie_hotplug_monitor(probe_ctx);
       }

       if (enum_ctx.has_pci_addr == false) {
              return spdk_pci_enumerate(spdk_pci_nvme_get_driver(),
                                     pcie_nvme_enum_cb, &enum_ctx);
       } else {
              return spdk_pci_device_attach(spdk_pci_nvme_get_driver(),
                                         pcie_nvme_enum_cb, &enum_ctx, &enum_ctx.pci_addr);
       }
}
```



接下来重点看下22行return spdk_pci_enumerate(spdk_pci_nvme_get_driver(),pcie_nvme_enum_cb, &enum_ctx);



spdk_pci_nvme_get_driver()

```javascript
/**
 * Get the NVMe PCI driver object.
 *
 * \return PCI driver.
 */
struct spdk_pci_driver *spdk_pci_nvme_get_driver(void);


struct spdk_pci_driver {
       struct rte_pci_driver         driver;

       const char                      *name;
       const struct spdk_pci_id       *id_table;
       uint32_t                     drv_flags;

       spdk_pci_enum_cb              cb_fn;
       void                        *cb_arg;
       TAILQ_ENTRY(spdk_pci_driver)   tailq;
};


/**
 * A structure describing a PCI driver.
 */
struct rte_pci_driver {
       TAILQ_ENTRY(rte_pci_driver) next;  /**< Next in list. */
       struct rte_driver driver;          /**< Inherit core driver. */
       struct rte_pci_bus *bus;           /**< PCI bus reference. */
       rte_pci_probe_t *probe;            /**< Device probe function. */
       rte_pci_remove_t *remove;          /**< Device remove function. */
       pci_dma_map_t *dma_map;                  /**< device dma map function. */
       pci_dma_unmap_t *dma_unmap;       /**< device dma unmap function. */
       const struct rte_pci_id *id_table; /**< ID table, NULL terminated. */
       uint32_t drv_flags;                /**< Flags RTE_PCI_DRV_*. */
};
```



接下来重点看下22行return spdk_pci_enumerate(spdk_pci_nvme_get_driver(),pcie_nvme_enum_cb, &enum_ctx);

spdk_pci_enumerate源码如下

```javascript
/* Note: You can call spdk_pci_enumerate from more than one thread
 *       simultaneously safely, but you cannot call spdk_pci_enumerate
 *       and rte_eal_pci_probe simultaneously.
 */
int
spdk_pci_enumerate(struct spdk_pci_driver *driver,
                 spdk_pci_enum_cb enum_cb,
                 void *enum_ctx)
{
       struct spdk_pci_device *dev;
       int rc;

       cleanup_pci_devices();

       pthread_mutex_lock(&g_pci_mutex);
       TAILQ_FOREACH(dev, &g_pci_devices, internal.tailq) {
              if (dev->internal.attached ||
                  dev->internal.driver != driver ||
                  dev->internal.pending_removal) {
                     continue;
              }

              rc = enum_cb(enum_ctx, dev);
              if (rc == 0) {
                     dev->internal.attached = true;
              } else if (rc < 0) {
                     pthread_mutex_unlock(&g_pci_mutex);
                     return -1;
              }
       }
       pthread_mutex_unlock(&g_pci_mutex);

       if (scan_pci_bus(true) != 0) {
              return -1;
       }

       driver->cb_fn = enum_cb;
       driver->cb_arg = enum_ctx;

       if (rte_bus_probe() != 0) {
              driver->cb_arg = NULL;
              driver->cb_fn = NULL;
              return -1;
       }

       driver->cb_arg = NULL;
       driver->cb_fn = NULL;

       cleanup_pci_devices();
       return 0;
}
```



spdk_pci_enumerate()方法体内

33行if (scan_pci_bus(true) != 0) 

```javascript
static int
scan_pci_bus(bool delay_init)
{
       struct spdk_pci_driver *driver;
       struct rte_pci_device *rte_dev;
       uint64_t now;

       rte_bus_scan();
       now = spdk_get_ticks();

       driver = TAILQ_FIRST(&g_pci_drivers);
       if (!driver) {
              return 0;
       }

       TAILQ_FOREACH(rte_dev, &driver->driver.bus->device_list, next) {
              struct rte_devargs *da;

              da = rte_dev->device.devargs;
              if (!da) {
                     char devargs_str[128];

                     /* the device was never blocked or allowed */
                     da = calloc(1, sizeof(*da));
                     if (!da) {
                            return -1;
                     }

                     snprintf(devargs_str, sizeof(devargs_str), "pci:%s", rte_dev->device.name);
                     if (rte_devargs_parse(da, devargs_str) != 0) {
                            free(da);
                            return -1;
                     }

                     rte_devargs_insert(&da);
                     rte_dev->device.devargs = da;
              }

              if (get_allowed_at(da)) {
                     uint64_t allowed_at = get_allowed_at(da);

                     /* this device was seen by spdk before... */
                     if (da->policy == RTE_DEV_BLOCKED && allowed_at <= now) {
                            da->policy = RTE_DEV_ALLOWED;
                     }
              } else if ((driver->driver.bus->bus.conf.scan_mode == RTE_BUS_SCAN_ALLOWLIST &&
                         da->policy == RTE_DEV_ALLOWED) || da->policy != RTE_DEV_BLOCKED) {
                     /* override the policy only if not permanently blocked */

                     if (delay_init) {
                            da->policy = RTE_DEV_BLOCKED;
                            set_allowed_at(da, now + 2 * spdk_get_ticks_hz());
                     } else {
                            da->policy = RTE_DEV_ALLOWED;
                            set_allowed_at(da, now);
                     }
              }
       }

       return 0;
}
```



8行rte_bus_scan();

```javascript
/* Scan all the buses for registered devices */
int
rte_bus_scan(void)
{
       int ret;
       struct rte_bus *bus = NULL;

       TAILQ_FOREACH(bus, &rte_bus_list, next) {
              ret = bus->scan();
              if (ret)
                     RTE_LOG(ERR, EAL, "Scan for (%s) bus failed.\n",
                            bus->name);
       }

       return 0;
}
```



spdk_pci_enumerate()方法体内

40行  if (rte_bus_probe() != 0) {

```javascript
/* Probe all devices of all buses */
int
rte_bus_probe(void)
{
       int ret;
       struct rte_bus *bus, *vbus = NULL;

       TAILQ_FOREACH(bus, &rte_bus_list, next) {
              if (!strcmp(bus->name, "vdev")) {
                     vbus = bus;
                     continue;
              }

              ret = bus->probe();
              if (ret)
                     RTE_LOG(ERR, EAL, "Bus (%s) probe failed.\n",
                            bus->name);
       }

       if (vbus) {
              ret = vbus->probe();
              if (ret)
                     RTE_LOG(ERR, EAL, "Bus (%s) probe failed.\n",
                            vbus->name);
       }

       return 0;
}
```





重点关注14行  ret = bus->probe();



这里的probe()就是调研下面的mlx5_common_pci_probe，下面的rte_pci_driver struct是不是有点眼熟，对，就是上文提到的spdk_pci_nvme_get_driver()

```javascript
static struct rte_pci_driver mlx5_common_pci_driver = {
       .driver = {
                 .name = MLX5_PCI_DRIVER_NAME,
       },
       .probe = mlx5_common_pci_probe,
       .remove = mlx5_common_pci_remove,
       .dma_map = mlx5_common_pci_dma_map,
       .dma_unmap = mlx5_common_pci_dma_unmap,
};
```





然后重点关注mlx5_common_pci_probe源码如下





```javascript
int
mlx5_common_dev_probe(struct rte_device *eal_dev)
{
       struct mlx5_common_device *dev;
       uint32_t classes = 0;
       bool new_device = false;
       int ret;

       DRV_LOG(INFO, "probe device \"%s\".", eal_dev->name);
       ret = parse_class_options(eal_dev->devargs);
       if (ret < 0) {
              DRV_LOG(ERR, "Unsupported mlx5 class type: %s",
                     eal_dev->devargs->args);
              return ret;
       }
       classes = ret;
       if (classes == 0)
              /* Default to net class. */
              classes = MLX5_CLASS_ETH;
       dev = to_mlx5_device(eal_dev);
       if (!dev) {
              dev = rte_zmalloc("mlx5_common_device", sizeof(*dev), 0);
              if (!dev)
                     return -ENOMEM;
              dev->dev = eal_dev;
              TAILQ_INSERT_HEAD(&devices_list, dev, next);
              new_device = true;
       } else {
              /* Validate combination here. */
              ret = is_valid_class_combination(classes |
                                           dev->classes_loaded);
              if (ret != 0) {
                     DRV_LOG(ERR, "Unsupported mlx5 classes combination.");
                     return ret;
              }
       }
       ret = drivers_probe(dev, classes);
       if (ret)
              goto class_err;
       return 0;
class_err:
       if (new_device)
              dev_release(dev);
       return ret;
}
```



关注重点是37行 ret = drivers_probe(dev, classes);



drivers_probe源码如下



```javascript
static int
drivers_probe(struct mlx5_common_device *dev, uint32_t user_classes)
{
       struct mlx5_class_driver *driver;
       uint32_t enabled_classes = 0;
       bool already_loaded;
       int ret;

       TAILQ_FOREACH(driver, &drivers_list, next) {
              if ((driver->drv_class & user_classes) == 0)
                     continue;
              if (!mlx5_bus_match(driver, dev->dev))
                     continue;
              already_loaded = dev->classes_loaded & driver->drv_class;
              if (already_loaded && driver->probe_again == 0) {
                     DRV_LOG(ERR, "Device %s is already probed",
                            dev->dev->name);
                     ret = -EEXIST;
                     goto probe_err;
              }
              ret = driver->probe(dev->dev);
              if (ret < 0) {
                     DRV_LOG(ERR, "Failed to load driver %s",
                            driver->name);
                     goto probe_err;
              }
              enabled_classes |= driver->drv_class;
       }
       dev->classes_loaded |= enabled_classes;
       return 0;
probe_err:
       /* Only unload drivers which are enabled which were enabled
        * in this probe instance.
        */
       drivers_remove(dev, enabled_classes);
       return ret;
}
```

关注重点12行 if (!mlx5_bus_match(driver, dev->dev))



mlx5_bus_match源码如下

```javascript
static bool
mlx5_bus_match(const struct mlx5_class_driver *drv,
              const struct rte_device *dev)
{
       if (mlx5_dev_is_pci(dev))
              return mlx5_dev_pci_match(drv, dev);
       return true;
}
```



关注重点

return mlx5_dev_pci_match(drv, dev);



mlx5_dev_pci_match源码如下

```javascript
bool
mlx5_dev_pci_match(const struct mlx5_class_driver *drv,
                 const struct rte_device *dev)
{
       const struct rte_pci_device *pci_dev;
       const struct rte_pci_id *id_table;

       if (!mlx5_dev_is_pci(dev))
              return false;
       pci_dev = RTE_DEV_TO_PCI_CONST(dev);
       for (id_table = drv->id_table; id_table->vendor_id != 0;
            id_table++) {
              /* Check if device's ids match the class driver's ids. */
              if (id_table->vendor_id != pci_dev->id.vendor_id &&
                  id_table->vendor_id != RTE_PCI_ANY_ID)
                     continue;
              if (id_table->device_id != pci_dev->id.device_id &&
                  id_table->device_id != RTE_PCI_ANY_ID)
                     continue;
              if (id_table->subsystem_vendor_id !=
                  pci_dev->id.subsystem_vendor_id &&
                  id_table->subsystem_vendor_id != RTE_PCI_ANY_ID)
                     continue;
              if (id_table->subsystem_device_id !=
                  pci_dev->id.subsystem_device_id &&
                  id_table->subsystem_device_id != RTE_PCI_ANY_ID)
                     continue;
              if (id_table->class_id != pci_dev->id.class_id &&
                  id_table->class_id != RTE_CLASS_ANY_ID)
                     continue;
              return true;
       }
       return false;
}
```





发现SSD设备的时候，从SPDK进入到DPDK中，函数调用栈为：

```javascript
00 hello_word.c
01 -> main()
02 --> spdk_nvme_probe()
03 ---> spdk_nvme_probe_async()
04 ----> nvme_probe_internal()
05 -----> nvme_transport_ctrlr_scan()
06 -----> nvme_pcie_ctrlr_scan()  
07 ------> spdk_pci_enumerate()		
08 ------> spdk_pci_enumerate()
09 ------> rte_bus_probe()				             			    | SPDK |
   =========================================================================
10 -------> mlx5_common_dev_probe()                                 | DPDK |
11 --------> drivers_probe()
12 ---------> mlx5_bus_match()
13 ----------> mlx5_dev_pci_match()
```





hello_world()方法解析



```javascript
static void
hello_world(void)
{
       struct ns_entry                      *ns_entry;
       struct hello_world_sequence    sequence;
       int                         rc;
       size_t                      sz;

       TAILQ_FOREACH(ns_entry, &g_namespaces, link) {
              /*
               * Allocate an I/O qpair that we can use to submit read/write requests
               *  to namespaces on the controller.  NVMe controllers typically support
               *  many qpairs per controller.  Any I/O qpair allocated for a controller
               *  can submit I/O to any namespace on that controller.
               *
               * The SPDK NVMe driver provides no synchronization for qpair accesses -
               *  the application must ensure only a single thread submits I/O to a
               *  qpair, and that same thread must also check for completions on that
               *  qpair.  This enables extremely efficient I/O processing by making all
               *  I/O operations completely lockless.
               */
               //每个NVMe SSD控制器下支持多个namespace，同事支持多个qpair，但是应用需要确保多线程进行
               //qpair操作时保证同时只能一个线程对同一个qpair进行操作
              ns_entry->qpair = spdk_nvme_ctrlr_alloc_io_qpair(ns_entry->ctrlr, NULL, 0);
              if (ns_entry->qpair == NULL) {
                     printf("ERROR: spdk_nvme_ctrlr_alloc_io_qpair() failed\n");
                     return;
              }

              /*
               * Use spdk_dma_zmalloc to allocate a 4KB zeroed buffer.  This memory
               * will be pinned, which is required for data buffers used for SPDK NVMe
               * I/O operations.
               */
              sequence.using_cmb_io = 1;
              sequence.buf = spdk_nvme_ctrlr_map_cmb(ns_entry->ctrlr, &sz);
              if (sequence.buf == NULL || sz < 0x1000) {
                     sequence.using_cmb_io = 0;
                     sequence.buf = spdk_zmalloc(0x1000, 0x1000, NULL, SPDK_ENV_SOCKET_ID_ANY, SPDK_MALLOC_DMA);
              }
              if (sequence.buf == NULL) {
                     printf("ERROR: write buffer allocation failed\n");
                     return;
              }
              if (sequence.using_cmb_io) {
                     printf("INFO: using controller memory buffer for IO\n");
              } else {
                     printf("INFO: using host memory buffer for IO\n");
              }
              sequence.is_completed = 0;
              sequence.ns_entry = ns_entry;

              /*
               * If the namespace is a Zoned Namespace, rather than a regular
               * NVM namespace, we need to reset the first zone, before we
               * write to it. This not needed for regular NVM namespaces.
               */
              if (spdk_nvme_ns_get_csi(ns_entry->ns) == SPDK_NVME_CSI_ZNS) {
                     reset_zone_and_wait_for_completion(&sequence);
              }

              /*
               * Print "Hello world!" to sequence.buf.  We will write this data to LBA（Logical Block Addressing）
               *  0 on the namespace, and then later read it back into a separate buffer
               *  to demonstrate the full I/O path.
               */
              snprintf(sequence.buf, 0x1000, "%s", "Hello world!\n");

              /*
               * Write the data buffer to LBA 0 of this namespace.  "write_complete" and
               *  "&sequence" are specified as the completion callback function and
               *  argument respectively.  write_complete() will be called with the
               *  value of &sequence as a parameter when the write I/O is completed.
               *  This allows users to potentially specify different completion
               *  callback routines for each I/O, as well as pass a unique handle
               *  as an argument so the application knows which I/O has completed.
               *
               * Note that the SPDK NVMe driver will only check for completions
               *  when the application calls spdk_nvme_qpair_process_completions().
               *  It is the responsibility of the application to trigger the polling
               *  process.
               */
              rc = spdk_nvme_ns_cmd_write(ns_entry->ns, ns_entry->qpair, sequence.buf,
                                       0, /* LBA start */
                                       1, /* number of LBAs */
                                       write_complete, &sequence, 0);
              if (rc != 0) {
                     fprintf(stderr, "starting write I/O failed\n");
                     exit(1);
              }

              /*
               * Poll for completions.  0 here means process all available completions.
               *  In certain usage models, the caller may specify a positive integer
               *  instead of 0 to signify the maximum number of completions it should
               *  process.  This function will never block - if there are no
               *  completions pending on the specified qpair, it will return immediately.
               *
               * When the write I/O completes, write_complete() will submit a new I/O
               *  to read LBA 0 into a separate buffer, specifying read_complete() as its
               *  completion routine.  When the read I/O completes, read_complete() will
               *  print the buffer contents and set sequence.is_completed = 1.  That will
               *  break this loop and then exit the program.
               */
              while (!sequence.is_completed) {
                     spdk_nvme_qpair_process_completions(ns_entry->qpair, 0);
              }

              /*
               * Free the I/O qpair.  This typically is done when an application exits.
               *  But SPDK does support freeing and then reallocating qpairs during
               *  operation.  It is the responsibility of the caller to ensure all
               *  pending I/O are completed before trying to free the qpair.
               */
              spdk_nvme_ctrlr_free_io_qpair(ns_entry->qpair);
       }
}
```





```javascript
static void
write_complete(void *arg, const struct spdk_nvme_cpl *completion)
{
       struct hello_world_sequence    *sequence = arg;
       struct ns_entry                      *ns_entry = sequence->ns_entry;
       int                         rc;

       /* See if an error occurred. If so, display information
        * about it, and set completion value so that I/O
        * caller is aware that an error occurred.
        */
       if (spdk_nvme_cpl_is_error(completion)) {
              spdk_nvme_qpair_print_completion(sequence->ns_entry->qpair, (struct spdk_nvme_cpl *)completion);
              fprintf(stderr, "I/O error status: %s\n", spdk_nvme_cpl_get_status_string(&completion->status));
              fprintf(stderr, "Write I/O failed, aborting run\n");
              sequence->is_completed = 2;
              exit(1);
       }
       /*
        * The write I/O has completed.  Free the buffer associated with
        *  the write I/O and allocate a new zeroed buffer for reading
        *  the data back from the NVMe namespace.
        */
       if (sequence->using_cmb_io) {
              spdk_nvme_ctrlr_unmap_cmb(ns_entry->ctrlr);
       } else {
              spdk_free(sequence->buf);
       }
       sequence->buf = spdk_zmalloc(0x1000, 0x1000, NULL, SPDK_ENV_SOCKET_ID_ANY, SPDK_MALLOC_DMA);
       //读取刚写入的内容
       rc = spdk_nvme_ns_cmd_read(ns_entry->ns, ns_entry->qpair, sequence->buf,
                               0, /* LBA start */
                               1, /* number of LBAs */
                               read_complete, (void *)sequence, 0);
       if (rc != 0) {
              fprintf(stderr, "starting read I/O failed\n");
              exit(1);
       }
}
```





```javascript
static void
read_complete(void *arg, const struct spdk_nvme_cpl *completion)
{
       struct hello_world_sequence *sequence = arg;

       /* Assume the I/O was successful */
       sequence->is_completed = 1;
       /* See if an error occurred. If so, display information
        * about it, and set completion value so that I/O
        * caller is aware that an error occurred.
        */
       if (spdk_nvme_cpl_is_error(completion)) {
              spdk_nvme_qpair_print_completion(sequence->ns_entry->qpair, (struct spdk_nvme_cpl *)completion);
              fprintf(stderr, "I/O error status: %s\n", spdk_nvme_cpl_get_status_string(&completion->status));
              fprintf(stderr, "Read I/O failed, aborting run\n");
              sequence->is_completed = 2;
              exit(1);
       }

       /*
        * The read I/O has completed.  Print the contents of the
        *  buffer, free the buffer, then mark the sequence as
        *  completed.  This will trigger the hello_world() function
        *  to exit its polling loop.
        */
       printf("%s", sequence->buf);
       spdk_free(sequence->buf);
}
```

