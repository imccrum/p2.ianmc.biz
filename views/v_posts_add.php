<div class="container">

<h1>Post</h1>

<?php if(isset($error)): ?>
        <div class="error">
            ooops.. posts can't be blank
        </div>
<?php endif; ?>

<form method="post" action="/posts/p_add">

<textarea class="textbox" name="content"></textarea>

<input class="submit" type="submit" value="POST">

</form>

</div>