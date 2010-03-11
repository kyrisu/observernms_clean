#!/usr/bin/php
<?php

include("includes/defaults.inc.php");
include("config.php");
include("includes/functions.php");

$poller_start = utime();
echo("Observer Poller v".$config['version']."\n\n");

$options = getopt("h:t:i:n:d::a::");

if ($options['h'] == "odd") {
  $where = "AND MOD(device_id,2) = 1";  $doing = $options['h'];
} elseif ($options['h'] == "even") {
  $where = "AND MOD(device_id,2) = 0";  $doing = $options['h'];
} elseif ($options['h'] == "all") {
  $where = " ";  $doing = "all";
} elseif ($options['h']) {
  $where = "AND `device_id` = '".$options['h']."'";  $doing = "Host ".$options['h'];
} elseif ($options['i'] && isset($options['n'])) {
  $where = "AND MOD(device_id,".$options['i'].") = '" . $options['n'] . "'";  $doing = "Proc ".$options['n'] ."/".$options['i'];
}

if (!$where) {
  echo("-h <device id>                Poll single device\n");
  echo("-h odd                        Poll odd numbered devices  (same as -i 2 -n 0)\n");
  echo("-h even                       Poll even numbered devices (same as -i 2 -n 1)\n");
  echo("-h all                        Poll all devices\n\n");
  echo("-i <instances> -n <number>    Poll as instance <number> of <instances>\n");
  echo("                              Instances start at 0. 0-3 for -n 4\n\n");
  echo("-d                            Enable some debugging output\n");
  echo("\n");
  echo("No polling type specified!\n");
  exit;
 }

if (isset($options['d'])) { echo("DEBUG!\n"); $debug = 1; }


