<?php
// Show the menu and screens for Staff functions
require_once('../includes/utilities.php');
require_once 'includes/staff_class.php';
require_once 'includes/log_class.php';

// Get necessary data for this Staff
if (!isset($_SESSION)) {
  session_start();
}

if (!isset($_SESSION['staff'])) {
  header("Location: stafflogin.php");
}

$staff = $_SESSION['staff'];
$staffFirstname = $staff->FirstName;
$staffLastname = $staff->LastName;
$loadScript = "";

Log::LogActivity($_SESSION['staff'], GetScriptName());

ClearEditSession();

$pageSubtitle = "Staff Home Page";
$pageSubSubtitle = "";
$loadScript = "";
$formAction = "";

include_once 'includes/admin_main_header.php';

include_once 'includes/admin_main_body_precontent.php';
include_once 'includes/leftmenu.php';
include_once 'includes/admin_main_body_postmenu.php';

echo "<td align='center' valign='top'>";
echo <<<CONTENT
	Welcome to the<br/>
	<span class="subtitlesmaller">Response-Relief.net Staff Site</span><br/><br/>
	Besides being the initial "landing page" for the site,<br/>
	this page will also be used for important notices,<br/>
	keeping Response &amp; Relief Network staff members up to date.<br/><br/>
	If you have any questions or issues please contact<br/>
	webmaster@response-relief.net
CONTENT;

include_once 'includes/admin_main_body_postcontent.php';
?>
