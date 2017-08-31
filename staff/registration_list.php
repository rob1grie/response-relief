<?php
// Shows list of Registrations
// Drop down allows selection of All or for selected Event
require_once '../includes/registration_class.php';
require_once '../includes/gallery_class.php';
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

// Get list of Registrations based on eventID
$allRegistrations = Registration::GetRegistrations($eventID);

$pageSubtitle = "Registrations List";
include_once 'includes/admin_main_header.php';

echo <<<JAVASCRIPT
<script language="JavaScript">
function ChangeEvent(selObj) {
	var idx = selObj.selectedIndex;
	var which = selObj.options[idx].value;
	window.location = "registration_list.php?id=" + which;
}

</script>
JAVASCRIPT;

include_once 'includes/admin_main_body_precontent.php';
include_once 'includes/leftmenu.php';
include_once 'includes/admin_main_body_postmenu.php';

echo "<td align='center' valign='top'>";
echo <<<CONTENT1
<table width="100%" border="1" cellpadding="2" cellspacing="0">
	<tr><td colspan="4" align="center">Select Event: $eventDropDown</td></tr>
	<tr class="tableheader">
		<td>Event</td><td>Volunteer</td><td>Date of Reg</td><td>&nbsp;</td>
	</tr>
CONTENT1;

foreach ($allRegistrations as $reg) {
	$registration = $reg['Registration'];
	$event = $reg['Event'];
	$volunteer = $reg['Volunteer'];
	$regDate = GetStringDateFromTime($registration->DateOfReg);
	
	echo <<<CONTENT2
	<tr>
		<td class="formvalue">$event->Title</td>
		<td class="formvalue">$volunteer->LastName, $volunteer->FirstName</td>
		<td class="formvalue">$regDate</td>
		<td class="formvalue">&nbsp;</td>
	</tr>
CONTENT2;
}

echo <<<CONTENT3
</table>
CONTENT3;

include_once 'includes/admin_main_body_postcontent.php';
?>
