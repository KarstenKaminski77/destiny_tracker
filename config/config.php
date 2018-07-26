<?php
session_start();

// Define API Host
define('BASE_URL', 'https://www.bungie.net/');

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
