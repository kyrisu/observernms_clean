<?php

function generate_front_box ($type, $content) {
 echo("<div style='float: left; padding: 5px; width: 135px; margin: 0px;'>
  <b class='box-".$type."'>
  <b class='box-".$type."1'><b></b></b>
  <b class='box-".$type."2'><b></b></b>
  <b class='box-".$type."3'></b>
  <b class='box-".$type."4'></b>
  <b class='box-".$type."5'></b></b>
  <div class='box-".$type."fg' style='height: 90px;'>
   ".$content."
  </div>
  <b class='box-".$type."'>
  <b class='box-".$type."5'></b>
  <b class='box-".$type."4'></b>
  <b class='box-".$type."3'></b>
  <b class='box-".$type."2'><b></b></b>
  <b class='box-".$type."1'><b></b></b></b>
 </div>");
}

echo("<div style='padding: 3px 10px; background: #fff;'>");

if($_SESSION['userlevel'] == '10') {
$sql = mysql_query("SELECT * FROM `devices` WHERE `status` = '0' AND `ignore` = '0'");
} else {
$sql = mysql_query("SELECT * FROM `devices` AS D, devices_perms AS P WHERE D.device_id = P.device_id AND P.user_id = '" . $_SESSION['user_id'] . "' AND D.status = '0' AND D.ignore = '0'");
}
while($device = mysql_fetch_array($sql)){

      generate_front_box("alert", "<center><strong>".generatedevicelink($device, shorthost($device['hostname']))."</strong><br />
      <span style='font-size: 14px; font-weight: bold; margin: 5px; color: #c00;'>Device Down</span> 
      <span class=body-date-1>".truncate($device['location'], 20)."</span>
      </center>");


}

if($_SESSION['userlevel'] == '10') {
$sql = mysql_query("SELECT * FROM `interfaces` AS I, `devices` AS D WHERE I.device_id = D.device_id AND ifOperStatus = 'down' AND ifAdminStatus = 'up' AND D.ignore = '0' AND I.ignore = '0'");
} else {
$sql = mysql_query("SELECT * FROM `interfaces` AS I, `devices` AS D, devices_perms AS P WHERE D.device_id = P.device_id AND P.user_id = '" . $_SESSION['user_id'] . "' AND  I.device_id = D.device_id AND ifOperStatus = 'down' AND ifAdminStatus = 'up' AND D.ignore = '0' AND I.ignore = '0'");
}
while($interface = mysql_fetch_array($sql)){
  $interface = ifNameDescr($interface);
  generate_front_box("warn", "<center><strong>".generatedevicelink($interface, shorthost($interface['hostname']))."</strong><br />
      <span style='font-size: 14px; font-weight: bold; margin: 5px; color: #c00;'>Port Down</span><br />
<!--      <img src='graph.php?type=bits&if=".$interface['interface_id']."&from=$day&to=$now&width=100&height=32' /> -->
      <strong>".generateiflink($interface, truncate(makeshortif($interface['label']),13,''))."</strong> <br />
      <span class=body-date-1>".truncate($interface['ifAlias'], 20, '')."</span>
      </center>");

}

/* FIXME service permissions? seem nonexisting now.. */
$sql = mysql_query("SELECT * FROM `services` AS S, `devices` AS D WHERE S.service_host = D.device_id AND service_status = 'down' AND D.ignore = '0' AND S.service_ignore = '0'");
while($service = mysql_fetch_array($sql)){
      generate_front_box("alert", "<center><strong>".generatedevicelink($service, shorthost($service['hostname']))."</strong><br />
      <span style='font-size: 14px; font-weight: bold; margin: 5px; color: #c00;'>Service Down</span> 
      <strong>".$service['service_type']."</strong><br />
      <span class=body-date-1>".truncate($interface['ifAlias'], 20)."</span>
      </center>");

}

if($_SESSION['userlevel'] == '10') {
$sql = mysql_query("SELECT * FROM `devices` AS D, bgpPeers AS B WHERE bgpPeerState != 'established' AND B.device_id = D.device_id AND D.ignore = 0");
} else {
$sql = mysql_query("SELECT * FROM `devices` AS D, bgpPeers AS B, devices_perms AS P WHERE D.device_id = P.device_id AND P.user_id = '" . $_SESSION['user_id'] . "' AND bgpPeerState != 'established' AND B.device_id = D.device_id AND D.ignore = 0");
}
while($peer = mysql_fetch_array($sql)){

  generate_front_box("alert", "<center><strong>".generatedevicelink($peer, shorthost($peer['hostname']))."</strong><br />
      <span style='font-size: 14px; font-weight: bold; margin: 5px; color: #c00;'>BGP Down</span> 
      <strong>".$peer['bgpPeerIdentifier']."</strong> <br />
      <span class=body-date-1>AS".$peer['bgpPeerRemoteAs']." ".truncate($peer['astext'], 10)."</span>
      </center>");

}

if($_SESSION['userlevel'] == '10') {
$sql = mysql_query("SELECT * FROM `devices` AS D WHERE D.status = '1' AND D.uptime < '84600' AND D.ignore = 0");
} else {
$sql = mysql_query("SELECT * FROM `devices` AS D, devices_perms AS P WHERE D.device_id = P.device_id AND P.user_id = '" . $_SESSION['user_id'] . "' AND D.status = '1' AND D.uptime < '84600' AND D.ignore = 0");
}
while($device = mysql_fetch_array($sql)){
   generate_front_box("info", "<center><strong>".generatedevicelink($device, shorthost($device['hostname']))."</strong><br />
      <span style='font-size: 14px; font-weight: bold; margin: 5px; color: #009;'>Device<br />Rebooted</span><br />
      <span class=body-date-1>".formatUptime($device['uptime'], 'short')."</span>
      </center>");

}

if($config['frontpage_display'] == 'syslog') {

  ## Open Syslog Div
  echo("<div style='margin: 4px; clear: both; padding: 5px;'>  
    <h3>Recent Syslog Messages</h3>
  ");

  $sql = "SELECT *, DATE_FORMAT(datetime, '%D %b %T') AS date from syslog ORDER BY datetime DESC LIMIT 20";
  $query = mysql_query($sql);
  echo("<table cellspacing=0 cellpadding=2 width=100%>");
  while($entry = mysql_fetch_array($query)) { include("includes/print-syslog.inc"); }
  echo("</table>");

  echo("</div>"); ## Close Syslog Div

} else {

  ## Open eventlog Div
  echo("<div style='margin: 4px; clear: both; padding: 5px;'>
    <h3>Recent Eventlog Entries</h3>
  ");

if($_SESSION['userlevel'] == '10') {
  $query = "SELECT *,DATE_FORMAT(datetime, '%D %b %T') as humandate  FROM `eventlog` ORDER BY `datetime` DESC LIMIT 0,15";
} else {
  $query = "SELECT *,DATE_FORMAT(datetime, '%D %b %T') as humandate  FROM `eventlog` AS E, devices_perms AS P WHERE E.host =
  P.device_id AND P.user_id = " . $_SESSION['user_id'] . " ORDER BY `datetime` DESC LIMIT 0,15";
}

$data = mysql_query($query);

echo("<table cellspacing=0 cellpadding=1 width=100%>");

while($entry = mysql_fetch_array($data)) {
  include("includes/print-event.inc");
}

echo("</table>");
  echo("</div>"); ## Close Syslog Div
}

echo("</div>");

?>
