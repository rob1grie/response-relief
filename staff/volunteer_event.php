<?php
// Processes a change in the Event drop-down on volunteer_edit.php
require_once '../includes/volunteer_class.php';
require_once '../includes/event_class.php';

if (!isset($_SESSION)) {
  session_start();
}

if (isset($_GET['id']) && (strlen($_GET['id'])>0)) {
	// If an ID is sent, need to get all posted values from the form, save them to the session, and reload volunteer_edit
	// Get an Event and set start and end dates to the Event's dates
	$id = $_GET['id'];
	$event = new Event($id);
	
	// If id has changed, unset posted start and end date
	if ($id != $_SESSION['selectedEvent']) {
		unset($_POST['dos']);
		unset($_POST['doe']);
		unset($_POST['regDate']);
	}
	
	SaveVolunteerToSession();
	if (isset($_POST['dos']))
		$_SESSION['dosDate'] = strtotime($_POST['dos']);
	else 
		$_SESSION['dosDate'] = $event->StartDate;
		
	if (isset($_POST['doe']))
		$_SESSION['doeDate'] = strtotime($_POST['doe']);
	else 
		$_SESSION['doeDate'] = $event->EndDate;
		
	if (isset($_POST['regDate']))
		$_SESSION['regDate'] = strtotime($_POST['regDate']);
	else 
		$_SESSION['regDate'] = time();
		
	$_SESSION['selectedEvent'] = $id;
}
else {
	// If GET[id] == -1 unset Session[selectedEvent]
	unset($_SESSION['selectedEvent']);
}
$mode = $_SESSION['editMode'];
header("Location: volunteer_edit.php?m=$mode");
//var_dump($_POST);

function SaveVolunteerToSession() {
	// Save all posted fields to SESSION[editingVolunteer]
	$volunteer = new Volunteer();
	
	$volunteer = GetVolunteerFields($volunteer);
	
	$_SESSION['editingVolunteer'] = $volunteer;
}

function GetVolunteerFields($volunteer) {
	// Get volunteer fields from posted form
	$volunteer->FirstName = $_POST['firstName'];
	$volunteer->LastName = $_POST['lastName'];
	$volunteer->Address1 = $_POST['address1'];
	$volunteer->Address2 = $_POST['address2'];
	$volunteer->City = $_POST['city'];
	$volunteer->State = $_POST['statesDropDown'];
	$volunteer->Zip = $_POST['zip'];
	$volunteer->HomePhone = $_POST['homePhone'];
	$volunteer->WorkPhone = $_POST['workPhone'];
	$volunteer->CellPhone = $_POST['cellPhone'];
	$volunteer->Email = $_POST['email'];
	$volunteer->DOBMonth = $_POST['dobMonth'];
	$volunteer->DOBDay = $_POST['dobDay'];
	$volunteer->DOBYear = $_POST['dobYear'];
	$volunteer->HomeChurch = $_POST['homeChurch'];
	$volunteer->SkillsCerts = $_POST['skillsCerts'];
	$volunteer->MailingList = (isset($_POST['mailingList'])) ? 1 : 0;
	$volunteer->AgreeCoC = (isset($_POST['agreeCoC'])) ? 1 : 0;
	
	return $volunteer;
}


?>