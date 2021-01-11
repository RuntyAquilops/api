<?php
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

$connection = mysqli_connect('localhost:3307', 'api', 'aquilops', 'hotel') or die('Not connected : Ah sh*t ' . mysqli_connect_error());

?>
