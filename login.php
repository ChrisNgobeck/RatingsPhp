<?php
session_start();

// Define a simple username and password for demonstration (in a real application, use a database)
$valid_username = 'Admin';
// Check if a new password was set during the reset process
$valid_password = $_SESSION['valid_password'] ?? 'X9p!Vq2@Yw#K7l$Z'; // Default or previously set password

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    // Validate the username and password
    if ($username === $valid_username && $password === $valid_password) {
        // Regenerate session ID to prevent session fixation
        session_regenerate_id(true);

        // Set a session variable to indicate the user is logged in
        $_SESSION['loggedin'] = true;
        $_SESSION['username'] = $username;

        // Redirect to results.php if the user is an admin
        if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
            unset($_SESSION['role']); // Clear the role after login
            header('Location: results.php');
        } else {
            header('Location: index.php');
        }
        exit();
    } else {
        $error = "Invalid username or password";
    }
}

// Check if the role is admin and show a specific message
$role_message = '';
if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
    $role_message = "Admin login required to see the results.";
}

// Check if the password was reset successfully
$password_reset_success = isset($_SESSION['password_reset_success']) ? true : false;
unset($_SESSION['password_reset_success']); // Clear the success message after displaying it
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style/main.css">
    <link rel="stylesheet" href="style/stylerating.css">
    <link rel="stylesheet" href="style/style1.css">
    <link rel="stylesheet" href="style/style2.css">
    <script src="style/script.js"></script>
    <title>Login</title>
    <style>
        

    </style>
</head>
<body>
    <div class="login-container">
        <h2>Login</h2>
        
        <?php if ($password_reset_success): ?>
            <p class="success-message">Password reset successful. Please log in with your new password.</p>
        <?php endif; ?>

        <?php if (!empty($role_message)): ?>
            <p class="role-message"><?= htmlspecialchars($role_message) ?></p>
        <?php endif; ?>

        <?php if (isset($error)): ?>
            <p class="error-message"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>
        
        <form action="login.php" method="POST">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div>
                <button type="submit">Login</button>
            </div>
            <div class="forgot-password">
                <a href="forgot_password.php">Forgot Password?</a>
            </div>
        </form>
    </div>
</body>
</html>
