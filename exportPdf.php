<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/vendor/autoload.php';

session_start();

// Redirect to login page if the user is not logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit();
}

// File to store the results
$filename = 'results.txt';

// Initialize variables to hold the results data
$results = [];

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
                    $results[] = [
                        'id' => htmlspecialchars($id),
                        'gender' => htmlspecialchars($gender),
                        'rating' => htmlspecialchars($rating),
                        'date' => htmlspecialchars($date),
                    ];
                }
            }
        }
        fclose($file);
    }
}

// Create an instance of mPDF and specify the temporary directory
$mpdf = new \Mpdf\Mpdf([
    'tempDir' => __DIR__ . '/tmp' // Use the custom tmp directory
]);

// Create PDF content
$html = '
    <h1>Survey Results</h1>
    <table border="1" cellpadding="10" cellspacing="0" style="width:100%; border-collapse: collapse;">
        <thead>
            <tr>
                <th>ID</th>
                <th>Gender</th>
                <th>Rating</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>';

if (!empty($results)) {
    foreach ($results as $result) {
        $html .= '
            <tr>
                <td>' . $result['id'] . '</td>
                <td>' . $result['gender'] . '</td>
                <td>' . $result['rating'] . '</td>
                <td>' . $result['date'] . '</td>
            </tr>';
    }
} else {
    $html .= '
        <tr>
            <td colspan="4">No results to display.</td>
        </tr>';
}

$html .= '
        </tbody>
    </table>';

// Write the HTML content to the PDF
$mpdf->WriteHTML($html);

// Output the PDF as a download
$mpdf->Output('survey_results.pdf', \Mpdf\Output\Destination::DOWNLOAD);
