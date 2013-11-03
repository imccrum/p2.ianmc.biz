			<h1>choose a post to edit...</h1>

			<?php foreach($posts as $post): ?>

				<a href="/posts/editpost/<?=$post[post_id] ?>"><div class="mbox">
					<strong><?=$post["first_name"]?></strong>
					<strong class="date_string"><?=$post["created"]?></strong>
					<br><br>
					<?=$post["content"]?>
				</div></a>

			<?php endforeach; ?>

