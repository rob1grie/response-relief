<?php
session_start();

$gal = $_GET['gal'];
if(isset($gal) && (strlen($gal)>0)) {
	$_SESSION['selected_gallery'] = $gal;
}
else {
	unset($_SESSION['selected_gallery']);
}
header("Location:photos.php");

?>