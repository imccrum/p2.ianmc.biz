<?php foreach($posts as $post): ?>			

<div class="container post">

<h1>Edit</h1>

	<?php if(isset($error)): ?>
        <div class="error">
            ooops.. posts can't be blank
        </div>
        
    <?php endif; ?>

<form method="post" action="/posts/p_editpost">

<textarea class="textbox" name="content" ><?=$post["content"] ?></textarea>

<input type="hidden" name="post_id" value="<?=$post["post_id"] ?>">

<input class="submit" type="submit" value="EDIT">

</form>

</div>

<?php endforeach; ?>