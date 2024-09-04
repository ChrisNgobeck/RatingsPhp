<?php
session_start();

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $role = $_POST['role'] ?? 'user';
    $id = uniqid(); // Generate a unique ID for the entry
    $gender = isset($_POST['gender']) ? htmlspecialchars($_POST['gender']) : 'unknown';
    $rating = isset($_POST['selectedRating']) ? htmlspecialchars($_POST['selectedRating']) : '0';
    $date = date('Y-m-d H:i:s'); // Get the current date and time

    // Save the data
    $filename = 'results.txt';
    $data = $id . '|' . $gender . '|' . $rating . '|' . $date . PHP_EOL;
    file_put_contents($filename, $data, FILE_APPEND | LOCK_EX);

    if ($role === 'admin') {
        // If the role is admin, redirect to login.php after saving the data
        $_SESSION['role'] = 'admin';
        header('Location: login.php');
        exit();
    } else {
        // If the role is user, return a response for AJAX to handle
        echo json_encode(['status' => 'success']);
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rating Program</title>
    <link rel="stylesheet" href="style/main.css">
    <link rel="stylesheet" href="style/stylerating.css">
    <link rel="stylesheet" href="style/style1.css">
    <link rel="stylesheet" href="style/modal.css">
    <script src="style/script/script.js"></script>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <div class="role-dropdown">
            <label for="roleSelect"></label>
            <select id="roleSelect" name="role" form="ratingForm">
                <option value="user">User</option>
                <option value="admin">Admin</option>
            </select>
        </div>
        <div class="mode-toggle">
                <label class="switch">
                    <span class="sun">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                            <g fill="#ffd43b">
                                <circle r="5" cy="12" cx="12"></circle>
                                <path d="m21 13h-1a1 1 0 0 1 0-2h1a1 1 0 0 1 0 2zm-17 0h-1a1 1 0 0 1 0-2h1a1 1 0 0 1 0 2zm13.66-5.66a1 1 0 0 1 -.66-.29 1 1 0 0 1 0-1.41l.71-.71a1 1 0 1 1 1.41 1.41l-.71.71a1 1 0 0 1 -.75.29zm-12.02 12.02a1 1 0 0 1 -.71-.29 1 1 0 0 1 0-1.41l.71-.66a1 1 0 0 1 1.41 1.41l-.71.71a1 1 0 0 1 -.7.24zm6.36-14.36a1 1 0 0 1 -1-1v-1a1 1 0 0 1 2 0v1a1 1 0 0 1 -1 1zm0 17a1 1 0 0 1 -1-1v-1a1 1 0 0 1 2 0v1a1 1 0 0 1 -1 1zm-5.66-14.66a1 1 0 0 1 -.7-.29l-.71-.71a1 1 0 0 1 1.41-1.41l.71.71a1 1 0 0 1 0 1.41 1 1 0 0 1 -.71.29zm12.02 12.02a1 1 0 0 1 -.7-.29l-.66-.71a1 1 0 0 1 1.36-1.36l.71.71a1 1 0 0 1 0 1.41 1 1 0 0 1 -.71.24z"></path>
                            </g>
                        </svg>
                    </span>
                    <span class="moon">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512">
                            <path fill="#ffd43b"
                                d="m223.5 32c-123.5 0-223.5 100.3-223.5 224s100 224 223.5 224c60.6 0 115.5-24.2 155.8-63.4 5-4.9 6.3-12.5 3.1-18.7s-10.1-9.7-17-8.5c-9.8 1.7-19.8 2.6-30.1 2.6-96.9 0-175.5-78.8-175.5-176 0-65.8 36-123.1 89.3-153.3 6.1-3.5 9.2-10.5 7.7-17.3s-7.3-11.9-14.3-12.5c-6.3-.5-12.6-.8-19-.8z">
                            </path>
                        </svg>
                    </span>
                    <input type="checkbox" class="input">
                    <span class="slider"></span>
                </label>
            </div>


    </div>
             <h2 style="text-align: center; margin-bottom: 20px;">Give us feedback on how we succeeded in helping you today</h2>

    <form id="ratingForm">
        <div class="gender-dropdown">
            <label class="gender-option male" style="color: #36A2EB;">
                <input type="radio" name="gender" value="male" checked> ♂ Male
            </label>
            <label class="gender-option female" style="color: #FF6384;">
                <input type="radio" name="gender" value="female"> ♀ Female
            </label>
            <label class="gender-option other" style="color: #FFCE56;">
                <input type="radio" name="gender" value="other"> ⚧ Other
            </label>
        </div>

        <div class="emojis text-center">
            <button type="button" data-rating="Excellent" class="emoji-button" onclick="submitRating(1)">
                <span class="emoji" style="color: #FF6384;">😀</span>
                <hr class="emoji-line" style="border-color: #FF6384;">
                <p style="color: #FF6384;">Excellent</p>
            </button>
            <button type="button" data-rating="Great" class="emoji-button" onclick="submitRating(2)">
                <span class="emoji" style="color: #36A2EB;">😊</span>
                <hr class="emoji-line" style="border-color: #36A2EB;">
                <p style="color: #36A2EB;">Great</p>
            </button>
            <button type="button" data-rating="Good" class="emoji-button" onclick="submitRating(3)">
                <span class="emoji" style="color: #FFCE56;">🙂</span>
                <hr class="emoji-line" style="border-color: #FFCE56;">
                <p style="color: #FFCE56;">Good</p>
            </button>
            <button type="button" data-rating="Okay" class="emoji-button" onclick="submitRating(4)">
                <span class="emoji" style="color: #4BC0C0;">😐</span>
                <hr class="emoji-line" style="border-color: #4BC0C0;">
                <p style="color: #4BC0C0;">Okay</p>
            </button>
            <button type="button" data-rating="Bad" class="emoji-button" onclick="submitRating(5)">
                <span class="emoji" style="color: #9966FF;">😟</span>
                <hr class="emoji-line" style="border-color: #9966FF;">
                <p style="color: #9966FF;">Bad</p>
            </button>
            <button type="button" data-rating="Very Bad" class="emoji-button" onclick="submitRating(6)">
                <span class="emoji" style="color: #FF9F40;">😠</span>
                <hr class="emoji-line" style="border-color: #FF9F40;">
                <p style="color: #FF9F40;">Very Bad</p>
            </button>
        </div>




        <input type="hidden" name="selectedRating" id="selectedRating" value="">
        <input type="hidden" name="timestamp" id="timestamp" value="">
    </form>
</div>

<!-- Feedback Modal -->
<div id="feedbackModal" class="modal">
    <div class="modal-content">
        <span class="close-button">&times;</span>
        <p>Thank you for your feedback!</p>
    </div>
</div>

<script>
    function submitRating(rating) {
    // Set the rating value
    document.getElementById('selectedRating').value = rating;

    // Set the timestamp value
    document.getElementById('timestamp').value = new Date().toISOString();

    // Get the selected role
    const role = document.getElementById('roleSelect').value;

    if (role === 'admin') {
        // If the role is admin, submit the form and redirect to index.php
        document.getElementById('ratingForm').action = 'index.php';
        document.getElementById('ratingForm').method = 'POST';
        document.getElementById('ratingForm').submit();
    } else {
        // If the role is user, submit the form data via AJAX
        const formData = new FormData(document.getElementById('ratingForm'));

        fetch('index.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                const modal = document.getElementById('feedbackModal');
                modal.style.display = 'block';

                // Automatically close the modal after 3 seconds (3000 milliseconds)
                setTimeout(function() {
                    modal.style.display = 'none';
                    document.getElementById('ratingForm').reset();
                }, 10000); // Time in milliseconds (3 seconds)

                // Close the modal when the close button is clicked
                const closeButton = document.querySelector('.close-button');
                closeButton.onclick = function() {
                    modal.style.display = 'none';
                    document.getElementById('ratingForm').reset();
                };

                // Close the modal if the user clicks outside the modal content
                window.onclick = function(event) {
                    if (event.target == modal) {
                        modal.style.display = 'none';
                        document.getElementById('ratingForm').reset();
                    }
                };
            }
        })
        .catch(error => console.error('Error:', error));
    }
}
    
</script>


</body>
</html>
