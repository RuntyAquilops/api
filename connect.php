<?php

$host = 'localhost:3307';
$userName = 'api';
$userPassword = 'aquilops';
$database = 'hotel';

$connection = mysqli_connect($host, $userName, $userPassword, $database) or die('Some troubles with connection. Please check your settings. ' . mysqli_connect_error());

?>
