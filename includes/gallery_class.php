<?php
// Gallery class
require_once 'includes/dbmanager.php';

class Gallery {
	public $ID;
	public $EventID;
	public $Name;
	public $Directory;
	
	public function __construct($id = null) {
		global $connDB, $database_connDB;
		mysql_select_db($database_connDB, $connDB);
		
		if ($id == null) {
			$this->ID = -1;
			$this->EventID = -1;
			$this->Name = "";
			$this->Directory = "";
		}
		else {
			$query = "SELECT * FROM galleries WHERE gallery_id=$id";
			$result = @mysql_query($query, $connDB);
			
			$row = @mysql_fetch_assoc($result);
			$this->ID = $id;
			$this->EventID = $row['event_id'];
			$this->Name = $row['name'];
			$this->Directory = $row['directory'];
			
			mysql_free_result($result);
		}
	}
	
	public function Insert() {
		// Add new Gallery
		global $connDB, $database_connDB;
		mysql_select_db($database_connDB, $connDB);
		
		$query = "INSERT INTO galleries (event_id, name, directory) " .
				"VALUES ($this->EventID, '$this->Name', '$this->Directory')";
		if(!mysql_query($query, $connDB)) {
			return FALSE;
		}
		
		$this->ID = mysql_insert_id();
		
		return TRUE;
	}
	
	public function Update() {
		// Update this Gallery
		global $connDB, $database_connDB;
		mysql_select_db($database_connDB, $connDB);
		
		$query = "UPDATE galleries SET " .
				"event_id=$this->EventID, name='$this->Name', directory='$this->Directory' " .
				"WHERE gallery_id=$this->ID";
		
		if(!mysql_query($query, $connDB)) 
			return FALSE;
		else
			return TRUE;
	}
	
	public static function DeleteGallery($id) {
		// Delete Gallery
		global $connDB, $database_connDB;
		mysql_select_db($database_connDB, $connDB);
		
		$query = "DELETE FROM galleries WHERE gallery_id=$id";
		
		if(!mysql_query($query, $connDB)) {
			return FALSE;
		}
		
		return TRUE;
	}
	
	public static function GetAllGalleries() {
		// Return an array of all Galleries
		global $connDB, $database_connDB;
		mysql_select_db($database_connDB, $connDB);
		
		$query = "SELECT gallery_id FROM galleries ORDER BY name";
		$result = mysql_query($query, $connDB);
		
		$allGalleries = array();
		while($row = @mysql_fetch_assoc($result)) {
			$gallery = new Gallery($row['gallery_id']);
			array_push($allGalleries, $gallery);
		}
		
		@mysql_free_result($result);
		
		return $allGalleries;
		
	}
	
	public static function GetGalleryID($galleryDir) {
		// Return the ID of Gallery with galleryDir
		global $connDB, $database_connDB;
		mysql_select_db($database_connDB, $connDB);
		
		$query = "SELECT gallery_id FROM galleries WHERE directory='$galleryDir'";
		$result = mysql_query($query, $connDB);
		
		// Should only be one
		while($row = mysql_fetch_assoc($result)) {
			$galleryID = $row['gallery_id'];
		}
		
		mysql_free_result($result);
		
		return $galleryID;
	}
	
	public static function GetGalleryDirList() {
		// Returns an array of photo gallery folders from the global $galleriesdir specified by koschtit
		global $galleriesdir;
		$gallerylist = array();
		
		// Default sort order is ascending, which is fine
		$filelist = scandir($galleriesdir);
		for ($i=0; $i<count($filelist); $i++) {
			$file = $filelist[$i];
			if(($file != ".") && ($file != "..") && (substr($file, 0, 1) != ".")) {
				$file = $galleriesdir . '/' . $filelist[$i];
				if(is_dir($file)) {
					// Only saving names of directories
					array_push($gallerylist, $filelist[$i]);
				}
			}
		}
		
		return $gallerylist;
	}
	
	public static function GetGalleryIDIndex($id) {
		// Builds Gallery directory list and returns the index of $id
		$gallerylist = Gallery::GetGalleryDirList();
		
		$index = -1;
		
		for ($i=0; $i<count($gallerylist); $i++) {
			$galleryName = $gallerylist[$i];
			$galleryID = Gallery::GetGalleryID($galleryName);
			if ($galleryID == $id) {
				$index = $i;
				break;
			}
		}
		
		return $index;
	}
	
	public static function GetEventGallery($eventID) {
		// Return Gallery for given Event ID
		global $connDB, $database_connDB;
		mysql_select_db($database_connDB, $connDB);
		
		$query = "SELECT * FROM galleries WHERE event_id=$eventID";
		$result = mysql_query($query, $connDB);
		$gallery = new Gallery();
		
		if ($row = mysql_fetch_assoc($result)) {
			$gallery->ID = $row['gallery_id'];
			$gallery->EventID = $eventID;
			$gallery->Name = $row['name'];
			$gallery->Directory = $row['directory'];
			
			mysql_free_result($result);
		}
		
		return $gallery;
	}
}
?>