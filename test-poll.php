#!/usr/bin/php
<?php

include("includes/defaults.inc.php");
include("config.php");
include("includes/functions.php");

$debug =1;

$poller_start = utime();

### Observer Device Polling Test

echo("Observer v".$config['version']." Discovery\n\n");

if($argv[1] == "--device" && $argv[2]) {
  $where = "AND `device_id` = '".$argv[2]."'";
} elseif ($argv[1] == "--os") {
  $where = "AND `os` = '".$argv[2]."'";
} elseif ($argv[1] == "--odd") {
  $where = "AND MOD(device_id,2) = 1";
} elseif ($argv[1] == "--even") {
  $where = "AND MOD(device_id,2) = 0";
} elseif ($argv[1] == "--all") {
  $where = "";
} else {
  echo("--device <device id>    Poll single device\n");
  echo("--os <os string>        Poll all devices of a given OS\n");
  echo("--all                   Poll all devices\n\n");
  echo("No polling type specified!\n");
  exit;
}

if ($argv[2] == "--type" && $argv[3]) {
  $type = $argv[3];
} elseif ($argv[3] == "--type" && $argv[4]) {
  $type = $argv[4];
} else {
  echo("Require valid polling type.\n");
  exit;
}

$devices_polled = 0;

$device_query = mysql_query("SELECT * FROM `devices` WHERE status = '1' $where ORDER BY device_id DESC");
while ($device = mysql_fetch_array($device_query)) {

  echo("\n" . $device['hostname'] ."\n");
  $host_rrd = $config['rrd_dir'] . "/" . $device['hostname'];
  $where = "WHERE device_id = '" . $device['device_id'] . "' AND deleted = '0'";
  include("includes/polling/".$type.".inc.php");

  echo("\n"); $devices_polled++;

  unset($array);

}

$poller_end = utime(); $poller_run = $poller_end - $poller_start; $poller_time = substr($poller_run, 0, 5);

$string = $argv[0] . " " . date("F j, Y, G:i") . " - $i devices polled in $poller_time secs";
echo("$string\n");
shell_exec("echo '".$string."' >> /tmp/observer.log");

?>
