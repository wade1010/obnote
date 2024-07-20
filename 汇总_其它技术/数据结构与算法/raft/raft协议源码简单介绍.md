newRaft方法

源码：

```
// NewRaft is used to construct a new Raft node. It takes a configuration, as well
// as implementations of various interfaces that are required. If we have any
// old state, such as snapshots, logs, peers, etc, all those will be restored
// when creating the Raft node.
func NewRaft(conf *Config, fsm FSM, logs LogStore, stable StableStore, snaps SnapshotStore, trans Transport) (*Raft, error) {
    // Validate the configuration.
    if err := ValidateConfig(conf); err != nil {
        return nil, err
    }

    // Ensure we have a LogOutput.
    logger := conf.getOrCreateLogger()

    // Try to restore the current term.
    currentTerm, err := stable.GetUint64(keyCurrentTerm)
    if err != nil && err.Error() != "not found" {
        return nil, fmt.Errorf("failed to load current term: %v", err)
    }

    // Read the index of the last log entry.
    lastIndex, err := logs.LastIndex()
    if err != nil {
        return nil, fmt.Errorf("failed to find last log: %v", err)
    }

    // Get the last log entry.
    var lastLog Log
    if lastIndex > 0 {
        if err = logs.GetLog(lastIndex, &lastLog); err != nil {
            return nil, fmt.Errorf("failed to get last log at index %d: %v", lastIndex, err)
        }
    }

    // Make sure we have a valid server address and ID.
    protocolVersion := conf.ProtocolVersion
    localAddr := trans.LocalAddr()
    localID := conf.LocalID

    // TODO (slackpad) - When we deprecate protocol version 2, remove this
    // along with the AddPeer() and RemovePeer() APIs.
    if protocolVersion < 3 && string(localID) != string(localAddr) {
        return nil, fmt.Errorf("when running with ProtocolVersion < 3, LocalID must be set to the network address")
    }

    // Buffer applyCh to MaxAppendEntries if the option is enabled
    applyCh := make(chan *logFuture)
    if conf.BatchApplyCh {
        applyCh = make(chan *logFuture, conf.MaxAppendEntries)
    }

    // Create Raft struct.
    r := &Raft{
        protocolVersion:       protocolVersion,
        applyCh:               applyCh,
        fsm:                   fsm,
        fsmMutateCh:           make(chan interface{}, 128),
        fsmSnapshotCh:         make(chan *reqSnapshotFuture),
        leaderCh:              make(chan bool, 1),
        localID:               localID,
        localAddr:             localAddr,
        logger:                logger,
        logs:                  logs,
        configurationChangeCh: make(chan *configurationChangeFuture),
        configurations:        configurations{},
        rpcCh:                 trans.Consumer(),
        snapshots:             snaps,
        userSnapshotCh:        make(chan *userSnapshotFuture),
        userRestoreCh:         make(chan *userRestoreFuture),
        shutdownCh:            make(chan struct{}),
        stable:                stable,
        trans:                 trans,
        verifyCh:              make(chan *verifyFuture, 64),
        configurationsCh:      make(chan *configurationsFuture, 8),
        bootstrapCh:           make(chan *bootstrapFuture),
        observers:             make(map[uint64]*Observer),
        leadershipTransferCh:  make(chan *leadershipTransferFuture, 1),
        leaderNotifyCh:        make(chan struct{}, 1),
        followerNotifyCh:      make(chan struct{}, 1),
        mainThreadSaturation:  newSaturationMetric([]string{"raft", "thread", "main", "saturation"}, 1*time.Second),
    }

    r.conf.Store(*conf)

    // Initialize as a follower.
    r.setState(Follower)

    // Restore the current term and the last log.
    r.setCurrentTerm(currentTerm)
    r.setLastLog(lastLog.Index, lastLog.Term)

    // Attempt to restore a snapshot if there are any.
    if err := r.restoreSnapshot(); err != nil {
        return nil, err
    }

    // Scan through the log for any configuration change entries.
    snapshotIndex, _ := r.getLastSnapshot()
    for index := snapshotIndex + 1; index <= lastLog.Index; index++ {
        var entry Log
        if err := r.logs.GetLog(index, &entry); err != nil {
            r.logger.Error("failed to get log", "index", index, "error", err)
            panic(err)
        }
        if err := r.processConfigurationLogEntry(&entry); err != nil {
            return nil, err
        }
    }
    r.logger.Info("initial configuration",
        "index", r.configurations.latestIndex,
        "servers", hclog.Fmt("%+v", r.configurations.latest.Servers))

    // Setup a heartbeat fast-path to avoid head-of-line
    // blocking where possible. It MUST be safe for this
    // to be called concurrently with a blocking RPC.
    trans.SetHeartbeatHandler(r.processHeartbeat)

    if conf.skipStartup {
        return r, nil
    }
    // Start the background work.
    r.goFunc(r.run)
    r.goFunc(r.runFSM)
    r.goFunc(r.runSnapshots)
    return r, nil
}
```

