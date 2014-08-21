<?php
	if(isset($_GET['v'])){
		$username="cbdb";
		$password="CastleBlack111!";
		$host="localhost";
		$dbname="castleblackgaming";
		$con=mysqli_connect($host, $username, $password, $dbname);
		if(mysqli_connect_errno()){
			echo "ERROR <br />";
		}
		$vget = $_GET['v'];
		$iget = $_GET['id'];
		$query = "SELECT * FROM verify WHERE uid=" . $iget;
		$result = mysqli_query($con, $query);
		$row = mysqli_fetch_array($result, MYSQLI_ASSOC);
		$vdb = $row['key'];
		if($vget != $vdb){
			echo "<script type='text/javascript'> alert('There was a problem with verification.'); window.location.href = 'http://www.castleblackgaming.com/dev/index.php'; </script>";
		}
		$query = "UPDATE user SET verified=1 WHERE id=" . $iget;
		mysqli_query($con, $query);
		echo "<script type='text/javascript'> alert('Thank you! You may log in now.'); window.location.href = 'http://www.castleblackgaming.com/dev/index.php'; </script>";
	}
	if($_SERVER['REQUEST_METHOD'] != "POST"){
		header("Location: http://www.castleblackgaming.com/dev/index.php");
	}
	$email = $_POST['email'];
	$username = $_POST['username'];
	$verification = $_POST['verification'];
	$id = $_POST['id'];
	
	$username="cbdb";
	$password="CastleBlack111!";
	$host="localhost";
	$dbname="castleblackgaming";
	$con=mysqli_connect($host, $username, $password, $dbname);
	if(mysqli_connect_errno()){
		echo "ERROR <br />";
	}
	$query = "SELECT * FROM verify WHERE uid=" . $id;
	$result = mysqli_query($con, $query);
	$row = mysqli_fetch_array($result, MYSQLI_ASSOC);
	$verificationDB = $row['key'];
	if($verification !== $verificationDB){
		echo "Verification ERROR. <br />";
	}

	//TODO: Implement random hash generator to validate post request coming from internal server. 
	$subject = "Account Email Verification - Action Required";
	$message = "<html>
	<head>
	<title>Email Verification</title>
	</head>
	<body>
	<h3>Welcome</h3>
	<p>	Thank you for registering with Castle Black </p>
	<p>
		Please verify your email by clicking <a href='http://www.castleblackgaming.com/dev/validate_email.php?v=".$verification."&id=".$id."'>HERE</a>
		
	</p>
	</body>
	</html>";
	$headers = 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
	$headers .= 'From: Castle Black Gaming <do-not-reply@castleblackgaming.com>' . "\r\n";
	mail($email, $subject, $message, $headers);
	
?>

<!DOCTYPE html>
<html>
<head>
<link href="core.css" rel="stylesheet" type="text/css">
</head>

<body>
<script type="text/javascript">
	function selectText(textField) 
	  {
	    textField.focus();
	    textField.select();
	  }
</script>

<div id="header">
  <div id="header_content-container">
    <div id="header-logo"><a href="http://www.castleblackgaming.com/dev/index.php"><img src="images/logo.png" width="184" height="91" /></a></div>
  </div>
</div>
<div id="content-container">
  <div class="empty-container">
    <div>THANK YOU FOR REGISTERING</div>
  </div>
  <div class="content-box accent"><br>
  	You will receive an email to verify your email address. Please click the link included in the email and you will be able to log in. You may close this tab. 
    <br>
  </div>
</div>

</body>
</html>