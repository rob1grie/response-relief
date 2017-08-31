<?php
// Final processing of Resource 
/*
 * All Resource fields have been confirmed and are in the Session
*/
include_once 'includes/resource_class.php';
include_once 'includes/email_tools.php';
include_once 'includes/maininc.php';

session_start();

$success = TRUE;
$mailSuccess = TRUE;

$resource = $_SESSION['editingResource'];

if ($resource->Insert()) {
	// All inserts and updates were successful, send email
	$mailSuccess = EmailResource($resource);
}

if (!$mailSuccess) {
	$loc = "index.php?m=resmail";
}
else if ($success) {
	$loc = "index.php?m=ressuccess";
}
else {
	$loc = "resource_confirm.php?m=sql";
}
header("Location: $loc");

function EmailResource($resource) {
	// Sends confirmation email to contributor and to Staff at registration@response-relief.net
	// First email to Staff
	if (!StaffEmail($resource)) {
		return FALSE;
	}
	
	if (!ContributorEmail($resource)) {
		return FALSE;
	}
	
	return TRUE;
}

function StaffEmail($resource) {
	// Email to Staff only includes minimal info regarding the event
	$toAddress = "resources@response-relief.net";
	$fromAddress = $toAddress;
	$subject = "New Resource Contribution submitted at Response-Relief.net";
	$message = "The following Resource Contribution has been registered at Response-Relief.net:\r\n\r\n";
	$message = GetMessageBody($resource, $message);
	
	$success = SendEmail($toAddress, $fromAddress, $subject, $message);
	
	return $success;
}

function ContributorEmail($resource) {
	// Email to Resource Contributor contains all details
	$toAddress = "resources@response-relief.net";
	$fromAddress = $toAddress;
	$subject = "Your Resource Contribution at Response-Relief.net";
	$message = "Thank you for contributing your resources to the Response and Relief Network of North Texas!\r\n" .
				"Someone from our office will be contacting you within 48 hours.\r\n\r\n" .
				"Below is the information you provided when you registered your contribution:\r\n\r\n";
	$message = GetMessageBody($resource, $message);
	
	$success = SendEmail($toAddress, $fromAddress, $subject, $message);
	
	return $success;
}

function GetMessageBody($resource, $message) {
	$message .= "$resource->Name\r\n$resource->Address1\r\n";
	if(strlen($resource->Address2)>0)
		$message .= "$resource->Address2\r\n";
	$message .= "$resource->City, $resource->State $resource->Zip\r\n";
	if (strlen($resource->HomePhone)>0)
		$message .= "$resource->HomePhone (Home)\r\n";
	if (strlen($resource->WorkPhone)>0)
		$message .= "$resource->WorkPhone (Work)\r\n";
	if (strlen($resource->CellPhone)>0)
		$message .= "$resource->CellPhone (Cell)\r\n";
	$message .= "$resource->Email\r\n\r\n";
	$message .= "Description:\r\n$resource->Description\r\n\r\n";
	$duration = "";
	if ($resource->LoanIndef)
		$duration = "Resource is available whenever needed";
	else if ($resource->Donate)
		$duration = "This is a permanent donation";
	else {
		$duration = "Resource is available from " . GetShortStringDateFromSQLDate($resource->LoanStart ) .
					" to " . GetShortStringDateFromSQLDate($resource->LoanEnd);
	}
	
	$message .= "$duration\r\n";
	
	return $message;
}
?>