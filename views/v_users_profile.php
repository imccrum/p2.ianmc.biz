<h1>hi <?=$user_name?></h1>

<?php if(isset($message)): ?>
		
	<div class="success">

		<?php if($message == "post_successful"): ?>
			
			<div class="success">
            you just made a post
            </div>

        <?php endif; ?>

        <?php if($message == "edit_successful"): ?> 
            
            post edited

         <?php endif; ?>

         <?php if($message == "delete_successful"): ?> 
            
            post deleted

         <?php endif; ?>

    </div>

<?php endif; ?>

<?php if(!isset($posts[0])): ?>

	this is where your posts will appear - follow someone or add a post..
	
	<br>

<?php endif; ?>

<?php foreach($posts as $post): ?>

<div class="mbox">
	<strong><?=$post["first_name"]?></strong>
	<strong class="date_string"><?=$post["created"]?></strong>
	<br><br>
	<?=$post["content"]?><br>
</div>

<?php endforeach; ?>

		

	