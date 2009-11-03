<?php

$scale_min = "0";

include("common.inc.php");

$database = $config['rrd_dir'] . "/" . $hostname . "/sys.rrd";

$rrd_options .= " DEF:procs=$database:procs:AVERAGE";
$rrd_options .= " COMMENT:Processes\ \ \ \ Cur\ \ \ \ \ Ave\ \ \ \ \ \ Min\ \ \ \ \ Max\\\\n";
$rrd_options .= " AREA:procs#CDEB8B:";
$rrd_options .= " LINE1.25:procs#008C00:\ ";
$rrd_options .= " GPRINT:procs:LAST:\ \ \ \ %6.2lf";
$rrd_options .= " GPRINT:procs:AVERAGE:%6.2lf";
$rrd_options .= " GPRINT:procs:MIN:%6.2lf";
$rrd_options .= " GPRINT:procs:MAX:%6.2lf\\\\n";

?>
