<?php

$query = mysql_query("SELECT * FROM `processors` where `device_id` = '".mres($device_id)."'");

$i=0;
while($proc = mysql_fetch_array($query)) {

  $rrd_filename  = $config['rrd_dir'] . "/$hostname/" . safename("processor-" . $proc['processor_type'] . "-" . $proc['processor_index'] . ".rrd");

  if(is_file($rrd_filename)) {

    $descr = short_hrDeviceDescr($proc['processor_descr']);

    $rrd_list[$i]['filename'] = $rrd_filename;
    $rrd_list[$i]['descr'] = $descr;
    $rrd_list[$i]['rra'] = "usage";
    $i++;
  }
}

$unit_text = "Load %";

$units='%';
$total_units='%';
$colours='mixed';

$scale_min = "0";
$scale_max = "100";

$nototal = 1;

if ($rrd_list) {
  include ("generic_multi_line.inc.php");
}

?>
