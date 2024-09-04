<?php
session_start();

// For demonstration, the valid username is predefined (In real scenarios, this would be checked against a database)
$valid_username = 'Admin';

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'] ?? '';
    $new_password = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    // Validate that the username matches and the passwords are not empty and match
    if ($username === $valid_username) {
        if (!empty($new_password) && $new_password === $confirm_password) {
            // Update the password (In real applications, hash the password and update it in the database)
            $_SESSION['valid_password'] = $new_password;

            // Redirect to the login page with a success message
            $_SESSION['password_reset_success'] = true;
            header('Location: login.php');
            exit();
        } else {
            $error = "Passwords do not match or are empty.";
        }
    } else {
        $error = "Invalid username.";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style/main.css">
    <link rel="stylesheet" href="style/style1.css">
    <link rel="stylesheet" href="style/style2.css">
    <link rel="stylesheet" href="style/stylerating.css">
    <script src="style/script.js"></script>
    <title>Forgot Password</title>
    <style>
       
    </style>
</head>
<body>
    <div class="forgot-password-container">
        <h2>Reset Password</h2>

        <?php if (isset($error)): ?>
            <p class="error-message"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>

        <form action="forgot_password.php" method="POST">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="new_password">New Password:</label>
                <input type="password" id="new_password" name="new_password" required>
            </div>
            <div class="form-group">
                <label for="confirm_password">Confirm New Password:</label>
                <input type="password" id="confirm_password" name="confirm_password" required>
            </div>
            <div>
                <button type="submit">Reset Password</button>
            </div>
        </form>
    </div>
</body>
</html>
