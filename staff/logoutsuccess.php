<?php
require_once('../includes/utilities.php');
require_once 'includes/staff_class.php';
require_once 'includes/log_class.php';

if (!isset($_SESSION)) {
  session_start();
}

Log::LogActivity($_SESSION['staff'], GetScriptName());

ClearSession();
?>
<html>
<head>
	<title>Response &amp; Relief Staff - Logout Successful</title>
	<link href="staff.css" rel="stylesheet" type="text/css" />
</head>
<body>
	<table width="100%" border="0" id="tbl_page">
		<tr>
			<td width="15%" rowspan="4" valign="top" style="padding-right: 10px;">
				<div align="center"><img src="../images/stafflogo.jpg" border="0"></div>
			</td>
			<td width="70%" class="maintitle">Response-Relief.net</td>
			<td width="15%">&nbsp;</td>
		</tr>
		<tr>
			<td class="subtitle">You have successfully logged out.
			</td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td class="bodylinksmall">&nbsp;</td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td valign="top"><p><a href="stafflogin.php">Log back in</a>
				</p>
				<p><a href="..">Go to Response-Relief.net home page</a>
				</p>
			</td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
		</tr>
	</table>
</body>
</html>