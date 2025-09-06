<?php
include 'config.php';

if (isset($_GET['booking_id'])) {
    $booking_id = $_GET['booking_id'];

    // Update booking status to "Approved"
    $sql = "UPDATE bookings SET status = 'Approved' WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $booking_id);
    $stmt->execute();
    $stmt->close();

    // Fetch user ID linked to the booking
    $user_sql = "SELECT user_id FROM bookings WHERE id = ?";
    $stmt = $conn->prepare($user_sql);
    $stmt->bind_param("i", $booking_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $booking = $result->fetch_assoc();
    $stmt->close();

    if ($booking) {
        $user_id = $booking['user_id'];

        // Insert a notification for the user
        $notif_sql = "INSERT INTO notifications (user_id, message, status) VALUES (?, 'Your booking has been approved!', 'unread')";
        $stmt = $conn->prepare($notif_sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $stmt->close();
    }

    header("Location: admin_dashboard.php?message=Booking Approved");
    exit();
}
?>
