<?php
/* 	Include this in pages where the HEAD closing tag would normally be
 	The inserted text will end at the opening TD tag of a 2-column spanning cell, 
		where the main page content should be placed

	Setup before include:
		$pageSubtitle
		$pageSubSubtitle
		$formAction
		$loadScript
*/
if (!isset($loadScript)) {
  $loadScript = "";
}
if (!isset($pageSubtitle)) {
  $pageSubtitle = "";
}
if (!isset($pageSubSubtitle)) {
  $pageSubSubtitle = "";
}
if (!isset($formAction)) {
  $formAction = "";
}

echo <<<BODY
</head>
<body $loadScript>
    <form name='form1' method='post' action='$formAction'>
		<table width="900px" border="0" align="center">
			<tr>
				<td align="center" valign="top" width="30%">&nbsp;
					<img alt="R and R Logo" width="120" src="../images/stafflogo.jpg" usemap="#header" border="0" />
					<map name="header">
						<area shape="rect" coords="0,15,130,225" href="index.php">
					</map>
				</td>
				<td valign="middle" width="70%">
				    <span class="maintitle">R &amp; R Network Staff</span><br />
				    <span class="subtitle">$pageSubtitle</span><br />
				    <span class="subtitlesmaller">$pageSubSubtitle</span>
				</td>
			</tr>
			<tr>
				<td colspan="2">&nbsp;</td>
			</tr>
			<tr>
BODY;
?>