<?php

$data = json_decode(file_get_contents('php://input'), true);
if ($data === null) {
  echo "unable to parse json";
  http_response_code(400);
  exit;
}

// TODO: Find a better way to less repitition throughout.
if (!array_key_exists("timestamp", $data) || 
    $data["name"] === "" || 
    !array_key_exists("probA", $data) ||
    !array_key_exists("probB", $data) ||
    !array_key_exists("probC", $data) ||
    !array_key_exists("probD", $data) ||
    !array_key_exists("probE", $data) ||
    !array_key_exists("maxAmp", $data)
) {
  echo "not all data set";
  http_response_code(400);
  exit;
}

include "db.php";
$stmt = $conn->prepare("INSERT INTO coughs (time, name, probA, probB, probC, probD, probE, maxAmp) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("ssdddddd", $time, $name, $probA, $probB, $probC, $probD, $probE, $maxAmp);

$time = date("Y-m-d H:i:s", $data["timestamp"] / 1000);
$name = $data["name"];
$probA = $data["probA"];
$probB = $data["probB"];
$probC = $data["probC"];
$probD = $data["probD"];
$probE = $data["probE"];
$maxAmp = $data["maxAmp"];

if ($stmt->execute()) {
  echo "entry created";
} else {
  echo "failed to create entry";
  http_response_code(400);
}

$stmt->close();
$conn->close();
?>