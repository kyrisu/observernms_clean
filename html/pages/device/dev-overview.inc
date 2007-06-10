<?php

$overview = 1;

$id = $_GET[id];

$device = mysql_fetch_array(mysql_query("SELECT * FROM `devices` WHERE `device_id` = '$_GET[id]'"));

$interfaces['total'] = mysql_result(mysql_query("SELECT count(*) FROM interfaces  WHERE device_id = '" . $device['device_id'] . "'"),0);
$interfaces['up'] = mysql_result(mysql_query("SELECT count(*) FROM interfaces  WHERE device_id = '" . $device['device_id'] . "' AND ifOperStatus = 'up'"),0);
$interfaces['down'] = mysql_result(mysql_query("SELECT count(*) FROM interfaces WHERE device_id = '" . $device['device_id'] . "' AND ifOperStatus = 'down' AND ifAdminStatus = 'up'"),0);
$interfaces['disabled'] = mysql_result(mysql_query("SELECT count(*) FROM interfaces WHERE device_id = '" . $device['device_id'] . "' AND ifAdminStatus = 'down'"),0);

$services['total'] = mysql_result(mysql_query("SELECT count(service_id) FROM services WHERE service_host = '" . $device['device_id'] . "'"),0);
$services['up'] = mysql_result(mysql_query("SELECT count(service_id) FROM services  WHERE service_host = '" . $device['device_id'] . "' AND service_status = '1' AND service_ignore ='0'"),0);
$services['down'] = mysql_result(mysql_query("SELECT count(service_id) FROM services WHERE service_host = '" . $device['device_id'] . "' AND service_status = '0' AND service_ignore = '0'"),0);
$services['disabled'] = mysql_result(mysql_query("SELECT count(service_id) FROM services WHERE service_host = '" . $device['device_id'] . "' AND service_ignore = '1'"),0);

if($services['down']) { $services_colour = $warn_colour_a; } else { $services_colour = $list_colour_a; }
if($interfaces['down']) { $interfaces_colour = $warn_colour_a; } else { $interfaces_colour = $list_colour_a; }

