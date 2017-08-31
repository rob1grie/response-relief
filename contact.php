<?php
/*
 * Email Us form
*/

require_once 'includes/utilities.php';

session_start();

$referrer = $_SERVER['HTTP_REFERER'];

if (isset($_GET['m'])) {
 $mode = $_GET['m'];
}
else {
 $mode = '';
}

$message = "&nbsp;";
$errorFields = array();

if (($mode=='err') || ($mode=='field')) {
 // If mode is an error mode set from process_email, set up mode for editing
 $errorFields = $_SESSION['errorFields'];
 $emailMessage = $_SESSION['emailMessage'];
}
else {
 // Otherwise, it is a new form; create empty emailMessage array
 $emailMessage = array('senderName'=>'', 'senderEmail'=>'', 'messageText'=>'');
}

if ($mode == "err") {
 // Error occurred when sending email
 $message = "";
 $loadScript = "error()";
}
else if ($mode == 'field') {
 // Fields failed validation
 $message = "Please check required fields";
}

$errorFlags = array();
@$errorFlags['senderName'] = in_array('senderName', $errorFields) ? ">>" : "";
@$errorFlags['senderEmail'] = in_array('senderEmail', $errorFields) ? ">>" : "";
@$errorFlags['messageText'] = in_array('messageText', $errorFields) ? ">>" : "";
@$errorFlags['captcha'] = in_array('captcha', $errorFields) ? ">>" : "";

$pageTitle = "Contact Us";

include_once 'includes/header.php';
?>

<script language="javascript">
function Cancel() {
	window.location = "<?php echo $referrer; ?>"
}
function error() {
	window.alert("There was an error while sending your message. Please try again.");
}
</script>
<!--[if IE 5]>
<style type="text/css"> 

#sidebar1 {width:258px}
</style>
<![endif]-->
<!--[if IE]>
<style type="text/css"> 

#mainContent {zoom:1}

</style>
<![endif]-->

<?php 
include_once 'includes/precontent.php';

echo <<<CONTACTFORM
<div id="contact_out">
<div id="contact">
<form name="form1" method="post" action="process_email.php">
<table width="100%" border="0" cellspacing="7">
	<tr><td align="center" colspan="2">
		<h2>Contact Us</h2>
		<h3>You may contact us by phone at:<br/>
			Phone:&nbsp;&nbsp;(469) 438-5232<br/>
  			Fax:&nbsp;&nbsp;&nbsp;&nbsp;(972) 564-4284</h3>
  		<h4>or use the form below to contact us by email</h4>
		<h3>Please provide your name, email address<br/>and any comments or questions you wish.</h3>
	</td></tr>
	<tr><td align="center" colspan="2" class="errortext">$message</td></tr>
	<tr>
		<td width="30%" align="right" class="formlabel">
			<span class="errortextsmall">{$errorFlags['senderName']}</span>
			Your Name:
		</td>
		<td width="70%" align="left">
			<input type="text" size="30" name="senderName" value="{$emailMessage['senderName']}" />
		</td>
	</tr>
	<tr>
		<td align="right" class="formlabel">
			<span class="errortextsmall">{$errorFlags['senderEmail']}</span>
			Email Address:
		</td>
		<td align="left">
			<input type="text" size="30" name="senderEmail" value="{$emailMessage['senderEmail']}" />
		</td>
	</tr>
	<tr>
		<td align="right" valign="top" class="formlabel">
			<span class="errortextsmall">{$errorFlags['messageText']}</span>
			Your message:
		</td>
		<td align="left" valign="top">
			<textarea name="messageText" cols="70" rows="10">{$emailMessage['messageText']}</textarea>
		</td>
	</tr>
	<tr>
		<td align="center" colspan="2" class="bordergreen">
			<div class="formlabel displayinline">
			  <span class="errortextsmall">{$errorFlags['captcha']}</span>
			  Please confirm that you are human:
			  <div id="cb" class="displayinline"></div>
        <script type="text/javascript">
          var myDiv = document.getElementById("cb");
          var checkbox = document.createElement("input"); 
          checkbox.setAttribute("type", "checkbox");
          checkbox.setAttribute("name", "vcb");
          checkbox.setAttribute("value", "1");
          myDiv.appendChild(checkbox); 
          //do this after you append it
          checkbox.checked = false; 
        </script>

		</td>
	</tr>
	<tr><td colspan="2" align="center">
		<table width="60%"><tr>
			<td align="center" width="50%"><input type="submit" value="            Send            " onclick="SendIt();" /></td>
			<td align="center" width="50%"><input type="button" value="           Cancel           " onclick="Cancel();" /></td>
		</tr></table>
	</td></tr>
</table>
</form>
</div>
</div>
CONTACTFORM;
?>
<br class="clearfloat" />
</div>
<!-- / #outerdiv -->
</body>
</html>
