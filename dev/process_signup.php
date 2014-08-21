<?php
	session_start();
	$signupResult = array();
	if($_SERVER['REQUEST_METHOD'] != "POST"){
		header("Location: http://www.castleblackgaming.com/dev/index.php");
	}
	if(!isset($_POST['emailPost']) || !isset($_POST['userPost']) || !isset($_POST['passPost'])){
		$signupResult['error'] = "Information Error";
		echo json_encode($signupResult);
		exit();
	}
	//Check if email is valid
	if(!filter_var($_POST['emailPost'], FILTER_VALIDATE_EMAIL)){
		$signupResult['error'] = "Please enter a valid email address.";
		echo json_encode($signupResult);
		exit();
	}
	$username="cbdb";
	$password="CastleBlack111!";
	$host="localhost";
	$dbname="castleblackgaming";
	$con=mysqli_connect($host, $username, $password, $dbname);
	if(mysqli_connect_errno()){
		$signupResult['error'] = "Error!";
		echo json_encode($signupResult);
		exit();
	}
	
	//Make sure username and email are unique
	$query = "SELECT * FROM user WHERE username='" . $_POST['userPost'] . "'";
	$result = mysqli_query($con, $query);
	$row = mysqli_fetch_array($result, MYSQLI_ASSOC);
	if($row != null){
		$signupResult['error'] = "Username already exists. Please pick another username.";
		echo json_encode($signupResult);
		exit();
	}
	$query = "SELECT * FROM user WHERE email='" . $_POST['emailPost'] . "'";
	$result = mysqli_query($con, $query);
	$row = mysqli_fetch_array($result, MYSQLI_ASSOC);
	if($row != null){
		$signupResult['error'] = "There is already an account associated with this email.";
		echo json_encode($signupResult);
		exit();
	}
	
	$un = $_POST['userPost'];
	$pw = $_POST['passPost'];
	$em = $_POST['emailPost'];
	
	$salt = uniqid(mt_rand(), true) . sha1(uniqid(mt_rand(), true));
	$salt = hash('sha256', $salt);
	$hash = $salt . $pw;
	$hash = hash('sha256', $hash);	
	$pw = $salt . $hash;
	$query = "INSERT INTO `user`(`username`, `email`, `password`, `salt`) VALUES ('$un', '$em', '$pw', '$salt')";
	mysqli_query($con, $query);
	$query = "SELECT id FROM user WHERE username='" . $un . "'";
	$result = mysqli_query($con, $query);
	$row = mysqli_fetch_array($result);
	$id = $row['id'];
	
	$verification = uniqid(mt_rand(), true) . sha1(uniqid(mt_rand(), true));
	$verification = hash('sha256', $verification);
	$query = "INSERT INTO `verify` (`uid`, `key`) VALUES ($id, '$verification')";
	mysqli_query($con, $query);
	
	$signupResult['id'] = $id;
	$signupResult['verification'] = $verification;
	$signupResult['success'] = "success";
	echo json_encode($signupResult);
	
?>