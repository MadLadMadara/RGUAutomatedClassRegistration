<?php
	
	require_once('/masters/master.php');

	if(session_status() == PHP_SESSION_NONE)
  		session_start();

  	$username;
  	$password; 

	if(isset($_POST['username']) && isset($_POST['password'])){


		$username = strip_tags($_POST['username']);
		$password = encryption(strip_tags($_POST['password']));
		
		

		// check if login info is clean
		if(!ctype_alnum($username) && !ctype_alnum($password)){
			kill();
			redirect("../login.php?m=Username or password is not alphanumeric");
			die();
		}
		if(strlen($username) > 20 && strlen($username) < 6){
			kill();
			redirect("../login.php?m=Username must be between 5 and 21 characters");
			die();
		}
		if(strlen($password) > 20 && strlen($password) < 6){
			kill();
			redirect("../login.php?m=Password must be between 5 and 21 characters");
			die();
		}
		// check against DB
		$userLoggedIn = false;
		$checkUser = $db->prepare("SELECT * FROM user WHERE username = ? AND password = ? LIMIT 1");
		$checkUser->bind_param('ss', $username, $password);
		$checkUser->execute();
		$checkUser->bind_result($user_id, $username_db, $password_db, $emal, $admin);
		while ($checkUser->fetch()) {
			$userLoggedIn = true;
			$_SESSION["user"]['id'] = $user_id;
			$_SESSION["user"]['name'] = $username_db;
			$_SESSION["user"]['password'] = $password_db;
			$_SESSION["user"]['emal'] = $emal;
			$_SESSION["user"]['admin'] = $admin;
		}
		$checkUser->close();
		if($userLoggedIn){
			
			$_SESSION['current_session']['start_time'] = date("Y-m-d H:i:s", time());
			redirect("../search.php");
			die();
		}
		kill();
		redirect("../login.php?m=Username or password is incorect.");
		die();

	}else{
		kill();
		redirect("../login.php?m=No info sent");
		die();
	}



?>