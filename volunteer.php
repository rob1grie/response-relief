<?php 
include_once 'includes/event_class.php';
include_once 'includes/volunteer_class.php';
include_once 'includes/maininc.php';

session_start();

if (strpos($_SERVER['HTTP_REFERER'],'volunteer'))
	$referrer = $_SESSION['referrer'];
else {
	$referrer = $_SERVER['HTTP_REFERER'];
	$_SESSION['referrer'] = $referrer;
}

$loadScript = "";
$message = "&nbsp;";
if (isset ($_GET['m'])) {
 $mode = $_GET['m'];
}
else {
 $mode = '';
}

if (isset($_GET['id'])) {
 $eventID = $_GET['id'];
}
else {
 $eventID = '';
}

if (($mode=='err') || ($mode=='field')) {
	// Error in processVolunteer1
	$volunteer = $_SESSION['editingVolunteer'];
	$eventID = $_SESSION['editingID'];
	$errorFields = $_SESSION['errorFields'];
	
	if ($mode == "err") {
		$loadScript = "OnLoad='error();'";
	}
	else if ($mode == 'field') {
		$message = "Please check required fields";
	}
}
else if ($mode == "edit") {
	// Returning to form from volunteer2.php
	$volunteer = $_SESSION['editingVolunteer'];
	$eventID = $_SESSION['editingID'];
	$event = new Event($eventID);
}
else {
	// TODO Create dummy volunteer for development
	$volunteer = new Volunteer();
//	$volunteer = Volunteer::DummyVolunteer();
	
	if ($eventID > 0) {
		// Form called from Info page with specified event ID
		$event = new Event($eventID);
	}
	else {
		// Form called from the Volunteer button
		$event = new Event();
		$eventID = $event->ID;
	}
}

$mailingList = ($volunteer->MailingList) ? "checked" : "";

// Build errorFlags
$errorFlags = array();
@$errorFlags['event'] = in_array('event', $errorFields) ? "<<" : " ";
@$errorFlags['firstName'] = in_array('firstName', $errorFields) ? "<<" : " ";
@$errorFlags['lastName'] = in_array('lastName', $errorFields) ? "<<" : " ";
@$errorFlags['address1'] = in_array('address1', $errorFields) ? "<<" : " ";
@$errorFlags['cityzip'] = in_array('cityzip', $errorFields) ? "<<" : " ";
@$errorFlags['phoneNumber'] = in_array('phoneNumber', $errorFields) ? "<<" : " ";
@$errorFlags['email'] = in_array('email', $errorFields) ? "<<" : " ";
@$errorFlags['dob'] = in_array('dob', $errorFields) ? "<<" : " ";

// Build Events drop down
$allEvents = Event::GetLatestEvents(time());
$eventsDropDown = "<select name='eventsDropDown'><option value='-1'";
if ($eventID == -1)
	$eventsDropDown .= " selected";
$eventsDropDown .= ">[Please select]</option>";
$eventsDropDown .= "<option value='0'";
if ($eventID == 0)
	$eventsDropDown .= " selected";
$eventsDropDown .= ">Just notify me of future events</option>";
foreach ($allEvents as $e) {
	$startDate = GetStringDateFromTime($e->StartDate);
	$endDate = GetStringDateFromTime($e->EndDate);
	$eventsDropDown .= "<option value='$e->ID'";
	if ($e->ID == $eventID)
		$eventsDropDown .= " selected";
	$eventsDropDown .= ">$e->Title - $startDate to $endDate</option>";
}
$eventsDropDown .= "</select>";

// Build State drop down
$allStates = GetStates();
$statesDropDown = "<select name='statesDropDown'>";
foreach ($allStates as $state) {
	$statesDropDown .= "<option value='$state'";
	if($state == $volunteer->State)
		$statesDropDown .= " selected";
	$statesDropDown .= ">$state</option>";
}
$statesDropDown .= "</select>";

$pageTitle = "Volunteer";

include_once 'includes/header.php';

echo <<<JAVASCRIPT
<script language="JavaScript" type="text/JavaScript">
function Cancel() {
	window.location = "$referrer"
}
</script>
JAVASCRIPT;

include_once 'includes/precontent.php';
include_once 'includes/leftnav.php';

