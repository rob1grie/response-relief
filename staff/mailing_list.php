<?php
// Shows list of Volunteers
// Drop down allows selection of All or for selected Event

require_once '../includes/volunteer_class.php';
require_once '../includes/event_class.php';
require_once '../includes/utilities.php';
require_once 'includes/staff_class.php';
require_once 'includes/log_class.php';

if (!isset($_SESSION)) {
  session_start();
}

if (!isset($_SESSION['staff'])) {
  header("Location: stafflogin.php");
}

Log::LogActivity($_SESSION['staff'], GetScriptName());

ClearEditSession();

if (isset($_GET['id']) && (strlen($_GET['id'])>0)) {
	$eventID = $_GET['id'];
	$_SESSION['selectedEvent'] = $eventID;
}
else if (isset($_SESSION['selectedEvent']) && (strlen($_SESSION['selectedEvent'])>0))
	$eventID = $_SESSION['selectedEvent'];
else
	$eventID = NULL;
	
// Get list of all Events and build eventDropDown
$allEvents = Event::GetAllEvents();
$eventDropDown = "<select name='eventDropDown' onchange='ChangeEvent(this);'>";
$eventDropDown .= "<option value='0'"; 
if ($eventID == 0) 
	$eventDropDown .= " selected";
$eventDropDown .= ">All Events</option>";
foreach ($allEvents as $event) {
	$eventStart = GetStringDateFromTime($event->StartDate);
	$eventEnd = GetStringDateFromTime($event->EndDate);
	$eventDropDown .= "<option value='$event->ID'";
	if ($eventID == $event->ID)
		$eventDropDown .= " selected";
	$eventDropDown .= ">$event->Title - $eventStart to $eventEnd</option>";
}
$eventDropDown .= "</select>";

// Get list of Volunteers based on eventID if not already done
if (!isset($_SESSION['filterVolunteers'])) {
	$filterVolunteers = Volunteer::GetEventVolunteers($eventID);
	$_SESSION['filterVolunteers'] = $filterVolunteers;
}
else {
	$filterVolunteers = $_SESSION['filterVolunteers'];
}

// Get all Volunteers if not already done
if (!isset($_SESSION['allVolunteers'])) {
	$allVolunteers = Volunteer::GetEventVolunteers();
	$_SESSION['allVolunteers'] = $allVolunteers;
}
else {
	$allVolunteers = $_SESSION['allVolunteers'];
}

$pageSubtitle = "Select Mailout Recipients";

include_once 'includes/admin_main_header.php';

echo <<<JAVASCRIPT
<script language="JavaScript">
function ChangeEvent(selObj) {
	var idx = selObj.selectedIndex;
	var which = selObj.options[idx].value;
	document.form1.action = "mailing_select.php?id=" + which + "&m=select";
	document.form1.submit();
}

function error() {
	window.alert("There was an error while saving your changes. Please try again.");
}

</script>
JAVASCRIPT;

include_once 'includes/admin_main_body_precontent.php';
include_once 'includes/leftmenu.php';
include_once 'includes/admin_main_body_postmenu.php';

echo "<td align='center' valign='top'>";
echo <<<CONTENT1
<table width="100%" border="1" cellpadding="2" cellspacing="0">
	<tr><td colspan="4" align="center">
		<table width="100%" border="0">
			<tr>
				<td width="25%" align="center">
				</td>
				<td width="25%" align="center">&nbsp;</td>
				<td width="25%" align="center">&nbsp;</td>
				<td width="25%" align="center">&nbsp;</td>
			</tr>
		</table>
	</td></tr>
	<tr><td colspan="4" align="center">Select Event: $eventDropDown</td></tr>
	<tr class="tableheader">
		<td align="center">
			<a href="javascript:CheckAll();"><img src="../images/checked.gif" border="1" /></a>&nbsp;
			<a href="javascript:UncheckAll();"><img src="../images/unchecked.gif" border="1" /></a>
		</td>
		<td>Volunteer</td>
		<td>City/State</td>
		<td>&nbsp;</td>
	</tr>		
CONTENT1;
foreach ($filterVolunteers as $vol) {
	$checked = ($vol->Selected == 1) ? "checked=\"checked\"" : "";
	$id = $vol->ID;
	echo <<<SELECT2
	<tr>
		<td align="center"><input type="checkbox" value="$id" name="check$id" $checked /></td>
		<td class="formvalue">$vol->LastName, $vol->FirstName</td>
		<td class="formvalue">$vol->City, $vol->State</td>
		<td>&nbsp;</td>
	</tr>
SELECT2;
} // Close foreach

echo "</table>";

include_once 'includes/admin_main_body_postcontent.php';
?>
