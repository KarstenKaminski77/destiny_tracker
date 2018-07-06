<?php
session_start();

define('DB_HOST', 'db_host');
define('DB_USER', 'db_user');
define('DB_PWD', 'db_password');
define('DB_DATABASE', 'db_name');

// Define API Host
define('API_HOST', 'https://www.bungie.net/Platform/Destiny2/');

// Define API Key
define('API_KEY', 'your_key');

// Define the directory sepearator
define('DS', DIRECTORY_SEPARATOR);

// Define Absolute URL's
define('HOST', 'http://' . $_SERVER['HTTP_HOST'] . '/');
define('PATH', 'destiny_tracker/');
define('ROOT', $_SERVER['DOCUMENT_ROOT'] .'/');

define('CSS', HOST . PATH . 'webroot/css/');
define('JS', HOST . PATH . 'webroot/js/');
define('IMG', HOST . PATH . 'webroot/images/');
define('CLASSES', ROOT .  PATH . 'src/class/');
define('TEMPLATES', ROOT . PATH . 'templates/');

 ?>
