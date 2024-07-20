调用栈

```

Breakpoint reached: WeightedPriorityQueue.h:324
Stack:
        WeightedPriorityQueue::enqueue(unsigned long, unsigned int, unsigned int, ceph::osd::scheduler::OpSchedulerItem &&) WeightedPriorityQueue.h:324
        ceph::osd::scheduler::ClassedOpQueueScheduler::enqueue(ceph::osd::scheduler::OpSchedulerItem &&) OpScheduler.h:105
        OSD::ShardedOpWQ::_enqueue(ceph::osd::scheduler::OpSchedulerItem &&) OSD.cc:11306
        ShardedThreadPool::ShardedWQ::queue(ceph::osd::scheduler::OpSchedulerItem &&) WorkQueue.h:616
        OSD::enqueue_op(spg_t, boost::intrusive_ptr<…> &&, unsigned int) OSD.cc:9951
        OSD::ms_fast_dispatch(Message *) OSD.cc:7313
        Dispatcher::ms_fast_dispatch2(const boost::intrusive_ptr<…> &) Dispatcher.h:84
        Messenger::ms_fast_dispatch(const boost::intrusive_ptr<…> &) Messenger.h:685
        DispatchQueue::fast_dispatch(const boost::intrusive_ptr<…> &) DispatchQueue.cc:74
        DispatchQueue::fast_dispatch(Message *) DispatchQueue.h:203
        ProtocolV2::handle_message() ProtocolV2.cc:1478
        ProtocolV2::handle_read_frame_dispatch() ProtocolV2.cc:1141
        ProtocolV2::_handle_read_frame_epilogue_main() ProtocolV2.cc:1329
        ProtocolV2::handle_read_frame_epilogue_main(std::unique_ptr<…> &&, int) ProtocolV2.cc:1304
        CtRxNode::call(ProtocolV2 *) const Protocol.h:67
        ProtocolV2::run_continuation(Ct<…> &) ProtocolV2.cc:47
        operator() ProtocolV2.cc:755
        std::__invoke_impl<…>(std::__invoke_other, (unnamed struct) &) invoke.h:61
        std::__invoke_r<…>((unnamed struct) &) invoke.h:111
        std::_Function_handler::_M_invoke(const std::_Any_data &, char *&&, long &&) std_function.h:291
        std::function::operator()(char *, long) const std_function.h:560
        AsyncConnection::process() AsyncConnection.cc:454
        C_handle_read::do_request(unsigned long) AsyncConnection.cc:71
        EventCenter::process_events(unsigned int, std::chrono::duration<…> *) Event.cc:422
        operator() Stack.cc:53
        std::__invoke_impl<…>(std::__invoke_other, (unnamed struct) &) invoke.h:61
        std::__invoke_r<…>((unnamed struct) &) invoke.h:111
        std::_Function_handler::_M_invoke(const std::_Any_data &) std_function.h:291
        std::function::operator()() const std_function.h:560
        std::__invoke_impl<…>(std::__invoke_other, std::function<…> &&) invoke.h:61
        std::__invoke<…>(std::function<…> &&) invoke.h:96
        std::thread::_Invoker::_M_invoke<…>(std::_Index_tuple<…>) std_thread.h:253
        std::thread::_Invoker::operator()() std_thread.h:260
        std::thread::_State_impl::_M_run() std_thread.h:211
        <unknown> 0x00007fa7690e96b4
        start_thread 0x00007fa76929e609
        __clone 0x00007fa768dd0133
Breakpoint reached: PrimaryLogPG.cc:8900
Stack:
        PrimaryLogPG::complete_read_ctx(int, PrimaryLogPG::OpContext *) PrimaryLogPG.cc:8900
        PrimaryLogPG::execute_ctx(PrimaryLogPG::OpContext *) PrimaryLogPG.cc:4122
        PrimaryLogPG::do_op(boost::intrusive_ptr<…> &) PrimaryLogPG.cc:2413
        PrimaryLogPG::do_request(boost::intrusive_ptr<…> &, ThreadPool::TPHandle &) PrimaryLogPG.cc:1831
        OSD::dequeue_op(boost::intrusive_ptr<…>, boost::intrusive_ptr<…>, ThreadPool::TPHandle &) OSD.cc:10005
        ceph::osd::scheduler::PGOpItem::run(OSD *, OSDShard *, boost::intrusive_ptr<…> &, ThreadPool::TPHandle &) OpSchedulerItem.cc:32
        ceph::osd::scheduler::OpSchedulerItem::run(OSD *, OSDShard *, boost::intrusive_ptr<…> &, ThreadPool::TPHandle &) OpSchedulerItem.h:148
        OSD::ShardedOpWQ::_process(unsigned int, ceph::heartbeat_handle_d *) OSD.cc:11277
        ShardedThreadPool::shardedthreadpool_worker(unsigned int) WorkQueue.cc:313
        ShardedThreadPool::WorkThreadSharded::entry() WorkQueue.h:637
        Thread::entry_wrapper() Thread.cc:87
        Thread::_entry_func(void *) Thread.cc:74
        start_thread 0x00007fa76929e609
        __clone 0x00007fa768dd0133
```

