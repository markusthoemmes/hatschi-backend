<?php

include "config.php";

$conn = new mysqli(
  $db_credentials["server"], 
  $db_credentials["username"], 
  $db_credentials["password"], 
  $db_credentials["database"]
);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

?>