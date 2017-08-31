<?php
// Page to enter or edit Gallery
require_once '../calendar/classes/tc_calendar.php';
require_once '../includes/event_class.php';
require_once '../includes/utilities.php';
require_once '../includes/volunteer_class.php';
require_once 'includes/log_class.php';

if (!isset($_SESSION)) {
  session_start();
}

if (!isset($_SESSION['staff'])) {
  header("Location: stafflogin.php");
}

Log::LogActivity($_SESSION['staff'], GetScriptName());

// If no mode in URL go to volunteer_list.php
if (!isset($_GET['m']) || (strlen($_GET['m'])==0)) {
	header('Location: volunteer_list.php');
}

$loadScript = "";
$message = "&nbsp;";
$mode = $_GET['m'];

if (isset($_SESSION['selectedEvent']) && (strlen($_SESSION['selectedEvent'])>0)) {
	$eventID = $_SESSION['selectedEvent'];
}
else
	$eventID = -1;

if (($mode=='err') || ($mode=='field')) {
	// If mode is an error mode set from volunteer_update, set up mode for editing
	$errMode = $mode;
	$mode = $_SESSION['editMode'];
}
else {
	// Otherwise, it is an actual edit mode; save it to session
	$_SESSION['editMode'] = $mode;
}

if (isset($_SESSION['editingVolunteer'])) {
	$volunteer = $_SESSION['editingVolunteer'];
	$id = $_SESSION['editingID'];
}

if ($errMode == "err") {
	$errorFields = $_SESSION['errorFields'];
	$loadScript = "OnLoad='error();'";
}
else if ($errMode == 'field') {
	$errorFields = $_SESSION['errorFields'];
	$message = "Please check required fields";
}
else if ($mode == "edit") {
	// Get Volunteer to edit
	if (!isset($_SESSION['editingVolunteer'])){
		if (!isset($_GET['id']) || (strlen($_GET['id'])==0)) {
			header('Location: volunteer_list.php');
		}
		$id = $_GET['id'];
		$volunteer = new Volunteer($id);
		$_SESSION['editingVolunteer'] = $volunteer;
		$_SESSION['editingID'] = $volunteer->ID;
	}
	
	$pageSubtitle = "Edit Volunteer Entry";
}
else if ($mode == "add") {
	if (!isset($_SESSION['editingVolunteer'])){
		$volunteer = new Volunteer();
		$_SESSION['editingVolunteer'] = $volunteer;
		$_SESSION['editingID'] = $volunteer->ID;
	}
	
	$pageSubtitle = "Add Volunteer Entry";
}

// Build Event drop down
$allEvents = Event::GetAllEvents();
$eventDropDown = "<select name='eventDropDown' onchange='ChangeEvent(this);'>";
$eventDropDown .= "<option value='-1'";
if ( $eventID == -1)
	$eventDropDown .= " selected";
$eventDropDown .= ">[Volunteer Entry Only]</option>";
foreach ($allEvents as $event) {
	$eventDropDown .= "<option value='$event->ID'";
	if ($eventID == $event->ID)
		$eventDropDown .= " selected";
	$eventDropDown .= ">$event->Title</option>";
}
$eventDropDown .= "</select>";

$mailingList = ($volunteer->MailingList) ? "checked" : "";
$agreeCoC = ($volunteer->AgreeCoC) ? "checked" : "";

// Set Registration date to current date
$regCalendar = new tc_calendar("regDate", true, false);
$regCalendar->setIcon("../calendar/images/iconCalendar.gif");
$regCalendar->setPath("../calendar/");
$regCalendar->setDateFormat('F j, Y');
if (isset($_SESSION['regDate'])) {
	$setDate = getdate($_SESSION['regDate']);
}
else {
	$setDate = getdate();
}
$regCalendar->setDate($setDate['mday'], $setDate['mon'], $setDate['year']);

// Date of Start
$dosCalendar = new tc_calendar("dos", true, false);
$dosCalendar->setIcon("../calendar/images/iconCalendar.gif");
$dosCalendar->setPath("../calendar/");
$dosCalendar->setDateFormat('F j, Y');
if (isset($_SESSION['dosDate'])) {
	$setDate = getdate($_SESSION['dosDate']);
}
else {
	$setDate = getdate();
}
$dosCalendar->setDate($setDate['mday'], $setDate['mon'], $setDate['year']);

// Date of End
$doeCalendar = new tc_calendar("doe", true, false);
$doeCalendar->setIcon("../calendar/images/iconCalendar.gif");
$doeCalendar->setPath("../calendar/");
$doeCalendar->setDateFormat('F j, Y');
if (isset($_SESSION['doeDate'])) {
	$setDate = getdate($_SESSION['doeDate']);
}
else {
	$setDate = getdate();
}
$doeCalendar->setDate($setDate['mday'], $setDate['mon'], $setDate['year']);

// Builds array of flags for fields that have errors
$errorFlags = array();
@$errorFlags['event'] = in_array('event', $errorFields) ? "<<" : " ";
@$errorFlags['firstName'] = in_array('firstName', $errorFields) ? "<<" : " ";
@$errorFlags['lastName'] = in_array('lastName', $errorFields) ? "<<" : " ";
@$errorFlags['address1'] = in_array('address1', $errorFields) ? "<<" : " ";
@$errorFlags['cityzip'] = in_array('cityzip', $errorFields) ? "<<" : " ";
@$errorFlags['phoneNumber'] = in_array('phoneNumber', $errorFields) ? "<<" : " ";
@$errorFlags['dob'] = in_array('dob', $errorFields) ? "<<" : " ";

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

