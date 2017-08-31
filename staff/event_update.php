<?php
require_once '../includes/gallery_class.php';
require_once('../includes/event_class.php');
require_once '../includes/utilities.php';

session_start();

if (!isset($_GET['mode']) || (strlen($_GET['mode'])==0))
	header('Location: event_list.php');
	
$mode = $_GET['mode'];

if(!isset($_GET['id']) || (strlen($_GET['id'])==0))
	$id = $_SESSION['editingID'];
else 
	$id = $_GET['id'];
	
$loc = "event_list.php";
$errorFields = array();

switch ($mode) {
	case 'del':
		if(!isset($_GET['id']) || (strlen($_GET['id'])==0))
			header('Location: event_list.php');
		if (!Event::DeleteEvent($id)) 
			$loc = "event_list.php?m=err";
		break;
	case 'edit':
		if (ValidateEvent()){ 
			if (UpdateEvent($id)) {
				$loc = "event_list.php";
			}
			else {
				SaveEventToSession();
				$loc = "event_edit.php?m=err";
			}
		}
		else {
			SaveEventToSession();
			$loc = "event_edit.php?m=field";
		}
		break;
	case 'add':
		if (ValidateEvent()){ 
			if (InsertEvent($id)) {
				$loc = "event_list.php";
			}
			else {
				SaveEventToSession();
				$loc = "event_edit.php?m=err";
			}
		}
		else {
			SaveEventToSession();
			$loc = "event_edit.php?m=field";
		}
}
 header("Location: $loc");

function ValidateEvent() {
	// Validate posted values
	$valid = TRUE;
	$errorFields = array();
	
	if (strlen($_POST['title'])==0) {
		$valid = FALSE;
		array_push($errorFields, 'title');
	}
	if (strlen($_POST['location'])==0) {
		$valid = FALSE;
		array_push($errorFields, 'location');
	}
	if (strlen($_POST['description'])==0) {
		$valid = FALSE;
		array_push($errorFields, 'description');
	}
	if (strlen($_POST['departure'])==0) {
		$valid = FALSE;
		array_push($errorFields, 'departure');
	}
	if (strlen($_POST['whatToBring'])==0) {
		$valid = FALSE;
		array_push($errorFields, 'whatToBring');
	}
	if (strlen($_POST['whereToStay'])==0) {
		$valid = FALSE;
		array_push($errorFields, 'whereToStay');
	}
	if (strlen($_POST['ageRequirements'])==0) {
		$valid = FALSE;
		array_push($errorFields, 'ageRequirements');
	}
	if (strlen($_POST['healthRequirements'])==0) {
		$valid = FALSE;
		array_push($errorFields, 'healthRequirements');
	}
	if ($_POST['dos']=='0000-00-00') {
		$valid = FALSE;
		array_push($errorFields, 'startDate');
	}
	if ($_POST['doe']=='0000-00-00') {
		$valid = FALSE;
		array_push($errorFields, 'endDate');
	}
		
	$_SESSION['errorFields'] = $errorFields;
	
	return $valid;
}

function SaveEventToSession() {
	// Save all posted fields to SESSION[editingObit]
	$event = new Event();
	
	$event = GetEventFields($event);
	
	$_SESSION['editingEvent'] = $event;
}

function GetEventFields($event) {
	$event->Title = $_POST['title'];
	$event->Location = $_POST['location'];
	$event->Description = $_POST['description'];
	$event->Departure = $_POST['departure'];
	$event->WhatToBring = $_POST['whatToBring'];
	$event->WhereToStay = $_POST['whereToStay'];
	$event->StartDate = strtotime($_POST['dos']);
	$event->EndDate = strtotime($_POST['doe']);
	$event->AgeRequirements = $_POST['ageRequirements'];
	$event->HealthRequirements = $_POST['healthRequirements'];

	return $event;
}

function UpdateEvent($id) {
	// Get values from form fields and call Staff::Update
	$success = TRUE;
	
	$event = new Event($id);

	$event = GetEventFields($event);
	
	$success = $event->Update();
	
	return $success;
}

function InsertEvent() {
	// Get values from form fields and call Staff::Insert
	$success = TRUE;
	
	$event= new Event();
	
	$event = GetEventFields($event);
	
	$success = $event->Insert();
	
	return $success;
}
?>