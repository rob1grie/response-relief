<?php
// Activity Log class 
require_once '../includes/dbmanager.php';
require_once 'includes/staff_class.php';

session_start();

class Log
{
	public $ID;
	public $StaffID;
	public $Page;
	public $AccessTime;
	
	public function __construct($id = null) {

		global $connDB, $database_connDB;
		mysql_select_db($database_connDB, $connDB);
		
		if ($id === null) {
			$this->ID = -1;
			$this->StaffID = -1;
			$this->Page = "";
			$this->AccessTime = time();
		}
		else {
			$query = "SELECT * FROM log WHERE id = $id";
			
			$result = @mysql_query($query, $connDB);
			
			$row = @mysql_fetch_assoc($result);
			$this->ID = $id;
			$this->StaffID = $row['staff_id'];
			$this->Page = $row['page'];
			$this->AccessTime = $row['access_time'];
			
			mysql_free_result($result);
		}
	}
	
	public function GetAllLogs() {
		// Returns an array of Log objects
		global $connDB, $database_connDB;
		mysql_select_db($database_connDB, $connDB);
		
		$query = "SELECT id FROM log ORDER BY access_time;";
				
		$result = @mysql_query($query, $connDB);
				
		$allLogs = array();
		while ($row = @mysql_fetch_assoc($result)) {
			$log = new Log($row['id']);
			array_push($allLogs, $log);
		}
		
		mysql_free_result($result);
		
		return $allLogs;
	}
	
	public static function LogActivity($staff, $url) {
		// Saves activity log
		global $connDB, $database_connDB;
		mysql_select_db($database_connDB, $connDB);
		
		$staffName = $staff->FirstName . ' ' . $staff->LastName;
		$time = time();

		$query = "INSERT INTO log (staff_name, url, visit_date) " .
				"VALUES ('$staffName', '$url', $time)";
		
		$result = @mysql_query($query, $connDB);
		
		return $result;
	}
}
?>