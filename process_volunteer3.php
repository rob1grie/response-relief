<?php
// Final processing of Volunteer registration
/*
 * All Volunteer and Registration fields have been confirmed and are in the Session
*/
include_once 'includes/event_class.php';
include_once 'includes/volunteer_class.php';
include_once 'includes/registration_class.php';
include_once 'includes/email_tools.php';
include_once 'includes/maininc.php';

session_start();

// First check whether Volunteer accepted the terms
if (!isset($_POST['agreeCoC'])) {
	unset($_SESSION['agreeCoC']);
	header("Location: volunteer3.php?m=coc");
	exit;
}
else 
	$_SESSION['agreeCoC'] = 1;

$success = TRUE;
$mailSuccess = TRUE;

// If Session[dosDate] is empty, volunteer is only signing up for future notices
if (isset($_SESSION['dosDate']) && (strlen($_SESSION['dosDate'])>0)) {
	$registration = new Registration();
	$registration->DateOfReg = time();
	$registration->StartDate = $_SESSION['dosDate'];
	$registration->EndDate = $_SESSION['doeDate'];
	$registration->EventID = $_SESSION['editingID'];

  $event = new Event($registration->EventID);
}
else { 
	$registration = FALSE;
	$event = new Event();
}
	

$volunteer = $_SESSION['editingVolunteer'];
// At this point volunteer must have accepted the terms
$volunteer->AgreeCoC = 1;

// Check whether volunteer already exists
$volunteerID = $volunteer->Exists();
if($volunteerID) {
	$volunteer->ID = $volunteerID;
	$success = $volunteer->Update();
}
else {
	$success = $volunteer->Insert();
}

if ($success && $registration) {
	$registration->VolunteerID = $volunteer->ID;
	$success = $registration->Insert();
}

if ($success) {
	// All inserts and updates were successful, send email
	$mailSuccess = EmailRegistration($registration, $volunteer, $event);
}

if (!$mailSuccess) {
	$loc = "index.php?m=regmail";
}
else if ($success) {
	$loc = "index.php?m=regsuccess";
}
else {
	$loc = "volunteer3.php?m=sql";
}
header("Location: $loc");

function EmailRegistration($registration, $volunteer, $event) {
	// Sends confirmation email to Volunteer and to Staff at registration@response-relief.net
	// First email to Staff
	if (!StaffEmail($registration, $volunteer, $event)) {
		return FALSE;
	}
	
	if (!VolunteerEmail($registration, $volunteer, $event)) {
		return FALSE;
	}
	
	return TRUE;
}

function StaffEmail($registration, $volunteer, $event) {
	// Email to Staff only includes minimal info regarding the event
	if ($registration) {
		$regStart = GetStringDateFromTime($registration->StartDate);
		$regEnd = GetStringDateFromTime($registration->EndDate);
	}
	$eventStart = GetStringDateFromTime($event->StartDate);
	$eventEnd = GetStringDateFromTime($event->EndDate);
	
	$toAddress = "registration@response-relief.net";
	$fromAddress = $toAddress;
	$subject = "New Registration submitted at Response-Relief.net";
	$message = "The following Volunteer has registered at Response-Relief.net:\r\n\r\n";
	$message .= "$volunteer->FirstName $volunteer->LastName\r\n$volunteer->Address1\r\n";
	if(strlen($volunteer->Address2)>0)
		$message .= "$volunteer->Address2\r\n";
	$message .= "$volunteer->City, $volunteer->State $volunteer->Zip\r\n";
	if (strlen($volunteer->HomePhone)>0)
		$message .= "$volunteer->HomePhone (Home)\r\n";
	if (strlen($volunteer->WorkPhone)>0)
		$message .= "$volunteer->WorkPhone (Work)\r\n";
	if (strlen($volunteer->CellPhone)>0)
		$message .= "$volunteer->CellPhone (Cell)\r\n";
	$message .= "$volunteer->Email\r\n";
	$message .= "Date of Birth: $volunteer->DOBMonth/$volunteer->DOBDay/$volunteer->DOBYear\r\n";
	$message .= "Home Church: $volunteer->HomeChurch\r\n";
	$message .= "Skills and Certifications: $volunteer->SkillsCerts\r\n\r\n";
	if ($registration) {
		$message .= "Dates available for this trip: $regStart to $regEnd\r\n";
		$message .= "Trip Registration:\r\n";
		$message .= "$event->Title\r\n";
		$message .= "$event->Location\r\n";
		$message .= "$eventStart to $eventEnd";
	}
	else 
		$message .= "Volunteer signed up for future notifications only\r\n";
	
	$success = SendEmail($toAddress, $fromAddress, $subject, $message);
	
	return $success;
}

function VolunteerEmail($registration, $volunteer, $event) {
	// Email to Volunteer contains all details for the event
	if ($registration) {
		$regStart = GetStringDateFromTime($registration->StartDate);
		$regEnd = GetStringDateFromTime($registration->EndDate);
	}
	$eventStart = GetStringDateFromTime($event->StartDate);
	$eventEnd = GetStringDateFromTime($event->EndDate);
	
	$toAddress = $volunteer->Email;
	$fromAddress = $toAddress;
	$subject = "Your Volunteer Registration at Response-Relief.net";
	$message = "Thank you for offering your time and effort to the Response and Relief Network of North Texas!\r\n" .
				"Someone from our office will be contacting you within 48 hours.\r\n\r\n" .
				"Below is the information you provided when you registered:\r\n\r\n";
	$message .= "$volunteer->FirstName $volunteer->LastName\r\n$volunteer->Address1\r\n";
	if(strlen($volunteer->Address2)>0)
		$message .= "$volunteer->Address2\r\n";
	$message .= "$volunteer->City, $volunteer->State $volunteer->Zip";
	if (strlen($volunteer->HomePhone)>0)
		$message .= "$volunteer->HomePhone (Home)\r\n";
	if (strlen($volunteer->WorkPhone)>0)
		$message .= "$volunteer->WorkPhone (Work)\r\n";
	if (strlen($volunteer->CellPhone)>0)
		$message .= "$volunteer->CellPhone (Cell)\r\n";
	$message .= "$volunteer->Email\r\n";
	$message .= "Date of Birth: $volunteer->DOBMonth/$volunteer->DOBDay/$volunteer->DOBYear\r\n";
	$message .= "Home Church: $volunteer->HomeChurch\r\n";
	$message .= "Skills and Certifications: $volunteer->SkillsCerts\r\n\r\n";
	if ($registration) {
		$message .= "Dates available for this trip: $regStart to $regEnd\r\n";
		$message .= "Trip Registration:\r\n" .
					"$event->Title\r\n" .
					"$event->Location\r\n" .
					"$eventStart to $eventEnd\r\n" .
					"Trip Details:\r\n" .
					"$event->Description\r\n" .
					"Departing from: $event->Departure\r\n" .
					"What to bring: $event->WhatToBring\r\n" .
					"Where we will stay: $event->WhereToStay\r\n" .
					"Age requirements: $event->AgeRequirements\r\n" .
					"Health requirements: $event->HealthRequirements\r\n"; 
	}
	else 
		$message .= "You signed up for future notifications only\r\n";
	
	$success = SendEmail($toAddress, $fromAddress, $subject, $message);
	
	return $success;
}
?>