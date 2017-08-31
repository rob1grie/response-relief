<?php
// Process form submitted by volunteer.php
include_once 'includes/resource_class.php';
include_once 'includes/email_tools.php';
include_once 'includes/maininc.php';

session_start();

$resource = new Resource();
$resource = GetResourceFields($resource);
$_SESSION['editingResource'] = $resource;

if (ValidateFields($resource)) {
	$loc = "resource_confirm.php";
}
else {
	$loc = "resources.php?m=field";
}
header("Location: $loc");

function GetResourceFields($resource) {
	$resource->Name 		= $_POST['name'];
	$resource->Address1 	= $_POST['address1'];
	$resource->Address2 	= $_POST['address2'];
	$resource->City 		= $_POST['city'];
	$resource->State 		= $_POST['statesDropDown'];
	$resource->Zip 			= $_POST['zip'];
	$resource->HomePhone	= RemoveNonNumeric($_POST['homePhone']);
	$resource->WorkPhone	= RemoveNonNumeric($_POST['workPhone']);
	$resource->CellPhone	= RemoveNonNumeric($_POST['cellPhone']);
	$resource->Email		= $_POST['email'];
	$resource->LoanStart	= $_POST['dos'];
	$resource->LoanEnd		= $_POST['doe'];
	$resource->Description	= $_POST['description'];
	
	// Default values for both Donate and LoanIndef are 0
	$duration = $_POST['duration'];
	if($duration == 'donate')
		$resource->Donate = 1;
	else if ($duration == 'indefinite')
		$resource->LoanIndef = 1;
	
	return $resource;
}

function ValidateFields($resource) {
	// Validate all fields in volunteer
	$valid = TRUE;
	$errorFields = array();
	
	if (strlen($resource->Name)== 0) {
		$valid = FALSE;
		array_push($errorFields, 'name');;
	}
	
	if ((strlen($resource->Address1)== 0) && (strlen($resource->Address2)==0)) {
		$valid = FALSE;
		array_push($errorFields, 'address1');;
	}
	
	if ((strlen($resource->City)== 0) || (strlen($resource->Zip)==0)) {
		$valid = FALSE;
		array_push($errorFields, 'cityzip');
	}
	
	if ((strlen($resource->HomePhone)== 0) && 
			(strlen($resource->WorkPhone)==0) && 
			(strlen($resource->CellPhone==0))) {
		$valid = FALSE;
		array_push($errorFields, 'phoneNumber');;
	}
	
	if ((strlen($resource->Email)== 0) && isValidEmail($resource->Email)) {
		$valid = FALSE;
		array_push($errorFields, 'email');;
	}

	if (strlen($resource->Description)==0) {
		$valid = FALSE;
		array_push($errorFields, 'description');
	}
	$_SESSION['errorFields'] = $errorFields;
	
	return $valid;
}


?>