include_once 'includes/admin_main_header.php';

echo "<script language=\"javascript\" src=\"../calendar/calendar.js\"></script>";

echo <<<JAVASCRIPT
<script language="JavaScript">
function ChangeEvent(selObj) {
	var idx = selObj.selectedIndex;
	var which = selObj.options[idx].value;
	document.form1.action = "volunteer_event.php?id=" + which;
	document.form1.submit();
}
function Cancel() {
	window.location = "volunteer_list.php"
}
function Save() {
	document.form1.action="volunteer_update.php?mode=$mode";
	document.form1.submit();
}
function error() {
	window.alert("There was an error while saving your changes. Please try again.");
}

</script>
JAVASCRIPT;

include_once 'includes/admin_main_body_precontent.php';
echo "<td colspan=\"2\" valign=\"top\" align=\"center\">";

echo <<<VOLFORM1
<input type="hidden" name="eventID" value="$eventID" />
<table width="80%" border="0">
	<tr><td colspan="4" align="center" class="errortext">$message</td></tr>
	<tr><td colspan="4" align="center">
		<span class="formlabel">Select a trip:</span>&nbsp$eventDropDown
		<span class="errortextsmall">{$errorFlags['event']}</span>
	</td></tr>
	<tr><td colspan="4">&nbsp;</td></tr>
VOLFORM1;

if ($eventID > -1) {
echo <<<VOLFORM2
	<tr>
		<td colspan="2" align="right" class="formlabel">
			Registration Date:
		</td>
		<td colspan="2" align="left">
			<div class="date-tccontainer" style="position:relative; z-index:100">
VOLFORM2;
$regCalendar->writeScript();
echo <<<VOLFORM3
			</div>
		</td>
	</tr>
	<tr>
		<td colspan="2" align="right" class="formlabel">
			Service Start Date:
		</td>
		<td colspan="2" align="left">
			<div class="date-tccontainer" style="position:relative; z-index:100">
VOLFORM3;
$dosCalendar->writeScript();
echo <<<VOLFORM4
			</div>
		</td>
	</tr>
	<tr>
		<td colspan="2" align="right" class="formlabel">
			Service End Date:
		</td>
		<td colspan="2" align="left">
			<div class="date-tccontainer" style="position:relative; z-index:100">
VOLFORM4;
$doeCalendar->writeScript();
echo <<<VOLFORM5
			</div>
		</td>
	</tr>
	<tr><td colspan="4">&nbsp;</td></tr>
VOLFORM5;
}
echo <<<VOLFORM6
	<tr><td colspan="4" align="center" class="formlabel">Fields marked * are required</td></tr>
	<tr>
		<td width="20%">&nbsp;</td>
		<td width="30%">&nbsp;</td>
		<td width="20%">&nbsp;</td>
		<td width="30%">&nbsp;</td>
	</tr>
	<tr>
		<td colspan="4" align="center">
			<input type="checkbox" name="mailingList" $mailingList />
			<span class="formlabel">Requests notification of future trips</span>
		</td>
	</tr>
	<tr>
		<td colspan="4" align="center">
			<input type="checkbox" name="agreeCoC" $agreeCoC />
			<span class="formlabel">Signed Certificate of Compliance</span>
		</td>
	</tr>
	<tr><td colspan="4">&nbsp;</td></tr>
	<tr>
		<td align="right" class="formlabel">* First name:</td>
		<td align="left">
			<input type="text" name="firstName" size="20" value="$volunteer->FirstName" />
			<span class="errortextsmall">{$errorFlags['firstName']}</span>
		</td>
		<td align="left" class="formlabel" colspan="2">* Last name:&nbsp;
			<input type="text" name="lastName" size="20" value="$volunteer->LastName" />
			<span class="errortextsmall">{$errorFlags['lastName']}</span>
		</td>
	</tr>
	<tr>
		<td align="right" class="formlabel">* Address:</td>
		<td colspan="3" align="left">
			<input type="text" name="address1" size="30" value="$volunteer->Address1" />
			<span class="errortextsmall">{$errorFlags['address']}</span>
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
		<td align="right" class="formlabel">Email:</td>
		<td colspan="3" align="left">
			<input type="text" name="email" size="40" value="$volunteer->Email" />
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
		<td>&nbsp;</td>
		<td colspan="3" align="center" valign="top">
			<textarea name="skillsCerts" rows="3" cols="60">$volunteer->SkillsCerts</textarea>
		</td>
	</tr>
	<tr><td colspan="4">&nbsp;</td></tr>
	<tr>
		<td colspan="2" align="center">
			<input type="submit" value="          Save          " onclick="Save();" />
		</td>
		<td colspan="2" align="left">
			<input type="button" value="         Cancel         " onclick="Cancel();" />
		</td>
	</tr>
</table>
VOLFORM6;

include_once 'includes/admin_main_body_postcontent.php';
?>