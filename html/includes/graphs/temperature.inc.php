<?php

include("common.inc.php");

  $sql = mysql_query("SELECT * FROM temperature where temp_id = '$temp'");
  $temperature = mysql_fetch_array(mysql_query("SELECT * FROM temperature where temp_id = '".mres($_GET['id'])."'"));

  $hostname = mysql_result(mysql_query("SELECT hostname FROM devices WHERE device_id = '" . $temperature['temp_host'] . "'"),0);

  $temperature['temp_descr_fixed'] = str_pad($temperature['temp_descr'], 28);
  $temperature['temp_descr_fixed'] = substr($temperature['temp_descr_fixed'],0,28);

  $filename = str_replace(")", "_", str_replace("(", "_", str_replace("/", "_", str_replace(" ", "_",$temperature['temp_descr']))));

  $rrd_filename  = $config['rrd_dir'] . "/".$hostname."/temp-" . $filename . ".rrd";

  $rrd_options .= " DEF:temp=$rrd_filename:temp:AVERAGE";
  $rrd_options .= " CDEF:tempwarm=temp,".$temperature[temp_limit].",GT,temp,UNKN,IF";
  $rrd_options .= " LINE1.5:temp#cc0000:'" . quotemeta($temperature[temp_descr_fixed]."'");
  $rrd_options .= " LINE1.5:tempwarm#660000";
  $rrd_options .= " GPRINT:temp:LAST:%3.0lfC";
  $rrd_options .= " GPRINT:temp:MAX:%3.0lfC\\\\l";


?>