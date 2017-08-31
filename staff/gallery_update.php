<?php
require_once '../includes/event_class.php';
require_once '../includes/gallery_class.php';
require_once '../includes/utilities.php';

session_start();

if (!isset($_GET['mode']) || (strlen($_GET['mode'])==0))
	header('Location: gallery_list.php');
	
$mode = $_GET['mode'];

if(!isset($_GET['id']) || (strlen($_GET['id'])==0))
	$id = $_SESSION['editingID'];
else 
	$id = $_GET['id'];
	
$loc = "gallery_list.php";
$errorFields = array();

switch ($mode) {
	case 'del':
		if(!isset($_GET['id']) || (strlen($_GET['id'])==0))
			header('Location: gallery_list.php');
		if (!Gallery::DeleteGallery($id)) 
			$loc = "gallery_list.php?m=err";
		break;
	case 'edit':
		if (ValidateGallery()){ 
			if (UpdateGallery($id)) {
				$loc = "gallery_list.php";
			}
			else {
				SaveGalleryToSession();
				$loc = "gallery_edit.php?m=err";
			}
		}
		else {
			SaveGalleryToSession();
			$loc = "gallery_edit.php?m=field";
		}
		break;
	case 'add':
		if (ValidateGallery()){ 
			if (InsertGallery($id)) {
				$loc = "gallery_list.php";
			}
			else {
				SaveGalleryToSession();
				$loc = "gallery_edit.php?m=err";
			}
		}
		else {
			SaveGalleryToSession();
			$loc = "gallery_edit.php?m=field";
		}
}
 header("Location: $loc");

function ValidateGallery() {
	// Validate posted values
	$valid = TRUE;
	$errorFields = array();
	
	if (strlen($_POST['name'])==0) {
		$valid = FALSE;
		array_push($errorFields, 'name');
	}
	if ($_POST['eventDropDown']==-1) {
		$valid = FALSE;
		array_push($errorFields, 'event');
	}
	if ($_POST['dirDropDown']==-1) {
		$valid = FALSE;
		array_push($errorFields, 'directory');
	}
		
	$_SESSION['errorFields'] = $errorFields;
	
	return $valid;
}

function SaveGalleryToSession() {
	// Save all posted fields to SESSION[editingObit]
	$gallery = new Gallery();
	
	$gallery = GetGalleryFields($gallery);
	
	$_SESSION['editingGallery'] = $gallery;
}

function GetGalleryFields($gallery) {
	$gallery->Name = $_POST['name'];
	$gallery->EventID = $_POST['eventDropDown'];
	$gallery->Directory = $_POST['dirDropDown'];

	return $gallery;
}

function UpdateGallery($id) {
	// Get values from form fields and call Staff::Update
	$success = TRUE;
	
	$gallery = new Gallery($id);

	$gallery = GetGalleryFields($gallery);
	
	$success = $gallery->Update();
	
	return $success;
}

function InsertGallery() {
	// Get values from form fields and call Staff::Insert
	$success = TRUE;
	
	$gallery= new Gallery();
	
	$gallery = GetGalleryFields($gallery);
	
	$success = $gallery->Insert();
	
	return $success;
}
?>