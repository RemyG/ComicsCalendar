<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8" />

	<meta name="description" content="<?php echo isset($pageDescription) ? $pageDescription : DEFAULT_DESCRIPTION; ?>">
	<meta name="author" content="<?php echo isset($pageAuthor) ? $pageAuthor : DEFAULT_AUTHOR; ?>">

	<title><?php echo isset($pageTitle) ? $pageTitle : DEFAULT_TITLE; ?></title>    

	<link rel="stylesheet" href="<?php echo BASE_URL; ?>static/css/font-awesome.min.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="<?php echo BASE_URL; ?>static/css/style.less" type="text/less" media="screen" />

	<link href='http://fonts.googleapis.com/css?family=Bangers' rel='stylesheet' type='text/css'>
	<link href='http://fonts.googleapis.com/css?family=Roboto:400,700' rel='stylesheet' type='text/css'>

	<script type="text/javascript" src="<?php echo BASE_URL; ?>static/js/less-1.3.3.min.js"></script>
	<script type="text/javascript" src="<?php echo BASE_URL; ?>static/js/jquery-2.0.0.min.js"></script>
</head>
<body>

<header class="header-bar">
	<h1><a href="<?php echo BASE_URL; ?>"><span>C</span>omics<span>C</span>alendar</a></h1>
	<nav>
		<a href="<?php echo BASE_URL; ?>">Home</a>
		<?php
			if (isset($_SESSION['user-login']))
			{
				echo '<a href="'.BASE_URL.'series/manage">Manage my series</a>';
				echo '<a href="'.BASE_URL.'users/logout">Logout</a>';
			}
			else
			{
				echo '<a href="'.BASE_URL.'series/showall">Show all series</a>';
				echo '<a href="'.BASE_URL.'users/login">Log in / Sign up</a>';
			}
		?>
		<a href="<?php echo BASE_URL; ?>about">About</a>
	</nav>
</header>


