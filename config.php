<?php



define("DBNAME", "osiguranje");
define("HOST", "localhost");
define("DBUSERNAME", "root");
define("DBPASS", "");

define('DIR',basename(__DIR__));
define("MAILTRAPUSER", "5ecea122e6ad38");
define("MAILTRAPPASS", "f60d3c82257c97");

$url= $_SERVER['REQUEST_URI'];

define('BASE',substr($url,0,strpos($url,DIR)));
define('PATH',substr($url,strpos($url,DIR)-1)); 