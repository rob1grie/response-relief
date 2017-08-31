<?php
require_once '../includes/utilities.php';
$scriptName = GetScriptName();

echo "<td align=\"center\" valign=\"top\">";
echo "<table border=\"2\" cellspacing=\"0\" cellpadding=\"5\" width=\"70%\"><tr><td><table>";
	// Add Home link
	echo "<tr><td>";
	if ($scriptName == 'index.php')
		echo "<span class='selectedmenu'>Home</span>";
	else 
		echo "<a href='index.php'>Home</a></td></tr>\r\n";
		
echo "<tr><td>&nbsp;</td></tr>";

if ($scriptName == 'event_list.php')
	echo "<tr><td><span class='selectedmenu'>Event List&nbsp;</span></td></tr>";
else
	echo "<tr><td><a href='event_list.php'>Event List</a></td></tr>";
	
echo "<tr><td>&nbsp;</td></tr>";

if ($scriptName == 'volunteer_list.php')
	echo "<tr><td><span class='selectedmenu'>Volunteers&nbsp;</span></td></tr>";
else 
	echo "<tr><td><a href='volunteer_list.php'>Volunteers</a></td></tr>";

echo "<tr><td>&nbsp;</td></tr>";

if ($scriptName == 'mailing_list.php')
	echo "<tr><td><span class='selectedmenu'>Mail-Out&nbsp;</span></td></tr>";
else 
	echo "<tr><td><a href='mailing_list.php'>Mail-Out</a></td></tr>";

echo "<tr><td>&nbsp;</td></tr>";

if ($scriptName == 'registration_list.php')
	echo "<tr><td><span class='selectedmenu'>Registrations&nbsp;</span></td></tr>";
else 
	echo "<tr><td><a href='registration_list.php'>Registrations</a></td></tr>";

echo "<tr><td>&nbsp;</td></tr>";

if ($scriptName == 'gallery_list.php')
	echo "<tr><td><span class='selectedmenu'>Photo Galleries&nbsp;</span></td></tr>";
else 
	echo "<tr><td><a href='gallery_list.php'>Photo Galleries</a></td></tr>";

// If user is admin, include link to staff list
if ($staff->LastName == 'Admin') {
	echo "<tr><td>&nbsp;</td></tr>";

	if ($scriptName == 'stafflist.php')
		echo "<tr><td><span class='selectedmenu'>Staff List&nbsp;</span></td></tr>";
	else
		echo "<tr><td><a href='stafflist.php'>Staff List</a></td></tr>";
}
    echo "<tr><td>&nbsp;</td></tr><tr><td><a href='logoutsuccess.php'>Log Out</a></td></tr>";
    echo "</table></td></tr></table>";
?>