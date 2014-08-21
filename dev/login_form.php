<?php
	session_start();
?>

<form id="loginForm" action="index.php" method="POST" name="login" onsubmit="return validateForm()">
  <fieldset id="body">
    <fieldset>
      <label for="email">Email/Username</label><span id="usernameErrorMessage" style="float:left; color:red"></span>
      <input name="user" type="text"  id="userName" class="txtfield" onClick='selectText(this);'/>
    </fieldset>
    <fieldset>
      <label for="password">Password</label><span id="passwordErrorMessage" style="float:left; color:red"></span>
      <input type="password" name="pw" id="passwordIn" class="txtfield" onClick='selectText(this);'/>
    </fieldset>
    <label for="checkbox">
      <span id="remember-fontfix"><input type="checkbox" id="checkbox" />
      Remember me</span></label>
  </fieldset>
  <input type="submit" name="submit" value="Log In" id="submit">
</form>


<script type="text/javascript">

	function setCookie(cname, cvalue, exdays){
		var d = new Date();
		d.setTime(d.getTime() + (exdays*24*60*60*1000));
		var expires = "expires="+d.toGMTString();
		document.cookie = cname + "=" + cvalue + "; " + expires + "; path=/";
	}

	function validateForm(){
		var un = document.getElementById('userName').value;
		var pw = document.getElementById('passwordIn').value;
		if(un == null || un == ""){
			document.getElementById("usernameErrorMessage").innerHTML = "Please enter your Username";
			return false;
		}
		if(pw == null || pw == ""){
			document.getElementById("passwordErrorMessage").innerHTML = "Please enter your Password";
			return false;
		}
		
		var success = false;
		
		/*Process the login with php script. Open database and check for user, returning necessary errors or login information.*/
		$.ajax({
			type: "POST",
			url: 'http://www.castleblackgaming.com/dev/process_login.php',
			async: false,
			dataType: 'json',
			data: {accountNumberPost: un, passwordPost: pw, remember: $('#checkbox').is(':checked')},
			
			success: function(obj, textstatus){
				if(!('error' in obj)){
					//document.getElementById("usernameErrorMessage").innerHTML = $('#checkbox').is(':checked');
					success = true;
				}
				else{
					document.getElementById("usernameErrorMessage").innerHTML = obj['error'];
				}
			}
		});
		
		return success;
	}
</script>