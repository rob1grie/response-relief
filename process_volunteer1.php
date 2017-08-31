<?php
// Process form submitted by volunteer.php
include_once 'includes/volunteer_class.php';
include_once 'includes/email_tools.php';
include_once 'includes/maininc.php';

session_start();

// Get all posted values from form
$eventID = $_POST['eventsDropDown'];
$_SESSION['editingID'] = $eventID;

$volunteer = new Volunteer();
$volunteer = GetVolunteerFields($volunteer);
$_SESSION['editingVolunteer'] = $volunteer;

if (ValidateFields($eventID, $volunteer)) {
	$loc = "volunteer2.php";
}
else {
	$loc = "volunteer.php?m=field";
}
header("Location: $loc");

function GetVolunteerFields($volunteer) {
	$volunteer->FirstName 	= $_POST['firstName'];
	$volunteer->LastName 	= $_POST['lastName'];
	$volunteer->Address1 	= $_POST['address1'];
	$volunteer->Address2 	= $_POST['address2'];
	$volunteer->City 		= $_POST['city'];
	$volunteer->State 		= $_POST['statesDropDown'];
	$volunteer->Zip 		= $_POST['zip'];
	$volunteer->HomePhone	= RemoveNonNumeric($_POST['homePhone']);
	$volunteer->WorkPhone	= RemoveNonNumeric($_POST['workPhone']);
	$volunteer->CellPhone	= RemoveNonNumeric($_POST['cellPhone']);
	$volunteer->Email		= strtolower($_POST['email']);
	$volunteer->DOBMonth	= $_POST['dobMonth'];
	$volunteer->DOBDay		= $_POST['dobDay'];
	$volunteer->DOBYear		= $_POST['dobYear'];
	$volunteer->HomeChurch	= $_POST['homeChurch'];
	$volunteer->SkillsCerts	= $_POST['skillsCerts'];
	$volunteer->MailingList = (isset($_POST['mailingList'])) ? 1 : 0;
	
	return $volunteer;
}

function ValidateFields($eventID, $volunteer) {
	// Validate all fields in volunteer
	$valid = TRUE;
	$errorFields = array();
	
	if ($eventID == -1) {
		// [Please select] still selected in eventsDropDown
		$valid = FALSE;
		array_push($errorFields, 'event');
	}
	
	if (strlen($volunteer->FirstName)== 0) {
		$valid = FALSE;
		array_push($errorFields, 'firstName');;
	}
	
	if (strlen($volunteer->LastName)== 0) {
		$valid = FALSE;
		array_push($errorFields, 'lastName');;
	}
	
	if ((strlen($volunteer->Address1)== 0) && (strlen($volunteer->Address2)==0)) {
		$valid = FALSE;
		array_push($errorFields, 'address1');;
	}
	
	if ((strlen($volunteer->City)== 0) || (strlen($volunteer->Zip)==0)) {
		$valid = FALSE;
		array_push($errorFields, 'cityzip');
	}
	
	if ((strlen($volunteer->HomePhone)== 0) && 
			(strlen($volunteer->WorkPhone)==0) && 
			(strlen($volunteer->CellPhone==0))) {
		$valid = FALSE;
		array_push($errorFields, 'phoneNumber');;
	}
	
	if ((strlen($volunteer->Email)== 0) && isValidEmail($volunteer->Email)) {
		$valid = FALSE;
		array_push($errorFields, 'email');;
	}
	
	if ((strlen($volunteer->DOBMonth)== 0) || (strlen($volunteer->DOBDay)==0) || (strlen($volunteer->DOBYear)==0)) {
		$valid = FALSE;
		array_push($errorFields, 'dob');
	}
	
	$_SESSION['errorFields'] = $errorFields;
	
	return $valid;
}


?>