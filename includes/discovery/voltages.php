<?php
$id = $device['device_id'];
$hostname = $device['hostname'];
$community = $device['community'];
$snmpver = $device['snmpver'];
$port = $device['port'];

echo("Voltages : ");

## LMSensors Voltages (returned as 10,000ths of a volt.)

if ($device['os'] == "linux") 
{
  $oids = snmp_walk($device, "lmVoltSensorsDevice", "-OsqnU", "LM-SENSORS-MIB");
  if ($debug) { echo($oids."\n"); }
  if ($oids) echo("LM-SENSORS ");
  $precision = 1000;
  $type = "lmsensors";
  foreach(explode("\n", $oids) as $data) 
  {
    $data = trim($data);
    if ($data) 
    {
      list($oid,$descr) = explode(" ", $data,2);
      $split_oid = explode('.',$oid);
      $index = $split_oid[count($split_oid)-1];
      $oid  = ".1.3.6.1.4.1.2021.13.16.4.1.3." . $index;
      $current = snmp_get($device, $oid, "-Oqv", "LM-SENSORS-MIB") / $precision;
      discover_volt($device, $oid, $index, $type, $descr, $precision, NULL, NULL, $current);
      $volt_exists[$type][$index] = 1;
    }
  }
}


## Supermicro Voltages
if ($device['os'] == "linux") 
{
  $oids = snmp_walk($device, "1.3.6.1.4.1.10876.2.1.1.1.1.3", "-OsqnU", "SUPERMICRO-HEALTH-MIB");
  if ($debug) { echo($oids."\n"); }
  $oids = trim($oids);
  if ($oids) echo("Supermicro ");
  $type = "supermicro";
  $precision = "1000";
  foreach(explode("\n", $oids) as $data) 
  {
    $data = trim($data);
    if ($data) 
    {
      list($oid,$kind) = explode(" ", $data);
      $split_oid = explode('.',$oid);
      $index = $split_oid[count($split_oid)-1];
      if ($kind == 1)
      {
        $volt_oid     = "1.3.6.1.4.1.10876.2.1.1.1.1.4.".$index;
        $descr_oid    = "1.3.6.1.4.1.10876.2.1.1.1.1.2.".$index;
        $monitor_oid  = "1.3.6.1.4.1.10876.2.1.1.1.1.10.".$index;
        $limit_oid    = "1.3.6.1.4.1.10876.2.1.1.1.1.6.".$index;
        $lowlimit_oid = "1.3.6.1.4.1.10876.2.1.1.1.1.5.".$index;

        $descr    = snmp_get($device, $descr_oid, "-Oqv", "SUPERMICRO-HEALTH-MIB");
        $current  = snmp_get($device, $volt_oid, "-Oqv", "SUPERMICRO-HEALTH-MIB") / $precision;
        $limit    = snmp_get($device, $limit_oid, "-Oqv", "SUPERMICRO-HEALTH-MIB") / $precision;
	$lowlimit = snmp_get($device, $lowlimit_oid, "-Oqv", "SUPERMICRO-HEALTH-MIB") / $precision;
        $monitor  = snmp_get($device, $monitor_oid, "-Oqv", "SUPERMICRO-HEALTH-MIB");
        $descr    = trim(str_ireplace("Voltage", "", $descr));

        if ($monitor == 'true')
        {
          echo discover_volt($device, $volt_oid, $index, $type, $descr, $precision, $lowlimit, $limit, $current);
          $volt_exists[$type][$index] = 1;
        }
      }
    }
  }
}

## Delete removed sensors

if($debug) { print_r($volt_exists); }

$sql = "SELECT * FROM voltage WHERE device_id = '".$device['device_id']."'";
if ($query = mysql_query($sql))
{
  while ($test_volt = mysql_fetch_array($query))
  {
    $index = $test_volt['volt_index'];
    $type = $test_volt['volt_type'];
    if($debug) { echo("$type -> $index\n"); }
    if(!$volt_exists[$type][$index]) {
      echo("-");
      mysql_query("DELETE FROM `voltage` WHERE volt_id = '" . $test_volt['volt_id'] . "'");
    }
  }
}

unset($fan_exists); echo("\n");

?>
