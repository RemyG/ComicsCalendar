<form method="post" action="<?php echo BASE_URL; ?>users/login" class="user-form">
	<fieldset>
		<legend>Log in</legend>
		<div class="input-group">
		<label>Login</label>
		<input type="text" name="login" autofocus required>
		</div>
		<div class="input-group">
		<label>Password</label>
		<input type="password" name="password" required>
		</div>
		<div class="input-group">			
			<input type="checkbox" id="rememberme" name="rememberme">
			<label for="rememberme">Remember me</label>
		</div>
		<div class="input-group">
		<input type="submit" name="formlogin" value="Log in">
		</div>
	</fieldset>
</form>

<form method="post" action="<?php echo BASE_URL; ?>users/signup" class="user-form">
	<fieldset>
		<legend>Sign up</legend>
		<div class="input-group">
			<label>Login</label>
			<input type="text" name="login" required>
		</div>
		<div class="input-group">
			<label>Email</label>
			<input type="email" id="email" name="email" required>
		</div>
		<div class="input-group">
			<label>Confirm your email</label>
			<input type="email" name="confirmEmail" required oninput="checkEmail(this)">
		</div>
		<div class="input-group">
			<label>Password</label>
			<input type="password" id ="password" name="password" required>
		</div>
		<div class="input-group">
			<label>Confirm your password</label>
			<input type="password" name="confirmPassword" required oninput="checkPassword(this)">
		</div>
		<div class="input-group">
			<?php echo recaptcha_get_html(RECAPTCHA_PUBLIC_KEY); ?>
		</div>
		<div class="input-group">
			<input type="submit" name="formsignup" value="Sign up">
		</div>
	</fieldset>
</form>

<script>
function checkEmail(input) {
  if (input.value != document.getElementById('email').value) {
    input.setCustomValidity('The two email addresses must match.');
  } else {
    // input is valid -- reset the error message
    input.setCustomValidity('');
  }
}
function checkPassword(input) {
  if (input.value != document.getElementById('password').value) {
    input.setCustomValidity('The two passwords must match.');
  } else {
    // input is valid -- reset the error message
    input.setCustomValidity('');
  }
}
</script>