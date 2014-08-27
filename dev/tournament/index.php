<?php
	session_start();
	//Check cookies for successful login. 
	//If user is remembered with cookies, register session. 
	$username="cbdb";
	$password="CastleBlack111!";
	$host="localhost";
	$dbname="castleblackgaming";
	$con=mysqli_connect($host, $username, $password, $dbname);
	if(mysqli_connect_errno()){
		$aResult['error'] = "Error!";
	}
		
	if(isset($_COOKIE['id']) || isset($_SESSION['id'])){
		
		$id = (isset($_COOKIE['id'])) ? $_COOKIE['id'] : $_SESSION['id'];
		
		$query = "SELECT * FROM user WHERE id=" . $id;
		$result = mysqli_query($con, $query);
		$row = mysqli_fetch_array($result,MYSQLI_ASSOC);
		$_SESSION['username'] = $row['username'];
		$_SESSION['pro'] = $row['pro'];
		$_SESSION['battlenetid'] = $row['battlenetid'];
		$query = "SELECT * FROM admin WHERE username='" . $row['username'] . "'";
		$result = mysqli_query($con, $query);
		$row = mysqli_fetch_array($result, MYSQLI_ASSOC);
		if(count($row) == 1){
			$_SESSION['admin'] = 1;
		}
	}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Castle Black Gaming - Tournament</title>
<link rel="shortcut icon" type="image/ico" href="../images/favicon.ico"/>
<link href="../core.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="//code.jquery.com/jquery.js"></script>
<script src="/dev/js/jquery.countdown.js"></script>
</head>
<body>

<script type="text/javascript">
	function updateTournament(){
		$.ajax({
			type: "POST",
			url: "http://www.castleblackgaming.com/dev/tournament/update.php",
			dataType: "json",
			data: {func: "update"},
			success: function(obj, textstatus) {
				if('error' in obj){
					
				}
				else {
					if('change' in obj){
						document.getElementById("opponent-text").innerHTML = obj['change'];
						document.getElementById("binarybeast").src = document.getElementById("binarybeast").src;
					}
				}
			}
		});
	} 
	function selectText(textField){
		textField.focus();
		textField.select();
	}
	function tourney(selection){
		$.ajax({
			type: "POST",
			url: "http://www.castleblackgaming.com/dev/tournament/tourney.php",
			async: false,
			dataType: "json",
			data: {func: selection},
			success: function(obj, textstatus){
				if('error' in obj){
					switch(obj['error']){
						case 'signin':
							alert("Please sign into your account first.");
							break;
						case 'dup':
							alert("You have already signed up.");
							break;
						case 'nostart':
							alert("Tournament can't start now!");
							break;
						case 'noreport':
							alert("No match to report!");
							break;
					}
					alert(obj['error']);
				}
			}
		});
		window.location.href = window.location.href;
	}
</script>

<?php
	if(isset($_SESSION['username'])){
		echo "<script> window.setInterval(function(){updateTournament()}, 10000);</script>";
	}
?>

<div id="header">
  <div id="header_content-container">
    <div id="header-logo"><a href="http://www.castleblackgaming.com/dev/index.php"><img src="../images/logo.png" width="289" height="49" /></a></div>
  </div>
</div>
<nav>
  <ul>
    <li><a href="#">Decks</a>
      <ul>
        <li><a href="#">All Decks</a></li>
        <li><a href="#">Druid</a></li>
        <li><a href="#">Hunter</a></li>
        <li><a href="#">Mage</a></li>
        <li><a href="#">Paladin</a></li>
        <li><a href="#">Priest</a></li>
        <li><a href="#">Rogue</a></li>
        <li><a href="#">Shaman</a></li>
        <li><a href="#">Warlock</a></li>
        <li><a href="#">Warrior</a></li>
      </ul>
    </li>
    <li><a href="http://www.castleblackgaming.com/dev/tournament">Tournaments</a> 
      <!-- First Tier Drop Down -->
      <ul>
        <li><a href="#">How To Join</a></li>
        <li><a href="#">Schedule</a></li>
        <li><a href="#">Past Results</a></li>
        <li><a href="#">Rules</a></li>
      </ul>
    </li>
    <li><a href="#">Forums</a></li>
    <li><a href="#">Streams</a></li>
    <li><a href="#">Guides</a>
      <ul>
        <li><a href="#">Nuba&#39;s Corner</a></li>
      </ul>
    </li>
    <li><a href="#">FAQs</a></li>
    <li><a href="#">About</a></li>
    <?php if(!isset($_SESSION['username'])){ ?>
	    <li class="logreg"><a href="#">Sign Up</a>
	      <ul class="register">
	        <li>
	          <div id="loginBox">
	            <?php include '../sign-up.php'; ?>
	          </div>
	        </li>
	      </ul>
	    </li>
	    <li class="logreg"><a href="#">Login</a>
	      <ul class="login">
	        <li>
	          <div id="loginBox">
	            <?php include '../login_form.php'; ?>
	          </div>
	        </li>
	      </ul>
	    </li>
    <?php }
    	  else{?>
    	    <li class="logreg"><a href="#"><?php echo $_SESSION['username'];?></a>
    	    	<ul class="account">
    	    		<li>
    	    			<div id="loginBox">
    	    			<a href="http://www.castleblackgaming.com/dev/logout.php">Logout</a>
    	    			</div>
    	    		</li>
    	    	</ul>
    	    </li>
    	<?php } ?>
  </ul>
</nav>
<div id="content-container">
  <div class="empty-container">
    <div>Tournaments</div>
  </div>
  <div class="content-box-tournament accent"><br>
  	<?php
	  		require('BinaryBeast.php');
	  		$query = "SELECT * FROM tournament ORDER BY id DESC";
			$result = mysqli_query($con, $query);
			$row = mysqli_fetch_array($result);
			$tid = $row['tid'];
			$bb = new BinaryBeast();
			$tournament = $bb->tournament->load($tid);
			
			$_SESSION['tournament_url'] = $tournament->url;
			$_SESSION['tournament_status'] = $tournament->status;
			$_SESSION['teams_joined_count'] = $tournament->teams_joined_count;
  		
  	?>
  	
  	<div id="opponent">
  		YOUR OPPONENT<br />
  		<span id="opponent-text"></span>
  	</div>
  	<button onclick="tourney('lost')" class="tournament">I Lost</button><br />
  	<?php
  	if($_SESSION['admin'] == 1){ ?>
  		<button onclick="tourney('create')" class="tournament" style="float: left">Create Tournament</button>
  	<?php } ?>
  	<button onclick="tourney('signup')" class="tournament" style="float: right">Sign In</button><br />
  	<iframe src="tournament.php"  class="tournament" id="binarybeast" width="980" height="1080" scrolling="auto" frameborder="0">
    </iframe><br />
    <?php
    if($_SESSION['admin'] == 1) { ?>
    <button onclick="tourney('start')" class="tournament">Start Tournament</button>
    <?php } ?>
    
    <br>
  </div>
</div>
</body>
</html>