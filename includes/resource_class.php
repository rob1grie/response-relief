<?php
// Resource class
include_once 'includes/dbmanager.php';
include_once 'includes/utilities.php';

class Resource {
	public $ID;
	public $Name;
	public $Address1;
	public $Address2;
	public $City;
	public $State;
	public $Zip;
	public $HomePhone;
	public $WorkPhone;
	public $CellPhone;
	public $Email;
	public $LoanStart;
	public $LoanEnd;
	public $LoanIndef;
	public $Donate;
	public $Description;
	
	public function __construct($id = null) {
		global $connDB, $database_connDB;
		mysql_select_db($database_connDB, $connDB);
		
		if ($id == null) {
			$this->ID = -1;
			$this->Name = "";
			$this->Address1 = "";
			$this->Address2 = "";
			$this->City = "";
			$this->State = "";
			$this->Zip = "";
			$this->HomePhone = "";
			$this->WorkPhone = "";
			$this->CellPhone = "";
			$this->Email = "";
			$this->LoanStart = GetSQLDate(time());
			$this->LoanEnd = GetSQLDate(time());
			$this->LoanIndef = 0;
			$this->Donate = 0;
			$this->Description = "";
		}
		else {
			$query = "SELECT * FROM resources WHERE resource_id=$id";
			
			$result = mysql_query($query, $connDB);
			if ($row = mysql_fetch_assoc($result)){
				$this->ID = $id;
				$this->Name = $row['name'];
				$this->Address1 = $row['address1'];
				$this->Address2 = $row['address2'];
				$this->City = $row['city'];
				$this->State = $row['state'];
				$this->Zip = $row['zip'];
				$this->HomePhone = $row['home_phone'];
				$this->WorkPhone = $row['work_phone'];
				$this->CellPhone = $row['cell_phone'];
				$this->Email = $row['email'];
				$this->LoanStart = $row['loan_start'];
				$this->LoanEnd = $row['loan_end'];
				$this->LoanIndef = $row['loan_indef'];
				$this->Donate = $row['donate'];
				$this->Description = $row['description'];
			}
			
			mysql_free_result($result);
		}
	}
	
	public function Insert() {
		// Adds a new Resource
		global $connDB, $database_connDB;
		mysql_select_db($database_connDB, $connDB);
		
		$query = "INSERT INTO resources (name, address1, address2, city, state, zip, home_phone, work_phone, " .
				"cell_phone, email, loan_start, loan_end, loan_indef, donate, description) VALUES ('" .
				mysql_real_escape_string($this->Name, $connDB) . "', '" .
				mysql_real_escape_string($this->Address1, $connDB) . "', '" .
				mysql_real_escape_string($this->Address2, $connDB) . "', '" .
				mysql_real_escape_string($this->City, $connDB) . "', '" .
				mysql_real_escape_string($this->State, $connDB) . "', '" .
				mysql_real_escape_string($this->Zip, $connDB) . "', '" .
				mysql_real_escape_string($this->HomePhone, $connDB) . "', '" .
				mysql_real_escape_string($this->WorkPhone, $connDB) . "', '" .
				mysql_real_escape_string($this->CellPhone, $connDB) . "', '" .
				mysql_real_escape_string($this->Email, $connDB) . "', '" .
				$this->LoanStart . "', '" . $this->LoanEnd . "', " . $this->LoanIndef . ", " . $this->Donate . ", '" .
				mysql_real_escape_string($this->Description, $connDB) . "')";
				
		if (!mysql_query($query, $connDB))
			return FALSE;
			
		$this->ID = mysql_insert_id();
		
		return TRUE;
	}
	
	public function Update() {
		// Update existing Resource
		global $connDB, $database_connDB;
		mysql_select_db($database_connDB, $connDB);
		
		$query = "UPDATE resources SET " .
			"name='" . mysql_real_escape_string($this->Name, $connDB) . "', " .
			"address1='" . mysql_real_escape_string($this->Address1, $connDB) . "', " .
			"address2='" . mysql_real_escape_string($this->Address2, $connDB) . "', " .
			"city='" . mysql_real_escape_string($this->City, $connDB) . "', " .
			"state='" . mysql_real_escape_string($this->State, $connDB) . "', " .
			"zip='" . mysql_real_escape_string($this->Zip, $connDB) . "', " .
			"home_phone='" . mysql_real_escape_string($this->HomePhone, $connDB) . "', " .
			"work_phone='" . mysql_real_escape_string($this->WorkPhone, $connDB) . "', " .
			"cell_phone='" . mysql_real_escape_string($this->CellPhone, $connDB) . "', " .
			"email='" . mysql_real_escape_string($this->Email, $connDB) . "', " .
			"loan_start='$this->LoanStart', loan_end='$this->LoanEnd', loan_indef=$this->LoanIndef, " .
			"donate=$this->Donate, description='" . mysql_real_escape_string($this->Description, $connDB) . "' " .
			"WHERE resource_id=$this->ID";

		if(!mysql_query($query, $connDB)) 
			return FALSE;
		else
			return TRUE;
	}
	
	public static  function DummyResource() {
		// Returns a Resource for development
		$resource = new Resource();
		$resource->Name = "Tommy Tutone";
		$resource->Address1 = "123 Main St.";
		$resource->City = "Anytown";
		$resource->State = "TX";
		$resource->Zip = "123456";
		$resource->CellPhone = "9725551212";
		$resource->Email = "robgrie@gmail.com";
		$resource->LoanIndef = 1;
		$resource->Description = "I have an 11 passenger van available whenever you need it.";

		return $resource;
	}
}
?>