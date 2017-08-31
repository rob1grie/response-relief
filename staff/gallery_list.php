<?php
// List all Galleries for maintenance
include_once '../includes/event_class.php';
include_once '../includes/gallery_class.php';
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

$galleryList = Gallery::GetAllGalleries();
$pageSubtitle = "Photo Gallery List";

// Check for whether page is sent following error
if(isset($_GET['mode']) && (strlen($_GET['mode'])>0)) {
	$loadScript = "onload=error();";
}
else
	$loadScript = "";

include_once 'includes/admin_main_header.php';
echo <<<JAVASCRIPT
<script language="JavaScript">
function deleteGallery(id) {
	if (window.confirm("Delete this Gallery?"))
		window.location = "gallery_update.php?id=" + id + "&mode=del";
}
function editGallery(id) {
	window.location = "gallery_edit.php?m=edit&id=" + id;
}
function addGallery() {
	window.location = "gallery_edit.php?m=add"
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
<table width="80%" align="left" border="1" cellpadding="2" cellspacing="0">
	<tr><td colspan="6" align="center">
		<input type="button" value="        Add Gallery        ", onclick="addGallery();" />
	</td></tr>
	<tr><td colspan="6">&nbsp;</td></tr>
	<tr class="tableheader">
		<td>Gallery Name</td>
		<td>Event</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
	</tr>
TABLETOP;
for ($i=0; $i<count($galleryList); $i++) {
	$gallery = $galleryList[$i];
	$event = new Event($gallery->EventID);
	
	echo <<<EVENTROW
	<tr>
		<td class="formvalue">$gallery->Name</td>
		<td class="formvalue">$event->Title</td>
		<td align="center"><a href="javascript:editGallery($gallery->ID);">View/Edit</a></td>
		<td align="center"><a href="javascript:deleteGallery($gallery->ID);">Delete</a></td>
	</tr>
EVENTROW;
}
echo "</table>";
include_once 'includes/admin_main_body_postcontent.php';
?>