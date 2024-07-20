```
# coding=utf-8

"""
The NetworkCollector class collects metrics on network interface usage
using /proc/net/dev.

#### Dependencies

 * /proc/net/dev

"""
import socket
import time

import diamond.collector
from diamond.collector import str_to_bool
import diamond.convertor
import os
import re

import subprocess

pattern = r'\s*(\w+)\s+(\d+)\s+(\d+)\s+(\d+K)\s+(\d+K)\s*'

def exec_cmd(cmd, timeout=30):
    """
    执行系统命令
    :return:
    """
    cmd = "timeout {} {}".format(timeout, cmd)
    ret = subprocess.Popen(args=cmd, shell=True, stdout=subprocess.PIPE, stderr=subprocess.PIPE,
                           bufsize=4096)
    stdout, stderr = ret.communicate()
    code = ret.poll()

    return code, stdout, stderr

def extract_bandwidth(data_rate):
    bandwidth, unit = re.match(r'(\d+)(\w)?', data_rate).groups()
    return int(bandwidth), unit

def convert_to_bytes(bandwidth, unit):
    units = {'K': 1024, 'M': 1024**2, 'G': 1024**3}
    return bandwidth * units.get(unit, 1)

try:
    import psutil
except ImportError:
    psutil = None

_key_item = ['rx_byte', 'tx_byte']


class NetworkCollector(diamond.collector.Collector):

    PROC = '/proc/net/dev'

    def get_default_config_help(self):
        config_help = super(NetworkCollector, self).get_default_config_help()
        config_help.update({
            'interfaces': 'List of interface types to collect',
            'greedy': 'Greedy match interfaces',
        })
        return config_help

    def get_default_config(self):
        """
        Returns the default collector settings
        """
        config = super(NetworkCollector, self).get_default_config()
        config.update({
            'path': 'network',
            'interfaces': ['eth', 'bond', 'em', 'p1p', 'eno', 'enp', 'ens',
                           'enx'],
            'byte_unit': ['bit', 'byte'],
            'greedy': 'true',
        })
        return config

    def _collect(self):
        """
        Collect network interface stats.
        """

        # Initialize results
        results = {}

        if os.access(self.PROC, os.R_OK):

            # Open File
            file = open(self.PROC)
            # Build Regular Expression
            greed = ''
            if str_to_bool(self.config['greedy']):
                greed = r'\S*'

            exp = (('^(?:\s*)((?:%s)%s):(?:\s*)' +
                    r'(?P<rx_bytes>\d+)(?:\s*)' +
                    r'(?P<rx_packets>\w+)(?:\s*)' +
                    r'(?P<rx_errors>\d+)(?:\s*)' +
                    r'(?P<rx_drop>\d+)(?:\s*)' +
                    r'(?P<rx_fifo>\d+)(?:\s*)' +
                    r'(?P<rx_frame>\d+)(?:\s*)' +
                    r'(?P<rx_compressed>\d+)(?:\s*)' +
                    r'(?P<rx_multicast>\d+)(?:\s*)' +
                    r'(?P<tx_bytes>\d+)(?:\s*)' +
                    r'(?P<tx_packets>\w+)(?:\s*)' +
                    r'(?P<tx_errors>\d+)(?:\s*)' +
                    r'(?P<tx_drop>\d+)(?:\s*)' +
                    r'(?P<tx_fifo>\d+)(?:\s*)' +
                    r'(?P<tx_colls>\d+)(?:\s*)' +
                    r'(?P<tx_carrier>\d+)(?:\s*)' +
                    '(?P<tx_compressed>\d+)(?:.*)$') %
                   (('|'.join(self.config['interfaces'])), greed))
            reg = re.compile(exp)
            # Match Interfaces
            for line in file:
                match = reg.match(line)
                if match:
                    device = match.group(1)
                    results[device] = match.groupdict()
            # Close File
            file.close()
        else:
            if not psutil:
                self.log.error('Unable to import psutil')
                self.log.error('No network metrics retrieved')
                return None

            network_stats = psutil.network_io_counters(True)
            for device in network_stats.keys():
                network_stat = network_stats[device]
                results[device] = {}
                results[device]['rx_bytes'] = network_stat.bytes_recv
                results[device]['tx_bytes'] = network_stat.bytes_sent
                results[device]['rx_packets'] = network_stat.packets_recv
                results[device]['tx_packets'] = network_stat.packets_sent
        for device in results:
            stats = results[device]
            for s, v in stats.items():
                # Get Metric Name
                metric_name = '.'.join([device, s])
                # Get Metric Value
                #if v=='0':
                #    continue
                metric_value = self.derivative(metric_name,
                                               long(v),
                                               diamond.collector.MAX_COUNTER)
                # Convert rx_bytes and tx_bytes
                if s == 'rx_bytes' or s == 'tx_bytes':
                    convertor = diamond.convertor.binary(value=metric_value,
                                                         unit='byte')
                    print(convertor)
                    for u in self.config['byte_unit']:
                        # Public Converted Metric

                        for i in _key_item:
                            if i in metric_name.replace('bytes', u):

                                self.publish(metric_name.replace('bytes', u),
                                             convertor.get(unit=u), 2)
                else:
                    # Publish Metric Derivative

                    for i in _key_item:
                        if i in metric_name:
                            self.publish(metric_name, metric_value)

        return None

    def collect(self):

        cmd = "ifstat"
        code, out, err = exec_cmd(cmd)
        if not code and out:
            lines = out.strip().split('\n')
            eths = []
            eths_line_dict = {}
            for i in range(3, len(lines), 2):
                lines_arr = lines[i].split()
                eths.append(lines_arr[0])
                eths_line_dict[lines_arr[0]] = lines_arr[1:]
            date = time.time()
            hostname = socket.gethostname()
            for eth in eths:
                sE = ''
                try:
                    with open('/sys/class/net/{}/speed'.format(eth), 'r') as f:
                        speed = int(f.read().strip())

                    sE = 'GE' if speed == 1000 else "10-GE"
                except Exception:
                    pass

                ip_info = []
                if not psutil:
                    self.log.error('Unable to import psutil')
                    return None
                network_info = psutil.net_if_addrs()
                default_value = []
                for info in network_info.get(eth, default_value):
                    if info:
                        # ipv4 & ipv6
                        if info.family == 2 or info.family == 10:
                            ip_info.append(info.address.split('%')[0])
                rx_data_rate = eths_line_dict[eth][3]
                tx_data_rate =  eths_line_dict[eth][5]
                rx_bandwidth, rx_unit = extract_bandwidth(rx_data_rate)
                tx_bandwidth, tx_unit = extract_bandwidth(tx_data_rate)
                rx_bandwidth = convert_to_bytes(rx_bandwidth, rx_unit)
                tx_bandwidth = convert_to_bytes(tx_bandwidth, tx_unit)

                self.publish_metric({
                    "path": "network.{}&{}({} {})".format(hostname, eth, ",".join(ip_info), sE),
                    "timestamp": int(date),
                    'value': {
                        'w_traffic': rx_bandwidth,
                        'r_traffic': tx_bandwidth,
                    }
                })



nc = NetworkCollector()
nc.collect()

```