<?php

$dbserver = "24.199.95.130";
$dbuser = "root";
$dbpass = "1234";
$dbname = "db_webstore";
$port = "3306";

try {
  $conn = new PDO("mysql:host=$dbserver;port=$port;dbname=$dbname", $dbuser, $dbpass);
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
  header("Location: error.php?error=Connection failed:" . $e->getMessage());
}
