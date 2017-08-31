<?php
	// General scripts used application-wide
	function ClearSession() {
		// Unsets specific session variables
		unset($_SESSION);
	}
	
	function ClearEditSession() {
		// Unsets session variables used while editing
		if (isset($_SESSION['editMode'])) { unset($_SESSION['editMode']);}
		if (isset($_SESSION['errorFields'])) {unset($_SESSION['errorFields']);}
		if (isset($_SESSION['editingID'])) {unset($_SESSION['editingID']);}
		if (isset($_SESSION['editingVolunteer'])) {unset($_SESSION['editingVolunteer']);}
		if (isset($_SESSION['editingResource'])) {unset($_SESSION['editingResource']);}
		if (isset($_SESSION['editingGallery'])) {unset($_SESSION['editingGallery']);}
		if (isset($_SESSION['referrer'])) {unset($_SESSION['referrer']);}
		if (isset($_SESSION['dosDate'])) {unset($_SESSION['dosDate']);}
		if (isset($_SESSION['doeDate'])) {unset($_SESSION['doeDate']);}
		if (isset($_SESSION['selectedEvent'])) {unset($_SESSION['selectedEvent']);}
	}
	
	function GetScriptPath() {
		$script_filename = getenv('PATH_TRANSLATED');
		if (empty($script_filename)) {
			$script_filename = getenv('SCRIPT_FILENAME');
		}
		$script_filename = str_replace('', '/', $script_filename);
		$script_filename = str_replace('//', '/', $script_filename);
		$dir_fs_www_root_array = explode('/', dirname($script_filename));
		$dir_fs_www_root = array();
		for ($i=0, $n=sizeof($dir_fs_www_root_array); $i<$n; $i++) {
			$dir_fs_www_root[] = $dir_fs_www_root_array[$i];
		}
		$dir_fs_www_root = implode('/', $dir_fs_www_root) . '/';
		
		return $dir_fs_www_root;
	}
	
	function GetScriptName() {
		$parts = explode('/', $_SERVER['PHP_SELF']);
		$scriptName = $parts[count($parts)-1];
		return $scriptName;
	}

	function GetDateFields($date) {
		// Returns an associative array with Month, Day and Year from date
		return getdate($date);
	}
	
	function GetDateFromFields($month, $day, $year) {
		// Returns Unix INT timestamp from month, day and year
		$test = strtotime($year . '-' . $month . '-' . $day);
		return strtotime($year . '-' . $month . '-' . $day);
	}
	
	function GetPreviousSunday() {
		// Returns Unix INT timestamp for most recent Sunday, starting with today
		$date = time();
		while (date("w", $date)>0) {
			$date = $date - (24 * 60 * 60);
			$w = date("w", $date);
			$test = date("Y-m-d", $date);
		}
		return $date;
	}
	
	function GetSQLDate($date) {
		// Returns string SQL date from Unix timestamp $date
		$date = getdate($date);
		$test = $date['year'] . '-' . str_pad($date['mon'], 2, '0', STR_PAD_LEFT) . '-' . str_pad($date['mday'], 2, '0', STR_PAD_LEFT);
		return $test;
	}
	
	function GetStringDateFromSQLDate($sql_date) {
		// Returns a string Month Day, Year from a SQL formatted date string yyyy-mm-dd
		$date = strtotime($sql_date);
		$date = getdate($date);
		$date = $date['month'] . " " . $date['mday'] . ", " . $date['year'];
		return $date;
	}
	
	function GetShortStringDateFromSQLDate($sql_date) {
		// Returns a MM/DD/YYYY string date from a SQL formatted date string yyyy-mm-dd
		$date = strtotime($sql_date);
		$date = getdate($date);
		$date =$date['mon'] . "/" . $date['mday'] . "/" . $date['year'];
		return $date;
		 
	}
	
	function GetDateFromMySQLDate($mysqlDate) {
		// Receives mysqlDate in form YYYY-MM-DD and returns MM/DD/YYYY
		if (strlen($mysqlDate)<10 || ($mysqlDate == "0000-00-00"))
			return "";
			
		$newDate = substr($mysqlDate, 5, 2) . '/' . substr($mysqlDate, 8, 2) . '/' . substr($mysqlDate, 0, 4);
		return $newDate;
	}
	
	function GetStringDateFromTime($time) {
		// Returns MM/DD/YYYY from a unix timestamp
		$date = getdate($time);
		$date = $date['mon'] . "/" . $date['mday'] . "/" . $date['year'];
		return $date;
	}
	
  function GetSQLDateTime($date) {
    // Returns string SQL date and time from date array $date
    $test = $date['year'] . '-' . str_pad($date['mon'], 2, '0', STR_PAD_LEFT) . '-' . str_pad($date['mday'], 2, '0', STR_PAD_LEFT) .
            ' ' . str_pad($date['hours'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($date['minutes'], 2, '0', STR_PAD_LEFT) . ':' .
            str_pad($date['seconds'], 2, '0', STR_PAD_LEFT);
  
    return $test;
  }

	function GetCurrentDateTime() {
	 // Returns unix timestamp for current date and time
	 $date = getdate();
	 $dateTime = mktime($date['hours'], $date['minutes'], $date['seconds'], $date['mon'], $date['mday'], $date['year']);
	 return $dateTime;
	}
	
  function GetStates() {
		// Returns an array of 2-letter state and province abreviations
		$states = array();
		array_push($states, 'AA');
		array_push($states, 'AB');
		array_push($states, 'AE');
		array_push($states, 'AK');
		array_push($states, 'AL');
		array_push($states, 'AP');
		array_push($states, 'AR');
		array_push($states, 'AS');
		array_push($states, 'AZ');
		array_push($states, 'BC');
		array_push($states, 'CA');
		array_push($states, 'CO');
		array_push($states, 'CT');
		array_push($states, 'DC');
		array_push($states, 'DE');
		array_push($states, 'FL');
		array_push($states, 'FM');
		array_push($states, 'GA');
		array_push($states, 'GU');
		array_push($states, 'HI');
		array_push($states, 'IA');
		array_push($states, 'ID');
		array_push($states, 'IL');
		array_push($states, 'IN');
		array_push($states, 'KS');
		array_push($states, 'KY');
		array_push($states, 'LA');
		array_push($states, 'MA');
		array_push($states, 'MB');
		array_push($states, 'MD');
		array_push($states, 'ME');
		array_push($states, 'MH');
		array_push($states, 'MI');
		array_push($states, 'MN');
		array_push($states, 'MO');
		array_push($states, 'MP');
		array_push($states, 'MS');
		array_push($states, 'MT');
		array_push($states, 'NB');
		array_push($states, 'NC');
		array_push($states, 'ND');
		array_push($states, 'NE');
		array_push($states, 'NH');
		array_push($states, 'NJ');
		array_push($states, 'NL');
		array_push($states, 'NM');
		array_push($states, 'NS');
		array_push($states, 'NT');
		array_push($states, 'NU');
		array_push($states, 'NV');
		array_push($states, 'NY');
		array_push($states, 'OH');
		array_push($states, 'OK');
		array_push($states, 'ON');
		array_push($states, 'OR');
		array_push($states, 'PA');
		array_push($states, 'PE');
		array_push($states, 'PR');
		array_push($states, 'QC');
		array_push($states, 'RI');
		array_push($states, 'SC');
		array_push($states, 'SD');
		array_push($states, 'SK');
		array_push($states, 'TN');
		array_push($states, 'TX');
		array_push($states, 'UT');
		array_push($states, 'VA');
		array_push($states, 'VI');
		array_push($states, 'VT');
		array_push($states, 'WA');
		array_push($states, 'WI');
		array_push($states, 'WV');
		array_push($states, 'WY');
		array_push($states, 'YT');
		
		return $states;
	}
	
	function ReplaceCRLF($text) {
		// Replaces CRLF pairs with <br/>
		$newText = str_replace("\r\n", "<br/>", $text);
		
		return $newText;
	}
	
	function ReplaceBR($text) {
		// Replace <br/> with CRLF pair
		$newText = eregi_replace('<br[[:space:]]*/?' . '[[:space:]]*>',chr(13).chr(10),$text);
		
		return $newText;
	}
	
	function RemoveNonNumeric($string) {
		// Removes all non-numeric characters from a string
		$number = "";
		for ($i=0; $i<strlen($string); $i++) {
			if (is_numeric(substr($string, $i, 1)))
				$number .= substr($string, $i, 1);
		}
		
		return $number;
	}
	
	function FormatPhoneNumber($numeric) {
		// Formats a 10-digit phone number as xxx-xxx-xxxx
		if (strlen($numeric)!=10)
			return FALSE;
			
		$phone = substr($numeric, 0, 3) . "-" . substr($numeric, 3, 3) . "-" . substr($numeric, 6);
		
		return $phone;
	}
?>