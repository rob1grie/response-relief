<?php
// Event class
require_once 'utilities.php';
require_once 'includes/dbmanager.php';

class Event
{
	public $ID;
	public $Title;
	public $Location;
	public $Description;
	public $StartDate;
	public $EndDate;
	public $Departure;
	public $WhatToBring;
	public $WhereToStay;
	public $AgeRequirements;
	public $HealthRequirements;
	public $PhotoGalleryID;			// Must be set externally using gallery->ID retrieved from Gallery::GetEventGallery($eventID)
	
	public function __construct($id = null) {
		global $connDB, $database_connDB;
		mysql_select_db($database_connDB, $connDB);
		
		if ($id === null) {
			$this->ID = -1;
			$this->Title = "";
			$this->Location = "";
			$this->Description = "";
			$this->StartDate = GetCurrentDateTime();
			$this->EndDate = GetCurrentDateTime();
			$this->Departure = "";
			$this->WhatToBring = "";
			$this->WhereToStay = "";
			$this->AgeRequirements = "";
			$this->HealthRequirements = "";
		}
		else {
			$query = "SELECT * FROM events WHERE event_id = $id";
			
			$result = @mysql_query($query, $connDB);
			
			$row = @mysql_fetch_assoc($result);
			$this->ID = $id;
			$this->Title = stripslashes($row['title']);
			$this->Location = stripslashes($row['location']);
			$this->Description = stripslashes($row['description']);
			$this->StartDate = $row['start_date'];
			$this->EndDate = $row['end_date'];
			$this->Departure = stripslashes($row['departure']);
			$this->WhatToBring = stripslashes($row['what_to_bring']);
			$this->WhereToStay = stripslashes($row['where_to_stay']);
			$this->AgeRequirements = stripslashes($row['age_requirements']);
			$this->HealthRequirements = stripslashes($row['health_requirements']);
			
			mysql_free_result($result);
		}
	}
	
	public function Insert() {
		// Save new Event
		global $connDB, $database_connDB;
		mysql_select_db($database_connDB, $connDB);
		
		$description = ReplaceBR($this->Description);
		
		$query = "INSERT INTO events (title, location, description, start_date, end_date, departure, what_to_bring, where_to_stay, age_requirements, health_requirements) " .
				"VALUES ('" . mysql_real_escape_string($this->Title, $connDB) . "', '" .
				mysql_real_escape_string($this->Location, $connDB) . "', '" .
				mysql_real_escape_string($description, $connDB) . "', '" .
				$this->StartDate . "', '" . $this->EndDate . "', '" .
				mysql_real_escape_string($this->Departure, $connDB) . "', '" .
				mysql_real_escape_string($this->WhatToBring, $connDB) . "', '" .
				mysql_real_escape_string($this->WhereToStay, $connDB) . "', '" .
				mysql_real_escape_string($this->AgeRequirements, $connDB) . "', '" .
				mysql_real_escape_string($this->HealthRequirements, $connDB) . "')";
		if(!mysql_query($query, $connDB)) {
			return FALSE;
		}
		
		$this->ID = mysql_insert_id();
		
		return TRUE;
	}
	
	public function Update() {
		// Saves this Event
		global $connDB, $database_connDB;
		mysql_select_db($database_connDB, $connDB);
		
		$description = ReplaceBR($this->Description);
		
		$query = "UPDATE events SET " .
			"title='" . mysql_real_escape_string($this->Title, $connDB) . "', " .
			"location='" . mysql_real_escape_string($this->Location, $connDB) . "', " .
			"description='" . mysql_real_escape_string($description, $connDB) . "', " .
			"start_date=$this->StartDate, end_date=$this->EndDate, " .
			"departure='" . mysql_real_escape_string($this->Departure, $connDB) . "', " .
			"what_to_bring='" . mysql_real_escape_string($this->WhatToBring, $connDB) . "', " .
			"where_to_stay='" . mysql_real_escape_string($this->WhereToStay, $connDB) . "', " .
			"age_requirements='" . mysql_real_escape_string($this->AgeRequirements, $connDB) . "', " .
			"health_requirements='" . mysql_real_escape_string($this->HealthRequirements, $connDB) . "' " .
			"WHERE event_id=$this->ID;";
		
		if(!mysql_query($query, $connDB)) 
			return FALSE;
		else
			return TRUE;
	}
	
	public static function DeleteEvent($id) {
		// Delete an Event
		global $connDB, $database_connDB;
		mysql_select_db($database_connDB, $connDB);
			
		$query = "DELETE FROM events WHERE event_id=$id";
		
		if(!mysql_query($query, $connDB)) {
			return FALSE;
		}
		
		return TRUE;
	}
	
	public static function GetAllEvents() {
		// Returns an array of all events, sorted by StartDate in descending order
		global $connDB, $database_connDB;
		mysql_select_db($database_connDB, $connDB);
		
		$query = "SELECT event_id FROM events ORDER BY start_date DESC";
		$result = mysql_query($query, $connDB);
		
		$allEvents = array();
		while($row = mysql_fetch_assoc($result)) {
			$event = new Event($row['event_id']);
			array_push($allEvents, $event);
		}
		
		mysql_free_result($result);
		
		return $allEvents;
	}

	public static function GetLatestEvents($endDate) {
		// Returns an array of all events after endDate, sorted by StartDate in descending order
		global $connDB, $database_connDB;
		mysql_select_db($database_connDB, $connDB);
		
		$query = "SELECT event_id FROM events WHERE end_date >= $endDate ORDER BY start_date DESC";
		$result = mysql_query($query, $connDB);
		
		$events = array();
		while($row = mysql_fetch_assoc($result)) {
			$event = new Event($row['event_id']);
			array_push($events, $event);
		}
		
		mysql_free_result($result);
		
		return $events;
	}
	
	public static function GetDummyEvent() {
		// Returns dummy Event for development
		$event = new Event();
		$event->Title = "Wildfire Relief in Austin";
		$event->Location = "Austin, TX";
		$event->Description = "We will be providing meals for evacuees and firefighters";
		$event->Departure = "Mustang Creek Community Church";
		$event->WhatToBring = "You will need bedding, personal hygiene items, work clothes";
		$event->WhereToStay = "Tents, located as needed";
		$event->StartDate = 1315717200;
		$event->EndDate = 1316235600;
		$event->AgeRequirements = "Minimum 18 years of age";
		$event->HealthRequirements = "Must have no health conditions that will prevent strenuous lifting and a lot of walking";
		return $event;
	}
}
?>