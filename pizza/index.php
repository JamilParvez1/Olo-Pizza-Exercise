<?php
$response = file_get_contents('http://files.olo.com/pizzas.json');
$orders = json_decode($response, true);
$toppings_combinations = [];

foreach ($orders as $order) {
    $toppings = $order['toppings'];
    sort($toppings);
    $toppings_combinations[] = implode(', ', $toppings);
}

$top_combinations_with_count = array_count_values($toppings_combinations);
arsort($top_combinations_with_count);

$top_20_combinations = array_slice($top_combinations_with_count, 0, 20, true);
$chart_labels = [];
$chart_data = [];
$table_rows = '';
$color_index = 0;
$flavor_colors = [
    '#FF6384',
    '#36A2EB',
    '#FFCE56',
    '#4BC0C0',
    '#9966FF',
    '#FF9F40',
    '#FF6384',
    '#36A2EB',
    '#FFCE56',
    '#4BC0C0',
    '#9966FF',
    '#FF9F40',
    '#FF6384',
    '#36A2EB',
    '#FFCE56',
    '#4BC0C0',
    '#9966FF',
    '#FF9F40',
    '#FF6384',
    '#36A2EB',
];

foreach ($top_20_combinations as $combination => $count) {
    $chart_labels[] = $combination;
    $chart_data[] = $count;
    $flavors = explode(', ', $combination);
    $flavor_html = '';
    foreach ($flavors as $flavor) {
        $flavor_html .= "<span style=\"background-color:{$flavor_colors[$color_index]}\">{$flavor}</span>";
        $color_index = ($color_index + 1) % count($flavor_colors);
    }
    $table_rows .= "<tr><td>{$flavor_html}</td><td>{$count}</td></tr>";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>OLO - Pizza</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
        }

        .chart-container {
            width: 800px;
            margin: 0 auto;
        }

        table {
            width: 600px;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #f2f2f2;
        }

        span {
            padding: 2px 6px;
            margin-right: 4px;
            color: #fff;
            font-size: 12px;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <h1>Top 20 Most Frequently Ordered Pizza Topping Combinations</h1>

    <div class="chart-container">
        <canvas id="toppingsChart"></canvas>
    </div>
<center>
    <table>
        <tr>
            <th>Topping Combination</th>
            <th>Count</th>
        </tr>
        <?php echo $table_rows; ?>
    </table>
    </center>

    <script>
        var ctx = document.getElementById('toppingsChart').getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($chart_labels); ?>,
                datasets: [{
                    label: 'Number of Orders',
                    data: <?php echo json_encode($chart_data); ?>,
                    backgroundColor: <?php echo json_encode($flavor_colors); ?>,
                    borderColor: <?php echo json_encode($flavor_colors); ?>,
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        stepSize: 1
                    }
                }
            }
        });
    </script>
</body>
</html>
