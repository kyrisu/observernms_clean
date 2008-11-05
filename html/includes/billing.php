<?php

function printReport ($bill_id) {

$bi_q = mysql_query("SELECT * FROM bills WHERE bill_id = $bill_id");
$bill_data = mysql_fetch_array($bi_q);

$today = str_replace("-", "", mysql_result(mysql_query("SELECT CURDATE()"), 0));
$yesterday = str_replace("-", "", mysql_result(mysql_query("SELECT DATE_SUB(CURDATE(), INTERVAL 1 DAY)"), 0));
$tomorrow = str_replace("-", "", mysql_result(mysql_query("SELECT DATE_ADD(CURDATE(), INTERVAL 1 DAY)"), 0));
$last_month = str_replace("-", "", mysql_result(mysql_query("SELECT DATE_SUB(CURDATE(), INTERVAL 1 MONTH)"), 0));

$now = $today . date(His);
$before = $yesterday . date(His);
$lastmonth = $last_month . date(His);

$bill_name  = $bill_data['bill_name'];
$dayofmonth = $bill_data['bill_day'];
$paidrate   = $bill_data['bill_paid_rate'];
$paid_kb    = $paidrate / 1000;
$paid_mb    = $paid_kb / 1000;

if ($paidrate < 1000000) { $paidrate_text = $paid_kb . "Kbps is the CDR."; }
if ($paidrate >= 1000000) { $paidrate_text = $paid_mb . "Mbps is the CDR."; }

$day_data     = getDates($dayofmonth);
$datefrom     = $day_data['0'];
$dateto       = $day_data['1'];
$rate_data    = getRates($bill_id,$datefrom,$dateto);
$rate_95th    = $rate_data['rate_95th'];
$dir_95th     =	$rate_data['dir_95th'];
$total_data   = $rate_data['total_data'];
$rate_average = $rate_data['rate_average'];

if ($rate_95th > $paid_kb) {
	$over = $rate_95th - $paid_kb;
	$bill_text = $over . "Kbit excess.";
	$bill_color = "#cc0000";
} else {
	$under = $paid_kb - $rate_95th;
	$bill_text = $under . "Kbit headroom.";
	$bill_color = "#0000cc";
}

$fromtext = mysql_result(mysql_query("SELECT DATE_FORMAT($datefrom, '%M %D %Y')"), 0);
$totext   = mysql_result(mysql_query("SELECT DATE_FORMAT($dateto, '%M %D %Y')"), 0);
$unixfrom = mysql_result(mysql_query("SELECT UNIX_TIMESTAMP('$datefrom')"), 0);
$unixto = mysql_result(mysql_query("SELECT UNIX_TIMESTAMP('$dateto')"), 0);

echo("<table width=715 border=0 cellspace=0 cellpadding=0><tr><td><font face=\"Verdana, Arial, Sans-Serif\"><h2>
" . $bill_name . "</h2>");


echo("<h3>Billed Ports</h3>");

$ports = mysql_query("SELECT * FROM interfaces AS I, devices AS D, bill_ports as B WHERE B.bill_id = '$bill_id' AND B.port_id = I.interface_id AND I.device_id = D.device_id");

while ($port = mysql_fetch_array($ports)) {

	echo(generateiflink($port) . " on " . generatedevicelink($port) . "<br />");

}

echo("<h3>Bill Summary</h3>");

if($bill_data['bill_type'] == "quota") {

  // The Customer is billed based on a pre-paid quota

  $percent = round(($total_data / 1024) / $bill_data['bill_gb'] * 100, 2);
  $unit = "MB";
  $total_data = round($total_data, 2);
  echo("Billing Period from " . $fromtext . " to " . $totext . " 
  <br />Transferred ".formatStorage($total_data * 1024 * 1024)." of ".formatStorage($bill_data['bill_gb'] * 1024 * 1024 * 1024)." (".$percent."%)
  <br />Average rate " . formatRates($rate_average * 1000));
  if ($percent > 100) { $percent = "100"; }
  echo("<p><img src='percentage.php?per=$percent&width=350'></p>");

  $type="&ave=yes";


} elseif($bill_data['bill_type'] == "cdr") {

  // The customer is billed based on a CDR with 95th%ile overage

  $unit = "kbps";
  $cdr = $bill_data['bill_cdr'];
  if($rate_95th > "1000") { $rate_95th = $rate_95th / 1000; $cdr = $cdr / 1000; $unit = "Mbps"; }
  if($rate_95th > "1000") { $rate_95th = $rate_95th / 1000; $cdr = $cdr / 1000; $unit = "Gps"; }
  $rate_95th = round($rate_95th, 2);

  $percent = round(($rate_95th) / $cdr * 100, 2);

  $type="&95th=yes";


  echo("<strong>" . $fromtext . " to " . $totext . "</strong>
  <br />Measured ".$rate_95th."$unit of ".$cdr."$unit (".$percent."%)");
  if ($percent > 100) { $percent = "100"; }
  echo("<p><img src='percentage.php?per=$percent&width=350'></p>");

#  echo("<p>Billing Period : " . $fromtext . " to " . $totext . "<br />
#  " . $paidrate_text . " <br />
#  " . $total_data . "MB transfered in the current billing cycle. <br />
#  " . $rate_average . "Kbps Average during the current billing cycle. </p>
#  <font face=\"Trebuchet MS, Verdana, Arial, Sans-Serif\" color=" . $bill_color . "><B>" . $rate_95th . "Kbps @ 95th Percentile.</b> (" . $dir_95th . ") (" . $bill_text . ")</font>
#  </td><td><img src=\"images/billing-key.png\"></td></tr></table>
#  <br />");

}

echo("</td><td><img src='images/billing-key.png'></td></tr></table>");

$bi =       "<img src='billing-graph.php?bill_id=" . $bill_id . "&bill_code=" . $_GET['bill_code']; 
$bi = $bi . "&from=" . $unixfrom .  "&to=" . $unixto;
$bi = $bi . "&x=715&y=250";
$bi = $bi . "$type'>";

$lastmonth = mysql_result(mysql_query("SELECT UNIX_TIMESTAMP(DATE_SUB(NOW(), INTERVAL 1 MONTH))"), 0);
$yesterday = mysql_result(mysql_query("SELECT UNIX_TIMESTAMP(DATE_SUB(NOW(), INTERVAL 1 DAY))"), 0);
$now = date(U);

$di =       "<img src='billing-graph.php?bill_id=" . $bill_id . "&bill_code=" . $_GET['bill_code'];
$di = $di . "&from=" . $yesterday .  "&to=" . $now . "&x=715&y=250";
$di = $di . "$type'>";

$mi =       "<img src='billing-graph.php?bill_id=" . $bill_id . "&bill_code=" . $_GET['bill_code'];
$mi = $mi . "&from=" . $lastmonth .  "&to=" . $now . "&x=715&y=250";
$mi = $mi . "$type'>";

if($null) {

echo("
<script type='text/javascript' src='js/calendarDateInput.js'>
</script>

<FORM action='/' method='get'>
  <INPUT type='hidden' name='bill' value='".$_GET['bill']."'>
  <INPUT type='hidden' name='code' value='".$_GET['code']."'>
  <INPUT type='hidden' name='page' value='bills'>
  <INPUT type='hidden' name='custom' value='yes'>

  From: 
  <script>DateInput('fromdate', true, 'YYYYMMDD')</script>

  To: 
  <script>DateInput('todate', true, 'YYYYMMDD')</script>
  <INPUT type='submit' value='Generate Graph'>

</FORM>

");

}

 if ($_GET[all]) { 
	$ai =       "<img src=\"billing-graph.php?bill_id=" . $bill_id . "&bill_code=" . $_GET['bill_code'];
	$ai = $ai . "&from=0&to=" . $now;
	$ai = $ai . "&x=715&y=250";
	$ai = $ai . "&count=60\">";
	echo("<h3>Entire Data View</h3>$ai"); 
 } elseif ($_GET[custom]) {
        $cg =       "<img src=\"billing-graph.php?bill_id=" . $bill_id . "&bill_code=" . $_GET['bill_code'];
        $cg = $cg . "&from=" . $_GET['fromdate'] . "000000&to=" . $_GET['todate'] . "235959";
        $cg = $cg . "&x=715&y=250";
        $cg = $cg . "&count=60\">";
        echo("<h3>Custom Graph</h3>$cg");
 } else { 
   echo("<h3>Billing View</h3>$bi<h3>24 Hour View</h3>$di");
   #echo("<h3>Monthly View</h3>$li");
   #echo("<br /><a href=\"rate.php?" . $_SERVER['QUERY_STRING'] . "&all=yes\">Graph All Data (SLOW)</a>"); 
 }
}



if($_GET['bill'] && billpermitted($_GET['bill'])){

	        $bill_id = $_GET['bill'];
		printReport($bill_id);	

}

?>

