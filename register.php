<?php
session_start();

$host = "localhost";
$dbname = "account";
$username = "root";
$password = "";

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $first_name = $conn->real_escape_string($_POST['first-name']);
    $middle_name = $conn->real_escape_string($_POST['middle-name']);
    $last_name = $conn->real_escape_string($_POST['last-name']);
    $contact_number = $conn->real_escape_string($_POST['contact_number']);
    $user_name = $conn->real_escape_string($_POST['username']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm-password'];

    if ($password !== $confirm_password) {
        echo "<script>
                alert('Passwords do not match!');
                window.location.href = 'register.php';
              </script>";
        exit();
    }

    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    $check_stmt = $conn->prepare("SELECT username FROM users WHERE username = ?");
    $check_stmt->bind_param("s", $user_name);
    $check_stmt->execute();
    $check_stmt->store_result();

    if ($check_stmt->num_rows > 0) {
        echo "<script>
                alert('Username already exists! Please choose a different one.');
                window.location.href = 'register.php';
              </script>";
        exit();
    }
    $check_stmt->close();

    // Insert new user
    $stmt = $conn->prepare("INSERT INTO users (first_name, middle_name, last_name, contact_number, username, password) VALUES (?, ?, ?, ?, ?, ?)");

    if (!$stmt) {
        die("SQL error: " . $conn->error);
    }

    $stmt->bind_param("ssssss", $first_name, $middle_name, $last_name, $contact_number, $user_name, $hashed_password);

    if ($stmt->execute()) {
        echo "<script>
                alert('Account created successfully!');
                window.location.href = 'login.php'; 
              </script>";
        exit();
    } else {
        echo "<script>
                alert('Error: " . addslashes($stmt->error) . "');
                window.location.href = 'register.php';
              </script>";
        exit();
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Register</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>

<section class="register-form">
    <h2>Register</h2>

    <form action="register.php" method="POST">
        <label>First Name</label>
        <input type="text" name="first-name" required>
        
        <label>Middle Name</label>
        <input type="text" name="middle-name">
        
        <label>Last Name</label>
        <input type="text" name="last-name" required>

        <label>Contact #</label>
        <input type="text" name="contact_number" required>

        <label>Username</label>
        <input type="text" name="username" required>

        <label>Password</label>
        <input type="password" name="password" required>
        
        <label>Confirm Password</label>
        <input type="password" name="confirm-password" required>

        <button type="submit">Register</button>
    </form>

    <p>Already have an account? <a href="login.php">Login here</a></p>
</section>

</body>
</html>
