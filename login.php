<?php
$is_invalid = false;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $mysqli = require __DIR__ . "/config.php";

    $email = $_POST["email"] ?? "";
    $stmt = $mysqli->prepare("SELECT id, password_hash FROM user WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $stmt->bind_result($user, $password);
        $stmt->fetch();

        if (password_verify($_POST['password'], $password)) {
            session_start();
            session_regenerate_id();
            $_SESSION["user_id"] = $user;
            header("Location: index.php");
            exit;
        }
    }

    $is_invalid = true;
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <meta charset="UTF-8">
</head>
<body>
    <h1>Login</h1>
    
    <?php if ($is_invalid): ?>
        <p><em>Invalid login</em></p>
    <?php endif; ?>
    
    <form method="post">
        <label for="email">Email</label>
        <input type="email" name="email" id="email" value="<?= htmlspecialchars($_POST["email"] ?? "") ?>">
        
        <label for="password">Password</label>
        <input type="password" name="password" id="password">
        
        <button type="submit">Log in</button>
    </form>
</body>
</html>
