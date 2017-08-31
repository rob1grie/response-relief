<?php
// Page to enter or edit Event
require_once '../calendar/classes/tc_calendar.php';
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

// If no mode in URL go to staffobitlist.php
if (!isset($_GET['m']) || (strlen($_GET['m'])==0)) {
	header('Location: event_list.php');
}

$loadScript = "";
$message = "&nbsp;";
$mode = $_GET['m'];

if (($mode=='err') || ($mode=='field')) {
	// If mode is an error mode set from updateapplicant, set up mode for editing
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
	$event = $_SESSION['editingEvent'];
	$id = $_SESSION['editingID'];
}
else if ($errMode == 'field') {
	$errorFields = $_SESSION['errorFields'];
	$message = "Please check required fields";
	$event = $_SESSION['editingEvent'];
	$id = $_SESSION['editingID'];
//	echo "DOD=" . strlen($obit->DateOfDeath);
//	echo "DOS=" . strlen($obit->DateOfService);
}
else if ($mode == "edit") {
	// Get Event to edit
	if (!isset($_GET['id']) || (strlen($_GET['id'])==0)) {
		header('Location: event_list.php');
	}
	$id = $_GET['id'];
	$event = new Event($id);
	$_SESSION['editingEvent'] = $event;
	$_SESSION['editingID'] = $event->ID;
	
	$pageSubtitle = "Edit Event Entry";
}
else if ($mode == "add") {
	$event = new Event();
//	$event = Event::GetDummyEvent();
	$_SESSION['editingEvent'] = $event;
	$_SESSION['editingID'] = $event->ID;
	
	$pageSubtitle = "Add Event Entry";
}

include_once 'includes/admin_main_header.php';

echo "<script language=\"javascript\" src=\"../calendar/calendar.js\"></script>";

echo <<<JAVASCRIPT
<script language="JavaScript">
function Cancel() {
	window.location = "event_list.php"
}
function Save() {
	document.form1.action="event_update.php?mode=$mode";
	document.form1.submit();
}
function error() {
	window.alert("There was an error while saving your changes. Please try again.");
}

</script>
JAVASCRIPT;

include_once 'includes/admin_main_body_precontent.php';
echo "<td colspan=\"2\" valign=\"top\" align=\"center\">";

$startDate = GetDateFromMySQLDate(GetSQLDate($event->StartDate));
$endDate = GetDateFromMySQLDate(GetSQLDate($event->EndDate));

// Builds array of flags for fields that have errors
$errorFlags = array();
@$errorFlags['title'] = in_array('title', $errorFields) ? ">>" : " ";
@$errorFlags['location'] = in_array('location', $errorFields) ? ">>" : " ";
@$errorFlags['startDate'] = in_array('startDate', $errorFields) ? ">>" : " ";
@$errorFlags['endDate'] = in_array('endDate', $errorFields) ? ">>" : " ";
@$errorFlags['description'] = in_array('departure', $errorFields) ? ">>" : " ";
@$errorFlags['departure'] = in_array('departure', $errorFields) ? ">>" : " ";
@$errorFlags['whatToBring'] = in_array('whatToBring', $errorFields) ? ">>" : " ";
@$errorFlags['whereToStay'] = in_array('whereToStay', $errorFields) ? ">>" : " ";
@$errorFlags['ageRequirements'] = in_array('ageRequirements', $errorFields) ? ">>" : " ";
@$errorFlags['healthRequirements'] = in_array('healthRequirements', $errorFields) ? ">>" : " ";

// Date of Start
$dosCalendar = new tc_calendar("dos", true, false);
$dosCalendar->setIcon("../calendar/images/iconCalendar.gif");
$dosCalendar->setPath("../calendar/");
$dosCalendar->setDateFormat('F j, Y');
if ((($errMode == "field") || ($mode == "edit")) && (strlen($event->StartDate)>0)) {
	// Set dod if form loading after invalid fields
	$setDate = getdate($event->StartDate);
	$dosCalendar->setDate($setDate['mday'], $setDate['mon'], $setDate['year']);
}

