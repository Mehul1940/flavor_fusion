<?php

require(__DIR__ . '/Connection.php');


$servername = 'localhost';
$port = 3306;
$db_name = 'flavor_fusion_db';
$username = 'root';
$password = '';


$connection = new Connection($servername, $port, $db_name, $username, $password);
