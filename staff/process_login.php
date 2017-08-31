<?php
require_once('../includes/dbmanager.php');
require_once('includes/staff_class.php');
require_once '../includes/utilities.php';
require_once 'includes/log_class.php';

if (!isset($_SESSION)) {
  session_start();
}

// Script to process the login
$username = $_POST['username'];
$userpass = $_POST['userpass'];

$id = Staff::ValidateUser($username, $userpass);


if ($id > -1) {
	// Valid login, go to staffindex.php
	$staff = new Staff($id);
	
	$_SESSION['staff'] = $staff;
	
	Log::LogActivity($staff, 'stafflogin.php');

	if ($staff->ResetPassword == 1)
		$loc = "changepass.php?m=reset";
	else
		$loc = "index.php";
}
else {
	$loc = "stafflogin.php";
}

header("Location: $loc");

?>