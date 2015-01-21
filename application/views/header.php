<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width,initial-scale=1.0">
	<meta name="description" content="<?php echo isset($pageDescription) ? $pageDescription : DEFAULT_DESCRIPTION; ?>">
	<meta name="author" content="<?php echo isset($pageAuthor) ? $pageAuthor : DEFAULT_AUTHOR; ?>">

	<title><?php echo isset($pageTitle) ? $pageTitle : DEFAULT_TITLE; ?></title>

	<link rel="icon" type="image/png" href="favicon.png">

	<script src="http://code.jquery.com/jquery-2.1.3.min.js"></script>
	
	<link rel="stylesheet" href="/static/css/font-awesome.min.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="/static/css/style.css" type="text/css" media="screen" />

	<link href='http://fonts.googleapis.com/css?family=Bangers' rel='stylesheet' type='text/css'>
	<link href='http://fonts.googleapis.com/css?family=Roboto:400,700' rel='stylesheet' type='text/css'>
</head>
<body>

<header class="header-bar">
	<h1><a href="/"><span>C</span>omics<span>C</span>alendar</a></h1>
	<nav>
		<a href="/">Home</a>
		<?php
			if (isset($_SESSION['user-login']))
			{
				echo '<a href="/series/manage">Manage my series</a>';
				echo '<a href="/issues/unread">Unread issues</a>';
				echo '<a href="/advanced">Advanced</a>';
				echo '<a href="/users/logout">Logout</a>';
			}
			else
			{
				echo '<a href="/series/showall">Show all series</a>';
				echo '<a href="/users/login">Log in / Sign up</a>';
			}
		?>
		<a href="/about">About</a>
	</nav>
</header>


