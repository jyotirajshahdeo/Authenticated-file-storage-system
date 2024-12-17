<?php
// USERS DATA FILE
define("USERS_FILE", "users.json");
include("./database.php");

session_start(); // Start session for user management

// Handle requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    // Handle Create Account
    if ($action === 'create_account') {
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';
        $pin = $_POST['pin'] ?? '';

        // Ensure PIN is numeric and exactly 4 digits
        if (!is_numeric($pin) || strlen($pin) !== 4) {
            $error = "PIN must be a 4-digit number!";
        } else {
            $users = file_exists(USERS_FILE) ? json_decode(file_get_contents(USERS_FILE), true) : [];

            foreach ($users as $user) {
                if ($user['username'] === $username) {
                    $error = "Username already exists!";
                    break;
                }
            }

            if (!isset($error)) {
                $users[] = ['username' => $username, 'password' => $password, 'pin' => $pin];
                file_put_contents(USERS_FILE, json_encode($users));
                $_SESSION['logged_in_user'] = ['username' => $username, 'pin' => $pin];
                // After account creation, redirect to login page
                header("Location: ?page=login");
                exit();
            }
        }
    }

    // Handle Login
    elseif ($action === 'login') {
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';
        $users = file_exists(USERS_FILE) ? json_decode(file_get_contents(USERS_FILE), true) : [];

        foreach ($users as $user) {
            if ($user['username'] === $username && $user['password'] === $password) {
                $_SESSION['logged_in_user'] = ['username' => $username, 'pin' => $user['pin']];
                // After successful login, redirect to PIN verification
                header("Location: ?page=pin_verification");
                exit();
            }
        }
        $error = "Invalid username or password!";
    }

    // Handle PIN Verification
    elseif ($action === 'verify_pin') {
        $enteredPin = $_POST['pin'] ?? '';

        if (isset($_SESSION['logged_in_user'])) {
            $storedPin = $_SESSION['logged_in_user']['pin'];
            if ($enteredPin === $storedPin) {
                $message = "PIN Verified Successfully!";
                // Redirect to the next page (e.g., dashboard)
                header("Location: color_verification.html"); // Replace with your actual next page
                exit();
            } else {
                $error = "Incorrect PIN!";
            }
        } else {
            $error = "Session expired! Please log in again.";
            header("Location: ?page=login");
            exit();
        }
    }
}

// Render HTML Pages
$page = $_GET['page'] ?? 'login';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PIN Verification</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body, html {
            height: 100%;
            margin: 0;
            font-family: 'Roboto', sans-serif;
            background: linear-gradient(135deg, #74ebd5, #acb6e5);
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .container {
            background-color: white;
            border-radius: 15px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.2);
            padding: 40px;
            text-align: center;
            width: 400px;
        }
        input, button {
            width: calc(100% - 20px);
            padding: 12px;
            margin: 10px 0;
            border-radius: 8px;
            border: 1px solid #ccc;
        }
        button {
            background-color: #6a82fb;
            color: white;
            cursor: pointer;
        }
        a { text-decoration: none; color: #6a82fb; }
    </style>
</head>
<body>
    <div class="container">
        <?php if ($page === 'login'): ?>
            <h3>Login</h3>
            <form method="POST">
                <input type="hidden" name="action" value="login">
                <input type="text" name="username" placeholder="Username" required>
                <input type="password" name="password" placeholder="Password" required>
                <button type="submit">Log In</button>
            </form>
            <p>Don't have an account? <a href="?page=create_account">Create Account</a></p>
            <?php if (isset($error)) echo "<p style='color: red;'>$error</p>"; ?>

        <?php elseif ($page === 'create_account'): ?>
            <h3>Create Account</h3>
            <form method="POST">
                <input type="hidden" name="action" value="create_account">
                <input type="text" name="username" placeholder="Username" required>
                <input type="password" name="password" placeholder="Password" required>
                <input type="text" name="pin" placeholder="4-digit PIN" required>
                <button type="submit">Submit</button>
            </form>
            <p>Already have an account? <a href="?page=login">Login</a></p>
            <?php if (isset($error)) echo "<p style='color: red;'>$error</p>"; ?>

        <?php elseif ($page === 'pin_verification'): ?>
            <h3>PIN Verification</h3>
            <form method="POST">
                <input type="hidden" name="action" value="verify_pin">
                <input type="text" name="pin" placeholder="Enter your PIN" required>
                <button type="submit">Verify</button>
            </form>
            <?php if (isset($error)) echo "<p style='color: red;'>$error</p>"; ?>
            <?php if (isset($message)) echo "<p style='color: green;'>$message</p>"; ?>
        <?php endif; ?>
    </div>
</body>
</html>
