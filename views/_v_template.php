<!DOCTYPE html>
<html>
<head>
	<title><?php if(isset($title)) echo $title; ?></title>
	<meta name="viewport" content="initial-scale=0.7">
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />	
	<link href='http://fonts.googleapis.com/css?family=Oswald' rel='stylesheet' type='text/css'>

	<!-- Controller Specific JS/CSS -->
	<?php if(isset($client_files_head)) echo $client_files_head; ?>
	
	<style>


		body {

			background: White;
			font-family: 'Oswald', sans-serif;
			text-align: center;

		}

		a {

			color: black;
			text-decoration: none;

		}

	</style>
	
</head>

<body>	

<?php if($user): ?>

<nav>
	<a href="/"><img class="logo" src="/assets/duck.png"></a>
	<a href="/posts/add">POST</a> | 
	<a href="/posts/users">FOLLOW</a> | 
	<a href="/posts/edit">EDIT</a> | 
	<a href="/users/logout">LOGOUT</a>
</nav>

 <?php else: ?>

    <a href="/"><img class="logo" src="/assets/duck.png"></a>
	<a href="/users/login">LOGIN</a> | 
	<a href="/users/signup">SIGNUP</a>

<?php endif; ?>
	
<?php if(isset($content)) echo $content; ?>

<?php if(isset($client_files_body)) echo $client_files_body; ?>

</body>
</html>