<?php

$storage_cache = array();

$query = "SELECT * FROM storage WHERE device_id = '" . $device['device_id'] . "'";
$storage_data = mysql_query($query);
while($storage = mysql_fetch_array($storage_data)) {

  echo($storage['storage_descr'] . ": ");

  $storage_rrd  = $config['rrd_dir'] . "/" . $device['hostname'] . "/" . safename("storage-" . $storage['storage_mib'] . "-" . $storage['storage_index'] . ".rrd");

  if (!is_file($storage_rrd)) {
   rrdtool_create($storage_rrd, "--step 300 \
     DS:used:GAUGE:600:-273:100000000000 \
     DS:free:GAUGE:600:-273:100000000000 \
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

  $file = $config['install_dir']."/includes/polling/storage-".$storage['storage_mib'].".inc.php";
  if(is_file($file)) {
    include($file);
  } else {
    ### FIXME GENERIC
  }

  if($debug) {print_r($storage);}

  $percent = round($storage['used'] / $storage['size'] * 100);

  echo($percent."% ");

  rrdtool_update($storage_rrd,"N:".$storage['used'].":".$storage['free']);

  $update_query  = "UPDATE `storage` SET `storage_used` = '".$storage['used']."'";
  $update_query .= ", `storage_free` = '".$storage['free']."', `storage_size` = '".$storage['size']."'";
  $update_query .= ", `storage_units` = '".$storage['units']."', `storage_perc` = '".$percent."'";
  $update_query .= " WHERE `storage_id` = '".$storage['storage_id']."'";
  if($debug) { echo("$update_query\n"); }
  mysql_query($update_query);

}

unset($storage_cache, $storage);

?>
