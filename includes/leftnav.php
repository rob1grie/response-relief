<?php
$scriptName = GetScriptName();

echo "<div id=\"sidebar\">";

if ($scriptName == 'index.php')
	echo "<img src='images/home_button_blank.jpg' /><br/>";
else 
	echo <<<HOMEBUTTON
		<a href="index.php" onMouseOver="MM_swapImage('home_button','','images/home_button_rollover.jpg',1)" onMouseOut="MM_swapImgRestore()"><img src="images/home_button.jpg" name="home_button" border="0" id="home_button" /></a><br/>
HOMEBUTTON;

if ($scriptName == 'info.php')
	echo "<img src='images/info_button_rollover.jpg' /><br/>";
else 
	echo <<<INFOBUTTON
	<a href="info.php" onMouseOver="MM_swapImage('info_button','','images/info_button_rollover.jpg',1)" onMouseOut="MM_swapImgRestore()"><img src="images/info_button.jpg" name="info_button" border="0" id="info_button" /></a><br/>
INFOBUTTON;

if ($scriptName == 'volunteer.php')
	echo "<img src='images/volunteer_button_rollover.jpg' /><br/>";
else 
	echo <<<VOLBUTTON
	<a href="volunteer.php" onMouseOver="MM_swapImage('volunteer_button','','images/volunteer_button_rollover.jpg',1)" onMouseOut="MM_swapImgRestore()"><img src="images/volunteer_button.jpg" name="volunteer_button" border="0" id="volunteer_button" /></a><br/>
VOLBUTTON;

if ($scriptName == 'resources.php')
	echo "<img src='images/resources_button_rollover.jpg' /><br/>";
else 
	echo <<<RESBUTTON
	<a href="resources.php" onMouseOver="MM_swapImage('resources_button','','images/resources_button_rollover.jpg',1)" onMouseOut="MM_swapImgRestore()"><img src="images/resources_button.jpg" name="resources_button" border="0" id="resources_button" /></a><br/>
RESBUTTON;

if ($scriptName == 'contact.php')
	echo "<img src='images/contact_button_rollover.jpg' /><br/>";
else 
	echo <<<CONTACTBUTTON
	<a href="contact.php" onMouseOver="MM_swapImage('contact_button','','images/contact_button_rollover.jpg',1)" onMouseOut="MM_swapImgRestore()"><img src="images/contact_button.jpg" name="contact_button" border="0" id="contact_button" /></a>
CONTACTBUTTON;

echo "</div>";
?>