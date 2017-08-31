<?php 
require_once 'calendar/classes/tc_calendar.php';
include_once 'includes/resource_class.php';
include_once 'includes/maininc.php';

session_start();

if (strpos($_SERVER['HTTP_REFERER'],'resource_confirm.php'))
	$referrer = $_SESSION['referrer'];
else {
	$referrer = $_SERVER['HTTP_REFERER'];
	// If referrer is blank, or possibly has the home page with previous arguments, set it to home page
	if((strlen($referrer)==0) || strpos($_SERVER['HTTP_REFERER'], 'index.php'))
		$referrer = "index.php";
}
$_SESSION['referrer'] = $referrer;

$message = "&nbsp;";

if (isset($_GET['m'])) {
 $mode = $_GET['m'];
}
else {
 $mode = '';
}
if(($mode == "err") ||  ($mode == "field")) {
	$resource = $_SESSION['editingResource'];
	$errorFields = $_SESSION['errorFields'];
	
	if ($mode == "err") {
		$loadScript = "OnLoad='error();'";
	}
	else if ($mode == 'field') {
		$message = "Please check required fields";
	}
}
else if (isset($_SESSION['editingResource'])) {
	$resource = $_SESSION['editingResource'];
}
else {
	// TODO Create dummy Resource for development
//	$resource = Resource::DummyResource();
	$resource = new Resource();
}

// Build errorFlags
$errorFlags = array();
@$errorFlags['name'] = in_array('name', $errorFields) ? "<<" : " ";
@$errorFlags['address1'] = in_array('address1', $errorFields) ? "<<" : " ";
@$errorFlags['cityzip'] = in_array('cityzip', $errorFields) ? "<<" : " ";
@$errorFlags['phoneNumber'] = in_array('phoneNumber', $errorFields) ? "<<" : " ";
@$errorFlags['email'] = in_array('email', $errorFields) ? "<<" : " ";
@$errorFlags['description'] = in_array('description', $errorFields) ? "<<" : " ";

// Build State drop down
$allStates = GetStates();
$statesDropDown = "<select name='statesDropDown'>";
foreach ($allStates as $state) {
	$statesDropDown .= "<option value='$state'";
	if($state == $resource->State)
		$statesDropDown .= " selected";
	$statesDropDown .= ">$state</option>";
}
$statesDropDown .= "</select>";

$pageTitle = "Resources";

// Date of Start
$dosCalendar = new tc_calendar("dos", true, false);
$dosCalendar->setIcon("calendar/images/iconCalendar.gif");
$dosCalendar->setPath("calendar/");
$dosCalendar->setDateFormat('M j, Y');

// Date of End
$doeCalendar = new tc_calendar("doe", true, false);
$doeCalendar->setIcon("calendar/images/iconCalendar.gif");
$doeCalendar->setPath("calendar/");
$doeCalendar->setDateFormat('M j, Y');

// Create initial dates for date pickers if they were previously set
if(isset($_SESSION['editingResource'])) {
	if ($resource->LoanStart != '0000-00-00') {
		$selectStart = getdate(strtotime($resource->LoanStart));
		$dosCalendar->setDate($selectStart['mday'], $selectStart['mon'], $selectStart['year']);
	}
	if ($resource->LoanEnd != '0000-00-00') {
		$selectEnd = getdate(strtotime($resource->LoanEnd));
		$doeCalendar->setDate($selectEnd['mday'], $selectEnd['mon'], $selectEnd['year']);
	}
}

// Set checked states for radio buttons
$chkDonate = "";
$chkIndef = "";
$chkRange = "";

// If this is a new form, default to LoanIndef
if (!isset($_SESSION['editingResource'])) {
	$chkIndef = " checked='checked'";
}
else {
	if ($resource->Donate)
		$chkDonate = " checked='checked'";
	else if ($resource->LoanIndef)
		$chkIndef = " checked='checked'";
	else 
		$chkRange = " checked='checked'";
}	

include_once 'includes/header.php';

echo <<<JAVASCRIPT
<script language="javascript" src="calendar/calendar.js"></script>

<script language="JavaScript" type="text/JavaScript">
function Cancel() {
	window.location = "$referrer"
}
</script>
JAVASCRIPT;

