<?php
include "db.php";

$sql = "SELECT * from coughs";
$result = $conn->query($sql);

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=cough-data.csv');
$output = fopen("php://output", "w");
fputcsv($output, array('id', 'name', 'time', 'probA', 'probB', 'probC', 'probD', 'probE', 'maxAmp'));
while($row = $result->fetch_assoc()) {
  fputcsv($output, $row);
}
fclose($output);
?>