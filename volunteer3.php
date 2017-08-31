<?php
/*
 * Email Us form
 */
include_once 'includes/event_class.php';
include_once 'includes/volunteer_class.php';
include_once 'includes/maininc.php';

session_start();

if (strpos($_SERVER['HTTP_REFERER'], 'volunteer.php'))
	// Page loaded from volunteer.php
	$referrer = "volunteer.php?m=edit";
else if (strpos($_SERVER['HTTP_REFERER'], 'volunteer2.php'))
	$referrer = "volunteer2.php?m=edit";
else if (strpos($_SERVER['HTTP_REFERER'], 'volunteer3.php')) {
	// Page loaded from process_volunteer3.php
	$referrer = "volunteer2.php?m=edit";
}
else 
	// Page called illegally, send them to the Volunteer page
	header("Location: volunteer.php");
	
$eventID = $_SESSION['editingID'];

if (isset($_SESSION['agreeCoC']))
	$agreeCoC = $_SESSION['agreeCoC'] ? " checked" : "";
else 
	$agreeCoC = "";

if (isset($_GET['m'])) {
 $mode = $_GET['m'];
}
else {
 $mode = '';
}

if ($mode == "coc") {
	// Volunteer didn't accept the terms
	$loadScript = "Error();";
	$mode = "err";
}
if ($mode == "sql") {
	// Error in SQL
	$loadScript = "SQLError();";
	$mode = "err";
}

$volunteer = $_SESSION['editingVolunteer'];

if ($eventID > 0) {
	// Event was selected by volunteer
	if ($mode == "err") {
		$regStartDate = GetStringDateFromTime($_SESSION['dosDate']);
		$regEndDate = GetStringDateFromTime($_SESSION['doeDate']);
	}
	else {
		$regStartDate = $_POST['dos'];
		$_SESSION['dosDate'] = strtotime($regStartDate);
		$regEndDate = $_POST['doe'];
		$_SESSION['doeDate'] = strtotime($regEndDate);
		$regStartDate = GetShortStringDateFromSQLDate($regStartDate);
		$regEndDate = GetShortStringDateFromSQLDate($regEndDate);
	}
	$message = "You have selected the following dates to volunteer your service:";
}
else {
	// Volunteer wants notification only
	$regStartDate = "";
	$_SESSION['dosDate'] = "";
	$message = "You have selected to only receive notices of future trips";
}

$event = new Event($eventID);

$eventStartDate = GetStringDateFromTime($event->StartDate);
$eventEndDate = GetStringDateFromTime($event->EndDate);

// Build conditional values for volunteer
$address = $volunteer->Address1;
if (strlen($volunteer->Address2)>0)
	$address .= "<br/>" . $volunteer->Address2;
$address .= "<br/>$volunteer->City, $volunteer->State $volunteer->Zip";

$phone = "";
if (strlen($volunteer->HomePhone)>0) {
	$phone = $volunteer->HomePhone . " (Home)";
}
if (strlen($volunteer->WorkPhone)>0) {
	if (strlen($phone)>0)
		$phone .= "<br/>";
	$phone .= $volunteer->WorkPhone . " (Work)";
}
if (strlen($volunteer->CellPhone)>0) {
	if (strlen($phone)>0)
		$phone .= "<br/>";
	$phone .= $volunteer->CellPhone . " (Cell)";
}

$dob = "$volunteer->DOBMonth/$volunteer->DOBDay/$volunteer->DOBYear";
	
$pageTitle = "Volunteer";

include_once 'includes/header.php';
?>

<script language="javascript">
function GoBack() {
	window.location = "<?php echo $referrer; ?>";
}

function Error() {
	window.alert("Please accept the terms of the Response and Relief Network Guidelines and Code of Conduct");
}

function SQLError() {
	window.alert("An error occurred while trying to submit your registration.\r\nPlease try again or contact us for assistance.");
}
</script>
<!--[if IE 5]>
<style type="text/css"> 

#sidebar1 {width:258px}
</style>
<![endif]--><!--[if IE]>
<style type="text/css"> 

