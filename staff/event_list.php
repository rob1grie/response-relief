<?php
// List all Events for maintenance
include_once '../includes/gallery_class.php';
include_once '../includes/event_class.php';
include_once '../includes/utilities.php';
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

$eventList = Event::GetAllEvents();
$pageSubtitle = "Event List";

// Check for whether page is sent following error
if(isset($_GET['mode']) && (strlen($_GET['mode'])>0)) {
	$loadScript = "onload=error();";
}
else
	$loadScript = "";

include_once 'includes/admin_main_header.php';
echo <<<JAVASCRIPT
<script language="JavaScript">
function deleteEvent(id) {
	if (window.confirm("Delete this Event?"))
		window.location = "event_update.php?id=" + id + "&mode=del";
}
function editEvent(id) {
	window.location = "event_edit.php?m=edit&id=" + id;
}
function addEvent() {
	window.location = "event_edit.php?m=add"
}
function error() {
	window.alert("There was an error while saving your changes. Please try again.");
}
</script>
JAVASCRIPT;
include_once 'includes/admin_main_body_precontent.php';
include_once 'includes/leftmenu.php';
include_once 'includes/admin_main_body_postmenu.php';
echo "<td valign=\"top\">";

echo <<<TABLETOP
<table width="100%" align="left" border="1" cellpadding="2" cellspacing="0">
	<tr><td colspan="6" align="center">
		<input type="button" value="        Add Event        ", onclick="addEvent();" />
	</td></tr>
	<tr><td colspan="6">&nbsp;</td></tr>
	<tr class="tableheader">
		<td>Title</td>
		<td>Location</td>
		<td align="center">Start Date</td>
		<td align="center">End Date</td>
		<td align="center">&nbsp;</td>
		<td align="center">&nbsp;</td>
	</tr>
TABLETOP;
for ($i=0; $i<count($eventList); $i++) {
	$event = $eventList[$i];
	$startDate = getdate($event->StartDate);
	$startDate = $startDate['mon'] . "/" . $startDate['mday'] . "/" . $startDate['year'];
	$endDate = getdate($event->EndDate);
	$endDate = $endDate['mon'] . "/" . $endDate['mday'] . "/" . $endDate['year'];
	
	echo <<<EVENTROW
	<tr>
		<td class="formvalue">$event->Title</td>
		<td class="formvalue">$event->Location</td>
		<td class="formvalue">$startDate</td>
		<td class="formvalue">$endDate</td>
		<td align="center"><a href="javascript:editEvent($event->ID);">View/Edit</a></td>
		<td align="center"><a href="javascript:deleteEvent($event->ID);">Delete</a></td>
	</tr>
EVENTROW;
}
echo "</table>";
include_once 'includes/admin_main_body_postcontent.php';
?>