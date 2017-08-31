<?php
/*
 * Volunteer class
 */
require_once('includes/dbmanager.php');

class Volunteer {
	public $ID;
	public $FirstName;
	public $LastName;
	public $Address1;
	public $Address2;
	public $City;
	public $State;
	public $Zip;
	public $HomePhone;
	public $WorkPhone;
	public $CellPhone;
	public $Email;
	public $DOBMonth;
	public $DOBDay;
	public $DOBYear;
	public $HomeChurch;
	public $SkillsCerts;
	public $MailingList;
	public $AgreeCoC;
	public $Selected;

	public function __construct($id = null) {
		global $connDB, $database_connDB;
		mysql_select_db($database_connDB, $connDB);
		
		if ($id === null) {
			$this->ID = -1;
			$this->FirstName = "";
			$this->LastName = "";
			$this->Address1 = "";
			$this->Address2 = "";
			$this->City = "";
			$this->State = "TX";
			$this->Zip = "";
			$this->HomePhone = "";
			$this->WorkPhone = "";
			$this->CellPhone = "";
			$this->Email = "";
			$this->GetDOBFields();
			$this->HomeChurch = "";
			$this->SkillsCerts = "";
			$this->MailingList = 1;
			$this->AgreeCoC = 0;
			$this->Selected = 0;
		}
		else {
			$query = "SELECT * FROM volunteers WHERE volunteer_id = $id";
			
			$result = @mysql_query($query, $connDB);
			
			// User has already been validated
			$row = @mysql_fetch_assoc($result);
			$this->ID = $id;
			$this->FirstName = $row['first_name'];
			$this->LastName = $row['last_name'];
			$this->Address1 = $row['address1'];
			$this->Address2 = $row['address2'];
			$this->City = $row['city'];
			$this->State = $row['state'];
			$this->Zip = $row['zip'];
			$this->HomePhone = $row['home_phone'];
			$this->WorkPhone = $row['work_phone'];
			$this->CellPhone = $row['cell_phone'];
			$this->Email = $row['email'];
			$this->DOBDay = $row['dobDay'];
			$this->DOBMonth = $row['dobMonth'];
			$this->DOBYear = $row['dobYear'];
			$this->HomeChurch = $row['home_church'];
			$this->SkillsCerts = $row['skills_certs'];
			$this->MailingList = $row['mailing_list'];
			$this->AgreeCoC = $row['agree_coc'];
			$this->Selected = 0;	// Used for selecting for mailing or report. Not saved in database
			
			mysql_free_result($result);
		}
	}
	
	private function GetDOBFields() {
		$dob = time();
		$this->DOBMonth = $dob['mon'];
		$this->DOBDay = $dob['mday'];
		$this->DOBYear = $dob['year'];
	}
	
	public function Select() {
		$this->Selected = 1;
	}
	
	public function Insert() {
		global $connDB, $database_connDB;
		mysql_select_db($database_connDB, $connDB);
		
		$query = "INSERT INTO volunteers (first_name, last_name, address1, address2, city, state, zip, " .
				"home_phone, work_phone, cell_phone, email, dobDay, dobMonth, dobYear, home_church, skills_certs, mailing_list, agree_coc) " .
				"VALUES ('" . mysql_real_escape_string(ucwords(strtolower($this->FirstName)), $connDB) . "', '" .
				mysql_real_escape_string(ucwords(strtolower($this->LastName)), $connDB) . "', '" .
				mysql_real_escape_string($this->Address1) . "', '" .
				mysql_real_escape_string($this->Address2) . "', '" .
				mysql_real_escape_string($this->City, $connDB) . "', '" .
				mysql_real_escape_string($this->State, $connDB) . "', '" .
				mysql_real_escape_string($this->Zip, $connDB) . "', '" .
				mysql_real_escape_string($this->HomePhone, $connDB) . "', '" .
				mysql_real_escape_string($this->WorkPhone, $connDB) . "', '" .
				mysql_real_escape_string($this->CellPhone, $connDB) . "', '" .
				mysql_real_escape_string($this->Email, $connDB) . "', $this->DOBDay, $this->DOBMonth, $this->DOBYear, '" .
				mysql_real_escape_string($this->HomeChurch, $connDB) . "', '" .
				mysql_real_escape_string($this->SkillsCerts, $connDB) . "', $this->MailingList, $this->AgreeCoC)";
				
		if(!mysql_query($query, $connDB)) {
			return FALSE;
		}
		
		$this->ID = mysql_insert_id();
		
		return TRUE;
	}
	
