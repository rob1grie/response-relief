<?php
// Staff class 
require_once('../includes/dbmanager.php');

class Staff
{
	public $ID;
	public $Username;
	public $Password;
	public $FirstName;
	public $LastName;
	public $Email;
	public $ResetPassword;
	
	public function __construct($id = null) {

		global $connDB, $database_connDB;
		mysql_select_db($database_connDB, $connDB);
		
		if ($id === null) {
			$this->ID = -1;
			$this->Username = "";
			$this->Password = "";
			$this->FirstName = "";
			$this->LastName = "";
			$this->Email = "";
			$this->ResetPassword = 0;
			$this->Menu = array();
		}
		else {
			$query = "SELECT * FROM staff WHERE staff_id = $id";
			
			$result = @mysql_query($query, $connDB);
			
			// User has already been validated
			$row = @mysql_fetch_assoc($result);
			$this->ID = $id;
			$this->Username = strtoupper($row['username']);
			$this->Password = $row['password'];
			$this->FirstName = stripslashes($row['first_name']);
			$this->LastName = stripslashes($row['last_name']);
			$this->Email = $row['email'];
			$this->ResetPassword = $row['reset_password'];
			
			mysql_free_result($result);
		}
	}
	
	public static function ValidateUser($in_username, $in_userpass) {
		global $connDB, $database_connDB;
		mysql_select_db($database_connDB, $connDB);

		$query = "SELECT * FROM staff WHERE username='$in_username' and password='$in_userpass'";

		$result = @mysql_query($query, $connDB) or die(mysql_error());

		if (mysql_num_rows($result) == 0) {
			// No user found with the given username and password. Return -1
			$ID = -1;
		}
		else {
			$row = mysql_fetch_assoc($result);
			// User found. Return user ID
			$ID = $row['staff_id'];
		}
		
		mysql_free_result($result);
		
		return $ID;
	}
	
	public static function StaffNameExists($username) {
		// Checks whether username already exists in STAFF table
		global $connDB, $database_connDB;
		mysql_select_db($database_connDB, $connDB);
			
		$query = "SELECT * FROM staff where username='$username';";
		$result = @mysql_query($query, $connDB);
		
		$exists = mysql_num_rows($result)>0;
		
		mysql_free_result($result);
		
		return $exists;
	}
	
	public function Update() {
		// Saves this Staff
		global $connDB, $database_connDB;
		mysql_select_db($database_connDB, $connDB);
		
		$query = "UPDATE staff SET " .
			"username='$this->Username', password='$this->Password', first_name='" . mysql_escape_string($this->FirstName) . "', " .
			"last_name='" . mysql_escape_string($this->LastName) . "', email='$this->Email', " .
			"reset_password=$this->ResetPassword " .
			"WHERE staff_id=$this->ID;";
		
		if(!mysql_query($query, $connDB)) 
			return FALSE;
		else
			return TRUE;
	}
	
	public function Insert() {
		// Save new Staff
		global $connDB, $database_connDB;
		mysql_select_db($database_connDB, $connDB);
			
		$query = "INSERT INTO staff (username, password, first_name, last_name, email, reset_password) " .
				"VALUES ('$this->Username', '" . mysql_escape_string($this->Password) . "', '" . 
				mysql_escape_string($this->FirstName) . "', " . "'" . mysql_escape_string($this->LastName) . 
				"', '" . mysql_escape_string($this->Email) . "', $this->ResetPassword)";
		if(!mysql_query($query, $connDB)) {
			return FALSE;
		}
		
		$this->ID = mysql_insert_id();
		
		return TRUE;
	}
	
	public function UpdatePassword($newPass) {
		// Updates this Staff's password
		global $connDB, $database_connDB;
		mysql_select_db($database_connDB, $connDB);
		
		$query = "UPDATE staff SET password='$newPass' WHERE staff_id = $this->ID";
		if(mysql_query($query, $connDB)) {
			$this->Password = $newPass;
			$this->PassConfirm = $newPass;
			return TRUE;
		}
		else
			return FALSE;
	}
	
	public function ClearResetPassword() {
		// Clears the flag ResetPassword
		global $connDB, $database_connDB;
		mysql_select_db($database_connDB, $connDB);
		
		$query = "UPDATE staff SET reset_password = 0 WHERE staff_id = $this->ID";
		if(mysql_query($query, $connDB)) {
			$this->ResetPassword = 0;
			return TRUE;
		}
		else
			return FALSE;
	}
	
	private function SetStaffSession() {
		// Saves this ID to the session
		$_SESSION['staff_id'] = $this->ID;
	}
	
	public static function GetStaffName($staffID) {
		// Static function to just retrieve staffID's name
		// Name is returned in an array with keys of firstname and lastname
		global $connDB, $database_connDB;
		mysql_select_db($database_connDB, $connDB);
		
		$query = "SELECT first_name, last_name FROM staff WHERE staff_id=$staffID";

		$result = mysql_query($query, $connDB) or die(mysql_error());

		if (mysql_num_rows($result) == 0) {
			// If for some reason no result was found, just set the name to something generic
			$staffName = array("firstname" => "Staff", "lastname" => "Member");
		}
		else {
			// Staff found, get the name
			$staff = mysql_fetch_assoc($result);
			$staffName = array("firstname" => $staff[first_name], "lastname" => $staff[last_name]);
		}
		
		mysql_free_result($result);

		return $staffName;
	}
	
	public static function DeleteStaff($id) {
		// Deletes Staff with id
		global $connDB, $database_connDB;
		mysql_select_db($database_connDB, $connDB);
		
		$success = true;
		
		// Delete Staff from STAFF table
		$query = "DELETE FROM staff WHERE staff_id=$id";
		return @mysql_query($query, $connDB);
	}
	
	public static function GetAllStaff() {
		// Returns an array of Staff objects
		global $connDB, $database_connDB;
		mysql_select_db($database_connDB, $connDB);
		
		$query = "SELECT staff_id FROM staff ORDER BY UserName;";
				
		$result = @mysql_query($query, $connDB);
				
		$allStaff = array();
		while ($row = @mysql_fetch_assoc($result)) {
			$staff = new Staff($row['staff_id']);
			array_push($allStaff, $staff);
		}
		
		mysql_free_result($result);
		
		return $allStaff;
	}	
}
?>