<?php

function formatMac($mac) {
  $mac = preg_replace("/(..)(..)(..)(..)(..)(..)/", "\\1:\\2:\\3:\\4:\\5:\\6", $mac);
  return $mac;
}


function ifNameDescr($interface, $device = NULL) {
  return ifLabel($interface, $device);
}

function ifLabel ($interface, $device = NULL) {
  global $config;
  if(!$device) { $device = device_array($interface['device_id']); }
  $os = strtolower($device['os']);

  if(isset($config['ifname'][$os])) {
    $interface['label'] = $interface['ifName'];
  } elseif(isset($config['ifAlias'][$os])) {
    $interface['label'] = $interface['ifAlias'];
  } else {
    $interface['label'] = $interface['ifDescr'];
    if(isset($config['appendifindex'][$os])) { $interface['label'] = $interface['label'] . " " . $interface['ifIndex']; }
  }
  return $interface;

}

$rewrite_entSensorType = array (
  'celsius' => 'C',
  'unknown' => '',
  'specialEnum' => 'C',
  'watts' => 'W',
  'truthvalue' => '',
);


function entPhysical_scale($value, $scale) {

  switch ($scale) {
    case "nano":
  $value = $value / 1000000000;
  break;
    case "micro":
  $value = $value / 1000000;
  break;
    case "milli":
  $value = $value / 1000;
  break;
    case "units":
  break;
    case "kilo":
  $value = $value * 1000;
  break;
    case "mega":
  $value = $value * 1000000;
  break;
    case "giga":
  $value = $value * 1000000000;
  break;
  }

  return $value;

}

$translate_ifOperStatus = array(
  "1" => "up",
  "2" => "down",
  "3" => "testing",
  "4" => "unknown",
  "5" => "dormant",
  "6" => "notPresent",
  "7" => "lowerLayerDown",
);

function translate_ifOperStatus ($ifOperStatus) {
  global $translate_ifOperStatus;
  if($translate_ifOperStatus['$ifOperStatus']) {
    $ifOperStatus = $translate_ifOperStatus['$ifOperStatus'];
  }
  return $ifOperStatus;
}

$translate_ifAdminStatus = array(
  "1" => "up",
  "2" => "down",
  "3" => "testing",
);

function translate_ifAdminStatus ($ifAdminStatus) {
  global $translate_ifAdminStatus;
  if($translate_ifAdminStatus[$ifAdminStatus]) {
    $ifAdminStatus = $translate_ifAdminStatus[$ifAdminStatus];
  }
  return $ifAdminStatus;
}

$rewrite_junose_hardware = array(
  'juniErx1400' => 'ERX-1400',
  'juniErx700' => 'ERX-700',
  'juniErx1440' => 'ERX-1440',
  'juniErx705' => 'ERX-705',
  'juniErx310' => 'ERX-310',
  'juniE320' => 'E320',
  'juniE120' => 'E120',
  'juniSsx1400' => 'SSX-1400',
  'juniSsx700' => 'SSX-700',
  'juniSsx1440' => 'SSX-1440',
);