#mainContent {zoom:1}

</style>
<![endif]-->

<?php 
include_once 'includes/precontent.php';

echo <<<VOLFORM1
<form name="form1" method="post" action="process_volunteer3.php">
<span style="text-align: center"><h2>Volunteer Registration</h2></span>
<table align="center" width="80%" border="0" cellspacing="3">
	<tr><td colspan="2" align="center">Please confirm the information you have entered<br/>
		and accept the Terms and Conditions for Volunteer Service</td></tr>
	<tr><td width="50%">&nbsp;</td><td width="50%">&nbsp;</td></tr>
VOLFORM1;

if (strlen($regStartDate)>0){
	// Registering for a trip
	echo <<<VOLFORM2
	<tr><td colspan="2" align="left" class="largeboldtext">Trip Info</td></tr>
	<tr>
		<td align="right" class="formlabel">Trip Name:</td>
		<td align="left" class="formvalue">$event->Title</td>
	</tr>
	<tr>
		<td align="right" class="formlabel">Location:</td>
		<td align="left" class="formvalue">$event->Location</td>
	</tr>
	<tr>
		<td align="right" class="formlabel">Duration:</td>
		<td align="left" class="formvalue">$eventStartDate to $eventEndDate</td>
	</tr>
	<tr><td colspan="2">&nbsp;</td></tr>
	<tr>
		<td align="right" class="formlabel">Dates you are available:</td>
		<td align="left" class="formvalue">$regStartDate to $regEndDate</td>
	</tr>
VOLFORM2;
}
else {
	echo <<<VOLFORM3
	<tr><td align="center" colspan="2" class="mediumboldunderlinedtext">You are signing up for future notifications only</td></tr>
VOLFORM3;
}
echo <<<VOLFORM4
	<tr><td colspan="2">&nbsp;</td></tr>
	<tr><td colspan="2" align="left" class="largeboldtext">Your Info</td></tr>
	<tr>
		<td align="right" class="formlabel">Name:</td
		<td align="left" class="formvalue">$volunteer->FirstName $volunteer->LastName</td>
	</tr>
	<tr>
		<td align="right" valign="top" class="formlabel">Address:</td>
		<td align="left" class="formvalue">$address</td>
	</tr>
	<tr>
		<td align="right" valign="top" class="formlabel">Phone:</td>
		<td align="left" class="formvalue">$phone</td>
	</tr>
	<tr>
		<td align="right" class="formlabel">Email:</td>
		<td align="left" class="formvalue">$volunteer->Email</td>
	</tr>
	<tr>
		<td align="right" class="formlabel">Date of Birth:</td>
		<td align="left" class="formvalue">$dob</td>
	</tr>
	<tr>
		<td align="right" class="formlabel">Home Church:</td>
		<td align="left" class="formvalue">$volunteer->HomeChurch</td>
	</tr>
	<tr>
		<td align="right" class="formlabel">Skills and Certifications:</td>
		<td align="left" class="formvalue">$volunteer->SkillsCerts</td>
	</tr>
	<tr><td colspan="2">&nbsp;</td></tr>
	<tr>
		<td colspan="2" align="left" class="formlabel">Read the Guidelines and Code of Conduct</td>
	</tr>
	<tr>
		<td colspan="2">
			<div id="scrollContent">
VOLFORM4;
include_once 'includes/code.php';
echo <<<VOLFORM5
			</div>
		</td>
	</tr>
	<tr>
		<td colspan="2" align="center">
			<input type="checkbox" name="agreeCoC" $agreeCoC/>
			Accept the terms of the Response and Relief<br/>Network Guidelines and Code of Conduct
		</td>
	</tr>
	<tr>
		<td align="center">
			<input type="submit" value = "  Complete Registration  " />
		</td>
		<td align="center">
			<input type="button" value = "    Return to Previous   " onclick="GoBack();" />
		</td>
	</tr>
</table>
</form>
VOLFORM5;
?>
<br class="clearfloat" />
</div>
<!-- / #outerdiv -->
</body>
</html>