<?php
// Reusable library of code for handling email messages

function isValidEmail($email) {
	$result = eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$", $email);
	return $result;
}

function CleanUpForEmail($text) {
	// Formats text to have new lines and only escape apostrophes once
	global $connDB;
	$text = mysql_real_escape_string($text, $connMC);
	$text = str_replace("\\r\\n", chr(10), $text);
	$text = str_replace("\\\'", "\'", $text);
	
	return $text;
}

function SendEmail($to, $from, $subject, $message) {
	// Sends the arguments as an email message
	// Email is valid; send it
	$headers = "From: $from\r\nReply-To: $from";

	$result = @mail($to, $subject, $message, $headers);
	return $result;
}
?>