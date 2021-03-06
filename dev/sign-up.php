<form id="loginForm" action="validate_email.php" method="post" name="login" onsubmit="return validateSignUp()" autocomplete="off">
  <fieldset id="body">
    <fieldset>
      <label for="email">Email</label><span id="requiredFieldError" style="float:left; color:red"></span>
      <input name="email" type="text"  id="emailVal" class="txtfield" onClick='selectText(this);' value="" placeholder= 'example@example.com'/>
    </fieldset>
    <fieldset>
      <label for="username">Username</label>
      <input name="username" type="text" id="usernameVal" class="txtfield" onClick='selectText(this);' value="" placeholder='Enter desired username'/>
    </fieldset>
    <!--TODO: Grab data and put in database. -->
        <fieldset>
      <label for="battlenetid">Battle.net ID</label>
      <!-- TODO: Add text hint for format -->
      <input name="battlenetid" type="text" id="battlenetidVal" class="txtfield" onClick='selectText(this);' value="" placeholder='example#1234'/>
    </fieldset>
    <fieldset>
      <label for="password">Password</label>
      <input type="password" name="password" id="passwordVal" class="txtfield" value="" onClick='selectText(this);' value="" placeholer='Enter a password'/>
    </fieldset>
    <fieldset>
      <label for="password">Re-EnterPassword</label>
      <input type="password" name="repassword" id="repasswordVal" class="txtfield" onClick='selectText(this);'/>
      <input type="hidden" name="verification" id="verificationString" value="" />
      <input type="hidden" name="id" id="idNum" value="" />
    </fieldset>
  </fieldset>
  <input type="submit" name="submit" value="Sign Up" id="submit">
</form>

<script type="text/javascript">
	function validateSignUp(){
		var email = document.getElementById("emailVal").value;
		var user = document.getElementById("usernameVal").value;
		var pass = document.getElementById("passwordVal").value;
		var repass = document.getElementById("repasswordVal").value;
		var bnid = document.getElementById("battlenetidVal").value;
		
		if(email == null || email == "" || user == null || user == "" || pass == null || pass == "" || repass == null || repass == "" || bnid == ""){
			document.getElementById("requiredFieldError").innerHTML = "All Fields are Required";
			return false;
		}
		if(pass != repass){
			document.getElementById("requiredFieldError").innerHTML = "Passwords do not match";
			return false;
		}
		
		var success = false;
		var verification = "";
		var id = 0;
		
		$.ajax({
			type: "POST",
			url: 'process_signup.php',
			async: false,
			dataType: 'json',
			data: {emailPost: email, userPost: user, passPost: pass, battlenetidPost: bnid},
			
			success: function(obj, textstatus){
				if(!('error' in obj)){
					verification = obj['verification'];
					id = obj['id'];
					success = true;
				}
				else{
					document.getElementById("requiredFieldError").innerHTML = obj['error'];
					success = false;
				}
			}
		});
		
		//Pass along verification string for added security.
		document.getElementById("verificationString").value = verification;
		document.getElementById("idNum").value = id;
		if(success){
			window.location.href = "./validate_email.php";
		}
		return success;
	}
</script>