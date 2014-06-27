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
    define('SECURE_SITE_URL', 'https://'.$_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT']);
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
// Hayden's Mac
elseif ($_SERVER["DOCUMENT_ROOT"] == "/Users/haydengomes/Work/repos/parcelpuppy/sites/puppy/docroot") {
    $host_id = HOST_NAME;
    $host_role = HOST_DEV;

    define('SITE_PATH', '/Users/haydengomes/Work/repos/parcelpuppy/sites/puppy/');
    define('TEMPLATES_PATH', '/Users/haydengomes/Work/repos/parcelpuppy/sites/puppy/templates/');
    define('MODULES_PATH', '/Users/haydengomes/Work/repos/parcelpuppy/sites/puppy/templates/modules/');

    define('SITE_URL', 'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT']);
    define('SECURE_SITE_URL', 'https://'.$_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT']);
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
}elseif ($_SERVER["DOCUMENT_ROOT"] == "C:/wamp/www/pubchase/sites/puppy/docroot") { // Alex win laptop

    // SITE
    $host_id = HOST_NAME;
    $host_role = HOST_DEV;
    define('SITE_PATH', 'C:/wamp/www/pubchase/sites/puppy/');

    define('SITE_URL', 'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT']);
    define('PRODUCT_NAME', 'parcelpuppy');
    define('MODULES_PATH', SITE_PATH.'templates/modules/');
    define('DB_HOST', '127.0.0.1');
    define('DB_NAME', 'parcelpuppy');
    define('DB_PORT', '3306');
    define('DB_USER', 'root');
    define('DB_PASS', '');

    define('MEMCACHE_SERVER', 'localhost');
    define('MEMCACHE_PORT', 11211);

    //PAYPAL
    define('PP_MODE', 'sandbox');
    define('PP_USERNAME', 'shlangster_api1.gmail.com');
    define('PP_PASSWORD', '1401215286');
    define('PP_SIGNATURE', 'AFcWxV21C7fd0v3bYYYRCpSSRl31AcX9XbBprixPpQMHMmrwSWdZ7k7e');
    define('PP_SECRET', 'EAjUiRCplSkoVWF8CE1ogrPbGWaglBUiUUVhZXuQUxrVYuYKjO2tKuS63hQz');
    define('PP_CLIENTID', 'ATahFRD6akvyRS6U6jXSTf3tb_U2y5HABQoyiLiku5hb9DB5CwijEy15q2JG');
    define('PP_APPID', 'APP-80W284485P519543T');
    DEFINE('PP_CANCEL_URL', SITE_URL.'/api/v1/cancel_payment');
    DEFINE('PP_RETURN_URL', SITE_URL.'/api/v1/success_payment');
    DEFINE('PP_RECEIVER_ACC', 'parcelpuppy-developer@gmail.com');

    error_reporting(E_ALL ^ E_NOTICE);
}elseif ($_SERVER["DOCUMENT_ROOT"] == "G:/xampp/htdocs/projects/parcelpuppy/sites/puppy/docroot") { // Alex desktop

    // SITE
    $host_id = HOST_NAME;
    $host_role = HOST_DEV;
    define('SITE_PATH', 'G:/xampp/htdocs/projects/parcelpuppy/sites/puppy/');

    define('SITE_URL', 'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT']);
    define('PRODUCT_NAME', 'parcelpuppy');
    define('MODULES_PATH', SITE_PATH.'templates/modules/');
    define('DB_HOST', '127.0.0.1');
    define('DB_NAME', 'parcelpuppy');
    define('DB_PORT', '3306');
    define('DB_USER', 'root');
    define('DB_PASS', '');

    define('MEMCACHE_SERVER', 'localhost');
    define('MEMCACHE_PORT', 11211);

    //PAYPAL
    define('PP_MODE', 'sandbox');
    define('PP_USERNAME', 'shlangster_api1.gmail.com');
    define('PP_PASSWORD', '1401215286');
    define('PP_SIGNATURE', 'AFcWxV21C7fd0v3bYYYRCpSSRl31AcX9XbBprixPpQMHMmrwSWdZ7k7e');
    define('PP_SECRET', 'EAjUiRCplSkoVWF8CE1ogrPbGWaglBUiUUVhZXuQUxrVYuYKjO2tKuS63hQz');
    define('PP_CLIENTID', 'ATahFRD6akvyRS6U6jXSTf3tb_U2y5HABQoyiLiku5hb9DB5CwijEy15q2JG');
    define('PP_APPID', 'APP-80W284485P519543T');
    DEFINE('PP_CANCEL_URL', SITE_URL.'/api/v1/cancel_payment');
    DEFINE('PP_RETURN_URL', SITE_URL.'/api/v1/success_payment');
    DEFINE('PP_RECEIVER_ACC', 'parcelpuppy-developer@gmail.com');
    DEFINE('PP_IPN_URL', SITE_URL.'/api/v1/paypal_ipn');
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

define('AVATAR_FILE_TYPE_ID', 1);
define('IMAGE_FILE_TYPE_ID', 2);
define('VIDEO_FILE_TYPE_ID', 3);

define('AVATAR_CDN_ROOT', '.s3.amazonaws.com');
define('IMAGE_CDN_ROOT', '.s3.amazonaws.com');
define('VIDEO_CDN_ROOT', '.s3.amazonaws.com');

define('AVATAR_S3_BUCKET', '');
define('IMAGE_S3_BUCKET', '');
define('VIDEO_S3_BUCKET', '');

define('AVATAR_BASE_URL', 'https://s3.amazonaws.com');
define('IMAGE_BASE_URL', 'https://s3.amazonaws.com');
define('VIDEO_BASE_URL', 'https://s3.amazonaws.com');

define('LOG_FILE', '/tmp/'.HOST_NAME);

define('TEMPORARY_SECURITY_TOKEN', '');
define('EMAIL_SPAM_WAIT_PERIOD', 300);

// Social logins TODO: create parcelpuppy social network apps and put real keys below
define('FACEBOOK_APPID', '');
define('FACEBOOK_APPSECRET', '');

define('GOOGLE_CLIENTID', '');
define('GOOGLE_CLIENTSECRET', '');
define('GOOGLE_SERVER_KEY', '');
