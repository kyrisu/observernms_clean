<?php

/// HOST-RESOURCES-MIB
//  Generic System Statistics

$hrSystem_rrd   = $config['rrd_dir'] . "/" . $device['hostname'] . "/hrSystem.rrd";
$hrSystem_cmd  = $config['snmpget'] ." -m HOST-RESOURCES-MIB -O qv -" . $device['snmpver'] . " -c " . $device['community'] . " " . $device['hostname'].":".$device['port'];
$hrSystem_cmd .= " hrSystemProcesses.0 hrSystemNumUsers.0 hrMemorySize.0";
$hrSystem  = `$hrSystem_cmd`;
list ($hrSystemProcesses, $hrSystemNumUsers, $hrMemorySize) = explode("\n", $hrSystem);

if (!is_file($hrSystem_rrd)) {
  shell_exec($config['rrdtool'] . " create $hrSystem_rrd \
    --step 300 \
    DS:users:GAUGE:600:0:U \
    DS:procs:GAUGE:600:0:U \
    DS:uptime:GAUGE:600:0:U \
    RRA:AVERAGE:0.5:1:800 \
    RRA:AVERAGE:0.5:6:800 \
    RRA:AVERAGE:0.5:24:800 \
    RRA:AVERAGE:0.5:288:800 \
    RRA:MAX:0.5:1:800 \
    RRA:MAX:0.5:6:800 \
    RRA:MAX:0.5:24:800 \
    RRA:MAX:0.5:288:800");
}

rrdtool_update($hrSystem_rrd,  "N:$hrSystemNumUsers:$hrSystemProcesses:$uptime");


?>
