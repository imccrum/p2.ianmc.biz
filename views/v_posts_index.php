			<?php foreach($posts as $post): ?>


				<div class="mbox">
					<strong><?=$post["first_name"]?></strong>
					<strong class="date_string"><?=$post["created"]?></strong>
					<br><br>
					<?=$post["content"]?>
				</div>

			<?php endforeach; ?>