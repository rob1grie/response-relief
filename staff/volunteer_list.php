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

// Get list of Volunteers based on eventID
$allVolunteers = Volunteer::GetEventVolunteers($eventID);

$pageSubtitle = "Volunteers List";
include_once 'includes/admin_main_header.php';

echo <<<JAVASCRIPT
<script language="JavaScript">
function ChangeEvent(selObj) {
	var idx = selObj.selectedIndex;
	var which = selObj.options[idx].value;
	window.location = "volunteer_list.php?id=" + which;
}

function deleteVolunteer(id) {
	if (window.confirm("Delete this Volunteer?"))
		window.location = "volunteer_update.php?id=" + id + "&mode=del";
}

function editVolunteer(id) {
	window.location = "volunteer_edit.php?m=edit&id=" + id;
}

function addVolunteer() {
	window.location = "volunteer_edit.php?m=add"
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
	<tr><td colspan="6" align="center">
		<input type="button" value="        Add Volunteer        ", onclick="addVolunteer();" />
	</td></tr>
	<tr><td colspan="4" align="center">Select Event: $eventDropDown</td></tr>
	<tr class="tableheader">
		<td>Volunteer</td>
		<td>City/State</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
	</tr>
CONTENT1;

foreach ($allVolunteers as $vol) {
	echo <<<CONTENT2
	<tr>
		<td class="formvalue">$vol->LastName, $vol->FirstName</td>
		<td class="formvalue">$vol->City, $vol->State</td>
		<td align="center"><a href="javascript:editVolunteer($vol->ID);">View/Edit</a></td>
		<td align="center"><a href="javascript:deleteVolunteer($vol->ID);">Delete</a></td>
	</tr>
CONTENT2;
}

echo <<<CONTENT3
</table>
CONTENT3;

include_once 'includes/admin_main_body_postcontent.php';
?>
