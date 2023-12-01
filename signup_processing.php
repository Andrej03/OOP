<?php

session_start();

$name = $_POST["name"] ?? '';
$email = $_POST["email"] ?? '';
$password = $_POST["password"] ?? '';
$password_confirmation = $_POST["password_confirmation"] ?? '';

$errors = [];

if (empty($name)) {
    $errors[] = "Name is required";
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Valid email is required";
}

$uppercase = preg_match('@[A-Z]@', $password);
$lowercase = preg_match('@[a-z]@', $password);
$number = preg_match('@[0-9]@', $password);
$specialChars = preg_match('@[^\w]@', $password);

if (!$uppercase || !$lowercase || !$number || !$specialChars || strlen($password) < 8) {
    $errors[] = 'Password should be at least 8 characters in length and should include at least one upper case letter, one number, and one special character.';
} elseif ($password !== $password_confirmation) {
    $errors[] = "Passwords must match";
}

if (!empty($errors)) {
    foreach ($errors as $error) {
        exit($error);
    }
}

$password_hash = password_hash($password, PASSWORD_DEFAULT);
$mysqli = require __DIR__ . "/config.php";

$sql = "INSERT INTO user (name, email, password_hash) VALUES (?, ?, ?)";

$stmt = $mysqli->prepare($sql);
if (!$stmt) {
    exit("SQL error: " . $mysqli->error);
}

$stmt->bind_param("sss", $name, $email, $password_hash);

if ($stmt->execute()) {
    $_SESSION["user_id"] = $mysqli->insert_id;
    header("Location: signup_success.php");
    exit;
} else {
    exit("Email already taken");
}
