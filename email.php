<?php
$email = $_POST["email"] ?? "";
$response = ["valid" => false, "available" => false, "emailErr" => ""];

if (!empty($email)) {
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $response["emailErr"] = "Invalid email format";
    } else {
        $email = validateInput($email);

        $mysqli = require __DIR__ . "/config.php";

        $sql = "SELECT * FROM user WHERE email = ?";
        $stmt = $mysqli->prepare($sql);

        if (!$stmt) {
            exit("SQL error: " . $mysqli->error);
        }

        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        $response["available"] = $result->num_rows === 0;
        $response["valid"] = true;
    }
}

// Set the response header to indicate JSON content
header("Content-Type: application/json");

// Send JSON response indicating email availability
echo json_encode($response);
?>
