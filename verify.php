<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $storedUsername = 'user';
    $storedPassword = 'password';

    if ($username == $storedUsername && $password == $storedPassword) {
        header('Location: pin_verification.html');
        exit();
    } else {
        echo "Invalid username or password.";
    }
}
?>