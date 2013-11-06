<!DOCTYPE html>
<html>
	<head>
	<title><?php if(isset($title)) echo $title; ?></title>
	<meta name="viewport" content="initial-scale=0.7">
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />	
	<link href='http://fonts.googleapis.com/css?family=Oswald' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" type="text/css" href="/css/main.css">

	<?php if(isset($client_files_head)) echo $client_files_head; ?>

	</head>
	<body>	

	<?php if($user): ?>

	<nav>
		<a href="/"><img class="logo" src="/assets/duck.png"></a>
		<a href="/posts/add">POST</a> | 
		<a href="/posts/users">FOLLOW</a> | 
		<a href="/posts/myposts/edit">EDIT</a> | 
		<a href="/posts/myposts/delete">DELETE</a> |
		<a href="/users/logout">LOGOUT</a>
	</nav>

	<?php else: ?>

	<nav>
		<a href="/"><img class="logo" src="/assets/duck.png"></a>
		<a href="/users/login">LOGIN</a> | 
		<a href="/users/signup">SIGNUP</a>
	</nav>

	<?php endif; ?>

	<?php if(isset($content)) echo $content; ?>

	</body>
</html>