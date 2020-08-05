<?php
ini_set('display_errors', 1); 
include "db.php";

// Queries to fill in the form
$name_result = $conn->query("SELECT DISTINCT name FROM coughs");

// Compute data itself
$name_filter = "All";
if (isset($_GET["name"])) {
  $name_filter = $_GET["name"];
}

$aggregate_by = "day";
if (isset($_GET["aggregate"])) {
  $aggregate_by = $_GET["aggregate"];
}

$last = 7;
if (isset($_GET["last"])) {
  $last = intval($_GET["last"]);
}

$timeframe = "date(time)";
if ($aggregate_by === "hour") {
  $timeframe = 'date_format(time, "%Y-%m-%d %H:00:00")';
}

$sql = "";
if ($name_filter === "All") {
  $sql = "SELECT name, $timeframe AS timeframe, COUNT(*) AS coughs FROM coughs WHERE (probA+probB+probC+probD+probE)/5 > 0.8 AND time >= (NOW() - INTERVAL ? $aggregate_by) GROUP BY name, timeframe ORDER BY timeframe";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("i", $last);
} else {
  $sql = "SELECT name, $timeframe AS timeframe, COUNT(*) AS coughs FROM coughs WHERE name = ? AND (probA+probB+probC+probD+probE)/5 > 0.8 AND time >= (NOW() - INTERVAL ? $aggregate_by) GROUP BY name, timeframe ORDER BY timeframe";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("si", $name_filter, $last);
}

if (!$stmt->execute()) {
  die("query failed");
}

$result = $stmt->get_result();

$sets = [];
while($row = $result->fetch_assoc()) {
  $sets[$row["name"]][] = '{x: new Date("' . $row["timeframe"] . '"), y: ' . $row["coughs"] . '}';
}

$datasets = [];
foreach($sets as $name => $values) {
  $datasets[] = '{label: "' . $name . '", data: [' . implode(",", $values) . ']}';
}
$datasets_str = implode(",", $datasets);
?>

<?php include "header.php"; ?>
<h1>Cough data visualization</h1>

<form action="index.php" method="GET">
  <label for="name">Name:</label>
  <select id="name" name="name">
    <option <?php if($name_filter === "All") echo 'selected'; ?>>All</option>
    <?php while($row = $name_result->fetch_assoc()): ?>
    <option <?php if($name_filter === $row["name"]) echo 'selected'; ?>><?=$row["name"]?></option>
    <?php endwhile; ?>
  </select>
  <br />

  Show the last: <input type="number" min="0" name="last" value="<?=$last?>" /> 
  <input type="radio" id="day" name="aggregate" value="day" <?php if($aggregate_by === "day") echo 'checked' ?>> <label for="day">Days</label>
  <input type="radio" id="hour" name="aggregate" value="hour" <?php if($aggregate_by === "hour") echo 'checked' ?>> <label for="hour">Hours</label>
  <br />
  <input type="submit" /> 
</form>

<strong>Current Query:</strong> <?=$sql?>

<canvas id="chart"></canvas>
<script src="https://cdn.jsdelivr.net/npm/moment@2.24.0/moment.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.3/dist/Chart.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-colorschemes@0.4.0/dist/chartjs-plugin-colorschemes.min.js"></script>
<script>
console.log([<?=$datasets_str?>]);
var ctx = document.getElementById('chart');
var chart = new Chart(ctx, {
  type: 'line',
  data: {
    datasets: [<?=$datasets_str?>],
  },
  options: {
    scales: {
      xAxes: [{
        offset: true,
        type: 'time',
        time: {
          unit: '<?=$aggregate_by?>',
          round: '<?=$aggregate_by?>',
        }
      }],
      yAxes: [{
        ticks: {
          stepSize: 1,
          beginAtZero: true
        }
      }],
    },
    plugins: {
      colorschemes: {
        scheme: 'tableau.Tableau20',
      }
    }
  }
});
</script>

<?php include "footer.php"; ?>

<?php
$conn->close();
?>