<?

# Discover interfaces

  echo("Interfaces : ");

#  $cmd = $config['snmpwalk'] . " -O nsq -" . $device['snmpver'] . " -c " . $device['community'] . " " . $device['hostname'] . " .1.3.6.1.2.1.2.2.1.2 | sed s/ifDescr.//g | sed s/\ \"/\|\|\"/g | sed s/\ /\|\|/g";
  $cmd = $config['snmpwalk'] . " -O nsq -" . $device['snmpver'] . " -c " . $device['community'] . " " . $device['hostname'] . " .1.3.6.1.2.1.2.2.1.2";
  $interfaces = trim(shell_exec($cmd));
  $interfaces = str_replace("\"", "", $interfaces);
  $interfaces = str_replace("ifDescr.", "", $interfaces);
  $interfaces = str_replace(" ", "||", $interfaces);

  $interface_ignored = 0;
  $interface_added   = 0;

  foreach(explode("\n", $interfaces) as $entry){

    $entry = trim($entry);
    list($ifIndex, $ifName) = explode("||", $entry);
    if(!strstr($entry, "irtual")) {
      $ifName = trim(str_replace("\"", "", $ifName));
      $if = trim(strtolower($ifName));
      $nullintf = 0;
      foreach($config['bad_if'] as $bi) {
      if (strstr($if, $bi)) {
          $nullintf = 1;
        }
      }
      if (preg_match('/serial[0-9]:/', $if)) { $nullintf = '1'; }
      if (preg_match('/ng[0-9]+$/', $if)) { $nullintf = '1'; }
      if ($nullintf == 0) {
        if(mysql_result(mysql_query("SELECT COUNT(*) FROM `interfaces` WHERE `device_id` = '".$device['device_id']."' AND `ifIndex` = '$ifIndex'"), 0) == '0') {
          mysql_query("INSERT INTO `interfaces` (`device_id`,`ifIndex`,`ifDescr`) VALUES ('$id','$ifIndex','$ifName')");
           echo("+");
        } else { 
          # Interface Already Exists
          echo(".");
        }
      } else { 
          # Ignored ifName
          echo("X"); 
      }
    } 
  }

  echo("\n");

?>