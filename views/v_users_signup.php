<div class="container">
	
	<h1>Sign Up</h1>
	
	<form method="POST" action="/users/p_signup">

		<?php if(isset($error)): ?>
			
			<div class="error">

				<?if($error=="exists"): ?>

					ooops.. that email address already exists

				<?php endif; ?>

				<?if($error=="first name" || $error=="last name" || $error=="email" || $error=="password"): ?>

					<?=$error ?> field cannot be blank

				<?php endif; ?>

				<?if($error == "html"): ?>  

					sorry html is disabled please try again

				<?php endif; ?>

			</div>

		<?php endif; ?>

		<label>First Name:</label>
		<input type="text" name="first_name" required>

		<label>Last Name:</label>
		<input type="text" name="last_name" required>

		<label>Email:</label>
		<input type="email" name="email" required>

		<label>Password:</label>
		<input type="password" name="password" required>

		<input type="hidden" name="timezone">

		<script>
			
			$("input[name=timezone]").val(jstz.determine().name());
			
		</script>

		<input class="submit" type="submit" value="SIGN UP">

	</form>

</div>



