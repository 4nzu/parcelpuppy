<?php

include_once ('../config/config.php');  // this can be done with a config loader + ini files or with a single php file
include_once ('../../../Zappy/loader.php');  // framework class loader

import('Zappy.DB');
import('Zappy.User');
import('Zappy.Util');
import('Zappy.Template');

session_start();

// get config values from the database
$db = DB::instance();
$cfg = $db->query("SELECT c_key, c_value from config", null, 3600);
foreach($cfg as $cf) {
	define($cf['c_key'], $cf['c_value']);
}

$_SESSION['logged_in'] = false;

$user = new User();

if (!empty($user->id)) {
    $_SESSION['logged_in'] = true;
}
