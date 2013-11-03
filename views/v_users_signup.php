



<div class="container signup">


<h1>Sign Up</h1>

<form method="POST" action="/users/p_signup">

	<?php if(isset($error)): ?>
		<?if($error=="exists"): ?>
        <div class="error">
            ooops.. that email address already exists
        </div>
        <?php endif; ?>
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



