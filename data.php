<?php
include "db.php";

$sql = "SELECT * FROM coughs";
$result = $conn->query($sql);
?>

<?php include "header.php"; ?>
<h1>All data</h1>
<a href="csv.php">Export to CSV</a>

<?php if ($result->num_rows > 0): ?>
<table data-toggle="table" data-pagination="true" data-search="true" class="table table-striped">
  <thead>
    <tr>
      <th data-sortable="true" scope="col">ID</th>
      <th data-sortable="true" scope="col">Time</th>
      <th data-sortable="true" scope="col">Name</th>
      <th data-sortable="true" scope="col">ProbA</th>
      <th data-sortable="true" scope="col">ProbB</th>
      <th data-sortable="true" scope="col">ProbC</th>
      <th data-sortable="true" scope="col">ProbD</th>
      <th data-sortable="true" scope="col">ProbE</th>
      <th data-sortable="true" scope="col">MaxAmp</th>
    </tr>
  </thead>
  <tbody>
    <?php while($row = $result->fetch_assoc()): ?>
    <tr>
      <th scope="row"><?=$row['id']?></th>
      <td><?=$row['time']?></td>
      <td><?=$row['name']?></td>
      <td><?=$row['probA']?></td>
      <td><?=$row['probB']?></td>
      <td><?=$row['probC']?></td>
      <td><?=$row['probD']?></td>
      <td><?=$row['probE']?></td>
      <td><?=$row['maxAmp']?></td>
    </tr>
    <?php endwhile; ?>
  </tbody>
</table>
<?php else: ?>
nothing.
<?php endif; ?>

<?php include "footer.php"; ?>

<?php
$conn->close();
?>