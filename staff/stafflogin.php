<?php 
session_start();

?>
<html>
<head>
    <title>Response &amp; Relief Network | Staff Login</title>
	<link href="staff.css" rel="stylesheet" type="text/css" />
	<script language="Javascript">
function sendIt(arg){
	if (arg == "submit"){
		document.form1.action="process_login.php";
		document.form1.submit();
	}
	else if(arg == "cancel"){
		document.form1.action="logoutsuccess.php";
		document.form1.submit();
	}
}	
	</script>
</head>
<body OnLoad="document.form1.username.focus();">
	<form id="form1" name="form1" method="post" action="process_login.php">
		<table width="100%" border="0">
			<tr>
				<td vAlign="top" align="center" width="15%" rowSpan="4" class="maintitle" style="padding-right: 10px;">
					<img  src="../images/stafflogo.jpg" alt="R & R logo" /></td>
				<td width="85%"><span class="maintitle">Response-Relief.net</span><br>
						<span class="subtitle">Staff Login</span></td>
			</tr>
			<tr>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td class="formboldmessage">Enter your User Name and Password:
			</tr>
			<tr>
				<td>
					<table align="left" width="60%" border="0">
						<tr vAlign="baseline">
							<td width="50%" noWrap align="right" class="formlabel">User Name:</td>
							<td width="50%"><input type='text' name='username' size='20'/></td>
						</tr>
						<tr vAlign="baseline">
							<td noWrap align="right" class="formlabel">Password:</td>
							<td><input type='password' name='userpass' size='20'/></td>
						</tr>
						<tr>
							<td colspan="2">
								<?php
									if (isset($_SESSION['err']) && strlen($_SESSION['err'])>0) {
										echo "<span class='errortext'>";
										switch ($_SESSION['err']) {
											case 'uid':
												echo "Invalid username or password</span>";
												break;
											case 'mysql':
												echo "Error connecting to database</span>";
										}
									}
									else
										echo "&nbsp;";
								?>
							</td>
						</tr>
						<tr>
							<td colspan="2" align="center">
								<table width="80%" border="0">
									<tr>
										<td width="50%" align="center">
											<input type="submit" value="    Login    " OnClick="sendIt('submit')" />
										</td>
										<td width="50%" align="center">
											<input type="submit" value="    Cancel   " OnClick="sendIt('cancel')" />
										</td>
									</tr>
								</table>
							</td>
						</tr>
					</table> 
				</td>
			</tr>
			<tr>
				<td colspan="2">&nbsp;</td>
			</tr>
		</table>
	</form>
</body>
</html>