每个节点都会调用newRaft方法，用于创建raft角色，通过Config结构体传递配置参数，Config结构体如下

```
// Config provides any necessary configuration for the Raft server.
type Config struct {
    // ProtocolVersion allows a Raft server to inter-operate with older
    // Raft servers running an older version of the code. This is used to
    // version the wire protocol as well as Raft-specific log entries that
    // the server uses when _speaking_ to other servers. There is currently
    // no auto-negotiation of versions so all servers must be manually
    // configured with compatible versions. See ProtocolVersionMin and
    // ProtocolVersionMax for the versions of the protocol that this server
    // can _understand_.
    ProtocolVersion ProtocolVersion

    // HeartbeatTimeout specifies the time in follower state without contact
    // from a leader before we attempt an election.
    HeartbeatTimeout time.Duration

    // ElectionTimeout specifies the time in candidate state without contact
    // from a leader before we attempt an election.
    ElectionTimeout time.Duration

    // CommitTimeout specifies the time without an Apply operation before the
    // leader sends an AppendEntry RPC to followers, to ensure a timely commit of
    // log entries.
    // Due to random staggering, may be delayed as much as 2x this value.
    CommitTimeout time.Duration

    // MaxAppendEntries controls the maximum number of append entries
    // to send at once. We want to strike a balance between efficiency
    // and avoiding waste if the follower is going to reject because of
    // an inconsistent log.
    MaxAppendEntries int

    // BatchApplyCh indicates whether we should buffer applyCh
    // to size MaxAppendEntries. This enables batch log commitment,
    // but breaks the timeout guarantee on Apply. Specifically,
    // a log can be added to the applyCh buffer but not actually be
    // processed until after the specified timeout.
    BatchApplyCh bool

    // If we are a member of a cluster, and RemovePeer is invoked for the
    // local node, then we forget all peers and transition into the follower state.
    // If ShutdownOnRemove is set, we additional shutdown Raft. Otherwise,
    // we can become a leader of a cluster containing only this node.
    ShutdownOnRemove bool

    // TrailingLogs controls how many logs we leave after a snapshot. This is used
    // so that we can quickly replay logs on a follower instead of being forced to
    // send an entire snapshot. The value passed here is the initial setting used.
    // This can be tuned during operation using ReloadConfig.
    TrailingLogs uint64

    // SnapshotInterval controls how often we check if we should perform a
    // snapshot. We randomly stagger between this value and 2x this value to avoid
    // the entire cluster from performing a snapshot at once. The value passed
    // here is the initial setting used. This can be tuned during operation using
    // ReloadConfig.
    SnapshotInterval time.Duration

    // SnapshotThreshold controls how many outstanding logs there must be before
    // we perform a snapshot. This is to prevent excessive snapshotting by
    // replaying a small set of logs instead. The value passed here is the initial
    // setting used. This can be tuned during operation using ReloadConfig.
    SnapshotThreshold uint64

    // LeaderLeaseTimeout is used to control how long the "lease" lasts
    // for being the leader without being able to contact a quorum
    // of nodes. If we reach this interval without contact, we will
    // step down as leader.
    LeaderLeaseTimeout time.Duration

    // LocalID is a unique ID for this server across all time. When running with
    // ProtocolVersion < 3, you must set this to be the same as the network
    // address of your transport.
    LocalID ServerID

    // NotifyCh is used to provide a channel that will be notified of leadership
    // changes. Raft will block writing to this channel, so it should either be
    // buffered or aggressively consumed.
    NotifyCh chan<- bool

    // LogOutput is used as a sink for logs, unless Logger is specified.
    // Defaults to os.Stderr.
    LogOutput io.Writer

    // LogLevel represents a log level. If the value does not match a known
    // logging level hclog.NoLevel is used.
    LogLevel string

    // Logger is a user-provided logger. If nil, a logger writing to
    // LogOutput with LogLevel is used.
    Logger hclog.Logger

    // NoSnapshotRestoreOnStart controls if raft will restore a snapshot to the
    // FSM on start. This is useful if your FSM recovers from other mechanisms
    // than raft snapshotting. Snapshot metadata will still be used to initialize
    // raft's configuration and index values.
    NoSnapshotRestoreOnStart bool

    // skipStartup allows NewRaft() to bypass all background work goroutines
    skipStartup bool
}
```

