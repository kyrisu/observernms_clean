<?php

  function delete_port($interface_id) {
     $ipaddrs = mysql_query("SELECT * FROM `ipaddr` WHERE `interface_id` = '$interface_id'");
     while($ipaddr = mysql_fetch_array($ipaddrs)) {
       echo("<div style='padding-left:8px; font-weight: normal;'>Deleting IPv4 address " . $ipaddr['addr'] . "/" . $ipaddr['cidr'] );
         mysql_query("DELETE FROM addr WHERE id = '".$addr['id']."'");
       echo("</div>");
     }

     $ip6addr = mysql_query("SELECT * FROM `ip6addr` WHERE `interface_id` = '$interface_id'");
     while($ip6addr = mysql_fetch_array($ip6addrs)) {
       echo("<div style='padding-left:8px; font-weight: normal;'>Deleting IPv6 address " . $ip6addr['ip6_comp_addr'] . "/" . $ip6addr['ip6_prefixlen'] );
         mysql_query("DELETE FROM ip6addr WHERE ip6_addr_id = '".$ip6addr['ip6_addr_id']."'");
       echo("</div>");
     }

     $ip6addr = mysql_query("SELECT * FROM `ip6addr` WHERE `interface_id` = '$interface_id'");
     while($ip6addr = mysql_fetch_array($ip6addrs)) {
       echo("<div style='padding-left:8px; font-weight: normal;'>Deleting IPv6 address " . $ip6addr['ip6_comp_addr'] . "/" . $ip6addr['ip6_prefixlen'] );
         mysql_query("DELETE FROM ip6addr WHERE ip6_addr_id = '".$ip6addr['ip6_addr_id']."'");
       echo("</div>");
     }

     mysql_query("DELETE FROM `pseudowires` WHERE `interface_id` = '$interface_id'");
     mysql_query("DELETE FROM `mac_accounting` WHERE `interface_id` = '$interface_id'");
     mysql_query("DELETE FROM `links` WHERE `local_interface_id` = '$interface_id'");
     mysql_query("DELETE FROM `links` WHERE `remote_interface_id` = '$interface_id'");
     mysql_query("DELETE FROM `interfaces_perms` WHERE `interface_id` = '$interface_id'");
     mysql_query("DELETE FROM `interfaces` WHERE `interface_id` = '$interface_id'");
  }

  $ports = mysql_query("SELECT * FROM `interfaces` WHERE `deleted` = '1'");
  while($port = mysql_fetch_array($ports)) {
    echo("<div style='font-weight: bold;'>Deleting port " . $port['interface_id'] . " - " . $port['ifDescr'] );
    delete_port($port['interface_id']);
    echo("</div>");
  }

?>
