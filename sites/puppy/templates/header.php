<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Parcel Puppy: Home</title>

    <link href='http://fonts.googleapis.com/css?family=Lato:400,700' rel='stylesheet' type='text/css'>
    <link href="/css/bootstrap.min.css" rel="stylesheet">
    <link href="/css/main.css" rel="stylesheet">
</head>
<body>
    <nav class="header-nav-bar navbar navbar-default" role="navigation">
        <div class="container-fluid">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#parcel-puppy-navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <div class="header-logo-block">
                    <a href='/'><img id="header-logo" src="img/parcelpuppy.png">
                        <span id="company-name">Parcel Puppy</span></a>
                </div>
            </div>
            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="parcel-puppy-navbar-collapse">
                <ul class="nav navbar-nav navbar-right">
                    <? if ($_SESSION['logged_in']) { ?>
                        <li class="nav-bar-link"><a href="#" class="header-nav-bar-link">Browse Requests</a></li>
                        <li><a href="#" class="header-nav-bar-link">Discover</a></li>
                        <li><a href="#" class="header-nav-bar-link">Make a Request</a></li>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" id="header-account-link" class="header-nav-bar-link">
                                <img src="img/placeholderAvatar.png" id="header-account-avatar"> <b class="caret"></b>
                            </a>
                            <ul class="dropdown-menu">
                                <li><a href="/account#profile">PROFILE</a></li>
                                <li><a href="/account#requests">REQUESTS</a></li>
                                <li><a href="/account#messages">MESSAGES</a></li>
                                <li><a href="/account#bids">MY BIDS</a></li>
                                <li><a href="/edit_account">SETTINGS</a></li>
                                <li><a href="/signout">LOG OUT</a></li>
                            </ul>
                        </li>
                    <? } else { ?>
                        <li><a href="/how-it-works" class="header-nav-bar-link">How it Works</a></li>
                        <li><a href="/become_a_puppy" class="header-nav-bar-link">Become a Parcel Puppy</a></li>
                        <li><a href="/signin" class="header-nav-bar-link">Log In</a></li>
                        <li><a href="/signup" class="header-nav-bar-link">Sign Up</a></li>
                    <? } ?>
                </ul>
            </div><!-- /.navbar-collapse -->
        </div><!-- /.container-fluid -->
    </nav>
    <div class="page">
        <div class="content">
