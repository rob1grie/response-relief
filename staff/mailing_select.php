<?php
// Serves several purposes, depending on value of GET[m]
require_once '../includes/volunteer_class.php';
require_once '../includes/registration_class.php';

session_start();

if (isset($_GET['m']) && (strlen($_GET['m'])>0)) {
	$mode = $_GET['m'];	
}

if (isset($_GET['id']) && (strlen($_GET['id'])>0)) {
	$id = $_GET['id'];
}

$loc = "mailing_list.php";

if ($mode == 'select') {
	// Event drop-down changed on mailing_list.php
	// Need to get all posted checkboxes and set their counterparts in allVolunteers
	// filterVolunteers and allVolunteers are both keyed by the Volunteer ID
	// Will need to change Selected property of those that aren't checked just in case
	$filterVolunteers = $_SESSION['filterVolunteers'];
	$allVolunteers = $_SESSION['allVolunteers'];
	$checkedIDs = GetCheckedIDs();
	
	// Check or Uncheck each Volunteer in filterVolunteers according to checkedIDs
	$filterVolunteers = CheckFilter($filterVolunteers, $checkedIDs);
	
	// Update allVolunteers from filterVolunteers
	$allVolunteers = UpdateAllVolunteers($filterVolunteers, $allVolunteers);
	
	// Create new filterVolunteers updated from allVolunteers
	$filterVolunteers = CreateNewFilterVolunteers($allVolunteers, $id);
	
	// Update Session and return to list
	$_SESSION['allVolunteers'] = $allVolunteers;
	$_SESSION['filterVolunteers'] = $filterVolunteers;
	
	$loc = "mailing_list.php?id=$id";
}
header("Location: $loc");

function GetCheckedIDs() {
	// Steps through $_POST looking for checkboxes that are checked
	$checkedIDs = array();
	
	for ($i=0; $i<count($_POST); $i++) {
		$key = key($_POST[$i]);
		if (strpos($key, 'check')) {
			$id = str_replace('check', '', $key);
			array_push($checkedIDs, $id);
		}
	}
	
	return $checkedIDs;
}

function CheckFilter($filter, $checks) {
	// Steps through $filter array of Volunteers and marks Selected for values in $checks
	for ($i=0; $i<count($filter); $i++) {
		if(in_array($filter[$i]->ID, $checks))
			$filter[$i]->Selected = 1;
		else 
			$filter[$i]->Selected = 0;
	}
	
	return $filter;
}

function UpdateAllVolunteers($filterVol, $allVol) {
	// Steps through $filterVol, updating corresponding Volunteer record in all
	foreach ($filterVol as $vol) {
		$id = $vol->ID;
		$sel = $vol->Selected;
		$allVol[$id]->Selected = $sel;
	}
	
	return $allVol;
}

function CreateNewFilterVolunteers($allVol, $eventID) {
	// Creates a new array of Volunteers based on selected $eventID and $allVol
	$filterVol = Volunteer::GetEventVolunteers($eventID);
	
	foreach ($filterVol as $vol) {
		$id = $vol->ID;
		$sel = $allVol[$id]->Selected;
		$filterVol[$id]->Selected = $sel;
	}
	
	return $filterVol;
}
?>