<?php
include "db.php";

$sql = "SELECT * FROM status";
$result = $conn->query($sql);
?>

<?php include "header.php"; ?>
<h1>Device status</h1>

<?php if ($result->num_rows > 0): ?>
<table class="table table-striped">
  <thead>
    <tr>
      <th scope="col">ID</th>
      <th scope="col">Last Ping</th>
      <th scope="col">Uptime</th>
      <th scope="col">Currently Tracking</th>
      <th scope="col">Room</th>
      <th scope="col">Recording</th>
      <th scope="col">Detecting</th>
      <th scope="col">Battery Status</th>
      <th scope="col">Free Space</th>
    </tr>
  </thead>
  <tbody>
    <?php while($row = $result->fetch_assoc()): ?>
    <?php
      $minutes_ago = intval((time() - strtotime($row['last_ping'])) / 60);
      $ping_badge = '<span class="badge badge-success">Online</span>';
      if ($minutes_ago > 10) {
        $ping_badge = '<span class="badge badge-danger">Offline</span>';
      } else if ($minutes_ago > 5) {
        $ping_badge = '<span class="badge badge-warning">Online</span>';
      }


      $percent = $row['battery'] * 100;
      $battery_badge = '<span class="badge badge-success">Good</span>';
      if ($percent < 20) {
        $battery_badge = '<span class="badge badge-danger">Low</span>';
      } else if ($percent < 50) {
        $battery_badge = '<span class="badge badge-warning">Medium</span>';
      }
    ?>
    <tr>
      <th scope="row"><?=$row['id']?></th>
      <td><?=$minutes_ago?> minutes ago <?=$ping_badge?></td>
      <td><?=$row['uptime']?></td>
      <td><?=$row['currently_tracking']?></td>
      <td><?=$row['room']?></td>
      <td><?=$row['recording']?></td>
      <td><?=$row['detecting']?></td>
      <td><?=$percent?>% <?=$battery_badge?></td>
      <td><?=intval($row['free_space']/1024/1024)?> MB</td>
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