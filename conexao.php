
<?php

//ambiente free
$servername = 'mysql-prog3-prog3.g.aivencloud.com';
$username = '';
$password = '';
$dbname = 'ong';
$port = 14021;

// Create connection
$con = new mysqli($servername, $username, $password, $dbname, $port);

// Check connection
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

?>