// Date of End
$doeCalendar = new tc_calendar("doe", true, false);
$doeCalendar->setIcon("../calendar/images/iconCalendar.gif");
$doeCalendar->setPath("../calendar/");
$doeCalendar->setDateFormat('F j, Y');
if ((($errMode == "field") || ($mode == "edit")) && (strlen($event->EndDate)>0)) {
	// Set dos if form loading after invalid fields
	$setDate = getdate($event->EndDate);
	$doeCalendar->setDate($setDate['mday'], $setDate['mon'], $setDate['year']);
}

// Insert table for Event editing
echo <<<EVENTEDIT1
	<input type="hidden" name="id" value="$event->ID" />
	<table width="90%" align="center" border="0">
		<tr>
			<td colspan="4" align="center">
				<table width="60%" border="0">
					<tr>
						<td align="center" width="50%"><input type="button" value="      Save      " onclick="Save();" /></td>
						<td align="center" width="50%"><input type="button" value = "     Cancel     " onclick="Cancel();" /></td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td colspan="4" align="center" class="docunderlineboldsmall">All fields are required.</td>
		</tr>
		<tr>
			<td align="center" colspan="4" class="errortext">$message</td>
		</tr>
		<tr>
			<td align="right" class="formlabel">
				<span class="errortextsmall">{$errorFlags['title']}</span>
				Event Title: 
			</td>
			<td align="left" colspan="3">
				<input type="text" size="40" name="title" value="$event->Title" />
			</td>
		</tr>
		<tr>
			<td align="right" class="formlabel">
				<span class="errortextsmall">{$errorFlags['location']}</span>
				Location:
			</td>
			<td align="left" colspan="3">
				<input type="text" size="40" name="location" value="$event->Location" />
			</td>
		</tr>
		<tr>
			<td align="right" class="formlabel" width="25%">
				<span class="errortextsmall">{$errorFlags['startDate']}</span>
				Start Date:
			</td>
			<td align="left" width="20%">
				<div class="date-tccontainer" style="position:relative; z-index:100">
EVENTEDIT1;

$dosCalendar->writeScript();
			
echo <<<EVENTEDIT2
				</div>
			</td>
			<td align="right" class="formlabel" width="20%">
				<span class="errortextsmall">{$errorFlags['endDate']}</span>
				End Date:
			</td>
			<td align="left" width="35%">
				<div  class="date-tccontainer" style="z-index:50">
EVENTEDIT2;

$doeCalendar->writeScript();

echo <<<EVENTEDIT3
				</div>
			</td>
		</tr>
		<tr>
			<td align="right" class="formlabel">
				<span class="errortextsmall">{$errorFlags['departure']}</span>
				Depart From:</td>
			<td align="left" colspan="3">
				<input type="text" size="40" name="departure" value="$event->Departure" />
			</td>
		</tr>
		<tr>
			<td align="right" valign="top" class="formlabel">
				<span class="errortextsmall">{$errorFlags['description']}</span>
				Description:
			</td>
			<td align="left" valign="top" colspan="3">
				<textarea name="description" cols="60" rows="5">$event->Description</textarea>
			</td>
		</tr>
		<tr>
			<td align="right" class="formlabel">
				<span class="errortextsmall">{$errorFlags['whatToBring']}</span>
				What to Bring:
			</td>
			<td align="left" colspan="3">
				<input type="text" size="60" name="whatToBring" value="$event->WhatToBring" />
			</td>
		</tr>
		<tr>
			<td align="right" class="formlabel">
				<span class="errortextsmall">{$errorFlags['whereToStay']}</span>
				Where We'll Stay:
			</td>
			<td align="left" colspan="3">
				<input type="text" size="60" name="whereToStay" value="$event->WhereToStay" />
			</td>
		</tr>
		<tr>
			<td align="right" class="formlabel">
				<span class="errortextsmall">{$errorFlags['ageRequirements']}</span>
				Age Requirements:
			</td>
			<td align="left" colspan="3">
				<input type="text" size="60" name="ageRequirements" value="$event->AgeRequirements" />
			</td>
		</tr>
		<tr>
			<td align="right" class="formlabel">
				<span class="errortextsmall">{$errorFlags['healthRequirements']}</span>
				Health Requirements:</td>
			<td align="left" colspan="3">
				<input type="text" size="60" name="healthRequirements" value="$event->HealthRequirements" />
			</td>
		</tr>
EVENTEDIT3;

echo "</table>";
include_once 'includes/admin_main_body_postcontent.php';
?>