#!/usr/bin/php 
<?
include("config.php");
include("includes/functions.php");

# Remove a host and all related data from the system

if($argv[1] && $argv[2]) { 
  $host = strtolower($argv[1]);
  $id = getidbyname($host);
  if($id) {
    renamehost($id, $argv[2]);
    echo("Renamed $host\n");
  } else {
    echo("Host doesn't exist!\n");
  }
} else {
    echo("Host Rename Tool\nUsage: ./delhost.php <old hostname> <new hostname>\n");
}

?>
