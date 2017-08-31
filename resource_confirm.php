<?php 
include_once 'includes/resource_class.php';
include_once 'includes/maininc.php';

session_start();

if (isset($_GET['m'])) {
 $mode = $_GET['m'];
}
else {
 $mode = '';
}

if ($mode == "sql") {
	// Error in SQL
	$loadScript = "SQLError();";
	$mode = "err";
}

$resource = $_SESSION['editingResource'];

// Set up dependent values
$address = $resource->Address1;
if(strlen($resource->Address2)>0)
	$address .= "<br/>$resource->Address2";
$address .= "<br/>$resource->City, $resource->State $resource->Zip";

$phone = "";
if (strlen($resource->HomePhone)>0)
	$phone = "$resource->HomePhone (Home)";
if (strlen($resource->WorkPhone)>0){
	if (strlen($phone)>0)
		$phone .= "<br/>";
	$phone .= "$resource->WorkPhone (Work)";
}
if (strlen($resource->CellPhone)>0) {
	if (strlen($phone)>0)
		$phone .= "<br/>";
	$phone .= "$resource->CellPhone (Cell)";
}

$duration = "";
if ($resource->LoanIndef)
	$duration = "Resource is available whenever you need it";
else if ($resource->Donate)
	$duration = "This is a permanent donation";
else {
	$duration = "Resource is available from " . GetShortStringDateFromSQLDate($resource->LoanStart ) .
				" to " . GetShortStringDateFromSQLDate($resource->LoanEnd);
}

$pageTitle = "Resources";

include_once 'includes/header.php';

echo <<<JAVASCRIPT
<script language="javascript" src="calendar/calendar.js"></script>

<script language="JavaScript" type="text/JavaScript">
function GoBack() {
	window.location = "resources.php";
}

function SQLError() {
	window.alert("An error occurred while trying to record your contribution.\\r\\nPlease try again or contact us for assistance.");
}
</script>
JAVASCRIPT;

include_once 'includes/precontent.php';
include_once 'includes/leftnav.php';
echo <<<FORM1
<div id="content">
<form method="POST" action="process_resource2.php">
<span style="text-align: center"><h2>Resources</h2></span>
	<table border="0">
		<tr>
			<td colspan="4" align="center" class="formvalue">
				<p>Please confirm your information and click 'Confirm Resource'</p>
			</td>
		</tr>
		<tr>
			<td align="right" class="formlabel">Your name:</td>
			<td colspan="3" align="left" class="formvalue">$resource->Name</td>
		</tr>
		<tr>
			<td align="right" class="formlabel">Adress:</td>
			<td colspan="3" align="left" class="formvalue">$address</td>
		</tr>
		<tr>
			<td align="right" class="formlabel">Phone:</td>
			<td colspan="3" align="left" class="formvalue">$phone</td>
		</tr>
		<tr>
			<td align="right" class="formlabel">Email:</td>
			<td colspan="3" align="left" class="formvalue">$resource->Email</td>
		</tr>
		<tr><td colspan="4">&nbsp;</td></tr>
		<tr>
			<td align="right" valign="top" class="formlabel">Description:</td>
			<td colspan="3" align="left" class="formvalue">$resource->Description</td>
		</tr>
		<tr><td colspan="4">&nbsp;</td></tr>
		<tr>
			<td colspan="4" align="center" class="formvalue">$duration</td>
		</tr>
		<tr><td colspan="4">&nbsp;</td></tr>
		<tr>
			<td colspan="2" align="center">
				<input type="submit" value="      Confirm Resource      " />
			</td>
			<td colspan="2" align="center">
				<input type="button" value="     Return to Previous     " onclick="GoBack();" />
			</td>
		</tr>
		<tr><td colspan="4">&nbsp;</td></tr>
</table>
</form>
FORM1;
include_once 'includes/postcontent.php';
?>