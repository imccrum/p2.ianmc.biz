<h1>click to follow...</h1>

<?php foreach($users as $user): ?>
				
	

	<?php if (isset($connections[$user["user_id"]])): ?>
		<a href="/posts/unfollow/<?=$user[user_id]?>">
		<div class="pbox">
		<?=$user["first_name"]?> <?=$user["last_name"]?><br>
		Following
		</div>
		</a>
	
	<?php else: ?>
		<a href="/posts/follow/<?=$user[user_id]?>">
		<div class="mbox">
		<?=$user["first_name"]?> <?=$user["last_name"]?><br>
		Not Following
		</div>
		</a>
	<?php endif; ?>

   
<?php endforeach ?>