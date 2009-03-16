<?

   echo("Polling SNOM device...\n");

   // Get SNOM specific version string from silly SNOM location. Silly SNOM!

   $cmd      = "snmpget -O qv -" . $device['snmpver'] . " -c " . $device['community'] . " " . $device['hostname'].":".$device['port'] . " 1.3.6.1.2.1.7526.2.4";
   $sysDescr = `$cmd`;
   $sysDescr = str_replace("-", " ", $sysDescr);
   $sysDescr = str_replace("\"", "", $sysDescr);
   list($hardware, $features, $version) = explode(" ", $sysDescr);

   // Get data for calls and network from SNOM specific SNMP OIDs.

   $cmda = "snmpget -O qv -" . $device['snmpver'] . " -c " . $device['community'] . " " . $device['hostname'].":".$device['port'] . " 1.3.6.1.2.1.7526.2.1.1 1.3.6.1.2.1.7526.2.1.2 1.3.6.1.2.1.7526.2.2.1 1.3.6.1.2.1.7526.2.2.2";
   $cmdb = "snmpget -O qv -" . $device['snmpver'] . " -c " . $device['community'] . " " . $device['hostname'].":".$device['port'] . " 1.3.6.1.2.1.7526.2.5 1.3.6.1.2.1.7526.2.6";
   echo($cmda);
   $snmpdata = `$cmda`;
   $snmpdatab = `$cmdb`;

   list($rxbytes, $rxpkts, $txbytes, $txpkts) = explode("\n", $snmpdata);
   list($calls, $registrations) = explode("\n", $snmpdatab);
   $txbytes = 0 - $txbytes * 8;
   $rxbytes = 0 - $rxbytes * 8;
   echo("$rxbytes, $rxpkts, $txbytes, $txpkts, $calls, $registrations");

   $rrdfile = $config['rrd_dir'] . "/" . $device['hostname'] . "/data.rrd";
   if(!is_file($rrdfile)) {
    $woo = `rrdtool create $rrdfile \
      DS:INOCTETS:COUNTER:600:U:100000000000 \
      DS:OUTOCTETS:COUNTER:600:U:10000000000 \
      DS:INPKTS:COUNTER:600:U:10000000000 \
      DS:OUTPKTS:COUNTER:600:U:10000000000 \
      DS:CALLS:COUNTER:600:U:10000000000 \
      DS:REGISTRATIONS:COUNTER:600:U:10000000000 \
      RRA:AVERAGE:0.5:1:600 \
      RRA:AVERAGE:0.5:6:700 \
      RRA:AVERAGE:0.5:24:775 \
      RRA:AVERAGE:0.5:288:797 \
      RRA:MAX:0.5:1:600 \
      RRA:MAX:0.5:6:700 \
      RRA:MAX:0.5:24:775 \
      RRA:MAX:0.5:288:797`;
   }

   $rrdupdate = "N:$rxbytes:$txbytes:$rxpkts:$rxbytes:$calls:$registrations";
   $ret = rrdtool_update("$rrdfile", $rrdupdate);

?>