$rewrite_ironware_hardware = array(
    'snFIWGSwitch' => 'Stackable FastIron workgroup',
    'snFIBBSwitch' => 'Stackable FastIron backbone',
    'snNIRouter' => 'Stackable NetIron',
    'snSI' => 'Stackable ServerIron',
    'snSIXL' => 'Stackable ServerIronXL',
    'snSIXLTCS' => 'Stackable ServerIronXL TCS',
    'snTISwitch' => 'Stackable TurboIron',
    'snTIRouter' => 'Stackable TurboIron',
    'snT8Switch' => 'Stackable TurboIron 8',
    'snT8Router' => 'Stackable TurboIron 8',
    'snT8SIXLG' => 'Stackable ServerIronXLG',
    'snBI4000Switch' => 'BigIron 4000',
    'snBI4000Router' => 'BigIron 4000',
    'snBI4000SI' => 'BigServerIron',
    'snBI8000Switch' => 'BigIron 8000',
    'snBI8000Router' => 'BigIron 8000',
    'snBI8000SI' => 'BigServerIron',
    'snFI2Switch' => 'FastIron II',
    'snFI2Router' => 'FastIron II',
    'snFI2PlusSwitch' => 'FastIron II Plus',
    'snFI2PlusRouter' => 'FastIron II Plus',
    'snNI400Router' => 'NetIron 400',
    'snNI800Router' => 'NetIron 800',
    'snFI2GCSwitch' => 'FastIron II GC',
    'snFI2GCRouter' => 'FastIron II GC',
    'snFI2PlusGCSwitch' => 'FastIron II Plus GC',
    'snFI2PlusGCRouter' => 'FastIron II Plus GC',
    'snBI15000Switch' => 'BigIron 15000',
    'snBI15000Router' => 'BigIron 15000',
    'snNI1500Router' => 'NetIron 1500',
    'snFI3Switch' => 'FastIron III',
    'snFI3Router' => 'FastIron III',
    'snFI3GCSwitch' => 'FastIron III GC',
    'snFI3GCRouter' => 'FastIron III GC',
    'snSI400Switch' => 'ServerIron 400',
    'snSI400Router' => 'ServerIron 400',
    'snSI800Switch' => 'ServerIron800',
    'snSI800Router' => 'ServerIron800',
    'snSI1500Switch' => 'ServerIron1500',
    'snSI1500Router' => 'ServerIron1500',
    'sn4802Switch' => 'Stackable 4802',
    'sn4802Router' => 'Stackable 4802',
    'sn4802SI' => 'Stackable 4802 ServerIron',
    'snFI400Switch' => 'FastIron 400',
    'snFI400Router' => 'FastIron 400',
    'snFI800Switch' => 'FastIron800',
    'snFI800Router' => 'FastIron800',
    'snFI1500Switch' => 'FastIron1500',
    'snFI1500Router' => 'FastIron1500',
    'snFES2402' => 'FastIron Edge Switch(FES) 2402',
    'snFES2402Switch' => 'FES2402',
    'snFES2402Router' => 'FES2402',
    'snFES4802' => 'FastIron Edge Switch(FES) 4802',
    'snFES4802Switch' => 'FES4802',
    'snFES4802Router' => 'FES4802',
    'snFES9604' => 'FastIron Edge Switch(FES) 9604',
    'snFES9604Switch' => 'FES9604',
    'snFES9604Router' => 'FES9604',
    'snFES12GCF' => 'FastIron Edge Switch(FES) 12GCF ',
    'snFES12GCFSwitch' => 'snFES12GCF ',
    'snFES12GCFRouter' => 'snFES12GCF',
    'snFES2402P' => 'FastIron Edge Switch(FES) 2402 POE ',
    'snFES2402P' => 'snFES2402POE ',
    'snFES2402P' => 'snFES2402POE',
    'snFES4802P' => 'FastIron Edge Switch (FES) 4802 POE ',
    'snFES4802P' => 'snFES4802POE ',
    'snFES4802P' => 'snFES4802POE',
    'snNI4802Switch' => 'NetIron 4802',
    'snNI4802Router' => 'NetIron 4802',
    'snBIMG8Switch' => 'BigIron MG8',
    'snBIMG8Router' => 'BigIron MG8',
    'snNI40GRouter' => 'NetIron 40G',
    'snFESX424' => 'FastIron Edge Switch(FES) 24G',
    'snFESX424Switch' => 'FESX424',
    'snFESX424Router' => 'FESX424',
    'snFESX424Prem' => 'FastIron Edge Switch(FES) 24G-PREM',
    'snFESX424PremSwitch' => 'FESX424-PREM',
    'snFESX424PremRouter' => 'FESX424-PREM',
    'snFESX424Plus1XG' => 'FastIron Edge Switch(FES) 24G + 1 10G',
    'snFESX424Plus1XGSwitch' => 'FESX424+1XG',
    'snFESX424Plus1XGRouter' => 'FESX424+1XG',
    'snFESX424Plus1XGPrem' => 'FastIron Edge Switch(FES) 24G + 1 10G-PREM',
    'snFESX424Plus1XGPremSwitch' => 'FESX424+1XG-PREM',
    'snFESX424Plus1XGPremRouter' => 'FESX424+1XG-PREM',
    'snFESX424Plus2XG' => 'FastIron Edge Switch(FES) 24G + 2 10G',
    'snFESX424Plus2XGSwitch' => 'FESX424+2XG',
    'snFESX424Plus2XGRouter' => 'FESX424+2XG',
    'snFESX424Plus2XGPrem' => 'FastIron Edge Switch(FES) 24G + 2 10G-PREM',
    'snFESX424Plus2XGPremSwitch' => 'FESX424+2XG-PREM',
    'snFESX424Plus2XGPremRouter' => 'FESX424+2XG-PREM',
    'snFESX448' => 'FastIron Edge Switch(FES) 48G',
    'snFESX448Switch' => 'FESX448',
    'snFESX448Router' => 'FESX448',
    'snFESX448Prem' => 'FastIron Edge Switch(FES) 48G-PREM',
    'snFESX448PremSwitch' => 'FESX448-PREM',
    'snFESX448PremRouter' => 'FESX448-PREM',
    'snFESX448Plus1XG' => 'FastIron Edge Switch(FES) 48G + 1 10G',
    'snFESX448Plus1XGSwitch' => 'FESX448+1XG',
    'snFESX448Plus1XGRouter' => 'FESX448+1XG',
    'snFESX448Plus1XGPrem' => 'FastIron Edge Switch(FES) 48G + 1 10G-PREM',
    'snFESX448Plus1XGPremSwitch' => 'FESX448+1XG-PREM',
    'snFESX448Plus1XGPremRouter' => 'FESX448+1XG-PREM',
    'snFESX448Plus2XG' => 'FastIron Edge Switch(FES) 48G + 2 10G',
    'snFESX448Plus2XGSwitch' => 'FESX448+2XG',
    'snFESX448Plus2XGRouter' => 'FESX448+2XG',
    'snFESX448Plus2XGPrem' => 'FastIron Edge Switch(FES) 48G + 2 10G-PREM',
    'snFESX448Plus2XGPremSwitch' => 'FESX448+2XG-PREM',
    'snFESX448Plus2XGPremRouter' => 'FESX448+2XG-PREM',
    'snFESX424Fiber' => 'FastIron Edge Switch(FES)Fiber 24G',
    'snFESX424FiberSwitch' => 'FESX424Fiber',
    'snFESX424FiberRouter' => 'FESX424Fiber',
    'snFESX424FiberPrem' => 'FastIron Edge Switch(FES)Fiber 24G-PREM',
    'snFESX424FiberPremSwitch' => 'FESX424Fiber-PREM',
    'snFESX424FiberPremRouter' => 'FESX424Fiber-PREM',
    'snFESX424FiberPlus1XG' => 'FastIron Edge Switch(FES)Fiber 24G + 1 10G',
    'snFESX424FiberPlus1XGSwitch' => 'FESX424Fiber+1XG',
    'snFESX424FiberPlus1XGRouter' => 'FESX424Fiber+1XG',
    'snFESX424FiberPlus1XGPrem' => 'FastIron Edge Switch(FES)Fiber 24G + 1 10G-PREM',
    'snFESX424FiberPlus1XGPremSwitch' => 'FESX424Fiber+1XG-PREM',
    'snFESX424FiberPlus1XGPremRouter' => 'FESX424Fiber+1XG-PREM',
    'snFESX424FiberPlus2XG' => 'FastIron Edge Switch(FES)Fiber 24G + 2 10G',
    'snFESX424FiberPlus2XGSwitch' => 'FESX424Fiber+2XG',
    'snFESX424FiberPlus2XGRouter' => 'FESX424Fiber+2XG',
    'snFESX424FiberPlus2XGPrem' => 'FastIron Edge Switch(FES)Fiber 24G + 2 10G-PREM',
    'snFESX424FiberPlus2XGPremSwitch' => 'FESX424Fiber+2XG-PREM',
    'snFESX424FiberPlus2XGPremRouter' => 'FESX424Fiber+2XG-PREM',
    'snFESX448Fiber' => 'FastIron Edge Switch(FES)Fiber 48G',
    'snFESX448FiberSwitch' => 'FESX448Fiber',
    'snFESX448FiberRouter' => 'FESX448Fiber',
    'snFESX448FiberPrem' => 'FastIron Edge Switch(FES)Fiber 48G-PREM',
    'snFESX448FiberPremSwitch' => 'FESX448Fiber-PREM',
    'snFESX448FiberPremRouter' => 'FESX448Fiber-PREM',
    'snFESX448FiberPlus1XG' => 'FastIron Edge Switch(FES)Fiber 48G + 1 10G',
    'snFESX448FiberPlus1XGSwitch' => 'FESX448Fiber+1XG',
    'snFESX448FiberPlus1XGRouter' => 'FESX448Fiber+1XG',
    'snFESX448FiberPlus1XGPrem' => 'FastIron Edge Switch(FES)Fiber 48G + 1 10G-PREM',
    'snFESX448FiberPlus1XGPremSwitch' => 'FESX448Fiber+1XG-PREM',
    'snFESX448FiberPlus1XGPremRouter' => 'FESX448Fiber+1XG-PREM',
    'snFESX448FiberPlus2XG' => 'FastIron Edge Switch(FES)Fiber 48G + 2 10G',
    'snFESX448FiberPlus2XGSwitch' => 'FESX448Fiber+2XG',
    'snFESX448FiberPlus2XGRouter' => 'FESX448+2XG',
    'snFESX448FiberPlus2XGPrem' => 'FastIron Edge Switch(FES)Fiber 48G + 2 10G-PREM',
    'snFESX448FiberPlus2XGPremSwitch' => 'FESX448Fiber+2XG-PREM',
    'snFESX448FiberPlus2XGPremRouter' => 'FESX448Fiber+2XG-PREM',
    'snFESX424P' => 'FastIron Edge Switch(FES) 24G POE',
    'snFESX424P' => 'FESX424POE',
    'snFESX424P' => 'FESX424POE',
    'snFESX424P' => 'FastIron Edge Switch(FES) 24GPOE-PREM',
    'snFESX424P' => 'FESX424POE-PREM',
    'snFESX424P' => 'FESX424POE-PREM',
    'snFESX424P' => 'FastIron Edge Switch(FES) 24GPOE + 1 10G',
    'snFESX424P' => 'FESX424POE+1XG',
    'snFESX424P' => 'FESX424POE+1XG',
    'snFESX424P' => 'FastIron Edge Switch(FES) 24GPOE + 1 10G-PREM',
    'snFESX424P' => 'FESX424POE+1XG-PREM',
    'snFESX424P' => 'FESX424POE+1XG-PREM',
    'snFESX424P' => 'FastIron Edge Switch(FES) 24GPOE + 2 10G',
    'snFESX424P' => 'FESX424POE+2XG',
    'snFESX424P' => 'FESX424POE+2XG',
    'snFESX424P' => 'FastIron Edge Switch(FES) 24GPOE + 2 10G-PREM',
    'snFESX424P' => 'FESX424POE+2XG-PREM',
    'snFESX424P' => 'FESX424POE+2XG-PREM',
    'snFESX624' => 'FastIron Edge V6 Switch(FES) 24G',
    'snFESX624Switch' => 'FESX624',
    'snFESX624Router' => 'FESX624',
    'snFESX624Prem' => 'FastIron Edge V6 Switch(FES) 24G-PREM',
    'snFESX624PremSwitch' => 'FESX624-PREM',
    'snFESX624PremRouter' => 'FESX624-PREM',
    'snFESX624Plus1XG' => 'FastIron Edge V6 Switch(FES) 24G + 1 10G',
    'snFESX624Plus1XGSwitch' => 'FESX624+1XG',
    'snFESX624Plus1XGRouter' => 'FESX624+1XG',
    'snFESX624Plus1XGPrem' => 'FastIron Edge V6 Switch(FES) 24G + 1 10G-PREM',
    'snFESX624Plus1XGPremSwitch' => 'FESX624+1XG-PREM',
    'snFESX624Plus1XGPremRouter' => 'FESX624+1XG-PREM',
    'snFESX624Plus2XG' => 'FastIron Edge V6 Switch(FES) 24G + 2 10G',
    'snFESX624Plus2XGSwitch' => 'FESX624+2XG',
    'snFESX624Plus2XGRouter' => 'FESX624+2XG',
    'snFESX624Plus2XGPrem' => 'FastIron Edge V6 Switch(FES) 24G + 2 10G-PREM',
    'snFESX624Plus2XGPremSwitch' => 'FESX624+2XG-PREM',
    'snFESX624Plus2XGPremRouter' => 'FESX624+2XG-PREM',
    'snFESX648' => 'FastIron Edge V6 Switch(FES) 48G',
    'snFESX648Switch' => 'FESX648',
    'snFESX648Router' => 'FESX648',
    'snFESX648Prem' => 'FastIron Edge V6 Switch(FES) 48G-PREM',
    'snFESX648PremSwitch' => 'FESX648-PREM',
    'snFESX648PremRouter' => 'FESX648-PREM',
    'snFESX648Plus1XG' => 'FastIron Edge V6 Switch(FES) 48G + 1 10G',
    'snFESX648Plus1XGSwitch' => 'FESX648+1XG',
    'snFESX648Plus1XGRouter' => 'FESX648+1XG',
    'snFESX648Plus1XGPrem' => 'FastIron Edge V6 Switch(FES) 48G + 1 10G-PREM',
    'snFESX648Plus1XGPremSwitch' => 'FESX648+1XG-PREM',
    'snFESX648Plus1XGPremRouter' => 'FESX648+1XG-PREM',
    'snFESX648Plus2XG' => 'FastIron Edge V6 Switch(FES) 48G + 2 10G',
    'snFESX648Plus2XGSwitch' => 'FESX648+2XG',
    'snFESX648Plus2XGRouter' => 'FESX648+2XG',
    'snFESX648Plus2XGPrem' => 'FastIron Edge V6 Switch(FES) 48G + 2 10G-PREM',
    'snFESX648Plus2XGPremSwitch' => 'FESX648+2XG-PREM',
    'snFESX648Plus2XGPremRouter' => 'FESX648+2XG-PREM',
    'snFESX624Fiber' => 'FastIron V6 Edge Switch(FES)Fiber 24G',
    'snFESX624FiberSwitch' => 'FESX624Fiber',
    'snFESX624FiberRouter' => 'FESX624Fiber',
    'snFESX624FiberPrem' => 'FastIron Edge V6 Switch(FES)Fiber 24G-PREM',
    'snFESX624FiberPremSwitch' => 'FESX624Fiber-PREM',
    'snFESX624FiberPremRouter' => 'FESX624Fiber-PREM',
    'snFESX624FiberPlus1XG' => 'FastIron Edge V6 Switch(FES)Fiber 24G + 1 10G',
    'snFESX624FiberPlus1XGSwitch' => 'FESX624Fiber+1XG',
    'snFESX624FiberPlus1XGRouter' => 'FESX624Fiber+1XG',
    'snFESX624FiberPlus1XGPrem' => 'FastIron Edge V6 Switch(FES)Fiber 24G + 1 10G-PREM',
    'snFESX624FiberPlus1XGPremSwitch' => 'FESX624Fiber+1XG-PREM',
    'snFESX624FiberPlus1XGPremRouter' => 'FESX624Fiber+1XG-PREM',
    'snFESX624FiberPlus2XG' => 'FastIron Edge V6 Switch(FES)Fiber 24G + 2 10G',
    'snFESX624FiberPlus2XGSwitch' => 'FESX624Fiber+2XG',
    'snFESX624FiberPlus2XGRouter' => 'FESX624Fiber+2XG',
    'snFESX624FiberPlus2XGPrem' => 'FastIron Edge V6 Switch(FES)Fiber 24G + 2 10G-PREM',
    'snFESX624FiberPlus2XGPremSwitch' => 'FESX624Fiber+2XG-PREM',
    'snFESX624FiberPlus2XGPremRouter' => 'FESX624Fiber+2XG-PREM',
    'snFESX648Fiber' => 'FastIron Edge V6 Switch(FES)Fiber 48G',
    'snFESX648FiberSwitch' => 'FESX648Fiber',
    'snFESX648FiberRouter' => 'FESX648Fiber',
    'snFESX648FiberPrem' => 'FastIron Edge V6 Switch(FES)Fiber 48G-PREM',
    'snFESX648FiberPremSwitch' => 'FESX648Fiber-PREM',
    'snFESX648FiberPremRouter' => 'FESX648Fiber-PREM',
    'snFESX648FiberPlus1XG' => 'FastIron Edge V6 Switch(FES)Fiber 48G + 1 10G',
    'snFESX648FiberPlus1XGSwitch' => 'FESX648Fiber+1XG',
    'snFESX648FiberPlus1XGRouter' => 'FESX648Fiber+1XG',
    'snFESX648FiberPlus1XGPrem' => 'FastIron Edge V6 Switch(FES)Fiber 48G + 1 10G-PREM',
    'snFESX648FiberPlus1XGPremSwitch' => 'FESX648Fiber+1XG-PREM',
    'snFESX648FiberPlus1XGPremRouter' => 'FESX648Fiber+1XG-PREM',
    'snFESX648FiberPlus2XG' => 'FastIron Edge V6 Switch(FES)Fiber 48G + 2 10G',
    'snFESX648FiberPlus2XGSwitch' => 'FESX648Fiber+2XG',
    'snFESX648FiberPlus2XGRouter' => 'FESX648+2XG',
    'snFESX648FiberPlus2XGPrem' => 'FastIron Edge V6 Switch(FES)Fiber 48G + 2 10G-PREM',
    'snFESX648FiberPlus2XGPremSwitch' => 'FESX648Fiber+2XG-PREM',
    'snFESX648FiberPlus2XGPremRouter' => 'FESX648Fiber+2XG-PREM',
    'snFESX624P' => 'FastIron Edge V6 Switch(FES) 24G POE',
    'snFESX624P' => 'FESX624POE',
    'snFESX624P' => 'FESX624POE',
    'snFESX624P' => 'FastIron Edge V6 Switch(FES) 24GPOE-PREM',
    'snFESX624P' => 'FESX624POE-PREM',
    'snFESX624P' => 'FESX624POE-PREM',
    'snFESX624P' => 'FastIron Edge V6 Switch(FES) 24GPOE + 1 10G',
    'snFESX624P' => 'FESX624POE+1XG',
    'snFESX624P' => 'FESX624POE+1XG',
    'snFESX624P' => 'FastIron Edge V6 Switch(FES) 24GPOE + 1 10G-PREM',
    'snFESX624P' => 'FESX624POE+1XG-PREM',
    'snFESX624P' => 'FESX624POE+1XG-PREM',
    'snFESX624P' => 'FastIron Edge V6 Switch(FES) 24GPOE + 2 10G',
    'snFESX624P' => 'FESX624POE+2XG',
    'snFESX624P' => 'FESX624POE+2XG',
    'snFESX624P' => 'FastIron Edge V6 Switch(FES) 24GPOE + 2 10G-PREM',
    'snFESX624P' => 'FESX624POE+2XG-PREM',
    'snFESX624P' => 'FESX624POE+2XG-PREM',
    'snFWSX424' => 'FastIron WorkGroup Switch(FWS) 24G',
    'snFWSX424Switch' => 'FWSX424',
    'snFWSX424Router' => 'FWSX424',
    'snFWSX424Plus1XG' => 'FastIron WorkGroup Switch(FWS) 24G + 1 10G',
    'snFWSX424Plus1XGSwitch' => 'FWSX424+1XG',
    'snFWSX424Plus1XGRouter' => 'FWSX424+1XG',
    'snFWSX424Plus2XG' => 'FastIron WorkGroup Switch(FWS) 24G + 2 10G',
    'snFWSX424Plus2XGSwitch' => 'FWSX424+2XG',
    'snFWSX424Plus2XGRouter' => 'FWSX424+2XG',
    'snFWSX448' => 'FastIron WorkGroup Switch(FWS) 48G',
    'snFWSX448Switch' => 'FWSX448',
    'snFWSX448Router' => 'FWSX448',
    'snFWSX448Plus1XG' => 'FastIron WorkGroup Switch(FWS) 48G + 1 10G',
    'snFWSX448Plus1XGSwitch' => 'FWSX448+1XG',
    'snFWSX448Plus1XGRouter' => 'FWSX448+1XG',
    'snFWSX448Plus2XG' => 'FastIron WorkGroup Switch(FWS) 48G + 2 10G',
    'snFWSX448Plus2XGSwitch' => 'FWSX448+2XG',
    'snFWSX448Plus2XGRouter' => 'FWSX448+2XG',
    'snFastIronSuperXFamily' => 'FastIron SuperX Family',
    'snFastIronSuperX' => 'FastIron SuperX',
    'snFastIronSuperXSwitch' => 'FastIron SuperX Switch',
    'snFastIronSuperXRouter' => 'FastIron SuperX Router',
    'snFastIronSuperXBaseL3Switch' => 'FastIron SuperX Base L3 Switch',
    'snFastIronSuperXPrem' => 'FastIron SuperX Premium',
    'snFastIronSuperXPremSwitch' => 'FastIron SuperX Premium Switch',
    'snFastIronSuperXPremRouter' => 'FastIron SuperX Premium Router',
    'snFastIronSuperXPremBaseL3Switch' => 'FastIron SuperX Premium Base L3 Switch',
    'snFastIronSuperX800' => 'FastIron SuperX 800 ',
    'snFastIronSuperX800Switch' => 'FastIron SuperX 800 Switch',
    'snFastIronSuperX800Router' => 'FastIron SuperX 800 Router',
    'snFastIronSuperX800BaseL3Switch' => 'FastIron SuperX 800 Base L3 Switch',
    'snFastIronSuperX800Prem' => 'FastIron SuperX 800 Premium',
    'snFastIronSuperX800PremSwitch' => 'FastIron SuperX 800 Premium Switch',
    'snFastIronSuperX800PremRouter' => 'FastIron SuperX 800 Premium Router',
    'snFastIronSuperX800PremBaseL3Switch' => 'FastIron SuperX 800 Premium Base L3 Switch',
    'snFastIronSuperX1600' => 'FastIron SuperX 1600 ',
    'snFastIronSuperX1600Switch' => 'FastIron SuperX 1600 Switch',
    'snFastIronSuperX1600Router' => 'FastIron SuperX 1600 Router',
    'snFastIronSuperX1600BaseL3Switch' => 'FastIron SuperX 1600 Base L3 Switch',
    'snFastIronSuperX1600Prem' => 'FastIron SuperX 1600 Premium',
    'snFastIronSuperX1600PremSwitch' => 'FastIron SuperX 1600 Premium Switch',
    'snFastIronSuperX1600PremRouter' => 'FastIron SuperX 1600 Premium Router',
    'snFastIronSuperX1600PremBaseL3Switch' => 'FastIron SuperX 1600 Premium Base L3 Switch',
    'snFastIronSuperXV6' => 'FastIron SuperX V6 ',
    'snFastIronSuperXV6Switch' => 'FastIron SuperX V6 Switch',
    'snFastIronSuperXV6Router' => 'FastIron SuperX V6 Router',
    'snFastIronSuperXV6BaseL3Switch' => 'FastIron SuperX V6 Base L3 Switch',
    'snFastIronSuperXV6Prem' => 'FastIron SuperX V6 Premium',
    'snFastIronSuperXV6PremSwitch' => 'FastIron SuperX V6 Premium Switch',
    'snFastIronSuperXV6PremRouter' => 'FastIron SuperX V6 Premium Router',
    'snFastIronSuperXV6PremBaseL3Switch' => 'FastIron SuperX V6 Premium Base L3 Switch',
    'snFastIronSuperX800V6' => 'FastIron SuperX 800 V6 ',
    'snFastIronSuperX800V6Switch' => 'FastIron SuperX 800 V6 Switch',
    'snFastIronSuperX800V6Router' => 'FastIron SuperX 800 V6 Router',
    'snFastIronSuperX800V6BaseL3Switch' => 'FastIron SuperX 800 V6 Base L3 Switch',
    'snFastIronSuperX800V6Prem' => 'FastIron SuperX 800 V6 Premium',
    'snFastIronSuperX800V6PremSwitch' => 'FastIron SuperX 800 Premium V6 Switch',
    'snFastIronSuperX800V6PremRouter' => 'FastIron SuperX 800 Premium V6 Router',
    'snFastIronSuperX800V6PremBaseL3Switch' => 'FastIron SuperX 800 Premium V6 Base L3 Switch',
    'snFastIronSuperX1600V6' => 'FastIron SuperX 1600 V6 ',
    'snFastIronSuperX1600V6Switch' => 'FastIron SuperX 1600 V6 Switch',
    'snFastIronSuperX1600V6Router' => 'FastIron SuperX 1600 V6 Router',
    'snFastIronSuperX1600V6BaseL3Switch' => 'FastIron SuperX 1600 V6 Base L3 Switch',
    'snFastIronSuperX1600V6Prem' => 'FastIron SuperX 1600 Premium V6',
    'snFastIronSuperX1600V6PremSwitch' => 'FastIron SuperX 1600 Premium V6 Switch',
    'snFastIronSuperX1600V6PremRouter' => 'FastIron SuperX 1600 Premium V6 Router',
    'snFastIronSuperX1600V6PremBaseL3Switch' => 'FastIron SuperX 1600 Premium V6 Base L3 Switch',
    'snBigIronSuperXFamily' => 'BigIron SuperX Family',
    'snBigIronSuperX' => 'BigIron SuperX',
    'snBigIronSuperXSwitch' => 'BigIron SuperX Switch',
    'snBigIronSuperXRouter' => 'BigIron SuperX Router',
    'snBigIronSuperXBaseL3Switch' => 'BigIron SuperX Base L3 Switch',
    'snTurboIronSuperXFamily' => 'TurboIron SuperX Family',
    'snTurboIronSuperX' => 'TurboIron SuperX',
    'snTurboIronSuperXSwitch' => 'TurboIron SuperX Switch',
    'snTurboIronSuperXRouter' => 'TurboIron SuperX Router',
    'snTurboIronSuperXBaseL3Switch' => 'TurboIron SuperX Base L3 Switch',
    'snTurboIronSuperXPrem' => 'TurboIron SuperX Premium',
    'snTurboIronSuperXPremSwitch' => 'TurboIron SuperX Premium Switch',
    'snTurboIronSuperXPremRouter' => 'TurboIron SuperX Premium Router',
    'snTurboIronSuperXPremBaseL3Switch' => 'TurboIron SuperX Premium Base L3 Switch',
    'snNIIMRRouter' => 'NetIron IMR',
    'snBIRX16Switch' => 'BigIron RX16',
    'snBIRX16Router' => 'BigIron RX16',
    'snBIRX8Switch' => 'BigIron RX8',
    'snBIRX8Router' => 'BigIron RX8',
    'snBIRX4Switch' => 'BigIron RX4',
    'snBIRX4Router' => 'BigIron RX4',
    'snBIRX32Switch' => 'BigIron RX32',
    'snBIRX32Router' => 'BigIron RX32',
    'snNIXMR16000Router' => 'NetIron XMR16000',
    'snNIXMR8000Router' => 'NetIron XMR8000',
    'snNIXMR4000Router' => 'NetIron XMR4000',
    'snNIXMR32000Router' => 'NetIron XMR32000',
    'snSecureIronLS100' => 'SecureIronLS 100',
    'snSecureIronLS100Switch' => 'SecureIronLS 100 Switch',
    'snSecureIronLS100Router' => 'SecureIronLS 100 Router',
    'snSecureIronLS300' => 'SecureIronLS 300',
    'snSecureIronLS300Switch' => 'SecureIronLS 300 Switch',
    'snSecureIronLS300Router' => 'SecureIronLS 300 Router',
    'snSecureIronTM100' => 'SecureIronTM 100',
    'snSecureIronTM100Switch' => 'SecureIronTM 100 Switch',
    'snSecureIronTM100Router' => 'SecureIronTM 100 Router',
    'snSecureIronTM300' => 'SecureIronTM 300',
    'snSecureIronTM300Switch' => 'SecureIronTM 300 Switch',
    'snSecureIronTM300Router' => 'SecureIronTM 300 Router',
    'snNetIronMLX16Router' => 'NetIron MLX-16',
    'snNetIronMLX8Router' => 'NetIron MLX-8',
    'snNetIronMLX4Router' => 'NetIron MLX-4',
    'snNetIronMLX32Router' => 'NetIron MLX-32',
    'snFGS624P' => 'FastIron GS Switch(FGS) 24-port 10/100/1000 POE Ready',
    'snFGS624PSwitch' => 'FGS624P',
    'snFGS624PRouter' => 'FGS624P',
    'snFGS624XGP' => 'FastIron GS Switch(FGS) 24-port 10/100/1000 POE Ready + 1 10G',
    'snFGS624XGPSwitch' => 'FGS624XGP',
    'snFGS624XGPRouter' => 'FGS624XGP',
    'snFGS624PP' => 'FastIron GS Switch(FGS) 24-port 10/100/1000 POE ',
    'snFGS624PP' => 'snFGS624P-POE',
    'snFGS624PP' => 'snFGS624P-POE',
    'snFGS624XGPP' => 'FastIron GS Switch(FGS) 24-port 10/100/1000 POE + 1 10G',
    'snFGS624XGPP' => 'FGS624XGP-POE',
    'snFGS624XGPP' => 'FGS624XGP-POE',
    'snFGS648P' => 'FastIron GS Switch(FGS) 48-port 10/100/1000 POE Ready',
    'snFGS648PSwitch' => 'FGS648P',
    'snFGS648PRouter' => 'FGS648P',
    'snFGS648PP' => 'FastIron GS Switch(FGS) 48-port 10/100/1000 POE ',
    'snFGS648PP' => 'snFGS648P-POE',
    'snFGS648PP' => 'snFGS648P-POE',
    'snFLS624' => 'FastIron LS Switch(FLS) 24-port 10/100/1000 ',
    'snFLS624Switch' => 'FLS624',
    'snFLS624Router' => 'FLS624',
    'snFLS648' => 'FastIron LS Switch(FLS) 48-port 10/100/1000',
    'snFLS648Switch' => 'FLS648',
    'snFLS648Router' => 'FLS648',
    'snSI100' => 'ServerIron 100 series',
    'snSI100Switch' => 'SI100',
    'snSI100Router' => 'SI100',
    'snSI350' => 'ServerIron 350 series',
    'snSI350Switch' => 'SI350',
    'snSI350Router' => 'SI350',
    'snSI450' => 'ServerIron 450 series',
    'snSI450Switch' => 'SI450',
    'snSI450Router' => 'SI450',
    'snSI850' => 'ServerIron 850 series',
    'snSI850Switch' => 'SI850',
    'snSI850Router' => 'SI850',
    'snSI350Plus' => 'ServerIron 350 Plus series',
    'snSI350PlusSwitch' => 'SI350 Plus',
    'snSI350PlusRouter' => 'SI350 Plus',
    'snSI450Plus' => 'ServerIron 450 Plus series',
    'snSI450PlusSwitch' => 'SI450 Plus',
    'snSI450PlusRouter' => 'SI450 Plus',
    'snSI850Plus' => 'ServerIron 850 Plus series',
    'snSI850PlusSwitch' => 'SI850 Plus',
    'snSI850PlusRouter' => 'SI850 Plus',
    'snServerIronGTc' => 'ServerIronGT C series',
    'snServerIronGTcSwitch' => 'ServerIronGT C',
    'snServerIronGTcRouter' => 'ServerIronGT C',
    'snServerIronGTe' => 'ServerIronGT E series',
    'snServerIronGTeSwitch' => 'ServerIronGT E',
    'snServerIronGTeRouter' => 'ServerIronGT E',
    'snServerIronGTePlus' => 'ServerIronGT E Plus series',
    'snServerIronGTePlusSwitch' => 'ServerIronGT E Plus',
    'snServerIronGTePlusRouter' => 'ServerIronGT E Plus',
    'snServerIron4G' => 'ServerIron4G series',
    'snServerIron4GSwitch' => 'ServerIron4G',
    'snServerIron4GRouter' => 'ServerIron4G',
    'wirelessAp' => 'wireless access point',
    'wirelessProbe' => 'wireless probe',
    'ironPointMobility' => 'IronPoint Mobility Series',
    'ironPointMC' => 'IronPoint Mobility Controller',
    'dcrs7504Switch' => 'DCRS-7504',
    'dcrs7504Router' => 'DCRS-7504',
    'dcrs7508Switch' => 'DCRS-7508',
    'dcrs7508Router' => 'DCRS-7508',
    'dcrs7515Switch' => 'DCRS-7515',
    'dcrs7515Router' => 'DCRS-7515',
);