msgr-worker 同 put ，可以参考[6、rados put调试 调用栈分析学习](note://WEBf192a22db581d8df736ed5716ff2ca91)

tp_osd_tp分析学习

```
ShardedThreadPool::WorkThreadSharded::entry() WorkQueue.h:637
ShardedThreadPool::shardedthreadpool_worker(unsigned int) WorkQueue.cc:313
OSD::ShardedOpWQ::_process(unsigned int, ceph::heartbeat_handle_d *) OSD.cc:11277
ceph::osd::scheduler::OpSchedulerItem::run(OSD *, OSDShard *, boost::intrusive_ptr<…> &, ThreadPool::TPHandle &) OpSchedulerItem.h:148
ceph::osd::scheduler::PGOpItem::run(OSD *, OSDShard *, boost::intrusive_ptr<…> &, ThreadPool::TPHandle &) OpSchedulerItem.cc:32
OSD::dequeue_op(boost::intrusive_ptr<…>, boost::intrusive_ptr<…>, ThreadPool::TPHandle &) OSD.cc:10005
PrimaryLogPG::do_request(boost::intrusive_ptr<…> &, ThreadPool::TPHandle &) PrimaryLogPG.cc:1831
PrimaryLogPG::do_op(boost::intrusive_ptr<…> &) PrimaryLogPG.cc:2413
```

对比put，可以发现上面的调用栈全部相同

##### 主要分析学习不同的部分

```
PrimaryLogPG::execute_ctx(PrimaryLogPG::OpContext *) PrimaryLogPG.cc:4122
PrimaryLogPG::complete_read_ctx(int, PrimaryLogPG::OpContext *) PrimaryLogPG.cc:8900
```

###### PrimaryLogPG::execute_ctx(PrimaryLogPG::OpContext *) PrimaryLogPG.cc:4122

```
// read or error?
if ((ctx->op_t->empty() || result < 0) && !ctx->update_log_only) {
  // finish side-effects
  if (result >= 0)
    do_osd_op_effects(ctx, m->get_connection());

  complete_read_ctx(result, ctx);
  return;
}
```

```
void PrimaryLogPG::do_osd_op_effects(OpContext *ctx, const ConnectionRef& conn)
{
  entity_name_t entity = ctx->reqid.name;
  // disconnects first
  断开watch的连接
  complete_disconnect_watches(ctx->obc, ctx->watch_disconnects);
  auto session = conn->get_priv();
  if (!session)
    return;
遍历ctx->watch_connects列表
  for (list<pair<watch_info_t,bool> >::iterator i = ctx->watch_connects.begin();
       i != ctx->watch_connects.end();
       ++i) {
    pair<uint64_t, entity_name_t> watcher(i->first.cookie, entity);
    WatchRef watch;
    检查是否已经存在
    if (ctx->obc->watchers.count(watcher)) {
      watch = ctx->obc->watchers[watcher];
    } else {
      watch = Watch::makeWatchRef(
    this, osd, ctx->obc, i->first.timeout_seconds,
    i->first.cookie, entity, conn->get_peer_addr());
      ctx->obc->watchers.insert(make_pair(watcher,watch));
    }
    watch->connect(conn, i->second);
  }
函数遍历ctx->notifies列表
  for (list<notify_info_t>::iterator p = ctx->notifies.begin();
       p != ctx->notifies.end();
       ++p) {
    ConnectionRef conn(ctx->op->get_req()->get_connection());
    NotifyRef notif(
      Notify::makeNotifyRef(
    conn,
    ctx->reqid.name.num(),
    p->bl,
    p->timeout,
    p->cookie,
    p->notify_id,
    ctx->obc->obs.oi.user_version,
    osd));
    遍历
    for (map<pair<uint64_t, entity_name_t>, WatchRef>::iterator i =
       ctx->obc->watchers.begin();
     i != ctx->obc->watchers.end();
     ++i) {
      i->second->start_notify(notif);在该 watcher 上启动一个通知
    }
    notif->init();
  }
遍历
  for (list<OpContext::NotifyAck>::iterator p = ctx->notify_acks.begin();
       p != ctx->notify_acks.end();
       ++p) {
    if (p->watch_cookie)
      dout(10) << "notify_ack " << make_pair(*(p->watch_cookie), p->notify_id) << dendl;
    else
      dout(10) << "notify_ack " << make_pair("NULL", p->notify_id) << dendl;
      遍历
    for (map<pair<uint64_t, entity_name_t>, WatchRef>::iterator i =
       ctx->obc->watchers.begin();
     i != ctx->obc->watchers.end();
     ++i) {
      if (i->first.second != entity) continue;
      if (p->watch_cookie &&
      *(p->watch_cookie) != i->first.first) continue;
      i->second->notify_ack(p->notify_id, p->reply_bl);确认接收到通知，并向发送方发回一个确认信息
    }
  }
}
```

```
void PrimaryLogPG::complete_read_ctx(int result, OpContext *ctx)
{
  auto m = ctx->op->get_req<MOSDOp>();
  ceph_assert(ctx->async_reads_complete());
  for (auto p = ctx->ops->begin();
    p != ctx->ops->end() && result >= 0; ++p) {
    if (p->rval < 0 && !(p->op.flags & CEPH_OSD_OP_FLAG_FAILOK)) {
      result = p->rval;
      break;
    }
    累加已读取的字节数。
    ctx->bytes_read += p->outdata.length();
  }
  设置响应头部的数据偏移量
  ctx->reply->get_header().data_off = (ctx->data_off ? *ctx->data_off : 0);
获取ctx中的响应，并将ctx中的响应指针设为nullptr
  MOSDOpReply *reply = ctx->reply;
  ctx->reply = nullptr;

  if (result >= 0) {
    if (!ctx->ignore_log_op_stats) {
        如果不忽略操作统计信息，记录操作统计信息并发布到OSD
      log_op_stats(*ctx->op, ctx->bytes_written, ctx->bytes_read);
      publish_stats_to_osd();
    }

    // on read, return the current object version
    设置响应中的版本信息
    if (ctx->obs) {
      reply->set_reply_versions(eversion_t(), ctx->obs->oi.user_version);
    } else {
      reply->set_reply_versions(eversion_t(), ctx->user_at_version);
    }
  } else if (result == -ENOENT) {
    // on ENOENT, set a floor for what the next user version will be.
    设置响应中的版本信息，以表示下一个用户版本的最小值。
    reply->set_enoent_reply_versions(info.last_update, info.last_user_version);
  }

  reply->set_result(result);设置响应的result值。
  reply->add_flags(CEPH_OSD_FLAG_ACK | CEPH_OSD_FLAG_ONDISK);为响应添加标志位，表示已接收并持久化到磁盘。
  osd->send_message_osd_client(reply, m->get_connection());通过OSD发送响应给客户端。
  close_op_ctx(ctx);
}
```

在读取操作完成后完成上下文，包括更新操作统计信息、设置对象版本和用户版本、设置操作结果等操作。