<?php

include_once ('../config/config.php');  // this can be done with a config loader + ini files or with a single php file
include_once ('../../../Zappy/loader.php');  // framework class loader

import('Zappy.DB');
import('Zappy.User');
import('Zappy.Util');
import('Zappy.Template');

session_start();

$_SESSION['logged_in'] = false;

$user = new User();

if (!empty($user->id)) {
    $_SESSION['logged_in'] = true;
}
