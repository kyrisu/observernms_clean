<?php

/// Draw generic bits graph
/// args: rra_in, rra_out, rrd_filename, bg, legend, from, to, width, height, inverse, $percentile

include("common.inc.php");

if(!$unit_text) {$unit_text = "\ \ \ \ \ \ \ ";}

$rrd_options .= " DEF:".$out."=".$rrd_filename.":".$rra_out.":AVERAGE";
$rrd_options .= " DEF:".$in."=".$rrd_filename.":".$rra_in.":AVERAGE";
$rrd_options .= " DEF:".$out."_max=".$rrd_filename.":".$rra_out.":MAX";
$rrd_options .= " DEF:".$in."_max=".$rrd_filename.":".$rra_in.":MAX";

$rrd_options .= " CDEF:dout_max=out_max,-1,*";
$rrd_options .= " CDEF:dout=out,-1,*";
$rrd_options .= " CDEF:both=in,out,+";

if($print_total) {
  $rrd_options .= " VDEF:totin=in,TOTAL";
  $rrd_options .= " VDEF:totout=out,TOTAL";
  $rrd_options .= " VDEF:tot=both,TOTAL";
}

if($percentile) {
  $rrd_options .= " VDEF:percentile_in=in,".$percentile.",PERCENT";
  $rrd_options .= " VDEF:percentile_out=out,".$percentile.",PERCENT";
  $rrd_options .= " VDEF:dpercentile_out=dout,".$percentile.",PERCENT";
}

if($graph_max) {
  $rrd_options .= " AREA:in_max#".$colour_area_in_max.":";
  $rrd_options .= " AREA:dout_max#".$colour_area_out_max.":";
}
$rrd_options .= " AREA:in#".$colour_area_in.":";
$rrd_options .= " COMMENT:".$unit_text."Now\ \ \ \ \ \ \ Ave\ \ \ \ \ \ Max";
if($percentile) {
  $rrd_options .= "\ \ \ \ \ \ ".$percentile."th\ %\\\\n";
}
$rrd_options .= "\\\\n";
$rrd_options .= " LINE1.25:in#".$colour_line_in.":In\ ";
$rrd_options .= " GPRINT:in:LAST:%6.2lf%s";
$rrd_options .= " GPRINT:in:AVERAGE:%6.2lf%s";
$rrd_options .= " GPRINT:in_max:MAX:%6.2lf%s";
if($percentile) {
  $rrd_options .= " GPRINT:percentile_in:%6.2lf%s";
}
$rrd_options .= " COMMENT:\\\\n";
$rrd_options .= " AREA:dout#".$colour_area_out.":";
$rrd_options .= " LINE1.25:dout#".$colour_line_out.":Out";
$rrd_options .= " GPRINT:out:LAST:%6.2lf%s";
$rrd_options .= " GPRINT:out:AVERAGE:%6.2lf%s";
$rrd_options .= " GPRINT:out_max:MAX:%6.2lf%s";
if($percentile) {
  $rrd_options .= " GPRINT:percentile_out:%6.2lf%s";
}
$rrd_options .= " COMMENT:\\\\n";
if($print_total) {
  $rrd_options .= " GPRINT:tot:Total\ %6.2lf%s";
  $rrd_options .= " GPRINT:totin:\(In\ %6.2lf%s";
  $rrd_options .= " GPRINT:totout:Out\ %6.2lf%s\)\\\\l";
}
if($percentile) {
  $rrd_options .= " LINE1:percentile_in#aa0000";
  $rrd_options .= " LINE1:dpercentile_out#aa0000";
}

?>
