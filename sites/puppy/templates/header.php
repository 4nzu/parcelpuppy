<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Parcel Puppy: Home</title>

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
                <div class="logo-block">
                    <a href='/'><img id="logo" src="img/parcelpuppy.png">
                        <span id="company-name">Parcel Puppy</span></a>
                </div>
            </div>
                                    <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="parcel-puppy-navbar-collapse">
                <ul class="nav navbar-nav navbar-right">
                    <li class="nav-bar-link"><a href="#" class="nav-bar-link">Browse Requests</a></li>
                    <li><a href="#">Discover</a></li>
                    <li><a href="#">Make a Request</a></li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <? if ($_SESSION['logged_in']) { ?>
                            <?= $_SESSION['user']->first_name ?> <b class="caret"></b></a>
                            <ul class="dropdown-menu">
                                <li><a href="/signout">Logout</a></li>
                            </ul>
                        <? } else { ?>
                            Account <b class="caret"></b></a>
                            <ul class="dropdown-menu">
                                <li><a href="/signin">Sign In</a></li>
                                <li><a href="/signup">Sign Up</a></li>
                            </ul>
                        <? } ?>
                    </li>
                </ul>
            </div><!-- /.navbar-collapse -->
        </div><!-- /.container-fluid -->
    </nav>
    <div class="page">
        <div class="content">
