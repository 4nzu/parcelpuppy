<?php
    require_once "init.php";
	$action = 'display_home';
	if (isset($_GET['object'])) {
		import('library.'.$_GET['object']);
		$display = new $_GET['object']();
	}   
	else {
		import('library.Display');
		// Create the display
		$display = new Display();
	}
	if (isset($_GET['action']) && !empty($_GET['action'])) {
		$action = $_GET['action'];
	}  
	$display->execute($action);
?>