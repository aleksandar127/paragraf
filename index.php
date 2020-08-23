<?php






require(__DIR__ . '/vendor/autoload.php');
require(__DIR__ . '/config.php');




$zahtev = new App\Zahtev();
$db = new App\Db();
$ruter = new App\Ruter($zahtev, $db);
