<?php
/*
 * Email Us form
 */

require_once 'calendar/classes/tc_calendar.php';
include_once 'includes/event_class.php';
include_once 'includes/volunteer_class.php';
include_once 'includes/maininc.php';

session_start();

// If form is accessed from volunteer3.php, get mode
if (isset($_GET['m'])) {
 $mode = $_GET['m'];
}
else {
 $mode = '';
}

// Get event ID and its range of dates
if (isset($_SESSION['editingID'])) {
 $eventID = $_SESSION['editingID'];
}
else {
 $eventID = 0;
}

if ($eventID == 0) {
	// If eventID is 0, only signing up for future notices. Continue on to volunteer3.php
	header("Location: volunteer3.php");
}

$event = new Event($eventID);

// If form is aceessed from volunteer3.php, mode is set, need to get dates from Session
if ($mode == "edit") {
	$selectStart = getdate($_SESSION['dosDate']);
	$selectEnd = getdate($_SESSION['doeDate']);
}
else {
	if(isset($_SESSION['dosDate']))
		$selectStart = getdate($_SESSION['dosDate']);
	else
		$selectStart = getdate($event->StartDate);
		
	if(isset($_SESSION['doeDate']))
		$selectEnd = getdate($_SESSION['doeDate']);
	else
		$selectEnd = getdate($event->EndDate);
}

$startDate = GetSQLDate($event->StartDate);
$endDate = GetSQLDate($event->EndDate);
$strStartDate = GetStringDateFromTime($event->StartDate);
$strEndDate = GetStringDateFromTime($event->EndDate);

// Date of Start
$dosCalendar = new tc_calendar("dos", true, false);
$dosCalendar->setIcon("calendar/images/iconCalendar.gif");
$dosCalendar->setPath("calendar/");
$dosCalendar->setDateFormat('M j, Y');
$dosCalendar->dateAllow($startDate, $endDate, false);
$dosCalendar->setDate($selectStart['mday'], $selectStart['mon'], $selectStart['year']);

// Date of End
$doeCalendar = new tc_calendar("doe", true, false);
$doeCalendar->setIcon("calendar/images/iconCalendar.gif");
$doeCalendar->setPath("calendar/");
$doeCalendar->setDateFormat('M j, Y');
$doeCalendar->dateAllow($startDate, $endDate, false);
$doeCalendar->setDate($selectEnd['mday'], $selectEnd['mon'], $selectEnd['year']);

$pageTitle = "Volunteer";

include_once 'includes/header.php';
?>
<script language="javascript" src="calendar/calendar.js"></script>

<script language="javascript">
function GoBack() {
	window.location = "volunteer.php?m=edit";
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
<form name="form1" method="post" action="volunteer3.php">
<table width="80%" border="0" cellspacing="7">
	<tr><td colspan="4" align="center"><h2>Volunteer Registration</h2></td></tr>
	<tr><td colspan="4" align="center">You are signing up for the following trip:</td></tr>
	<tr>
		<td colspan="2" align="right" class="formlabel">Trip Name:</td>
		<td colspan="2" align="left" class="formvalue">$event->Title</td>
	</tr>
	<tr>
		<td colspan="2" align="right" class="formlabel">Location:</td>
		<td colspan="2" align="left" class="formvalue">$event->Location</td>
	</tr>
	<tr>
		<td colspan="2" align="right" class="formlabel">Duration:</td>
		<td colspan="2" align="left" class="formvalue">$strStartDate to $strEndDate</td>
	</tr>
	<tr><td colspan="4">&nbsp;</td></tr>
	<tr><td colspan="4" align="center">Please select the dates you will be available</td></tr>
	<tr>
		<td align="right">Start date:</td>
		<td>
			<div class="date-tccontainer" style="position:relative; z-index:100">
VOLFORM1;
$dosCalendar->writeScript();
echo <<<VOLFORM2
			</div>
		</td>
		<td align="right">End date:</td>
		<td>
			<div  class="date-tccontainer" style="z-index:50">
VOLFORM2;
$doeCalendar->writeScript();
echo <<<VOLFORM3
			</div>
		</td>
	</tr>
	<tr><td colspan="4">&nbsp;</td></tr>
	<tr>
		<td colspan="2" align="center">
			<input type="submit" value="           Continue            " />
		</td>
		<td colspan="2" align="center">
			<input type="button" value="       Return to Previous      " onclick="GoBack();" />
		</td>
	</tr>
</table>
</form>
VOLFORM3;
?>
<br class="clearfloat" />
</div>
<!-- / #outerdiv -->
</body>
</html>