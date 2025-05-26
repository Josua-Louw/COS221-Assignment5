<?php
require_once 'header.php';
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

    <h3 class = "title">Welcome Abdulrahman to CompareIt</h3>
    <h3 class = "title">Registarted date: you have been registered for one year and three months</h3>


    <div class="chart-container">
        <canvas id="myChart"></canvas>
    </div>
   

    <?php
    $labels = ["Label_1", "Label_1", "Label_1", "Label_1", "Label_1"];
    $data = [120, 150, 180, 90, 200];
    ?>

    <script>
        const ctx = document.getElementById('myChart').getContext('2d');
        const chart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($labels); ?>,
                datasets: [{
                    label: 'Categories',
                    data: <?php echo json_encode($data); ?>,
                    backgroundColor: 'rgba(75, 192, 192, 0.6)',
                    borderWidth: 0
                }]
            }
        });
    </script>

</body>
</html>

<?php
require_once 'footer.php';
?>
