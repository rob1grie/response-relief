<?php
// Registration class
// Need to adjust include path if being used in the staff/ directory
if (strpos($_SERVER['SCRIPT_NAME'], 'staff/')) {
	require_once '../includes/event_class.php';
	require_once '../includes/volunteer_class.php';
}
else {
	require_once 'includes/event_class.php';
	require_once 'includes/volunteer_class.php';
}
require_once 'includes/dbmanager.php';

class Registration {
	public $ID;
	public $EventID;
	public $VolunteerID;
	public $StartDate;
	public $EndDate;
	public $DateOfReg;
	
	public function __construct($id = NULL) {
		global $connDB, $database_connDB;
		mysql_select_db($database_connDB, $connDB);
		
		if ($id === NULL) {
			$this->ID = -1;
			$this->EventID = -1;
			$this->VolunteerID = -1;
			$this->StartDate = time();
			$this->EndDate = time();
			$this->DateOfReg = time();
		}
		else {
			$query = "SELECT * FROM registrations WHERE registration_id = $id";
			
			$result = @mysql_query($query, $connDB);
			
			// User has already been validated
			$row = @mysql_fetch_assoc($result);
			$this->ID = $id;
			$this->EventID = $row['event_id'];
			$this->VolunteerID = $row['volunteer_id'];
			$this->StartDate = $row['start_date'];
			$this->EndDate = $row['end_date'];
			$this->DateOfReg = $row['date_of_reg'];
			
			mysql_free_result($result);
		}
	}
	
	public function Insert() {
		// Add this Registration
		global $connDB, $database_connDB;
		mysql_select_db($database_connDB, $connDB);
		
		$query = "INSERT INTO registrations (event_id, volunteer_id, start_date, end_date, date_of_reg) " .
				"VALUES ($this->EventID, $this->VolunteerID, $this->StartDate, $this->EndDate, $this->DateOfReg)";
		
		if(!mysql_query($query, $connDB)) {
			return FALSE;
		}
		
		$this->ID = mysql_insert_id();
		
		return TRUE;
	}
	
	public function Update() {
		// Update this Registration
		global $connDB, $database_connDB;
		mysql_select_db($database_connDB, $connDB);
		
		$query = "UPDATE registrations SET " .
			"event_id=$this->EventID, volunteer_id=$this->VolunteerID, start_date=$this->StartDate, " .
			"end_date=$this->EndDate, date_of_reg=$this->DateOfReg";
		
		if(!mysql_query($query, $connDB)) 
			return FALSE;
		else
			return TRUE;
	}
	
	public static function GetRegistrations($eventID = 0) {
		// Returns an array of Registrations, with associated Event and Volunteer objects
		// If eventID is 0, returns all Registrations
		// Otherwise, returns Registrations for eventID
		global $connDB, $database_connDB;
		mysql_select_db($database_connDB, $connDB);
		
		// First get set of all registrations, sorting by the associated event title
		$query = "SELECT r.registration_id, e.title, v.last_name, v.first_name " .
				"FROM registrations r, events e, volunteers v " .
				"WHERE r.event_id=e.event_id AND r.volunteer_id=v.volunteer_id ";
		if ($eventID > 0)
			$query .= "AND e.event_id=$eventID ";
		$query .= "ORDER BY e.title, v.last_name, v.first_name";
		
		$result = mysql_query($query, $connDB);
		$registrations = array();
		
		while($row = mysql_fetch_assoc($result)) {
			$registration = new Registration($row['registration_id']);
			$event = new Event($registration->EventID);
			$volunteer = new Volunteer($registration->VolunteerID);
			array_push($registrations, 
				array("Registration"=>$registration, "Event"=>$event, "Volunteer"=>$volunteer));
		}
		
		mysql_free_result($result);
		
		return $registrations;
	}
}
?>