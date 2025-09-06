<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch notifications from the database
$notif_sql = "SELECT message, status FROM notifications WHERE user_id = ? ORDER BY created_at DESC";
$stmt = $conn->prepare($notif_sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$notif_result = $stmt->get_result();
$notifications = $notif_result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// Fetch the latest booking status
$sql = "SELECT status FROM bookings WHERE user_id = ? ORDER BY id DESC LIMIT 1";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$latest_booking = $result->fetch_assoc();
$stmt->close();

$booking_status = $latest_booking['status'] ?? null;

// Check for new notifications
$has_new_notification = false;
$notification_message = '';
foreach ($notifications as $notification) {
    if ($notification['status'] == 'unread') {
        $has_new_notification = true;
        $notification_message = $notification['message'];
        break;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Badminton Court Booking</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        html, body { height: 100%; width: 100%; }
        body {
            font-family: Arial, sans-serif;
            background: url('1.png') no-repeat center center fixed;
            background-size: cover;
            color: white;
        }
        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #007D14;
            padding: 10px 20px;
        }
        .logo { font-size: 24px; font-weight: bold; }
        nav ul { list-style: none; display: flex; margin: 0; padding: 0; }
        nav ul li { margin: 0 15px; }
        nav ul li a { text-decoration: none; color: white; font-weight: bold; }
        .header-right { display: flex; align-items: center; }

        .notification-btn {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: #007D14;
            border: none;
            color: white;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            font-size: 22px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.3);
            z-index: 999;
        }
        .notification-btn .bell-icon { position: relative; }
        .notification-btn .red-dot {
            position: absolute;
            top: 5px;
            right: 5px;
            width: 10px;
            height: 10px;
            background: red;
            border-radius: 50%;
            display: <?= $has_new_notification ? 'block' : 'none' ?>;
        }

        .notification-panel {
            position: fixed;
            bottom: 80px;
            right: 20px;
            width: 300px;
            background: white;
            color: black;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
            padding: 10px;
            z-index: 1000;
            transition: opacity 0.3s ease-in-out, transform 0.3s ease-in-out;
            display: none;
            opacity: 0;
            transform: translateY(20px);
        }
        .notification-panel p { margin: 10px 0; font-size: 14px; }
        .notification-panel .close-btn {
            text-align: right;
            font-size: 12px;
            color: gray;
            cursor: pointer;
            padding: 5px;
        }

        .logout-btn {
            background: transparent;
            color: white;
            padding: 5px;
            border: 2px solid white;
            border-radius: 50%;
            cursor: pointer;
            font-size: 18px;
            transition: all 0.3s ease;
        }
        .logout-btn:hover { background: red; border-color: red; color: white; }

        .hero {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 80vh;
            text-align: center;
        }
        h1 { font-size: 60px; margin-bottom: 20px; }
    </style>
</head>
<body>

<header>
    <div class="logo">BADMINTON</div>
    <div class="header-right">
        <nav>
            <ul>
                <li><a href="#">Home</a></li>
                <li><a href="booking.php">Booking</a></li>
                <li><a href="profile.php">Profile</a></li>
            </ul>
        </nav>

        <a href="logout.php" class="logout-btn" title="Logout">
            <i class="fas fa-sign-out-alt"></i>
        </a>
    </div>
</header>

<section class="hero">
    <h1>BADMINTON COURT RESERVATION</h1>
    <p>Welcome, <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong>!</p>
</section>

<button class="notification-btn" onclick="toggleNotification()">
    <i class="fas fa-bell bell-icon"></i>
    <span class="red-dot" id="notificationDot" style="display: <?= !empty($notifications) ? 'inline-block' : 'none' ?>;"></span>
</button>

<div class="notification-panel" id="notificationPanel">
    <div class="close-btn" onclick="toggleNotification()">✖ Close</div>
    <p><strong>Notifications:</strong></p>
    <?php if (!empty($notifications)): ?>
        <?php foreach ($notifications as $notification): ?>
            <p><?= htmlspecialchars($notification['message']) ?></p>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No new notifications.</p>
    <?php endif; ?>
</div>

<script>
    function toggleNotification() {
        var panel = document.getElementById("notificationPanel");

        if (panel.style.display === "none" || panel.style.display === "") {
            panel.style.display = "block";
            setTimeout(() => {
                panel.style.opacity = "1";
                panel.style.transform = "translateY(0)";
            }, 10);
            markNotificationsRead();
        } else {
            panel.style.opacity = "0";
            panel.style.transform = "translateY(20px)";
            setTimeout(() => {
                panel.style.display = "none";
            }, 300);
        }
    }

    function markNotificationsRead() {
        fetch("mark_notifications_read.php", { method: "POST" })
            .then(response => response.text())
            .then(() => {
                document.getElementById("notificationDot").style.display = "none";
            });
    }

    document.addEventListener("click", function(event) {
        var panel = document.getElementById("notificationPanel");
        var button = document.querySelector(".notification-btn");
        if (!panel.contains(event.target) && !button.contains(event.target)) {
            panel.style.opacity = "0";
            panel.style.transform = "translateY(20px)";
            setTimeout(() => {
                panel.style.display = "none";
            }, 300);
        }
    });
</script>

</body>
</html>