echo("
<table width=100% cellspacing=0 cellpadding=0>
  <tr><td width=50% valign=top>");


if(file_exists("includes/dev-data-" . strtolower($device[os]) . ".inc")) {
  echo("<div style='background-color: #eeeeee; margin: 5px; padding: 5px;'>");
  echo("<p class=sectionhead>Device Data</p><div style='height: 5px;'></div>");
  include("includes/dev-data-" . strtolower($device[os]) . ".inc");
  echo("</div>");
}


if(mysql_result(mysql_query("SELECT count(storage_id) from storage WHERE host_id = '" . $device['device_id'] . "'"),0)) {
  echo("<div style='background-color: #eeeeee; margin: 5px; padding: 5px;'>");
  echo("<p class=sectionhead>Storage</p>");
  echo("<table width=100%>");
  $i = '1';

  echo("<tr class=tablehead><td>Mountpoint</td><td width=203>Usage</td><td width=40>%</td><td width=75>Total</td>
          <td width=75>Used</td></tr>");
  $drives = mysql_query("SELECT * FROM `storage` WHERE host_id = '" . $device['device_id'] . "'");
  while($drive = mysql_fetch_array($drives)) {
    $total = $drive['hrStorageSize'] * $drive['hrStorageAllocationUnits'];
    $used  = $drive['hrStorageUsed'] * $drive['hrStorageAllocationUnits'];
    $perc  = $drive['storage_perc'];
    $total = formatStorage($total);
    $used = formatStorage($used);

    $store_url    = "graph.php?id=" . $drive['storage_id'] . "&type=unixfs&from=$month&to=$now&width=400&height=125";
    $store_popup = "onmouseover=\"return overlib('<img src=\'$store_url\'>', LEFT);\" onmouseout=\"return nd();\"";

    if($perc > '80') { $drv_class='red'; } else { $drvclass='';  }
    echo("<tr><td class=tablehead>" . $drive['hrStorageDescr'] . "</td><td><a href='#' $store_popup><img src='percentage.php?per=" . $perc . "'></a>
          </td><td>" . $perc . "%</td><td>" . $total . "</td><td>" . $used . "</td></tr>");
    $i++;
  }
  echo("</table>");
  echo("</div>");
}

unset($temp_seperator);
if(mysql_result(mysql_query("SELECT count(temp_id) from temperature WHERE temp_host = '" . $device['device_id'] . "'"),0)) {
  $total = mysql_result(mysql_query("SELECT count(temp_id) from temperature WHERE temp_host = '" . $device['device_id'] . "'"),0);
  $rows = round($total / 2,0);
  echo("<div style='background-color: #eeeeee; margin: 5px; padding: 5px;'>");
  echo("<p class=sectionhead>Temperatures</p>");
  $i = '1';
  $temps = mysql_query("SELECT * FROM temperature WHERE temp_host = '" . $device['device_id'] . "'");
  echo("<table width=100% valign=top>");
  echo("<tr><td width=50%>");
  echo("<table width=100% cellspacing=0 cellpadding=2>");
  while($temp = mysql_fetch_array($temps)) {
    if(is_integer($i/2)) { $row_colour = $list_colour_a; } else { $row_colour = $list_colour_b; }

    if($temp['temp_current'] < '30') { $temp_class=''; 
    } elseif($temp['temp_current'] < '40') { $temp_class='blue';
    } elseif($temp['temp_current'] < '50') { $temp_class='green';
    } elseif($temp['temp_current'] < '60') { $temp_class='orange';
    } else { $temp_class='red'; }

    $temp['temp_descr'] = truncate($temp['temp_descr'], 25, '');
    echo("<tr bgcolor='$row_colour'><td>" . $temp['temp_descr'] . "</td><td width=40 class=tablehead><span class='$temp_class'>" . $temp['temp_current'] . "&deg;C</span></td></tr>");
    if($i == $rows) { echo("</table></td><td valign=top><table width=100% cellspacing=0 cellpadding=2>"); }
    $i++;
  }
  echo("</table>");
  echo("</td></tr>");
  echo("</table>");
  echo("</div>");
}



echo("<div style='background-color: #eeeeee; margin: 5px; padding: 5px;'>");
echo("<p class=sectionhead>Recent Events</p>");

$query = "SELECT *,DATE_FORMAT(datetime, '%d/%b/%y %T') as humandate  FROM `eventlog` WHERE `host` = '$_GET[id]' ORDER BY `datetime` DESC LIMIT 0,10";
$data = mysql_query($query);

echo("<table cellspacing=0 cellpadding=2 width=100%>");

while($entry = mysql_fetch_array($data)) {

include("includes/print-event-short.inc");

}
echo("</table></div>");

echo("</td><td width=50% valign=top>");

if($interfaces['total']) {
  echo("<div style='background-color: #eeeeee; margin: 5px; padding: 5px;'>
        <p class=sectionhead>Total Traffic</p>" . device_traffic_image($device['device_id'], 490, 100, $day, $now) . "</div>");
}

if($interfaces['total']) {
  echo("<div style='background-color: #eeeeee; margin: 5px; padding: 5px;'>");
  echo("<p class=sectionhead>Interfaces</p><div style='height: 5px;'></div>");

echo("
<table class=tablehead cellpadding=2 cellspacing=0 width=100%>
<tr bgcolor=$interfaces_colour align=center><td></td>
<td width=25%><img src='images/16/connect.png' align=absmiddle> $interfaces[total]</td>
<td width=25% class=green><img src='images/16/if-connect.png' align=absmiddle> $interfaces[up]</td>
<td width=25% class=red><img src='images/16/if-disconnect.png' align=absmiddle> $interfaces[down]</td>
<td width=25% class=grey><img src='images/16/if-disable.png' align=absmiddle> $interfaces[disabled]</td></tr>
</table>");

  echo("<div style='margin: 8px; font-size: 11px; font-weight: bold;'>");

  $sql = "SELECT * FROM interfaces WHERE `device_id` = '" . $device['device_id'] . "'";
  $query = mysql_query($sql);
  while($data = mysql_fetch_array($query)) {
    echo("$ifsep" . generateiflink($data, makeshortif(strtolower($data['ifDescr']))));
    $ifsep = ", ";
  }
  unset($ifsep);
  echo("</div>");

  echo("</div>");

}

if($services['total']) {

  echo("<div style='background-color: #eeeeee; margin: 5px; padding: 5px;'>");
  echo("<p class=sectionhead>Services</p><div style='height: 5px;'></div>");

echo("
<table class=tablehead cellpadding=2 cellspacing=0 width=100%>
<tr bgcolor=$services_colour align=center><td></td>
<td width=25%><img src='images/16/cog.png' align=absmiddle> $services[total]</td>
<td width=25% class=green><img src='images/16/cog_go.png' align=absmiddle> $services[up]</td>
<td width=25% class=red><img src='images/16/cog_error.png' align=absmiddle> $services[down]</td>
<td width=25% class=grey><img src='images/16/cog_disable.png' align=absmiddle> $services[disabled]</td></tr>
</table>");



  echo("<div style='padding: 8px; font-size: 11px; font-weight: bold;'>");

  $sql = "SELECT * FROM services WHERE service_host = '" . $device['device_id'] . "' ORDER BY service_type";
  $query = mysql_query($sql);
  while($data = mysql_fetch_array($query)) {
    if ($data[service_status] == "0" && $data[service_ignore] == "1") { $status = "grey"; }
    if ($data[service_status] == "1" && $data[service_ignore] == "1") { $status = "green"; }
    if ($data[service_status] == "0" && $data[service_ignore] == "0") { $status = "red"; }
    if ($data[service_status] == "1" && $data[service_ignore] == "0") { $status = "blue"; }
    echo("$break<a class=$status>" . strtolower($data[service_type]) . "</a>");
    $break = ", ";
  }

  echo("</div>");

}

echo("</td></tr></table>");

?>