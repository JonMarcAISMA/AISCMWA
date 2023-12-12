<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "schedules";


$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


if (!$conn->set_charset("utf8")) {
    die("Error setting character set utf8: " . $conn->error);
}
?>