include_once 'includes/precontent.php';
include_once 'includes/leftnav.php';
echo <<<FORM1
<div id="content">
<form method="POST" action="process_resource.php">
<span style="text-align: center"><h2>Resources</h2></span>
	<table border="0" cellpadding="5">
		<tr>
			<td colspan="4" align="center" class="formvalue">
				<p>This page allows you to make donations,<br/>or to loan resources for our use.</p>
				<p>Things we often need are vans or busses for transporting teams to disaster locations, 
					supplies for our volunteer teams, and other various tools and equipment.</p>
				<p>Something any such effort is always in need of is monetary donations. These can be made safely and 
				securely through<br/>the North Texas District Disaster Relief Fund,<br/>
				<a href="https://www.logiforms.com/formdata/user_forms/18879_8499773/59675/" target="_blank">
					which you can access by following this link</a></p>
				<p>Thank you for your continued prayers and support!</p>
			</td>
		</tr>
		<tr><td colspan="4"><hr/></td></tr>
		<tr><td colspan="4" align="center" class="formlabel">Fields marked * are required</td></tr>
		<tr><td colspan="4" align="center" class="errortext">$message</td></tr>
		<tr>
			<td align="right" class="formlabel">* Your name:</td>
			<td colspan="3" align="left">
				<input type="text" size="40" name="name" value="$resource->Name" />
				<span class="errortextsmall">{$errorFlags['name']}</span>
			</td>
		</tr>
		<tr>
			<td align="right" class="formlabel">* Adress:</td>
			<td colspan="3" align="left">
				<input type="text" name="address1" size="30" value="$resource->Address1" />
				<span class="errortextsmall">{$errorFlags['address1']}</span>
			</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td colspan="3" align="left">
				<input type="text" name="address2" size="30" value="$resource->Address2" />
			</td>
		</tr>
		<tr>
			<td align="right" class="formlabel" nowrap>* City, State Zip</td>
			<td colspan="3" align="left">
				<input type="text" name="city" size="20" value="$resource->City" />, 
				$statesDropDown
				<input type="text" name="zip" size="10" value="$resource->Zip" />
				<span class="errortextsmall">{$errorFlags['cityzip']}</span>
			</td>
		</tr>
		<tr>
			<td align="right" class="formlabel">Home phone:</td>
			<td colspan="3" align="left">
				<input type="text" name="homePhone" size="15" value="$resource->HomePhone" />&nbsp;
				<span class="errortextsmall">{$errorFlags['phoneNumber']}</span>
				<span class="smallboldtext">(* At least one phone number is required)</span>
			</td>
		</tr>
		<tr>
			<td align="right" class="formlabel">Work phone:</td>
			<td colspan="3" align="left">
				<input type="text" name="workPhone" size="15" value="$resource->WorkPhone" />
			</td>
		</tr>
		<tr>
			<td align="right" class="formlabel">Cell phone:</td>
			<td colspan="3" align="left">
				<input type="text" name="cellPhone" size="15" value="$resource->CellPhone" />
			</td>
		</tr>
		<tr>
			<td align="right" class="formlabel">* Email:</td>
			<td colspan="3" align="left">
				<input type="text" name="email" size="40" value="$resource->Email" />
				<span class="errortextsmall">{$errorFlags['email']}</span>
			</td>
		</tr>
		<tr><td colspan="4">&nbsp;</td></tr>
		<tr>
			<td align="right" valign="top" class="formlabel">* Description of Your Donation:</td>
			<td colspan="3" align="left">
				<textarea name="description" rows="3" cols="60">$resource->Description</textarea>
			</td>
		</tr>
		<tr><td colspan="4">&nbsp;</td></tr>
		<tr>
			<td colspan="4" align="center" class="formlabel">
				* Please specify whether the contribution is a permanent donation,<br/>
				if it will be available during a specified range of dates,<br/>
				or if it will be available whenever we may call on you.
			</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td colspan="3" align="left" class="formlabel">
				<input type="radio" name="duration" value="donate" $chkDonate />Permanent Donation<br/>
				<input type="radio" name="duration" value="indefinite" $chkIndef />Available Whenever You Need It<br/>
				<input type="radio" name="duration" value="range" $chkRange />Available during specified dates (specify below)
			</td>
		</tr>
		<tr>
			<td width="25%" align="right" class="formlabel">Start date:</td>
			<td width="25%">
				<div class="date-tccontainer" style="position:relative; z-index:100">
FORM1;
$dosCalendar->writeScript();
echo <<<FORM2
				</div>
			</td>
			<td width="25%" align="right" class="formlabel">End date:</td>
			<td width="25%">
				<div  class="date-tccontainer" style="z-index:50">
FORM2;
$doeCalendar->writeScript();
echo <<<FORM3
				</div>
			</td>
		</tr>
		<tr><td colspan="4">&nbsp;</td></tr>
		<tr>
			<td colspan="2" align="center">
				<input type="submit" value="      Continue      " />
			</td>
			<td colspan="2" align="center">
				<input type="button" value="       Cancel       " onclick="Cancel();" />
			</td>
		</tr>
		<tr><td colspan="4">&nbsp;</td></tr>
</table>
</form>
FORM3;
include_once 'includes/postcontent.php';
?>