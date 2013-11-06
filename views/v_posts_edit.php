<div class="container post">

	<h1>Edit</h1>

	<?php if(isset($error)): ?>
			
			<div class="error">

				<?php if($error == "error"): ?>

		            ooops.. posts can't be blank

		        <?php endif; ?>

		        <?php if($error == "html"): ?> 

		            sorry html is disabled please try again
		        
		        <?php endif; ?>
	        
	        </div>

	<?php endif; ?>

		<form method="post" action="/posts/p_edit">

			<textarea class="textbox" name="content" ><?=$posts["0"]["content"] ?></textarea>

			<input type="hidden" name="post_id" value="<?=$posts["0"]["post_id"] ?>">

			<input class="submit" type="submit" value="EDIT">

		</form>

</div>