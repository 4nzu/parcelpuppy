<?php
date_default_timezone_set('UTC');

// define hosts and roles
define('HOST_NAME', 'puppy');
define('HOST_PROD', 'prod');
define('HOST_DEV', 'dev');

define('SITE_ID', 3);

// determine host
if ($_SERVER["HTTP_HOST"] == 'dev.parcelpuppy.com') { // Alexei's iMac
    $host_id = HOST_NAME;
    $host_role = HOST_DEV;

    define('SITE_PATH', '/zappy/puppy/sites/puppy/');
    define('TEMPLATES_PATH', '/zappy/puppy/sites/puppy/templates/');
    define('MODULES_PATH', '/zappy/puppy/sites/puppy/templates/modules/');

    define('SITE_URL', 'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT']);
    define('PRODUCT_NAME', 'parcelpuppy');

    define('DB_HOST', '127.0.0.1');
    define('DB_NAME', 'puppy');
    define('DB_PORT', '3306');
    define('DB_USER', 'root');
    define('DB_PASS', '');

    define('MEMCACHE_SERVER', 'localhost');
    define('MEMCACHE_PORT', 11211);

    // define('PP_CONFIG_PATH', '/directory/that/contains/sdk_config.ini');
    error_reporting(E_ALL ^ E_NOTICE);
}

define('LOGIN_COOKIE_NAME','puppy-token');
define('HOST_ID', $host_id);
define('HOST_ROLE', $host_role);
define('FACEBOOK_ADMIN_ID', '');
define('SUPPORTED_API_VERSIONS', 'v1');
define('WIDGET_CACHE_TIME', 3*60*60);
define('DEFAULT_EMAIL', 'noreply@zappylab.com');
define('DEFAULT_EMAIL_PASS', '');

define('SIMPLE_ENCRYPTION_KEY', 'm4uS');

define('AWS_KEY','');
define('AWS_SECRET_KEY', '');
define('AWS_ACCOUNT_ID','');
define('AMAZON_S3_BUCKET', '');

define('LOG_FILE', '/tmp/'.HOST_NAME);

define('TEMPORARY_SECURITY_TOKEN', '');
define('EMAIL_SPAM_WAIT_PERIOD', 300);
