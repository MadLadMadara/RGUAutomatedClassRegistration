<?php

	$Salt = "hNFxr60v92N6MqBo";

	$Peper = "nC1KAd5JE3mOUdZ5";

	$root = (!empty($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . '/'; 


	date_default_timezone_set("Europe/London");

	//!!!! will need to use for DB


	// DB connection and setup
	// mysqli tut => http://codular.com/php-mysqli
	// server
	// $DB = array("url"=>"localhost", "username"=>"root", "password"=>"mMmMmM1mg", "DBName"=>"firespec");
	// local
	$DB = array("url"=>"localhost", "username"=>"userAccounts", "password"=>"IlOl7IREhiJ8cS5k", "DBName"=>"rguadministration");

	// mysqli DB object
	$db = new mysqli($DB['url'], $DB['username'], $DB['password'], $DB['DBName']);

	// error connecting to db
	if($db->connect_errno > 0){
		// 
    	die('no connect to db');

	}
	
	$db->autocommit(FALSE);

	// for ajax
	$response["success"] = "0";
	$response["message"] = "";
	$response["posts"] = [];



	// util functions

	/**
	 * [encryption encryption function for strings]
	 * @param  [string] $Str [sctring to be hashed]
	 * @return [string]      [hashed string]
	 */
	function encryption($Str){

		return hash('sha256', md5($GLOBALS['Salt'].$Str.$GLOBALS['Peper']));

	}

	/**
	 * [kill 
	 * removes all session 
	 * data and closes the 
	 * database
	 * ]
	 * @return [null] [description]
	 */
	function kill(){ 
		if(session_status() == PHP_SESSION_NONE)
	      	session_start(); // NEED TO START BEFORE YOU CAN KILL
		session_unset(); 
		session_destroy();
		session_write_close();
		session_regenerate_id(true);
		$GLOBALS['db']->close();
	}



	/**
	 * [RSG returns a random string of given length]
	 * @param [int] $l [length of string]
	 */
	function RSG($l) {
   		$chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    	$charLength = strlen($chars);
    	$s = '';
   		for ($i = 0; $i < $l; $i++) {
        	$s .= $chars[rand(0, $charLength - 1)];
    	}
    	return $s;
	}


	/**
	 * [redirect redirects user]
	 * @param  [string] $url [destination url]
	 * @return [void]      [description]
	 */
	function redirect($url) {
		// could be better and more flexible
    	ob_start();
    	header('Location: '.$url);
    	ob_end_flush();
    	die();
	}

	/**
	 * [tokenCheck
	 * checks the last active, token session and token post variables.
	 * resets last active varibal after it has been used.
	 * ]
	 * @param  [string] $tokenName [
	 * name of both $_SESSION[*] and $_POST[*]
	 * note both must be the same name eg
	 * $_SESSION["token"] and $_POST["token"]
	 * ]
	 * @param  [int] $mins [
	 *   number of mins from last active before
	 *   token can not be used
	 * ]
	 * @return [bool] [will return true if token hasnt timed out 
	 *                and the seesion token matches the post token 
	 * }
	 */
	function tokenCheck($tokenName, $mins){

		if(session_status() == PHP_SESSION_NONE)
    		session_start();

		if (isset($_SESSION['LAST_ACTIVITY'])) {
	    	if(time() - $_SESSION['LAST_ACTIVITY'] > ($mins * 60)){
	    	
	    		return false;
	  		}

		}else{
			return false;
		}

		$_SESSION['LAST_ACTIVITY'] = time(); // reset last active after use

		// token match check
		if(isset($_SESSION[$tokenName]) && isset($_POST[$tokenName])) {
			if($_POST[$tokenName] != $_SESSION[$tokenName]){
	    		
				return false; // for testing
			}
		}else{
	    		
			return false;
		}
		return true;
	}

	
?> 