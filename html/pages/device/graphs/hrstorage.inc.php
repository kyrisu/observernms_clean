<?php

      if(mysql_result(mysql_query("SELECT count(storage_id) FROM storage WHERE host_id = '" . $device['device_id'] . "'"),0)) {
        echo("<div class=graphhead>Storage</div>");
        $graph_type = "device_hrstorage";           include ("includes/print-device-graph.php");
        echo("<br />");
      }

?>