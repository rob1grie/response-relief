<?php
/*
 * Include this in pages between the HEAD tags
 * 	Inserted text ends before the closing HEAD tag, allowing for adding Javascript for control events
 * 
 * Setup before include:
 * 	$pageTitle
 * 	$pageSubtitle
 * 
*/
require_once '../includes/utilities.php';
require_once 'includes/log_class.php';

$pageTitle = "Response and Relief Network - " . $pageSubtitle;

if (!isset($_SESSION)) {
  session_start();
}

Log::LogActivity($_SESSION['staff'], GetScriptName());

echo <<<HEADER
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="shortcut icon" href="favicon.ico" >
<title>$pageTitle</title>
	<link href="staff.css" type="text/css" rel="stylesheet" />
HEADER;
?>