$rewrite_ios_features = array(
  "PK9S" => "IP w/SSH LAN Only",
  "LANBASEK9" => "Lan Base Crypto",
  "LANBASE" => "Lan Base",
  "ADVENTERPRISEK9_IVS" => "Advanced Enterprise Crypto Voice",
  "ADVENTERPRISEK9" => "Advanced Enterprise Crypto",
  "ADVSECURITYK9" => "Advanced Security Crypto",
  "K91P" => "Provider Crypto",
  "K4P" => "Provider Crypto",
  "ADVIPSERVICESK9" => "Adv IP Services Crypto",
  "ADVIPSERVICES" => "Adv IP Services",
  "IK9P" => "IP Plus Crypto",
  "K9O3SY7" => "IP ADSL FW IDS Plus IPSEC 3DES",
  "SPSERVICESK9" => "SP Services Crypto",
  "PK9SV" => "IP MPLS/IPV6 W/SSH + BGP",
  "IS" => "IP Plus",
  "IPSERVICESK9" => "IP Services Crypto",
  "BROADBAND" => "Broadband",
  "IPBASE" => "IP Base",
  "IPSERVICE" => "IP Services",
  "P" => "Service Provider",
  "P11" => "Broadband Router",
  "G4P5" => "NRP",
  "JK9S" => "Enterprise Plus Crypto",
  "IK9S" => "IP Plus Crypto",
  "JK" => "Enterprise Plus",
  "I6Q4L2" => "Layer 2",
  "I6K2L2Q4" => "Layer 2 Crypto",
  "C3H2S" => "Layer 2 SI/EI",
  "_WAN" => " + WAN",
  );



  $rewrite_shortif = array (
    'tengigabitethernet' => 'Te',
    'gigabitethernet' => 'Gi',
    'fastethernet' => 'Fa',
    'ethernet' => 'Et',
    'serial' => 'Se',
    'pos' => 'Pos',
    'port-channel' => 'Po',
    'atm' => 'Atm',
    'null' => 'Null',
    'loopback' => 'Lo',
    'dialer' => 'Di',
    'vlan' => 'Vlan',
    'tunnel' => 'Tunnel',
    'serviceinstance' => 'SI',
  );

  $rewrite_iftype = array (
    '/^frameRelay$/' => 'Frame Relay',
    '/^ethernetCsmacd$/' => 'Ethernet',
    '/^softwareLoopback$/' => 'Loopback',
    '/^tunnel$/' => 'Tunnel',
    '/^propVirtual$/' => 'Virtual Int',
    '/^ppp$/' => 'PPP',
    '/^ds1$/' => 'DS1',
    '/^pos$/' => 'POS',
    '/^sonet$/' => 'SONET',
    '/^slip$/' => 'SLIP',
    '/^mpls$/' => 'MPLS Layer',
    '/^l2vlan$/' => 'VLAN Subif',
    '/^atm$/' => 'ATM',
    '/^aal5$/' => 'ATM AAL5',
    '/^atmSubInterface$/' => 'ATM Subif',
    '/^propPointToPointSerial$/' => 'PtP Serial',
  );

  $rewrite_ifname = array (
    'ether' => 'Ether',
    'gig' => 'Gig',
    'fast' => 'Fast',
    'ten' => 'Ten',
    '-802.1q vlan subif' => '',
    '-802.1q' => '',
    'bvi' => 'BVI',
    'vlan' => 'Vlan',
    'ether' => 'Ether',
    'tunnel' => 'Tunnel',
    'serial' => 'Serial',
    '-aal5 layer' => ' aal5',
    'null' => 'Null',
    'atm' => 'ATM',
    'port-channel' => 'Port-Channel',
    'dial' => 'Dial',
    'hp procurve switch software loopback interface' => 'Loopback Interface',
    'control plane interface' => 'Control Plane',
    'loop' => 'Loop',
  );

  $rewrite_hrDevice = array (
    'GenuineIntel:' => '',
    'AuthenticAMD:' => '',
    'Intel(R)' => '',
    'CPU' => '',
    '(R)' => '',
    '  ' => ' ',
  );


