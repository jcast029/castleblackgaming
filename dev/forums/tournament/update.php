<?php
	session_start();
	if($_SERVER['REQUEST_METHOD'] != "POST"){
		header("Location: http://www.castleblackgaming.com/dev/index.php");
	}
	require("BinaryBeast.php");
	$username="cbdb";
	$password="CastleBlack111!";
	$host="localhost";
	$dbname="castleblackgaming";
	$con=mysqli_connect($host, $username, $password, $dbname);
	if(mysqli_connect_errno()){
		$aResult['error'] = "Error!";
	}
	$query = "SELECT * FROM tournament ORDER BY id DESC";
	$result = mysqli_query($con, $query);
	$row = mysqli_fetch_array($result);
	$tid = $row['tid'];
	$bb = new BinaryBeast();
	$tournament = $bb->tournament->load($tid);
	
	$teams = $tournament->teams();
	$myTeam = "";
	foreach($teams as $team){
		if($team->display_name == $_SESSION['battlenetid']){
			$myTeam = $team;
			break;
		}
	}
	if($myTeam->opponent->display_name != $_SESSION['opponent']){
		$_SESSION['opponent'] = $myTeam->opponent->display_name;
		$aResult = array();
		$aResult['change'] = $myTeam->opponent->display_name;
		echo json_encode($aResult);
		exit();
	}
	if($tournament->url != $_SESSION['tournament_url']){
		$_SESSION['tournament_url'] = $tournament->url;
		$aResult['change'] = $myTeam->opponent->display_name;
		echo json_encode($aResult);
		exit();
	}
	if($tournament->status != $_SESSION['tournament_status']){
		$_SESSION['tournament_status'] = $tournament->status;
		$aResult['change'] = $myTeam->opponent->display_name;
		echo json_encode($aResult);
		exit();
	}
	if($tournament->teams_joined_count != $_SESSION['teams_joined_count']){
		$_SESSION['teams_joined_count'] = $tournament->teams_joined_count;
		$aResult['change'] = $myTeam->opponent->display_name;
		echo json_encode($aResult);
		exit();
	}
?>