<?php
	session_start();
	date_default_timezone_set("America/Los_Angeles");
	if($_SERVER['REQUEST_METHOD'] != "POST" || !isset($_POST['func'])){
		header("Location: http://www.castleblackgaming.com/dev/index.php");
	}
	$username="cbdb";
	$password="CastleBlack111!";
	$host="localhost";
	$dbname="castleblackgaming";
	$con=mysqli_connect($host, $username, $password, $dbname);
	if(mysqli_connect_errno()){
		$aResult['error'] = "Error!";
	}
	require('BinaryBeast.php');
	switch($_POST['func']){
		case 'create':
			$date = date("Y-m-d H:i:s");
			$title_date = date("l, M Y");
			
			$bb = new BinaryBeast();
			$tournament = $bb->tournament;
			$tournament->title = $title_date;
			$tournament->elimination = BinaryBeast::ELIMINATION_SINGLE;
			$tournament->bronze = true;
			$tournament->game_code = "HS";
			$tournament->max_teams = 32;
			if(!$tournament->save()){
				//$aResult = array();
				//$aResult['error'] = $bb->last_error;
				//echo json_encode($aResult);
				//exit();
				var_dump($bb->last_error);
				exit();
			}
			
			$id = $tournament->id;
			$query = "INSERT INTO `tournament` (`tid`, `created`) VALUES ('$id', '$date')";
			mysqli_query($con, $query);
			break;
		case 'signup':
			if(!isset($_SESSION['battlenetid'])){
				$aResult = array();
				$aResult['error'] = "signin";
				echo json_encode($aResult);
				exit();
			}
			$query = "SELECT * FROM tournament ORDER BY id DESC";
			$result = mysqli_query($con, $query);
			$row = mysqli_fetch_array($result);
			$tid = $row['tid'];
			$bb = new BinaryBeast();
			$tournament = $bb->tournament->load($tid);
			
			//Search for existing team name.
			$teams = $tournament->teams();
			foreach($teams as $team){
				if($team->display_name == $_SESSION['battlenetid']){
					$aResult = array();
					$aResult['error'] = "dup";
					echo json_encode($aResult);
					exit();
				}
			}
			
			$team = $tournament->team();
			$team->confirm();
			$team->display_name = $_SESSION['battlenetid'];
			$team->country_code = "USA";
			if(!$tournament->save()){
				var_dump($bb->last_error);
			}
			break;
		case 'start':
			$query = "SELECT * FROM tournament ORDER BY id DESC";
			$result = mysqli_query($con, $query);
			$row = mysqli_fetch_array($result);
			$tid = $row['tid'];
			$bb = new BinaryBeast();
			$tournament = $bb->tournament->load($tid);
			
			if(!$tournament->start()){
				$aResult = array();
				$aResult['error'] = "nostart";
				echo json_encode($aResult);
				exit();
			}
			break;
		case 'lost':
			//Check if user is logged in. If not, ask user to log in. 
			if(!isset($_SESSION['battlenetid'])){
				$aResult = array();
				$aResult['error'] = "signin";
				echo json_encode($aResult);
				exit();
			}
			$query = "SELECT * FROM tournament ORDER BY id DESC";
			$result = mysqli_query($con, $query);
			$row = mysqli_fetch_array($result);
			$tid = $row['tid'];
			$bb = new BinaryBeast();
			$tournament = $bb->tournament->load($tid);
			
			$teams = $tournament->teams();
			foreach($teams as $team){
				if($team->display_name == $_SESSION['battlenetid']){
					$match = $team->match;
					if(!$match->set_loser($team)){
						$aResult = array();
						$aResult['error'] = "noreport";
						echo json_encode($aResult);
						exit();
					}
					if(!$match->report()){
						$aResult = array();
						$aResult['error'] = "noreport";
						echo json_encode($aResult);
						exit();
					}
					exit();
				}
			}
				$aResult = array();
				$aResult['error'] = "noreport";
				echo json_encode($aResult);
				exit();
			
			break;
	}
?>