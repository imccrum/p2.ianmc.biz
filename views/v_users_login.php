<div class="container">
	<h1>Log In</h1>

	<?php if(isset($error)): ?>
		<?if($error=="email"): ?>
	    
	        <div class="error">
	            ooops.. no record of that email address
	        </div>  
	    
	    <?elseif($error=="password"): ?>
	    
	        <div class="error">
	        	password incorrect
	        </div>

	    <?php endif; ?>
	    <?php endif; ?>

	<form method="POST" action="/users/p_login">

		<label>Email:</label> 
		<input type="email" name="email" required>
	
		<label>Password:</label>
		<input type="password" name="password" required>
	
		<input class="submit" type="submit" value="LOGIN">

	</form>
</div>