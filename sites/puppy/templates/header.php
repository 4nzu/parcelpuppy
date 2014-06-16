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
<div class="page">
    <div class="header">
        <a class='logo-link' href='/'><img id="logo" src="img/parcelpuppy.png"><span
                id="company-name">Parcel Puppy</span></a>

        <div class="header-links">
            <span>
                <a>How it works</a>
            </span>
            <span>
                <a>Become a Parcel Puppy</a>
            </span>
            <span>
                <? if (!$_SESSION['logged_in']) { ?>
                    <a href="/login_form">Login</a> or <a>Sign up</a>
                <? } else { ?>
                    <a href="/logout">Logout</a>
                <? } ?>
            </span>
        </div>
    </div>

    <div class="content">