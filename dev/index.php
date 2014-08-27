<?php
	session_start();
	//Check cookies for successful login. 
	//If user is remembered with cookies, register session. 
	if(isset($_COOKIE['id']) || isset($_SESSION['id'])){
		$username="cbdb";
		$password="CastleBlack111!";
		$host="localhost";
		$dbname="castleblackgaming";
		$con=mysqli_connect($host, $username, $password, $dbname);
		if(mysqli_connect_errno()){
			$aResult['error'] = "Error!";
		}
		
		$id = (isset($_COOKIE['id'])) ? $_COOKIE['id'] : $_SESSION['id'];
		
		$query = "SELECT * FROM user WHERE id=" . $id;
		$result = mysqli_query($con, $query);
		$row = mysqli_fetch_array($result,MYSQLI_ASSOC);
		$_SESSION['username'] = $row['username'];
		$_SESSION['pro'] = $row['pro'];
		$_SESSION['battlenetid'] = $row['battlenetid'];
	}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Castle Black Gaming</title>
<link rel="shortcut icon" type="image/ico" href="images/favicon.ico"/>
<link href="core.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="//code.jquery.com/jquery.js"></script>
<script src="/dev/js/jquery.countdown.js"></script>
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
    <div id="header-logo"><a href="http://www.castleblackgaming.com/dev/index.php"><img src="images/logo.png" width="289" height="49" /></a></div>
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
	            <?php include 'sign-up.php'; ?>
	          </div>
	        </li>
	      </ul>
	    </li>
	    <li class="logreg"><a href="#">Login</a>
	      <ul class="login">
	        <li>
	          <div id="loginBox">
	            <?php include 'login_form.php'; ?>
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
    	    			<a href="account_settings.php">Account Settings</a>
    	    			<a href="logout.php">Logout</a>
    	    			</div>
    	    		</li>
    	    	</ul>
    	    </li>
    	<?php } ?>
  </ul>
</nav>
<div id="content-container">
  <div class="empty-container">
    <div>TOURNAMENT HEADQUARTERS</div>
    <img src="images/hearthstone_logo.png" style="float:right" width="188" height="54" alt="Hearthstone" /></div>
  <div class="accent" id="heroA_banner">
    <div id="heroA-banner-subleft">NEXT TOURNAMENT</div>
    <div id="heroA-banner-subright">
      <div id="clock"></div>
      <script type="text/javascript">
var nextYear = new Date(new Date().getFullYear() + 1, 0, 0, 0, 0, 0, 0);
$('#clock').countdown('2014/10/10', function(event) {
  var $this = $(this).html(event.strftime(''
    + '<table>'
    + '<tr>'
    + '<td><span>%D</span>&nbsp<span>:</span></td>'
    + '<td><span>%H</span>&nbsp<span>:</span></td>'
    + '<td><span>%M</span>&nbsp&nbsp<span>:</span></td>'
    + '<td><span>%S</span>&nbsp<span>&nbsp</span></td>'
    + '</tr>'
    + '<tr>'
    + '<td><div class="clockLabel" id="daysLabel">DAYS</div></td>'
    + '<td><div class="clockLabel">HOURS</div></td>'
    + '<td><div class="clockLabel">MINUTES</div></td>'
    + '<td><div class="clockLabel" id="secondsLabel">SECONDS</div></td>'
    + '</tr>'
    + '</table>'));
});
</script> 
    </div>
  </div>
  <div class="content-box accent" id="garosh">
    <h1>$250 CASH PRIZE</h1>
    <h2>Weekly Freeroll<br>
    Tournaments!</h2>
    <p>Earn your way into our
    month <br>
    end $250 cash
    prize tournament<br>
    by
    participating in our
    weekly<br>
    freeroll tournaments.</p>
    <p>&nbsp;</p>
  </div>
</div>
</body>
</html>