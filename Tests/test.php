<?php 
	session_start();
	ini_set('display_errors',TRUE);
	error_reporting(E_ALL);
	require('bootstrap.php');
	
	if(!array_key_exists('logged_in',$_SESSION) || $_SESSION['logged_in']===FALSE){
		$username='sharda.suresh@indyzen.com';
		$password='January15!';
		$result=$trClient->login($username, $password);
		echo "<pre>";
		print_r($result);
		echo "</pre>";
		if($result===FALSE){
			echo "Invalid Username as Password";
		}else{
			$_SESSION['logged_in']=TRUE;
			$_SESSION['user_info']=$result;
		}
	}else{
		echo "<pre>";
		print_r($_SESSION['user_info']);
		echo "</pre>";
		$result = $trClient->call(FALSE,'/user/'.$_SESSION['user_info']->Id);
		echo "<pre>";
		print_r($result);
		echo "</pre>";
	}