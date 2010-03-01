<?php

$sql = "SELECT * FROM `ports` AS P, `juniAtmVp` AS J WHERE P.`device_id`  = '".$device['device_id']."' AND J.interface_id = P.interface_id";
echo("$sql");
$vp_data = mysql_query($sql);

if(mysql_affected_rows()) {


  $vp_cache = array();
  $vp_cache = snmpwalk_cache_multi_oid($device, "juniAtmVpStatsInCells", $vp_cache, "Juniper-UNI-ATM-MIB" , "+".$config['install_dir']."/mibs/junose");
  $vp_cache = snmpwalk_cache_multi_oid($device, "juniAtmVpStatsInPackets", $vp_cache, "Juniper-UNI-ATM-MIB" , "+".$config['install_dir']."/mibs/junose");
  $vp_cache = snmpwalk_cache_multi_oid($device, "juniAtmVpStatsInPacketOctets", $vp_cache, "Juniper-UNI-ATM-MIB" , "+".$config['install_dir']."/mibs/junose");
  $vp_cache = snmpwalk_cache_multi_oid($device, "juniAtmVpStatsInPacketErrors", $vp_cache, "Juniper-UNI-ATM-MIB" , "+".$config['install_dir']."/mibs/junose");
  $vp_cache = snmpwalk_cache_multi_oid($device, "juniAtmVpStatsOutCells", $vp_cache, "Juniper-UNI-ATM-MIB" , "+".$config['install_dir']."/mibs/junose");
  $vp_cache = snmpwalk_cache_multi_oid($device, "juniAtmVpStatsOutPackets", $vp_cache, "Juniper-UNI-ATM-MIB" , "+".$config['install_dir']."/mibs/junose");
  $vp_cache = snmpwalk_cache_multi_oid($device, "juniAtmVpStatsOutPacketOctets", $vp_cache, "Juniper-UNI-ATM-MIB" , "+".$config['install_dir']."/mibs/junose");
  $vp_cache = snmpwalk_cache_multi_oid($device, "juniAtmVpStatsOutPacketErrors", $vp_cache, "Juniper-UNI-ATM-MIB" , "+".$config['install_dir']."/mibs/junose");
  $vp_cache = $vp_cache[$device[device_id]];

  echo("Checking JunOSe ATM vps: ");

  while($vp=mysql_fetch_array($vp_data)) {

    echo(".");

    $oid = $vp['ifIndex'].".".$vp['vp_id'];

    if($debug) { echo("$oid "); }

    $t_vp = $vp_cache[$oid];

    $vp_update = $t_vp['juniAtmVpStatsInCells'].":".$t_vp['juniAtmVpStatsOutCells'];
    $vp_update .= ":".$t_vp['juniAtmVpStatsInPackets'].":".$t_vp['juniAtmVpStatsOutPackets'];
    $vp_update .= ":".$t_vp['juniAtmVpStatsInPacketOctets'].":".$t_vp['juniAtmVpStatsOutPacketOctets'];
    $vp_update .= ":".$t_vp['juniAtmVpStatsInPacketErrors'].":".$t_vp['juniAtmVpStatsOutPacketErrors'];

    $rrd  = $config['rrd_dir'] . "/" . $device['hostname'] . "/" . safename("vp-" . $vp['ifIndex'] . "-".$vp['vp_id'].".rrd");

   if($debug) { echo("$rrd "); }

    if (!is_file($rrd)) {
    
    rrdtool_create ($rrd, "--step 300 \
      DS:incells:DERIVE:600:0:125000000000 \
      DS:outcells:DERIVE:600:0:125000000000 \
      DS:inpackets:DERIVE:600:0:125000000000 \
      DS:outpackets:DERIVE:600:0:125000000000 \
      DS:inpacketoctets:DERIVE:600:0:125000000000 \
      DS:outpacketoctets:DERIVE:600:0:125000000000 \
      DS:inpacketerrors:DERIVE:600:0:125000000000 \
      DS:outpacketerrors:DERIVE:600:0:125000000000 \
      RRA:AVERAGE:0.5:1:600 \
      RRA:AVERAGE:0.5:6:700 \
      RRA:AVERAGE:0.5:24:775 \
      RRA:AVERAGE:0.5:288:797 \
      RRA:MIN:0.5:1:600 \
      RRA:MIN:0.5:6:700 \
      RRA:MIN:0.5:24:775 \
      RRA:MIN:0.5:288:797 \
      RRA:MAX:0.5:1:600 \
      RRA:MAX:0.5:6:700 \
      RRA:MAX:0.5:24:775 \
      RRA:MAX:0.5:288:797");
    }

    rrdtool_update($rrd,"N:$vp_update");

  }

  echo("\n");

  unset($vp_cache);

}

?>