echo <<<VOLFORM1
<div id="content">
<form method="POST" action="process_volunteer1.php">
<input type="hidden" name="eventID" value="$eventID" />
<span style="text-align: center"><h2>Volunteer Registration</h2></span>
<table width="100%" border="0">
	<tr><td colspan="4" align="center" class="errortext">$message</td></tr>
	<tr><td colspan="4" align="left">
		<span class="formlabel">Select a trip:</span>&nbsp$eventsDropDown
		<span class="errortextsmall">{$errorFlags['event']}</span>
	</td></tr>
	<tr><td colspan="4">&nbsp;</td></tr>
	<tr><td colspan="4" align="center">
		<input type="checkbox" name="mailingList" $mailingList />
		<span class="formlabel">Sign me up for notification of future trips</span>
	</td></tr>
	<tr><td colspan="4" align="center" class="formlabel">Fields marked * are required</td></tr>
	<tr><td colspan="4">&nbsp;</td></tr>
	<tr>
		<td align="right" class="formlabel" width="20%">* First name:</td>
		<td align="left" width="30%">
			<input type="text" name="firstName" size="20" value="$volunteer->FirstName" />
			<span class="errortextsmall">{$errorFlags['firstName']}</span>
		</td>
		<td align="right" class="formlabel" width="20%">* Last name:</td>
		<td align="left" width="30%">
			<input type="text" name="lastName" size="20" value="$volunteer->LastName" />
			<span class="errortextsmall">{$errorFlags['lastName']}</span>
		</td>
	</tr>
	<tr>
		<td align="right" class="formlabel">* Address:</td>
		<td colspan="3" align="left">
			<input type="text" name="address1" size="30" value="$volunteer->Address1" />
			<span class="errortextsmall">{$errorFlags['address1']}</span>
		</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td colspan="3" align="left">
			<input type="text" name="address2" size="30" value="$volunteer->Address2" />
		</td>
	</tr>
	<tr>
		<td align="right" class="formlabel" nowrap>* City, State Zip</td>
		<td colspan="3" align="left">
			<input type="text" name="city" size="20" value="$volunteer->City" />, 
			$statesDropDown
			<input type="text" name="zip" size="10" value="$volunteer->Zip" />
			<span class="errortextsmall">{$errorFlags['cityzip']}</span>
		</td>
	</tr>
	<tr><td colspan="4">&nbsp;</td></tr>
	<tr>
		<td align="right" class="formlabel">Home phone:</td>
		<td colspan="3" align="left">
			<input type="text" name="homePhone" size="15" value="$volunteer->HomePhone" />&nbsp;
			<span class="errortextsmall">{$errorFlags['phoneNumber']}</span>
			<span class="smallboldtext">(* At least one phone number is required)</span>
		</td>
	</tr>
	<tr>
		<td align="right" class="formlabel">Work phone:</td>
		<td colspan="3" align="left">
			<input type="text" name="workPhone" size="15" value="$volunteer->WorkPhone" />
		</td>
	</tr>
	<tr>
		<td align="right" class="formlabel">Cell phone:</td>
		<td colspan="3" align="left">
			<input type="text" name="cellPhone" size="15" value="$volunteer->CellPhone" />
		</td>
	</tr>
	<tr>
		<td align="right" class="formlabel">* Email:</td>
		<td colspan="3" align="left">
			<input type="text" name="email" size="40" value="$volunteer->Email" />
			<span class="errortextsmall">{$errorFlags['email']}</span>
		</td>
	</tr>
	<tr><td colspan="4">&nbsp;</td></tr>
	<tr>
		<td colspan="4" align="left" class="formlabel">* Date of birth (MM/DD/YYYY):&nbsp;
			<input type="text" name="dobMonth" size="2" value="$volunteer->DOBMonth" /> / 
			<input type="text" name="dobDay" size="2" value="$volunteer->DOBDay" /> / 
			<input type="text" name="dobYear" size="4" value="$volunteer->DOBYear" />
			<span class="errortextsmall">{$errorFlags['dob']}</span>
		</td>
	</tr>
	<tr><td colspan="4">&nbsp;</td></tr>
	<tr>
		<td align="right" class="formlabel">Home church:</td>
		<td colspan="3" align="left">
			<input type="text" name="homeChurch" size="40" value="$volunteer->HomeChurch" />
		</td>
	</tr>
	<tr>
		<td colspan="4" align="left" valign="top" class="formlabel">Skills and Certifications:</td>
	</tr>
	<tr>
		<td colspan="4" align="center" valign="top">
			<textarea name="skillsCerts" rows="3" cols="60">$volunteer->SkillsCerts</textarea>
		</td>
	</tr>
	<tr><td colspan="4">&nbsp;</td></tr>
	<tr>
		<td colspan="2" align="center">
			<input type="submit" value="        Continue        " />
		</td>
		<td colspan="2" align="left">
			<input type="button" value="         Cancel         " onclick="Cancel();" />
		</td>
	</tr>
</table>
</form>
</div>
VOLFORM1;

include_once 'includes/postcontent.php';
?>