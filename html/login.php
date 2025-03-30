<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-Type: application/json');

$host = "localhost";
$user = "root";
$pass = "";
$dbname = "epicstake";

// Database Connection
$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die(json_encode(["status" => "error", "message" => "DB Connection Failed: " . $conn->connect_error]));
}

// Check if request is received
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $action = $_POST["action"] ?? null;
    $email = $_POST["email"] ?? null;
    $password = $_POST["password"] ?? null;

    if (!$action || !$email || !$password) {
        die(json_encode(["status" => "error", "message" => "All fields are required!"]));
    }

    if ($action == "signup") {
        // Check if email exists
        $checkQuery = $conn->prepare("SELECT email FROM details WHERE email = ?");
        $checkQuery->bind_param("s", $email);
        $checkQuery->execute();
        $checkQuery->store_result();

        if ($checkQuery->num_rows > 0) {
            die(json_encode(["status" => "error", "message" => "Email already exists!"]));
        }

        // Insert new user
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $insertQuery = $conn->prepare("INSERT INTO details (email, password) VALUES (?, ?)");
        $insertQuery->bind_param("ss", $email, $hashedPassword);

        if ($insertQuery->execute()) {
            die(json_encode(["status" => "success", "message" => "Signup successful!"]));
        } else {
            die(json_encode(["status" => "error", "message" => "Signup failed!"]));
        }
    }

    if ($action == "login") {
        $query = $conn->prepare("SELECT password FROM details WHERE email = ?");
        $query->bind_param("s", $email);
        $query->execute();
        $query->store_result();

        if ($query->num_rows === 1) {
            $query->bind_result($hashedPassword);
            $query->fetch();

            if (password_verify($password, $hashedPassword)) {
                die(json_encode(["status" => "success", "message" => "Login successful!"]));
            } else {
                die(json_encode(["status" => "error", "message" => "Incorrect password!"]));
            }
        } else {
            die(json_encode(["status" => "error", "message" => "No account found!"]));
        }
    }
}

$conn->close();
?>
