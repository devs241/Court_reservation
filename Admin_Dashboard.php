<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin') {
    $_SESSION['error_message'] = "You do not have permission to access the Admin Dashboard.";
    header("Location: dashboard.php");
    exit();
}

// Fetch bookings ordered by latest first
$sql = "SELECT b.id, u.first_name, u.last_name, b.user_id, b.contact_number, b.date, b.start_time, 
               b.hours, b.people, b.payment_method, b.status 
        FROM bookings b
        JOIN users u ON b.user_id = u.id
        ORDER BY b.date DESC, b.start_time DESC";
$result = $conn->query($sql);

// Handle status update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['status'])) {
    $booking_id = $_POST['booking_id'];
    $new_status = $_POST['status'];

    // Fetch user ID
    $user_sql = "SELECT user_id FROM bookings WHERE id = ?";
    $stmt = $conn->prepare($user_sql);
    $stmt->bind_param("i", $booking_id);
    $stmt->execute();
    $user_result = $stmt->get_result();
    $user_data = $user_result->fetch_assoc();
    $stmt->close();

    $user_id = $user_data['user_id'];

    // Update booking status
    $update_sql = "UPDATE bookings SET status = ? WHERE id = ?";
    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param("si", $new_status, $booking_id);
    $stmt->execute();
    $stmt->close();

    // Send notification
    $notif_message = "";
    if ($new_status === 'Confirmed') {
        $notif_message = "Your booking has been approved!";
    } elseif ($new_status === 'Cancelled') {
        $notif_message = "Your booking has been cancelled.";
    } elseif ($new_status === 'Pending') {
        $notif_message = "Your booking is pending.";
    }

    if ($notif_message !== "") {
        $notif_status = "unread";
        $insert_notif_sql = "INSERT INTO notifications (user_id, message, status) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($insert_notif_sql);
        $stmt->bind_param("iss", $user_id, $notif_message, $notif_status);
        $stmt->execute();
        $stmt->close();
    }

    header("Location: Admin_Dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: url('1.png');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            height: 100vh;
            margin: 0;
            padding: 0;
        }

        .header {
            background-color: #007D14;
            color: white;
            text-align: center;
            padding: 15px;
            font-size: 24px;
            font-weight: bold;
            position: relative;
        }

        .logout-btn {
            position: absolute;
            right: 20px;
            top: 50%;
            transform: translateY(-50%);
            background: transparent;
            color: white;
            padding: 8px;
            border: 2px solid white;
            border-radius: 50%;
            cursor: pointer;
            font-size: 18px;
            transition: all 0.3s ease;
        }

        .logout-btn:hover {
            background: red;
            border-color: red;
            color: white;
        }

        table {
            width: 90%;
            margin: 30px auto;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
            overflow: hidden;
        }

        th, td {
            padding: 12px;
            text-align: center;
            border-bottom: 1px solid #ddd;
        }

        th {
            background: #007D14;
            color: white;
        }

        tr:hover {
            background: #f2f2f2;
            box-shadow: 0 0 8px rgba(0, 0, 0, 0.1);
        }

        select {
            padding: 8px;
            font-size: 14px;
            border: 1px solid #ccc;
            border-radius: 5px;
            cursor: pointer;
        }

        @media (max-width: 768px) {
            table {
                font-size: 14px;
            }
        }
    </style>
    <script>
        function updateStatus(selectElement, bookingId) {
            document.getElementById('status-form-' + bookingId).submit();
        }
    </script>
</head>
<body>

<div class="header">
    ADMIN DASHBOARD - BOOKING DETAILS
    <form action="logout.php" method="POST" style="display: inline;">
        <button type="submit" class="logout-btn">
            <i class="fa-solid fa-right-from-bracket"></i>
        </button>
    </form>
</div>

<table>
    <thead>
        <tr>
            <th>Name</th>
            <th>Contact Number</th>
            <th>Date</th>
            <th>Start Time</th>
            <th>Hours</th>
            <th>People</th>
            <th>Payment Method</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo htmlspecialchars($row['first_name'] . ' ' . $row['last_name']); ?></td>
                <td><?php echo htmlspecialchars($row['contact_number']); ?></td>
                <td><?php echo htmlspecialchars($row['date']); ?></td>
                <td><?php echo htmlspecialchars($row['start_time']); ?></td>
                <td><?php echo htmlspecialchars($row['hours']); ?> hrs</td>
                <td><?php echo htmlspecialchars($row['people']); ?></td>
                <td><?php echo htmlspecialchars($row['payment_method']); ?></td>
                <td><?php echo htmlspecialchars($row['status']); ?></td>
                <td>
                    <form method="POST" id="status-form-<?php echo $row['id']; ?>">
                        <input type="hidden" name="booking_id" value="<?php echo $row['id']; ?>">
                        <select name="status" onchange="updateStatus(this, <?php echo $row['id']; ?>)">
                            <option value="Pending" <?php echo ($row['status'] == 'Pending') ? 'selected' : ''; ?>>Pending</option>
                            <option value="Confirmed" <?php echo ($row['status'] == 'Confirmed') ? 'selected' : ''; ?>>Confirmed</option>
                            <option value="Cancelled" <?php echo ($row['status'] == 'Cancelled') ? 'selected' : ''; ?>>Cancelled</option>
                        </select>
                    </form>
                </td>
            </tr>
        <?php endwhile; ?>
    </tbody>
</table>

</body>
</html>
