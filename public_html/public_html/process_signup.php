<?php
	define('IN_PHPBB');
	session_start();
	$signupResult = array();
	if($_SERVER['REQUEST_METHOD'] != "POST"){
		header("Location: http://www.castleblackgaming.com/index.php");
	}
	if(!isset($_POST['emailPost']) || !isset($_POST['userPost']) || !isset($_POST['passPost']) || !isset($_POST['battlenetidPost'])){
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
	$hashtag = strpos($_POST['battlenetidPost'], "#");
	$valid = true;
	$len = 0;
	$sub = "";
	if($hashtag == false){
		$valid = false;
	}
	else{
		$sub = substr($_POST['battlenetidPost'], $hashtag + 1);
		$len = strlen($sub);
		if($len < 3 || $len > 5){
			$valid = false;
		} 
	}
	if($valid == false){
		$signupResult['error'] = "Please enter a valid Batle.net Battletag";
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
	$query = "SELECT * FROM user WHERE battlenetid='" . $_POST['battlenetidPost']. "'";
	$result = mysqli_query($con, $query);
	$row = mysqli_fetch_array($result, MYSQLI_ASSOC);
	if($row != null){
		$signupResult['error'] = "There is already an account associated with this Battle.Net ID.";
		echo json_encode($signupResult);
		exit();
	}
	
	$un = $_POST['userPost'];
	$pw = $_POST['passPost'];
	$em = $_POST['emailPost'];
	$bnid = $_POST['battlenetidPost'];
	
	$salt = uniqid(mt_rand(), true) . sha1(uniqid(mt_rand(), true));
	$salt = hash('sha256', $salt);
	$hash = $salt . $pw;
	$hash = hash('sha256', $hash);	
	$pw = $salt . $hash;
	$query = "INSERT INTO `user`(`username`, `battlenetid`, `email`, `password`, `salt`) VALUES ('$un', '$bnid', '$em', '$pw', '$salt')";
	mysqli_query($con, $query);
	$query = "SELECT id FROM user WHERE username='" . $un . "'";
	$result = mysqli_query($con, $query);
	$row = mysqli_fetch_array($result);
	$id = $row['id'];
	
	$verification = uniqid(mt_rand(), true) . sha1(uniqid(mt_rand(), true));
	$verification = hash('sha256', $verification);
	$query = "INSERT INTO `verify` (`uid`, `key`) VALUES ($id, '$verification')";
	mysqli_query($con, $query);
	
	define('IN_PHPBB', true);
	global $phpbb_root_path, $phpEx, $user, $db, $config, $cache, $template;
	$phpbb_root_path = (defined('PHPBB_ROOT_PATH')) ? PHPBB_ROOT_PATH : './forums/';
	$phpEx = substr(strrchr(__FILE__, '.'), 1);
	include($phpbb_root_path . 'common.' . $phpEx);
	include($phpbb_root_path . 'includes/functions_user.' . $phpEx);
	include($phpbb_root_path . 'includes/ucp/ucp_register.php');
	
	// Start session management
	$user->session_begin();
	$auth->acl($user->data);
	$user->setup();
	
	// default is 4 for registered users, or 5 for coppa users.
	$group_id = ($coppa) ? 5 : 4;
	// since group IDs may change, you may want to use a query to make sure you are grabbing the right default group...
	$group_name = ($coppa) ? 'REGISTERED_COPPA' : 'REGISTERED';
	$sql = 'SELECT group_id
	        FROM ' . GROUPS_TABLE . "
	        WHERE group_name = '" . $db->sql_escape($group_name) . "'
	            AND group_type = " . GROUP_SPECIAL;
	$result = $db->sql_query($sql);
	$row = $db->sql_fetchrow($result);
	$group_id = $row['group_id'];
	
	// timezone of the user... Based on GMT in the format of '-6', '-4', 3, 9 etc...
	$timezone = '0';
	
	// two digit default language for this use of a language pack that is installed on the board.
	$language = 'en';
	
	// user type, this is USER_INACTIVE, or USER_NORMAL depending on if the user needs to activate himself, or does not.
	// on registration, if the user must click the activation link in their email to activate their account, their account
	// is set to USER_INACTIVE until they are activated. If they are activated instantly, they would be USER_NORMAL
	$user_type = USER_NORMAL;
	
	// here if the user is inactive and needs to activate thier account through an activation link sent in an email
	// we need to set the activation key for the user... (the goal is to get it about 10 chars of randomization)
	// you can use any randomization method you want, for this example, I’ll use the following...
	//$user_actkey = md5(rand(0, 100) . time());
	//$user_actkey = substr($user_actkey, 0, rand(8, 12));
	
	// IP address of the user stored in the Database.
	$user_ip = $user->ip;
	
	// registration time of the user, timestamp format.
	$registration_time = time();
	
	// inactive reason is the string given in the inactive users list in the ACP.
	// there are four options: INACTIVE_REGISTER, INACTIVE_PROFILE, INACTIVE_MANUAL and INACTIVE_REMIND
	// you do not need this if the user is not going to be inactive
	// more can be read on this in the inactive users section
	//$user_inactive_reason = INACTIVE_REGISTER;
	
	// time since the user is inactive. timestamp.
	$user_inactive_time = time();
	
	// these are just examples and some sample (common) data when creating a new user.
	// you can include any information 
	$user_row = array(
	    'username'              => $un,
	    'user_password'         => md5($pw),
	    'user_pass_convert'     => 0,
	    'user_email'            => $em,
	    'group_id'              => (int) $group_id,
	    'user_timezone'         => (float) $timezone,
	    'user_dst'              => $is_dst,
	    'user_lang'             => $language,
	    'user_type'             => $user_type,
	    'user_ip'               => $user_ip,
	    'user_regdate'          => $registration_time,
	    'user_inactive_time'    => $user_inactive_time,
	);
	
	// Custom Profile fields, this will be covered in another article.
	// for now this is just a stub
	// all the information has been compiled, add the user
	// the user_add() function will automatically add the user to the correct groups
	// and adding the appropriate database entries for this user...
	// tables affected: users table, profile_fields_data table, groups table, and config table.
	$user_id = user_add($user_row);
	
	$signupResult['id'] = $id;
	$signupResult['verification'] = $verification;
	$signupResult['success'] = "success";
	echo json_encode($signupResult);
	
?>