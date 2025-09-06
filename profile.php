<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$userId = $_SESSION['user_id'];
$message = "";

if (isset($_POST['update_password'])) {
    $currentPassword = $_POST['current_password'];
    $newPassword = $_POST['new_password'];

    // Fetch current password
    $query = "SELECT password FROM users WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $stmt->bind_result($hashedPassword);
    $stmt->fetch();
    $stmt->close();

    if (password_verify($currentPassword, $hashedPassword)) {
        $newHashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $updateQuery = "UPDATE users SET password = ? WHERE id = ?";
        $updateStmt = $conn->prepare($updateQuery);
        $updateStmt->bind_param("si", $newHashedPassword, $userId);
        $updateStmt->execute();

        $message = "<p style='color:green;'>Password updated successfully!</p>";
    } else {
        $message = "<p style='color:red;'>Incorrect current password!</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - Change Password</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html, body {
            height: 100%;
            width: 100%;
        }

        body {
            font-family: Arial, sans-serif;
            background: url('1.png') no-repeat center center fixed;
            background-size: cover;
            color: white;
        }

        /* Header Styles */
        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #007D14;
            padding: 10px 20px;
        }
        

        .logo {
            font-size: 24px;
            font-weight: bold;
        }

        nav ul {
            list-style: none;
            display: flex;
            margin: 0;
            padding: 0;
        }

        nav ul li {
            margin: 0 15px;
        }

        nav ul li a {
            text-decoration: none;
            color: white;
            font-weight: bold;
        }

        .header-right {
            display: flex;
            align-items: center;
        }

        .logout-btn {
            margin-left: 20px;
            background: transparent;
            color: white;
            padding: 5px;
            border: 2px solid white;
            border-radius: 50%;
            cursor: pointer;
            font-size: 18px;
            transition: all 0.3s ease;
        }

        .logout-btn:hover {
            background: #0057cc; /* Match hover effect with button */
            border-color: #0057cc;
            color: white;
        }

        /* Hero Section */
        .hero {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 80vh;
            text-align: center;
        }

        .form-container {
            background: rgba(0, 0, 0, 0.7);
            padding: 20px;
            border-radius: 8px;
            width: 300px;
        }

        .form-container h2 {
            margin-bottom: 20px;
            color: #fff;
        }

        .form-container input {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: none;
            border-radius: 5px;
        }

        .form-container button {
            padding: 10px;
            background: #007D14; /* Button color matches header */
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
            transition: 0.3s;
        }

        .form-container button:hover {
            background: #0057cc; /* Hover effect matches logout button */
        }

        .form-container p {
            margin-top: 10px;
            font-size: 14px;
        }
    </style>
</head>
<body>

<?php if (basename($_SERVER['PHP_SELF']) !== 'profile.php'): ?>
<header>
    <div class="logo">BADMINTON</div>
    <div class="header-right">
        <nav>
            <ul>
                <li><a href="dashboard.php">Home</a></li>
                <li><a href="booking.php">Booking</a></li>
                <li><a href="user_management.php">User Management</a></li>
                <li><a href="admin.php">Admin</a></li>
                <li><a href="profile.php">Profile</a></li>
            </ul>
        </nav>
        <a href="logout.php" class="logout-btn" title="Logout">
            <i class="fas fa-sign-out-alt"></i>
        </a>
    </div>
</header>
<?php endif; ?>

<section class="hero">
    <div class="form-container">
        <h2>Change Password</h2>
        <form method="POST">
            <input type="password" name="current_password" placeholder="Current Password" required>
            <input type="password" name="new_password" placeholder="New Password" required>
            <button type="submit" name="update_password">Update Password</button>
        </form>
        <?php echo $message; ?>
    </div>
</section>

</body>
</html>