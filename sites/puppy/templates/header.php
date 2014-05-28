<!DOCTYPE html>
<html lang="en">
<head>
	<link href="/css/ladda.min.css" rel="stylesheet">
	<link href="/css/styles.css" rel="stylesheet">
	<link href="/css/signin.css" rel="stylesheet">
	<!-- <link href='http://fonts.googleapis.com/css?family=Lato:100,300,400' rel='stylesheet' type='text/css'> -->
	<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,300' rel='stylesheet' type='text/css'>
	<script type="text/javascript" src="http://code.jquery.com/jquery-latest.min.js"></script>
	<script type="text/javascript" src="/js/functions.js"></script>
	<script type="text/javascript" src="/js/home.js"></script>
	<script type="text/javascript" src="/js/ladda.min.js"></script>
</head>
<body>
<div class="header">
	<div class="header">
		<a href="/"><img src='/img/parcelpuppy.png' width="67" heigh="38"></a>
	</div>
	<div class="menu header">
	<? if (!$_SESSION['logged_in']) { ?>
		<a class="signin" href="#">Sign in</a>
	<? } else { ?>
		<a href="/logout">Sign out</a>
	&nbsp; | &nbsp;<a href="/create">Create new request</a>
	&nbsp; | &nbsp;<a href="/myrequests">My requests</a>
	<? } ?>
	&nbsp; | &nbsp;<a href="/view">Browse requests</a>
	</div>
	<div class="clearfix"></div>
	<? if (!$_SESSION['logged_in']) include_once(MODULES_PATH."signin-window.php"); ?>
</div>
