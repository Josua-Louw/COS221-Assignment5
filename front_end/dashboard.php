<?php
require_once 'header.php';
if (!isset($_SESSION['apikey'])) {
    header("Location: index.php");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            background-color: #ffffff;
        }


        .chart-container {
            background-color: white;
            padding: 30px;
            max-width: 1000px;
            margin: 0 auto;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            border: 1px solid #ddd;
        }

        .title
        {
            text-align: center;
            padding: 10px;

        }

       
    </style>
</head>
<body>

    <h3 class = "title" id="greeting_dash">Welcome User to CompareIt</h3>
    <h3 class = "title" id="register_date">User since: </h3>
    <h3 class="title" id="registered_time">You have been a member for </h3>


    <div class="chart-container">w
        <canvas id="myChart"></canvas>
    </div>

    <script src="scripts/dashboard.js"></script>
</body>
</html>

<?php
require_once 'footer.php';
?>
