<?php
  require_once 'header.php';
  if (!isset($_SESSION['apikey'])) {
    header("Location: index.php");
    exit;
  }
?>
<link rel="stylesheet" href="css/dashboard.css">

<h3 class="title" id="greeting_dash">Welcome User to CompareIt</h3>
<h3 class="title" id="register_date">User since:</h3>
<h3 class="title" id="registered_time">You have been a member for</h3>

<div class="chart-container">
  <canvas id="myChart"></canvas>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="scripts/dashboard.js"></script>

<?php
  require_once 'footer.php';
?>
