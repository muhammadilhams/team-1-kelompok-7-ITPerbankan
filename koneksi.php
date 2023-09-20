<?php
error_reporting(E_ALL ^ E_NOTICE ^ E_DEPRECATED);
$host = 'localhost';
$user = 'root';
$password = '';
$db = 'bank_db';

$conn = mysqli_connect($host,$user,$password,$db) or die('Not Connect');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} else
    echo "connected successfully";

?>