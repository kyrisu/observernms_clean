<?php

  ini_set('display_errors', 1);
  ini_set('display_startup_errors', 1);
  ini_set('log_errors', 1);
  ini_set('allow_url_fopen', 0);
#  ini_set('error_reporting', E_ALL);

  include("../config.php");
  include("../includes/functions.php");
  include("includes/authenticate.inc");

  if($_GET['params']) {
    list($_GET['host'], $_GET['if'], $_GET['from'], $_GET['to'], $_GET['width'], $_GET['height'], $_GET['title'], $_GET['vertical'], $_GET['type'], $_GET['interfaces']) = explode("||", mcrypt_ecb(MCRYPT_DES, $key_value, $_GET['params'], MCRYPT_DECRYPT));
  }


  if($_GET['host']) {
    $device_id = $_GET['host'];
  } elseif($_GET['device']) {
    $device_id = $_GET['device'];
  } elseif($_GET['if']) {
    $device_id = getifhost($_GET['if']);
    $ifIndex = getifindexbyid($_GET['if']);
  }
  if($device_id) {
    $hostname = gethostbyid($device_id);
  }

  $from = $_GET['from'];
  $to = $_GET['to'];
  $width = $_GET['width'];
  $height = $_GET['height'];
  $title = $_GET['title'];
  $vertical = $_GET['vertical'];
  $type = $_GET['type'];

  $graphfile = strgen() . ".png";

  $os = gethostosbyid($device_id);

  switch ($type) {

  case 'multi_bits':
    $graph = graph_multi_bits ($_GET['interfaces'], $graphfile, $from, $to, $width, $height);
    break;
  case 'global_bits':
    $graph = graph_global_bits ("global_bits.png", $from, $to, $width, $height);
    break;
  case 'device_bits':
    $graph = graph_device_bits ($device_id, $graphfile, $from, $to, $width, $height, $title, $vertical);
    break;  
  case 'bits':
    $graph = trafgraph ($hostname . "/". $ifIndex . ".rrd", $graphfile, $from, $to, $width, $height, $title, $vertical);
    break;
  case 'pkts':
    $graph = pktsgraph ($hostname . "/". $ifIndex . ".rrd", $graphfile, $from, $to, $width, $height, $title, $vertical);
    break;
  case 'errors':
    $graph = errorgraph ($hostname . "/". $ifIndex . ".rrd", $graphfile, $from, $to, $width, $height, $title, $vertical);
    break;
  case 'nupkts':
    $graph = nucastgraph ($hostname . "/". $ifIndex . ".rrd", $graphfile, $from, $to, $width, $height, $title, $vertical);
    break;
  case 'uptime':
    $graph = uptimegraph ($hostname . "/uptime.rrd", $graphfile, $from, $to, $width, $height, $title, $vertical);
    break;
  case 'unixfs_dev':
    $graph = unixfsgraph_dev ($device_id, $graphfile, $from, $to, $width, $height, $title, $vertical);
    break;
  case 'unixfs':
    $graph = unixfsgraph ($_GET['id'], $graphfile, $from, $to, $width, $height, $title, $vertical);
    break;
  case 'calls':
    $graph = callsgraphSNOM ($hostname . "-data.rrd", $graphfile, $from, $to, $width, $height, $title, $vertical);
    break;
  case 'ip_graph':
    $graph = ip_graph ($hostname . "-netinfo.rrd", $graphfile, $from, $to, $width, $height, $title, $vertical);
    break;
  case 'icmp_graph':
    $graph = icmp_graph ($hostname . "-netinfo.rrd", $graphfile, $from, $to, $width, $height, $title, $vertical);
    break;
  case 'tcp_graph':
    $graph = tcp_graph ($hostname . "-netinfo.rrd", $graphfile, $from, $to, $width, $height, $title, $vertical);
    break;
  case 'udp_graph':
    $graph = udp_graph ($hostname . "-netinfo.rrd", $graphfile, $from, $to, $width, $height, $title, $vertical);
    break;
  case 'cpu':
    if($os == "Linux" || $os == "NetBSD" || $os == "FreeBSD" || $os == "DragonFly" || $os == "OpenBSD" || $os == "Windows" || $os == "m0n0wall" || $os == "Voswall" || $os == "pfSense" || $os == "DragonFly" || $os == "OpenBSD") {
      $graph = cpugraphUnix ($hostname . "-cpu.rrd", $graphfile, $from, $to, $width, $height, $title, $vertical);
    } elseif($os == "IOS") {
      $graph = cpugraph ($hostname . "-cpu.rrd", $graphfile, $from, $to, $width, $height, $title, $vertical);
    } elseif($os == "Windows") {
      $graph = cpugraphwin ($hostname . "-cpu.rrd", $graphfile, $from, $to, $width, $height, $title, $vertical);
    } elseif($os == "ProCurve") {
      $graph = cpugraphHP ($hostname . "-cpu.rrd", $graphfile, $from, $to, $width, $height, $title, $vertical);
    } elseif($os == "Snom") {
      $graph = callsgraphSNOM ($hostname . "-data.rrd", $graphfile, $from, $to, $width, $height, $title, $vertical);
    }
    break;
  case 'temp':
      $graph = temp_graph ($_GET['id'], $graphfile, $from, $to, $width, $height, $title, $vertical);
    break;
  case 'dev_temp':
      $graph = temp_graph_dev ($device_id, $graphfile, $from, $to, $width, $height, $title, $vertical);
    break;
  case 'mem':
    if($os == "Linux" || $os == "FreeBSD" || $os == "DragonFly" || $os == "OpenBSD" || $os == "NetBSD" ) {
      $graph = memgraphUnix ($hostname . "-mem.rrd", $graphfile, $from, $to, $width, $height, $title, $vertical);
    } elseif($os == "IOS") {
      $graph = memgraph ($hostname . "-mem.rrd", $graphfile, $from, $to, $width, $height, $title, $vertical);
    } elseif($os == "Windows") {
    } elseif($os == "ProCurve") {
      $graph = memgraphHP ($hostname . "-mem.rrd", $graphfile, $from, $to, $width, $height, $title, $vertical);
    }
    break;
  case 'load':
    if($os == "Linux" || $os == "FreeBSD" || $os == "DragonFly" || $os == "OpenBSD" || $os == "NetBSD" ) {
      $graph = loadgraphUnix ($hostname . "-load.rrd", $graphfile, $from, $to, $width, $height, $title, $vertical);
    } elseif($os == "IOS") {
      $graph = loadgraph ($hostname . "-load.rrd", $graphfile, $from, $to, $width, $height, $title, $vertical);
    } elseif($os == "Windows") {
      $graph = loadgraphwin ($hostname . "-load.rrd", $graphfile, $from, $to, $width, $height, $title, $vertical);
    }
    break;
  case 'users':
    if($os == "Linux" || $os == "FreeBSD" || $os == "DragonFly" || $os == "OpenBSD" || $os == "NetBSD" ) {
      $graph = usersgraphUnix ($hostname . "-sys.rrd", $graphfile, $from, $to, $width, $height, $title, $vertical);
    } elseif($os == "Windows") {
      $graph = usersgraphwin ($hostname . "-sys.rrd", $graphfile, $from, $to, $width, $height, $title, $vertical);
    }
    break;
  case 'procs':
    if($os == "Linux" || $os == "FreeBSD" || $os == "DragonFly" || $os == "OpenBSD" || $os == "NetBSD" ) {
      $graph = procsgraphUnix ($hostname . "-sys.rrd", $graphfile, $from, $to, $width, $height, $title, $vertical);
    } elseif($os == "Windows") {
      $graph = procsgraphwin ($hostname . "-sys.rrd", $graphfile, $from, $to, $width, $height, $title, $vertical);
    }
    break;
  case 'unixfs':
    if($os == "Linux" || $os == "FreeBSD" || $os == "DragonFly" || $os == "OpenBSD" || $os == "NetBSD" ) {
      $graph = unixfsgraph ($device_id, $graphfile, $from, $to, $width, $height, $title, $vertical);
    }
    break;
  case 'postfix':
  case 'mail':
    if($os == "Linux" || $os == "FreeBSD" || $os == "DragonFly" || $os == "OpenBSD") {
      $graph = mailsgraphUnix ($hostname . "-mailstats.rrd", $graphfile, $from, $to, $width, $height, $title, $vertical);
    }
    break;
  case 'mailerrors':
  case 'postfixerrors':
    if($os == "Linux" || $os == "FreeBSD" || $os == "DragonFly" || $os == "OpenBSD") {
      $graph = mailerrorgraphUnix ($hostname, $graphfile, $from, $to, $width, $height, $title, $vertical);
    }
    break;
  case 'courier':
    if($os == "Linux" || $os == "FreeBSD" || $os == "DragonFly" || $os == "OpenBSD") {
      $graph = couriergraphUnix ($hostname . "-courierstats.rrd", $graphfile, $from, $to, $width, $height, $title, $vertical);
    }
    break;
  case 'apachehits':
    if($os == "Linux" || $os == "FreeBSD" || $os == "DragonFly" || $os == "OpenBSD") {
      $graph = apachehitsgraphUnix ($hostname . "-apache.rrd", $graphfile, $from, $to, $width, $height, $title, $vertical);
    }
    break;
  case 'apachebits':
    if($os == "Linux" || $os == "FreeBSD" || $os == "DragonFly" || $os == "OpenBSD") {
      $graph = apachebitsgraphUnix ($hostname . "-apache.rrd", $graphfile, $from, $to, $width, $height, $title, $vertical);
    }
    break;

  }

  if($graph) {
    header('Content-type: image/png');
    echo(`cat graphs/$graphfile`);
  } else {  
    header('Content-type: image/png');
    echo(`cat images/no-graph.png`);
  }

  $delete = `rm graphs/$graphfile`; 

?>
