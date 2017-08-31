<?php
// Page to enter or edit Gallery
include_once '../includes/gallery_class.php';
include_once '../includes/event_class.php';
include_once '../includes/utilities.php';
require_once 'includes/log_class.php';

if (!isset($_SESSION)) {
  session_start();
}

if (!isset($_SESSION['staff'])) {
  header("Location: stafflogin.php");
}

Log::LogActivity($_SESSION['staff'], GetScriptName());

include_once '../koschtit/ki_include.php';

// If no mode in URL go to gallery_list.php
if (!isset($_GET['m']) || (strlen($_GET['m'])==0)) {
	header('Location: gallery_list.php');
}

$loadScript = "";
$message = "&nbsp;";
$mode = $_GET['m'];

if (($mode=='err') || ($mode=='field')) {
	// If mode is an error mode set from gallery_update, set up mode for editing
	$errMode = $mode;
	$mode = $_SESSION['editMode'];
}
else {
	// Otherwise, it is an actual edit mode; save it to session
	$_SESSION['editMode'] = $mode;
}

if ($errMode == "err") {
	$errorFields = $_SESSION['errorFields'];
	$loadScript = "OnLoad='error();'";
	$gallery = $_SESSION['editingGallery'];
	$id = $_SESSION['editingID'];
}
else if ($errMode == 'field') {
	$errorFields = $_SESSION['errorFields'];
	$message = "Please check required fields";
	$gallery = $_SESSION['editingGallery'];
	$id = $_SESSION['editingID'];
}
else if ($mode == "edit") {
	// Get Event to edit
	if (!isset($_GET['id']) || (strlen($_GET['id'])==0)) {
		header('Location: gallery_list.php');
	}
	$id = $_GET['id'];
	$gallery = new Gallery($id);
	$_SESSION['editingGallery'] = $gallery;
	$_SESSION['editingID'] = $gallery->ID;
	
	$pageSubtitle = "Edit Photo Gallery Entry";
}
else if ($mode == "add") {
	$gallery = new Gallery();
//	$event = Event::GetDummyEvent();
	$_SESSION['editingGallery'] = $gallery;
	$_SESSION['editingID'] = $gallery->ID;
	
	$pageSubtitle = "Add Photo Gallery Entry";
}

// Build Event drop down
$allEvents = Event::GetAllEvents();
$eventDropDown = "<select name='eventDropDown'>";
$eventDropDown .= "<option value='-1'";
if ($gallery->EventID == -1)
	$eventDropDown .= " selected";
$eventDropDown .= ">[Select Event]</option>";
foreach ($allEvents as $event) {
	$eventDropDown .= "<option value='$event->ID'";
	if ($gallery->EventID == $event->ID)
		$eventDropDown .= " selected";
	$eventDropDown .= ">$event->Title</option>";
}
$eventDropDown .= "</select>";

// Build Gallery Directory drop down
$allDirectories = Gallery::GetGalleryDirList();
$dirDropDown = "<select name='dirDropDown'>";
$dirDropDown .= "<option value='-1'";
if ($gallery->Directory == '[Select Directory]')
	$dirDropDown .= " selected";
$dirDropDown .= ">[Select Directory]</option>";
foreach ($allDirectories as $dir) {
	$dirDropDown .= "<option value='$dir'";
	if ($gallery->Directory == $dir)
		$dirDropDown .= " selected";
	$dirDropDown .= ">$dir</option>";
}
$dirDropDown .= "</select>";

include_once 'includes/admin_main_header.php';

echo "<script language=\"javascript\" src=\"../calendar/calendar.js\"></script>";

echo <<<JAVASCRIPT
<script language="JavaScript">
function Cancel() {
	window.location = "gallery_list.php"
}
function Save() {
	document.form1.action="gallery_update.php?mode=$mode";
	document.form1.submit();
}
function error() {
	window.alert("There was an error while saving your changes. Please try again.");
}

</script>
JAVASCRIPT;

include_once 'includes/admin_main_body_precontent.php';
echo "<td colspan=\"2\" valign=\"top\" align=\"center\">";

// Builds array of flags for fields that have errors
$errorFlags = array();
@$errorFlags['name'] = in_array('name', $errorFields) ? "<<" : " ";
@$errorFlags['event'] = in_array('event', $errorFields) ? "<<" : " ";
@$errorFlags['directory'] = in_array('directory', $errorFields) ? "<<" : " ";

// Insert table for Gallery editing
echo <<<GALLERYEDIT
	<input type="hidden" name="id" value="$gallery->ID" />
	<table width="90%" align="center" border="0">
		<tr>
			<td colspan="2" align="center">
				<table width="60%" border="0">
					<tr>
						<td align="center" width="50%"><input type="button" value="      Save      " onclick="Save();" /></td>
						<td align="center" width="50%"><input type="button" value = "     Cancel     " onclick="Cancel();" /></td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td colspan="2" align="center" class="docunderlineboldsmall">All fields are required.</td>
		</tr>
		<tr>
			<td align="center" colspan="2" class="errortext">$message</td>
		</tr>
		<tr>
			<td width="30%" align="right" class="formlabel">
				Gallery Name: 
			</td>
			<td width="70%" align="left" colspan="3">
				<input type="text" size="40" name="name" value="$gallery->Name" />
				<span class="errortextsmall">{$errorFlags['name']}</span>
			</td>
		</tr>
		<tr>
			<td align="right" class="formlabel">
				Event:
			</td>
			<td align="left">
				$eventDropDown
				<span class="errortextsmall">{$errorFlags['event']}</span>
			</td>
		</tr>
		<tr>
			<td align="right" class="formlabel">
				Directory:</td>
			<td align="left">
				$dirDropDown
				<span class="errortextsmall">{$errorFlags['directory']}</span>
			</td>
		</tr>
GALLERYEDIT;

echo "</table>";
include_once 'includes/admin_main_body_postcontent.php';
?>