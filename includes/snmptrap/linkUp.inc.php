<?php

$interface = mysql_fetch_array(mysql_query("SELECT * FROM `interfaces` WHERE `device_id` = '".$device['device_id']."' AND `ifIndex` = '".$entry[2]."'"));

if(!$interface) {exit;}

    $ifOperStatus = "up";
    $ifAdminStatus = "up";

    log_event("SNMP Trap: linkUp $ifAdminStatus/$ifOperStatus " . $interface['ifDescr'], $device, "interface", $interface['interface_id']);

    if($ifAdminStatus != $interface['ifAdminStatus']) {
      log_event("Interface Enabled : " . $interface['ifDescr'] . " (TRAP)", $device, "interface", $interface['interface_id']);
      mysql_query("UPDATE `interfaces` SET ifAdminStatus = 'up' WHERE `interface_id` = '".$interface['interface_id']."'");
    }
    if($ifOperStatus != $interface['ifOperStatus']) {
      log_event("Interface went Up : " . $interface['ifDescr'] . " (TRAP)", $device, "interface", $interface['interface_id']);
      mysql_query("UPDATE `interfaces` SET ifOperStatus = 'up' WHERE `interface_id` = '".$interface['interface_id']."'");

    }

?>
