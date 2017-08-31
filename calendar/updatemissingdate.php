<?php
// Bug in date picker sends to autosend target_url in calendar directory instead of source directory
// Build new target_url and redirect
$date = $_GET['date1'];
$loc = "../updatemissingdate.php?date1=$date";
header("Location: $loc");

?>