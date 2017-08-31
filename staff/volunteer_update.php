<?php
require_once '../includes/volunteer_class.php';
require_once '../includes/registration_class.php';
require_once '../includes/event_class.php';
require_once '../includes/email_tools.php';
require_once '../includes/utilities.php';

session_start();

if (!isset($_GET['mode']) || (strlen($_GET['mode'])==0))
	header('Location: volunteer_list.php');
	
$mode = $_GET['mode'];

if(!isset($_GET['id']) || (strlen($_GET['id'])==0))
	$id = $_SESSION['editingID'];
else 
	$id = $_GET['id'];
	
$loc = "volunteer_list.php";
$errorFields = array();

switch ($mode) {
	case 'del':
		if(!isset($_GET['id']) || (strlen($_GET['id'])==0))
			header('Location: volunteer_list.php');
		if (!Volunteer::DeleteVolunteer($id)) 
			$loc = "volunteer_list.php?m=err";
		break;
	case 'edit':
		if (ValidateVolunteer()){ 
			if (UpdateVolunteer($id)) {
				$loc = "volunteer_list.php";
			}
			else {
				SaveVolunteerToSession();
				$loc = "volunteer_edit.php?m=err";
			}
		}
		else {
			SaveVolunteerToSession();
			$loc = "volunteer_edit.php?m=field";
		}
		break;
	case 'add':
		if (ValidateVolunteer()){ 
			if (InsertVolunteer($id)) {
				$loc = "volunteer_list.php";
			}
			else {
				SaveVolunteerToSession();
				$loc = "volunteer_edit.php?m=err";
			}
		}
		else {
			SaveVolunteerToSession();
			$loc = "volunteer_edit.php?m=field";
		}
}
header("Location: $loc");

function ValidateVolunteer() {
	// Validate posted values
	$valid = TRUE;
	$errorFields = array();
	
	if (strlen($_POST['firstName'])==0) {
		$valid = FALSE;
		array_push($errorFields, 'firstName');
	}
	if (strlen($_POST['firstName'])==0) {
		$valid = FALSE;
		array_push($errorFields, 'lastName');
	}
	if ((strlen($_POST['address1'])==0) && (strlen($_POST['address2'])==0)) {
		$valid = FALSE;
		array_push($errorFields, 'address');
	}
	if ((strlen($_POST['city'])==0) || (strlen($_POST['zip'])==0)) {
		$valid = FALSE;
		array_push($errorFields, 'cityzip');
	}
	if ((strlen($_POST['homePhone'])==0) && 
			(strlen($_POST['workPhone'])==0) && 
			(strlen($_POST['cellPhone'])==0)) {
		$valid = FALSE;
		array_push($errorFields, 'phoneNumber');;
	}	
//	if ((strlen($_POST['email'])==0) || !isValidEmail($_POST['email'])) {
//		$valid = FALSE;
//		array_push($errorFields, 'email');
//	}
	if ((strlen($_POST['dobMonth'])==0) || (strlen($_POST['dobDay'])==0) || (strlen($_POST['dobYear'])==0)) {
		$valid = FALSE;
		array_push($errorFields, 'dob');
	}
	
	$_SESSION['errorFields'] = $errorFields;
	
	return $valid;
}

function SaveVolunteerToSession() {
	// Save all posted fields to SESSION[editingVolunteer]
	$volunteer = new Volunteer();
	
	$volunteer = GetVolunteerFields($volunteer);
	
	$_SESSION['editingVolunteer'] = $volunteer;
	
	if (isset($_POST['eventDropDown']) && ($_POST['eventDropDown']>-1))
		$_SESSION['selectedEvent'] = $_POST['eventDropDown'];
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

function UpdateVolunteer($id) {
	// Get values from form fields and call Volunteer::Update
	$success = TRUE;
	
	$volunteer = new Volunteer($id);

	$volunteer = GetVolunteerFields($volunteer);
	
	$success = $volunteer->Update();
	if ($success)
		$success = AddRegistration($volunteer);
		
	return $success;
}

function InsertVolunteer() {
	// Get values from form fields and call Staff::Insert
	$success = TRUE;
	
	$volunteer= new Volunteer();
	
	$volunteer = GetVolunteerFields($volunteer);
	
	$success = $volunteer->Insert();
	if ($success)
		$success = AddRegistration($volunteer);
	
	return $success;
}

function AddRegistration($volunteer) {
	// Insert a Registration if an Event was selected
	$success = TRUE;
	$eventID = $_POST['eventDropDown'];
	
	// If an Event was selected, also insert a Registration for the Event
	if ($eventID > -1) {
		$event = new Event($eventID);
		$registration = new Registration();
		$registration->EventID = $eventID;
		$registration->VolunteerID = $volunteer->ID;
		// Set Registration dates to date of Event
		$registration->StartDate = $event->StartDate;
		$registration->EndDate = $event->EndDate;
		$registration->DateOfReg = strtotime($_POST['regDate']);
		$success = $registration->Insert();
	}
	
	return $success;
}
?>