<?php
	session_start();
	//To be implemented. PHP user login validation. 
	//AJAX Http request comes from login_form.
	//Database is accessed and valid user information is returned. 
	$aResult = array();
	if($_SERVER['REQUEST_METHOD'] != 'POST'){
		header("Location: http://www.castleblackgaming.com/dev/index.php");
	}
	if(!isset($_POST["accountNumberPost"]) || !isset($_POST["passwordPost"])){
		$aResult['error'] = "Error!";
	}
	else{
		$username="cbdb";
		$password="CastleBlack111!";
		$host="localhost";
		$dbname="castleblackgaming";
		$con=mysqli_connect($host, $username, $password, $dbname);
		if(mysqli_connect_errno()){
			$aResult['error'] = "Error!";
		}
		$pw = $_POST['passwordPost'];
		$query = "SELECT * FROM user WHERE username='".$_POST['accountNumberPost']."'";
		$result = mysqli_query($con, $query);
		$row = mysqli_fetch_array($result,MYSQLI_ASSOC);
		if($row == null){
			$aResult['error'] = "User not found";
		}
		else{
			if($row['verified'] == 0){
				$aResult['error'] = "Not Verified";
				echo json_encode($aResult);
				exit();
			}
			$salt = $row['salt'];
			$hash = $salt . $pw;
			$pw = hash('sha256', $hash);
			$pw = $salt . $pw;
			if($pw !== $row['password']){
				$aResult['error'] = "Password is Incorrect";
				echo json_encode($aResult);
				exit();
			}
			$aResult['id'] = $row['id'];
			
			$_SESSION['id'] = $row['id'];
			
			if($_POST['remember'] == 'true'){
				$expire = time()+(60*60*24*30);
				setcookie("id", $row['id'], $expire);
			}
		}
		
	}
	echo json_encode($aResult);
?>