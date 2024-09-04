<?php
session_start();

// Redirect to login page if the user is not logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit();
}

// File to store the results
$filename = 'results.txt';

// Initialize counters
$genderCounts = ['male' => 0, 'female' => 0,  'other' => 0];
$smileCounts = [1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0, 6 => 0];
$totalEntries = 0;

// Load the results from the file
if (file_exists($filename)) {
    $file = fopen($filename, 'r');
    if ($file !== false) {
        while (($line = fgets($file)) !== false) {
            $line = trim($line);
            if (!empty($line)) {
                $fields = explode('|', $line);
                if (count($fields) === 4) {
                    list($id, $gender, $rating, $date) = $fields;

                    // Count genders
                    if (isset($genderCounts[$gender])) {
                        $genderCounts[$gender]++;
                    }

                    // Count smile ratings
                    $rating = (int)$rating;
                    if (isset($smileCounts[$rating])) {
                        $smileCounts[$rating]++;
                    }

                    $totalEntries++;
                }
            }
        }
        fclose($file);
    }
}

// Calculate percentages with two decimal places
$genderPercentages = [
    'male' => $totalEntries ? number_format(($genderCounts['male'] / $totalEntries) * 100, 2) : 0,
    'female' => $totalEntries ? number_format(($genderCounts['female'] / $totalEntries) * 100, 2) : 0,
    'other' => $totalEntries ? number_format(($genderCounts['other'] / $totalEntries) * 100, 2) : 0
];

$smilePercentages = [];
foreach ($smileCounts as $rating => $count) {
    $smilePercentages[$rating] = $totalEntries ? number_format(($count / $totalEntries) * 100, 2) : 0;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Circle Diagram</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style/main.css">
    
    <link rel="stylesheet" href="style/diagram.css">
    
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div class="container">
        <h2 class="text-center my-4">Survey Results</h2>
        <div class="chart-container">
            <div class="chart-card">
                <h5 class="text-center">Gender Distribution</h5>
                <canvas id="genderChart"></canvas>
            </div>
            <div class="chart-card">
                <h5 class="text-center">Smile Ratings Distribution</h5>
                <canvas id="smileChart"></canvas>
            </div>
        </div>
        <button class="close-window-btn" onclick="window.close()">Close</button>
    </div>

    <script>
        // Gender Distribution Chart
        var genderCtx = document.getElementById('genderChart').getContext('2d');
        var genderChart = new Chart(genderCtx, {
            type: 'pie',
            data: {
                labels: ['Male', 'Female', 'Other'],
                datasets: [{
                    label: 'Gender Distribution',
                    data: [<?= $genderPercentages['male'] ?>, <?= $genderPercentages['female'] ?>, <?= $genderPercentages['other'] ?>],
                    backgroundColor: ['#36A2EB', '#FF6384','#FFCE56'],
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(tooltipItem) {
                                return tooltipItem.label + ': ' + tooltipItem.raw.toFixed(2) + '%';
                            }
                        }
                    }
                }
            }
        });

        // Smile Ratings Distribution Chart
        var smileCtx = document.getElementById('smileChart').getContext('2d');
        var smileChart = new Chart(smileCtx, {
            type: 'pie',
            data: {
                labels: ['Excellent', 'Great', 'Good', 'Okay', 'Bad', 'Very Bad'],
                datasets: [{
                    label: 'Smile Ratings Distribution',
                    data: [
                        <?= $smilePercentages[1] ?>, 
                        <?= $smilePercentages[2] ?>, 
                        <?= $smilePercentages[3] ?>, 
                        <?= $smilePercentages[4] ?>, 
                        <?= $smilePercentages[5] ?>, 
                        <?= $smilePercentages[6] ?>
                    ],
                    backgroundColor: [
                        '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF', '#FF9F40'
                    ],
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(tooltipItem) {
                                return tooltipItem.label + ': ' + tooltipItem.raw.toFixed(2) + '%';
                            }
                        }
                    }
                }
            }
        });
    </script>
</body>
</html>