// Specific rewrite functions

function makeshortif($if)
{
  global $rewrite_shortif;
  $if = fixifName ($if);
  $if = strtolower($if);
  $if = array_str_replace($rewrite_shortif, $if);
  return $if;
}

function rewrite_ios_features ($features)
{
  global $rewrite_ios_features;
  $type = array_preg_replace($rewrite_ios_features, $features);
  return ($features);
}

function rewrite_ironware_hardware ($hardware)
{
  global $rewrite_ironware_hardware;
  $hardware = array_str_replace($rewrite_ironware_hardware, $hardware);
  return ($hardware);
}

function rewrite_junose_hardware ($hardware)
{
  global $rewrite_junose_hardware;
  $hardware = array_str_replace($rewrite_junose_hardware, $hardware);
  return ($hardware);
}



function fixiftype ($type)
{
  global $rewrite_iftype;
  $type = array_preg_replace($rewrite_iftype, $type);
  return ($type);
}

function fixifName ($inf)
{
  global $rewrite_ifname;
  $inf = strtolower($inf);
  $inf = array_str_replace($rewrite_ifname, $inf);
  return $inf;
}

function short_hrDeviceDescr($dev)
{
  global $rewrite_hrDevice;
  $dev = array_str_replace($rewrite_hrDevice, $dev);
  $dev = preg_replace("/\ +/"," ", $dev);
  $dev = trim($dev);
  return $dev;
}

function short_port_descr ($desc) {

  list($desc) = explode("(", $desc);
  list($desc) = explode("[", $desc);
  list($desc) = explode("{", $desc);
  list($desc) = explode("|", $desc);
  list($desc) = explode("<", $desc);
  $desc = trim($desc);

  return $desc;

}


// Underlying rewrite functions


  function array_str_replace($array, $string) 
  {
    foreach ($array as $search => $replace) {
      $string = str_replace($search, $replace, $string);
    }
    return $string;
  }

  function array_preg_replace($array, $string) 
  {
    foreach ($array as $search => $replace) {
      $string = preg_replace($search, $replace, $string);
    }
    return $string;
  }



?>
