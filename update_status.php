<?php

$data = json_decode(file_get_contents('php://input'), true);
if ($data === null) {
  echo "unable to parse json";
  http_response_code(400);
  exit;
}

if (!array_key_exists("id", $data) ||
    !array_key_exists("currently_tracking", $data) ||
    !array_key_exists("battery", $data) ||
    !array_key_exists("free_space", $data) ||
    !array_key_exists("recording", $data) ||
    !array_key_exists("detecting", $data) ||
    !array_key_exists("room", $data) ||
    !array_key_exists("uptime", $data)) {
  echo "not all data set";
  http_response_code(400);
  exit;
}

include "db.php";
$stmt = $conn->prepare("UPDATE status SET battery = ?, free_space = ?, currently_tracking = ?, last_ping = ?, recording = ?, detecting = ?, room = ?, uptime = ? WHERE status.id = ?;");
$stmt->bind_param("disssssss", $battery, $free_space, $name, $last_ping, $recording, $detecting, $room, $uptime, $id);

$id = $data["id"];
$name = $data["currently_tracking"];
$battery = $data["battery"];
$last_ping = date("Y-m-d H:i:s", time());
$free_space = $data['free_space'];
$recording = $data['recording'];
$detecting = $data['detecting'];
$room = $data["room"];
$uptime = $data["uptime"];

if ($stmt->execute()) {
  echo "status updated";
} else {
  echo "status update failed";
  http_response_code(400);
}

$stmt->close();
$conn->close();
?>