	public function Update() {
		// Update this Volunteer
		global $connDB, $database_connDB;
		mysql_select_db($database_connDB, $connDB);

		$query = "UPDATE volunteers SET " .
			"first_name='" . mysql_real_escape_string(ucwords(strtolower($this->FirstName)), $connDB) . "', " .
			"last_name='" . mysql_real_escape_string(ucwords(strtolower($this->LastName)), $connDB) . "', " .
			"address1='" . mysql_real_escape_string($this->Address1, $connDB) . "', " .
			"address2='" . mysql_real_escape_string($this->Address2, $connDB) . "', " .
			"city='" . mysql_real_escape_string($this->City, $connDB) . "', " .
			"state='" . mysql_real_escape_string($this->State, $connDB) . "', " .
			"zip='" . mysql_real_escape_string($this->Zip, $connDB) . "', " .
			"home_phone='" . mysql_real_escape_string($this->HomePhone, $connDB) . "', " .
			"work_phone='" . mysql_real_escape_string($this->WorkPhone, $connDB) . "', " .
			"cell_phone='" . mysql_real_escape_string($this->CellPhone, $connDB) . "', " .
			"email='" . mysql_real_escape_string($this->Email, $connDB) . "', " .
			"dobDay=$this->DOBDay, dobMonth=$this->DOBMonth, dobYear=$this->DOBYear, " . 
			"home_church='" . mysql_real_escape_string($this->HomeChurch, $connDB) . "', " .
			"skills_certs='" . mysql_real_escape_string($this->SkillsCerts, $connDB) . "', " .
			"mailing_list=$this->MailingList, agree_coc=$this->AgreeCoC " .
			"WHERE volunteer_id=$this->ID";
		
		if(!mysql_query($query, $connDB)) 
			return FALSE;
		else
			return TRUE;
	}
	
	public static function DeleteVolunteer($id) {
		// Delete Volunteer
		global $connDB, $database_connDB;
		mysql_select_db($database_connDB, $connDB);
		
		$query = "DELETE FROM volunteers WHERE volunteer_id=$id";
		
		if(!mysql_query($query, $connDB)) {
			return FALSE;
		}
		
		return TRUE;
	}
	
	public function Exists() {
		// Checks whether this Volunteer already exists. 
		// Returns FALSE if not, returns the ID if true
		global $connDB, $database_connDB;
		mysql_select_db($database_connDB, $connDB);

		$query = "SELECT volunteer_id FROM volunteers WHERE UPPER(last_name)=UPPER('$this->LastName') AND UPPER(first_name)=UPPER('$this->FirstName')";
		
		$result = mysql_query($query, $connDB);
		if($row = mysql_fetch_assoc($result)) {
			$id = $row['volunteer_id'];
		}
		else {
			$id = FALSE;
		}
		
		mysql_free_result($result);
		
		return $id;
	}
	
	public static function DummyVolunteer() {
		// Create an instance for development
		$volunteer = new Volunteer();
		$volunteer->FirstName = "Rob";
		$volunteer->LastName = "Grieshaber";
		$volunteer->Address1 = "123 Main St.";
		$volunteer->City = "Anytown";
		$volunteer->State = "TX";
		$volunteer->Zip = "123456";
		$volunteer->CellPhone = "9725551212";
		$volunteer->Email = "rob@gmail.com";
		$volunteer->DOBDay = 11;
		$volunteer->DOBMonth = 1;
		$volunteer->DOBYear = 1958;
		$volunteer->HomeChurch = "Mustang Creek Community Church";
		$volunteer->SkillsCerts = "BS in Information Technology";
		
		return $volunteer;
	}
	
	public static function GetEventVolunteers($eventID = 0) {
		// Returns an array of Volunteer objects for eventID
		// If eventID is NULL returns all Volunteers
		global $connDB, $database_connDB;
		mysql_select_db($database_connDB, $connDB);

		$query = "SELECT v.volunteer_id FROM `volunteers` v ";
		if ($eventID > 0)
			$query .= ", registrations r WHERE v.volunteer_id=r.volunteer_id AND r.event_id=$eventID";
		$query .= " ORDER BY v.last_name, v.first_name";
			
		$result = mysql_query($query, $connDB);
		
		$volunteerList = array();
		
		while($row = mysql_fetch_assoc($result)) {
			$id = $row['volunteer_id'];
			$volunteer = new Volunteer($id);
			$volunteerList[$volunteer->ID] = $volunteer;
		}
		
		mysql_free_result($result);
		
		return $volunteerList;
	}
}
?>