<?php 
include_once 'includes/gallery_class.php';
include_once 'includes/event_class.php';
include_once 'includes/maininc.php';

ClearEditSession();

$pageTitle = "Info";

include_once 'includes/header.php';

echo <<<JAVASCRIPT
<script language="JavaScript" type="text/JavaScript">
function Register(id) {
	window.location = "volunteer.php?id=" + id;
}
function ShowGallery(id) {
	window.location = "photos.php?id=" + id;
}
</script>
JAVASCRIPT;

include_once 'includes/precontent.php';
include_once 'includes/leftnav.php';

$events = Event::GetAllEvents();
	
?>
<div id="content">
	<div class="titletext">Current Trip Info</div>
	<div class="messagetext">(Click a trip's title to expand its details)</div>
	<div class="accordion_container">
		<ul class="acc_options_ul">
<?php 
for ($i=0; $i<count($events); $i++) {
	$event = $events[$i];
	$gallery = Gallery::GetEventGallery($event->ID);
	$event->PhotoGalleryID = $gallery->ID;
	$startDate = getdate($event->StartDate);
	$startDate = $startDate['mon'] . "/" . $startDate['mday'] . "/" . $startDate['year'];
	$endDate = getdate($event->EndDate);
	$endDate = $endDate['mon'] . "/" . $endDate['mday'] . "/" . $endDate['year'];
	$description = ReplaceCRLF($event->Description);
	
echo <<<EVENTITEM1
<li class="acc_options"><h1 class="acc_options_title">$event->Title, $event->Location -- $startDate to $endDate</h1>
<ul class="acc_op_content_ul">
<li class="acc_op_content">
<table width="100%" class="acc_table">
	<tr>
		<td class="boldtext">Dates: </td>
		<td>$startDate to $endDate</td>
	</tr>
	<tr>
		<td class="boldtext" valign="top">Description: </td>
		<td>$description</td>
	</tr>
	<tr>
		<td class="boldtext">Depart From: </td>
		<td>$event->Departure</td>
	</tr>
	<tr>
		<td class="boldtext" valign="top">What to Bring: </td>
		<td>$event->WhatToBring</td>
	</tr>
	<tr>
		<td class="boldtext">Where we'll stay: </td>
		<td>$event->WhereToStay</td>
	</tr>
	<tr>
		<td class="boldtext" nowrap valign="top">Age requirements: </td>
		<td>$event->AgeRequirements</td>
	</tr>
	<tr>
		<td class="boldtext" nowrap valign="top">Health requirements: </td>
		<td>$event->HealthRequirements</td>
	</tr>
EVENTITEM1;
if (($event->EndDate > time()) || ($event->PhotoGalleryID > -1)) {
	if ($event->EndDate > time())
		$eventButton = "<input type=\"button\" value=\"Register for this Trip\" onclick=\"Register($event->ID);\" />";
	else 
		$eventButton = "&nbsp;";
		
	if ($event->PhotoGalleryID > -1){
		$galleryButton = "<input type=\"button\" 
			value=\"View Photo Gallery\" onclick=\"ShowGallery($event->PhotoGalleryID);\" />";
	}
	else 
		$galleryButton = "&nbsp;";
echo <<<EVENTITEM2
	<tr>
		<td align="left">
			$galleryButton
		</td>
		<td align="right">
			$eventButton
		</td>
	</tr>
EVENTITEM2;
}
echo <<<EVENTITEM3
</table>
</li></ul></li>
EVENTITEM3;
}
echo "</ul></div>";
?>

</div>
<?php 
include_once 'includes/postcontent.php';
?>