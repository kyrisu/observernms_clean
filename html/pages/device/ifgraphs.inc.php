<?php
echo("
<div style='float: right; text-align: right;'>
<a href='/?page=device&id=" . $_GET['id'] . "&section=dev-ifgraphs&type=bits'>Bits</a> | 
<a href='/?page=device&id=" . $_GET['id'] . "&section=dev-ifgraphs&type=pkts'>Packets</a> | 
<a href='/?page=device&id=" . $_GET['id'] . "&section=dev-ifgraphs&type=nupkts'>NU Packets</a> |
<a href='/?page=device&id=" . $_GET['id'] . "&section=dev-ifgraphs&type=errors'>Errors</a>
</div>");

$dographs = 1;

if(!$_GET['type']) { $_GET['type'] = "bits"; }

include("pages/device/dev-ifs.inc");

?>