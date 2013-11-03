



<h1>hi <?=$user_name?></h1>

	
			<?php foreach($posts as $post): ?>

				<div class="mbox">
					<strong><?=$post["first_name"]?></strong>
					<strong class="date_string"><?=$post["created"]?></strong>
					<br><br>
					<?=$post["content"]?><br>
				</div>

			<?php endforeach; ?>

			

		