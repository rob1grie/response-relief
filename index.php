<?php
include_once 'includes/maininc.php';

ClearEditSession();

// Check for arguments from registration process
if (isset ($_GET['m'])) {
 $mode = $_GET['m'];
}
else {
 $mode = '';
}

if ($mode == "regsuccess")
	$loadScript = "RegSuccess();";
else if ($mode == "regmail")
	$loadScript = "RegMailError();";
else if ($mode == "ressuccess")
	$loadScript = "ResSuccess();";
else if ($mode == "resmail")
	$loadScript = "ResMailError();";
else if ($mode == "mailsuccess")
	$loadScript = "MailSuccess();";
	
$pageTitle = "Home";

include_once 'includes/header.php';
?>

<script language="javascript">
function RegSuccess() {
	window.alert("Your Registration was a success.\r\nAn email has been sent to the R&R Registration Desk.\r\nPlease check your email for a confirmation message.");
}
function RegMailError() {
	window.alert("Your Registration has been recorded but an error occurred\r\nwhile sending your registration confirmation to you and to the R&R Registration Desk.\r\nPlease contact us using the information provided on the Contact page of this web site.");
}
function ResSuccess() {
	window.alert("Your Resource donation was successly recorded.\r\nAn email has been sent to the R&R Registration Desk.\r\nPlease check your email for a confirmation message.");
}
function ResMailError() {
	window.alert("Your Resource donation  has been recorded but an error occurred\r\nwhile sending the email confirmation to you and to the R&R Registration Desk.\r\nPlease contact us using the information provided on the Contact page of this web site.");
}
function MailSuccess() {
	var msg = "Thank you for contacting us, your message was sent successfully.\r\nIf you asked that we contact, you will be hearing from us soon!";
	window.alert(msg);
}
</script>
<?php
include_once 'includes/precontent.php';
include_once 'includes/leftnav.php';

?>
<div id="content">
<p>Welcome to the North Texas District Disaster Response and Relief
Network, or as we like to call it- R&amp;R. We, as God's people, are
instructed to 'know the season'- in other words- you know fall is here
when you start to see leaves turn brown and fall to the ground. In
this season of humanity, disasters are happening almost without any
break in between. We've decided that we want to be ready and waiting
to respond- rather than wait and react. Whether it�s a flood, 
hurricane, tornado or wildfire, we want to hit the ground running
within the first week.</p> 
<p>Take a moment to look around our site. Look at the 'Info' on our
trips. Click the 'Volunteer' button to be one of the volunteers, not
only for a current trip- but to be notified for future trips. Register
your 'Resources' to be used on a trip, or even donated. Or simply
'Contact' us and we will respond within twenty-four hours. It is our
honor to represent the King by serving the people He loves.</p>
</div>
<?php 
include_once 'includes/postcontent.php';
?>