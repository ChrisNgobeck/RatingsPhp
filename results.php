<?php
session_start();

// Redirect to login page if the user is not logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit();
}

// Get the username to display in the welcome message
$username = isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'User';

// File to store the results
$filename = 'results.txt';

// Load the results from the file
$results = [];
if (file_exists($filename)) {
    $file = fopen($filename, 'r');
    if ($file !== false) {
        while (($line = fgets($file)) !== false) {
            $line = trim($line);  // Remove any extra whitespace
            if (!empty($line)) {  // Ensure the line isn't empty
                $fields = explode('|', $line);
                if (count($fields) === 4) {  // Ensure there are 4 fields (ID, gender, rating, date)
                    list($id, $gender, $rating, $date) = $fields;
                    $results[] = [
                        'id' => htmlspecialchars($id),
                        'gender' => htmlspecialchars($gender),
                        'rating' => htmlspecialchars($rating),
                        'date' => htmlspecialchars($date),
                    ];
                }
            }
        }
        fclose($file);  // Ensure the file is properly closed
    } else {
        echo "Error opening the file.";
    }
} else {
    echo "No data file found.";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <link rel="stylesheet" href="style/result.css">
    <script src="style/script.js"></script>
    <title>Results</title>
    <style>
        
    </style>
</head>
<body>
    <div class="header">
        <div class="welcome">
            <h2>Welcome, <?= $username ?>!</h2>
        </div>
        <div class="logout">
        <form action="logout.php" method="POST">
            <button type="submit">Logout</button>
        </form>
        </div>
    </div>

    <div class="emojis">
        <div class="emoji-wrapper">
            <span>😀</span>
            
            <p style="color: #FF6384;">Excellent</p>
        </div>
        <div class="emoji-wrapper">
            <span>😊</span>
            
                <p style="color: #36A2EB;">Great</p>
        </div>
        <div class="emoji-wrapper">
            <span>🙂</span>
            
                <p style="color: #FFCE56;">Good</p>
        </div>
        <div class="emoji-wrapper">
            <span>😐</span>
            
                <p style="color: #4BC0C0;">Okay</p>
        </div>
        <div class="emoji-wrapper">
            <span>😟</span>
            
                <p style="color: #9966FF;">Bad</p>
        </div>
        <div class="emoji-wrapper">
            <span>😠</span>
            
                <p style="color: #FF9F40;">Very Bad</p>
        </div>
    </div>

    <table>
        <tr>
            <th>ID</th>
            <th>Gender</th>
            <th>Rating</th>
            <th>Date</th>
        </tr>
        <?php if (!empty($results)): ?>
            <?php foreach ($results as $result): ?>
            <tr>
                <td><?= $result['id'] ?></td>
                <td><?= $result['gender'] ?></td>
                <td><?= $result['rating'] ?></td>
                <td><?= $result['date'] ?></td>
            </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="4">No results to display.</td>
            </tr>
        <?php endif; ?>
    </table>

    <div class="button-container">
        <button class="table-button" onclick="openCircleDiagram()">Show Diagram</button>
        <form action="exportPdf.php" method="POST" style="display: inline;">
            <button type="submit" class="table-button">Export as PDF</button>
        </form>
    </div>
    <script>
        function openCircleDiagram() {
            window.open('CircleDiagram.php', 'CircleDiagram', 'width=600,height=800px');
        }
    </script>
</body>
</html>