通过LogStore结构体传递日志相关配置

```
// LogStore is used to provide an interface for storing
// and retrieving logs in a durable fashion.
type LogStore interface {
    // FirstIndex returns the first index written. 0 for no entries.
    FirstIndex() (uint64, error)

    // LastIndex returns the last index written. 0 for no entries.
    LastIndex() (uint64, error)

    // GetLog gets a log entry at a given index.
    GetLog(index uint64, log *Log) error

    // StoreLog stores a log entry.
    StoreLog(log *Log) error

    // StoreLogs stores multiple log entries.
    StoreLogs(logs []*Log) error

    // DeleteRange deletes a range of log entries. The range is inclusive.
    DeleteRange(min, max uint64) error
}
```

每一个字段都有详细的解释，可以多看看

校验Config中个字段的合法性

```
// Validate the configuration.
    if err := ValidateConfig(conf); err != nil {
        return nil, err
    }
```

创建raftlog实例，主要作用管理节点上的日志

```
    // Ensure we have a LogOutput.
    logger := conf.getOrCreateLogger()
```

创建raft实例

```
// Create Raft struct.
    r := &Raft{
        protocolVersion:       protocolVersion,
        applyCh:               applyCh,
        fsm:                   fsm,
        fsmMutateCh:           make(chan interface{}, 128),
        fsmSnapshotCh:         make(chan *reqSnapshotFuture),
        leaderCh:              make(chan bool, 1),
        localID:               localID,
        localAddr:             localAddr,
        logger:                logger,
        logs:                  logs,
        configurationChangeCh: make(chan *configurationChangeFuture),
        configurations:        configurations{},
        rpcCh:                 trans.Consumer(),
        snapshots:             snaps,
        userSnapshotCh:        make(chan *userSnapshotFuture),
        userRestoreCh:         make(chan *userRestoreFuture),
        shutdownCh:            make(chan struct{}),
        stable:                stable,
        trans:                 trans,
        verifyCh:              make(chan *verifyFuture, 64),
        configurationsCh:      make(chan *configurationsFuture, 8),
        bootstrapCh:           make(chan *bootstrapFuture),
        observers:             make(map[uint64]*Observer),
        leadershipTransferCh:  make(chan *leadershipTransferFuture, 1),
        leaderNotifyCh:        make(chan struct{}, 1),
        followerNotifyCh:      make(chan struct{}, 1),
        mainThreadSaturation:  newSaturationMetric([]string{"raft", "thread", "main", "saturation"}, 1*time.Second),
    }
```