<?php

$dbserver = "178.128.154.4";
$dbuser = "root";
$dbpass = "1234";
$dbname = "db_webstore";
$port = "3305";

try {
  $conn = new PDO("mysql:host=$dbserver;port=$port;dbname=$dbname", $dbuser, $dbpass);
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
  echo "Connection failed: " . $e->getMessage();
}
