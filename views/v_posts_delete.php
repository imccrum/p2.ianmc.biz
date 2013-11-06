			
<?php if(isset($posts["0"])): ?>

	<h1>are you sure you want to delete this post?</h1>

	<div class="mbox">
		<strong><?=$posts["0"]["first_name"]?></strong>
		<strong class="date_string"><?=$posts["0"]["created"]?></strong>
		<br><br>
		<?=$posts["0"]["content"]?>
	</div>

	<form method="post" action="/posts/p_delete">

		<input type="hidden" name="post_id" value="<?=$posts["0"]["post_id"] ?>">

		<input class="submit" type="submit" value="DELETE">

	</form>

<?php else: ?>

	<h1>ooops.. this post does not exist</h1>

<?php endif; ?>