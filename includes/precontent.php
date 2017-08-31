<?php
if (isset($loadScript) && strlen($loadScript)>0)
	$loadScript = $loadScript . "; MM_preloadImages('images/info_button_rollover.jpg','images/volunteer_button_rollover.jpg','images/resources_button_rollover.jpg','images/contact_button_rollover.jpg')";
else 
	$loadScript = "MM_preloadImages('images/info_button_rollover.jpg','images/volunteer_button_rollover.jpg','images/resources_button_rollover.jpg','images/contact_button_rollover.jpg')";
	
echo <<<PRECONTENT
</head>
<body onLoad="$loadScript">
<div id="outerdiv">
<div id="header_out">
<div id="header">
	<a href="index.php"><img src="images/banner.jpg" border="0" /></a>
</div>
</div>
PRECONTENT;
?>