<?php
	
	require_once('/masters/master.php');

	if(session_status() == PHP_SESSION_NONE)
  		session_start();
  	$username;
  	$password; 

	if(isset($_POST['username']) && isset($_POST['password'])){
		$username = $_POST['username'];
		$password = $_POST['password'];

		// check if login info is clean
		if(!ctype_alnum($username) && !ctype_alnum($password)){
			kill();
			redirect("../login.php?m=Username or password is not alphanumeric");
			die();
		}
		if(strlen($username) < 21 && strlen($username) > 5){
			kill();
			redirect("../login.php?m=Username must be between 5 and 21 characters");
			die();
		}
		if(strlen($password) < 21 && strlen($password) > 5){
			kill();
			redirect("../login.php?m=Password must be between 5 and 21 characters");
			die();
		}
		// check against DB 

		// create sesion data

	}else{
		kill();
		redirect("../login.php?m=No info sent");
		die();
	}



?>