echo("Starting polling run:\n\n");
$polled_devices = 0;
$device_query = mysql_query("SELECT * FROM `devices` WHERE `ignore` = 0 AND `disabled` = 0 $where  ORDER BY `device_id` ASC");
while ($device = mysql_fetch_array($device_query)) {
  $status = 0; unset($array);
  $device_start = utime();  // Start counting device poll time
  echo($device['hostname'] . " ".$device['device_id']." ".$device['os']." ");
  if ($os_groups[$device[os]]) {$device['os_group'] = $os_groups[$device[os]]; echo "(".$device['os_group'].")";}
  echo("\n");

  unset($poll_update); unset($poll_update_query); unset($poll_separator); unset($version); unset($uptime); unset($features); 
  unset($sysLocation); unset($hardware); unset($sysDescr); unset($sysContact); unset($sysName);

  $pingable = isPingable($device['hostname']);

  $host_rrd = $config['rrd_dir'] . "/" . $device['hostname'];

  if (!is_dir($host_rrd)) { mkdir($host_rrd); echo("Created directory : $host_rrd\n"); }

  if ($pingable) { echo("Pings : yes :)\n"); } else { echo("Pings : no :(\n"); }

  if ($pingable) {
    if (isSNMPable($device['hostname'], $device['community'], $device['snmpver'], $device['port'])) { 
      echo("SNMP : yes :)\n"); 
      $status = "1";
    } else { 
      echo("SNMP : no :(\n"); 
      $status = "0";
    }
  } else { 
    $status = "0";
  }

  if ($status) { 
    $snmp_cmd =  $config['snmpget'] . " -m SNMPv2-MIB -O qv -" . $device['snmpver'] . " -c " . $device['community'] . " " .  $device['hostname'].":".$device['port'];
    $snmp_cmd .= " sysUpTime.0 sysLocation.0 sysContact.0 sysName.0 HOST-RESOURCES-MIB::hrSystemUptime.0";
    $snmpdata = shell_exec($snmp_cmd);
    #$snmpdata = preg_replace("/^.*IOS/","", $snmpdata);
    $snmpdata = trim($snmpdata);
    $snmpdata = str_replace("\"", "", $snmpdata);
    list($sysUptime, $sysLocation, $sysContact, $sysName, $hrSystemUptime) = explode("\n", $snmpdata);
    $sysDescr = trim(shell_exec($config['snmpget'] . " -m SNMPv2-MIB -O qv -" . $device['snmpver'] . " -c " . $device['community'] . " " .  $device['hostname'].":".$device['port'] . " sysDescr.0"));
    $sysName = strtolower($sysName);

    if ($hrSystemUptime)
    {
      #HOST-RESOURCES-MIB::hrSystemUptime.0 = Timeticks: (63050465) 7 days, 7:08:24.65
      $hrSystemUptime = str_replace("(", "", $hrSystemUptime);
      $hrSystemUptime = str_replace(")", "", $hrSystemUptime); 
      list($days,$hours, $mins, $secs) = explode(":", $hrSystemUptime);
      list($secs, $microsecs) = explode(".", $secs);
      $hours = $hours + ($days * 24);
      $mins = $mins + ($hours * 60);
      $secs = $secs + ($mins * 60);
      $uptime = $secs;
      if ($device['os'] == "windows") { $uptime /= 10; }
    }
    else 
    { 
      #SNMPv2-MIB::sysUpTime.0 = Timeticks: (2542831) 7:03:48.31
      $sysUptime = str_replace("(", "", $sysUptime);
      $sysUptime = str_replace(")", "", $sysUptime); 
      list($days, $hours, $mins, $secs) = explode(":", $sysUptime);
      list($secs, $microsecs) = explode(".", $secs);
      $hours = $hours + ($days * 24);
      $mins = $mins + ($hours * 60);
      $secs = $secs + ($mins * 60);
      $uptime = $secs;
    }

    if ($uptime) 
    {
      if ( $uptime < $device['uptime'] ) {
        notify($device,"Device rebooted: " . $device['hostname'],  "Device Rebooted : " . $device['hostname'] . " " . formatUptime($uptime) . " ago.");
        eventlog('Device rebooted', $device['device_id']);
      }
  
      $uptimerrd = $config['rrd_dir'] . "/" . $device['hostname'] . "/uptime.rrd";
  
      if (!is_file($uptimerrd)) 
      {
        $woo = shell_exec($config['rrdtool'] . " create $uptimerrd \
        DS:uptime:GAUGE:600:0:U \
        RRA:AVERAGE:0.5:1:600 \
        RRA:AVERAGE:0.5:6:700 \
        RRA:AVERAGE:0.5:24:775 \
        RRA:AVERAGE:0.5:288:797");
      }
      rrdtool_update($uptimerrd, "N:$uptime");

      $poll_update .= $poll_separator . "`uptime` = '$uptime'";
      $poll_separator = ", ";
    } 

    if ( $device['status'] != $status ) 
    {
      $poll_update .= $poll_separator . "`status` = '$status'";
      $poll_separator = ", ";
      mysql_query("UPDATE `devices` SET `status` = '".$status."' WHERE `device_id` = '".$device['device_id']."'");
      mysql_query("INSERT INTO alerts (importance, device_id, message) VALUES ('0', '" . $device['device_id'] . "', 'Device is " . ($status == '1' ? 'up' : 'down') . "')");
      eventlog('Device status changed to ' . ($status == '1' ? 'Up' : 'Down'), $device['device_id']);
    }

    if (is_file($config['install_dir'] . "/includes/polling/device-".$device['os'].".inc.php")) {
      /// OS Specific
      include($config['install_dir'] . "/includes/polling/device-".$device['os'].".inc.php");
    }elseif ($device['os_group'] && is_file($config['install_dir'] . "/includes/polling/device-".$device['os_group'].".inc.php")) {
      /// OS Group Specific
      include($config['install_dir'] . "/includes/polling/device-".$device['os_group'].".inc.php");
    }else{
      echo("Generic :(");
    }

    $sysLocation = str_replace("\"","", $sysLocation); 
  
    include("includes/polling/temperatures.inc.php");
    include("includes/polling/fanspeeds.inc.php");
    include("includes/polling/voltages.inc.php");
    include("includes/polling/processors.inc.php");
    include("includes/polling/mempools.inc.php");
    include("includes/polling/storage.inc.php");
    include("includes/polling/device-netstats.inc.php");
    include("includes/polling/ipSystemStats.inc.php");
    include("includes/polling/ports.inc.php");
    include("includes/polling/cisco-mac-accounting.inc.php");
    include("includes/polling/bgpPeer.inc.php");

  unset( $update ) ;
  unset( $seperator) ;

  if ( $sysContact && $sysContact != $device['sysContact'] ) {
    $poll_update .= $poll_separator . "`sysContact` = '".mres($sysContact)."'";
    $poll_separator = ", ";
    eventlog("Contact -> $sysContact", $device['device_id']);
  }

  if ( $sysName && $sysName != $device['sysName'] ) {
    $poll_update .= $poll_separator . "`sysName` = '$sysName'";
    $poll_separator = ", ";
    eventlog("sysName -> $sysName", $device['device_id']);
  }

  if ( $sysDescr && $sysDescr != $device['sysDescr'] ) {
    $poll_update .= $poll_separator . "`sysDescr` = '$sysDescr'";
    $poll_separator = ", ";
    eventlog("sysDescr -> $sysDescr", $device['device_id']);
  }

  if ( $sysLocation && $device['location'] != $sysLocation ) {
    $poll_update .= $poll_separator . "`location` = '$sysLocation'";
    $poll_separator = ", ";
    eventlog("Location -> $sysLocation", $device['device_id']);
  }

  if ( $version && $device['version'] != $version ) {
    $poll_update .= $poll_separator . "`version` = '$version'";
    $poll_separator = ", ";
    eventlog("OS Version -> $version", $device['device_id']);
  }

  if ( $features && $features != $device['features'] ) {
    $poll_update .= $poll_separator . "`features` = '$features'";
    $poll_separator = ", ";
    eventlog("OS Features -> $features", $device['device_id']);
  }

  if ( $hardware && $hardware != $device['hardware'] ) {
    $poll_update .= $poll_separator . "`hardware` = '$hardware'";
    $poll_separator = ", ";
    eventlog("Hardware -> $hardware", $device['device_id']);
  }

  $poll_update .= $poll_separator . "`last_polled` = NOW()";
  $poll_separator = ", ";
  $polled_devices++;
  echo("\n");
  } 

  if ($poll_update) {
    $poll_update_query  = "UPDATE `devices` SET ";
    $poll_update_query .= $poll_update;
    $poll_update_query .= " WHERE `device_id` = '" . $device['device_id'] . "'";
    echo("Updating " . $device['hostname'] . " - $poll_update_query \n");
    $poll_update_result = mysql_query($poll_update_query);
  } else {
    echo("No Changes to " . $device['hostname'] . "\n");
  }

  $device_end = utime(); $device_run = $device_end - $device_start; $device_time = substr($device_run, 0, 5);
  echo("Polled in $device_time seconds\n");

}   

$poller_end = utime(); $poller_run = $poller_end - $poller_start; $poller_time = substr($poller_run, 0, 5);

$string = $argv[0] . " $doing " .  date("F j, Y, G:i") . " - $polled_devices devices polled in $poller_time secs";
if ($debug) echo("$string\n");
shell_exec("echo '".$string."' >> ".$config['install_dir']."/observer.log");


?>
