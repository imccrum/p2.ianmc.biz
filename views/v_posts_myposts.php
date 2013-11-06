	<?php if(isset($posts[0])): ?>

		<h1>choose a post to <?=$option ?>...</h1>

		<?php foreach($posts as $post): ?>

			<a href="/posts/<?=$option ?>/<?=$post[post_id] ?>"><div class="mbox">
				<strong><?=$post["first_name"]?></strong>
				<strong class="date_string"><?=$post["created"]?></strong>
				<br><br>
				<?=$post["content"]?>
			</div></a>

		<?php endforeach; ?>

	<?php else: ?>

		<h1>you need to add some posts first...</h1>

	<?php endif; ?>
