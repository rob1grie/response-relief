<?php
 require_once('includes/email_tools.php');
session_start();

// Validate values from email_form
$senderName = $_POST['senderName'];
$senderEmail = $_POST['senderEmail'];
//$emailSub = $_POST['email_subject'];
//$emailMsg = $_POST['email_message'];
//$messageText = addslashes($_POST['messageText']);
$messageText = str_replace("\\r\\n", chr(10), $_POST['messageText']);
$messageText = stripslashes($messageText);

// Save to session while we're at it
$emailMessage = array('senderName'=>$senderName, 'senderEmail'=>$senderEmail, 'messageText'=>$messageText);
$_SESSION['emailMessage'] = $emailMessage;

$loc = "";

// TODO: Change To address in SendMail
if (!ValidateEmail($emailMessage)) {
	$loc = "contact.php?m=field";
	$_SESSION['emailMessage'] = $emailMessage;
}
else if (!SendEmail('info@response-relief.net', $emailMessage['senderEmail'], 'Message from the R&R web site', $emailMessage['messageText']))
		$loc = "contact.php?m=err";
else {
//		Uncomment the following line to send the domain webmaster an email when SendMail is successful		
//		NotifyWebmaster($emailTo, $emailFrom);
		$loc = 'index.php?m=mailsuccess';
	}


function ValidateEmail($emailMessage) {
	$valid = TRUE;
	$errorFields = array();
	
	if ((strlen($emailMessage['senderEmail'])==0) || !isValidEmail($emailMessage['senderEmail'])) {
		$valid = FALSE;
		array_push($errorFields, 'senderEmail');
	}
	
	if (strlen($emailMessage['senderName'])==0) {
		$valid = FALSE;
		array_push($errorFields, 'senderName');
	}
	
	if (strlen($emailMessage['messageText'])==0) {
		$valid = FALSE;
		array_push($errorFields, 'messageText');
	}
		
	// Test CAPTCHA
	if (!isset($_POST['vcb'])) {
	  $valid = FALSE;
	  array_push($errorFields, 'captcha');
	}
	
	$_SESSION['errorFields'] = $errorFields;
	
	return $valid;
}


header("Location:$loc");

function NotifyWebmaster($to, $from) {
	// Sends message to webmaster that the form was used
	$subject = "Contact Form useage";
	$message = "The MustangCreek.org Contact Form was used to send a message\r\n";
	$message .= "From: $from \r\n";
	$message .= "To: $to \r\n";
	SendEmail("webmaster@mustangcreek.org", "webmaster@mustangcreek.org", $subject, $message);
}
?>