<?php
session_start();
include 'config.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    $_SESSION['error_message'] = "You must be logged in to make a booking.";
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $fullname = $_POST['fullname'];
    $contact = $_POST['contact'];
    $date = $_POST['date'];
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];
    $people = $_POST['people'];
    $payment = $_POST['payment'];
    $court_id = isset($_POST['court_id']) ? intval($_POST['court_id']) : 0;

    // Validate court ID
    if ($court_id <= 0) {
        $_SESSION['error_message'] = "Invalid court selection. Please try again.";
        header("Location: booking.php");
        exit();
    }

    // Convert payment method to match database enum values
    $payment_method = ($payment === 'gcash') ? 'GCash' : 'Onsite';

    // Validate start and end times
    $start = strtotime($start_time);
    $end = strtotime($end_time);

    if ($end <= $start) {
        $_SESSION['error_message'] = "End time must be after start time.";
        header("Location: booking_form.php");
        exit();
    }

    // Calculate total hours and payment
    $hours = ($end - $start) / 3600; // Convert seconds to hours
    $rate_per_hour = 150;
    $total_payment = $hours * $rate_per_hour;

    // Get the logged-in user's ID from the session
    $user_id = $_SESSION['user_id'];

    // Verify that the user exists in the database
    $stmt = $conn->prepare("SELECT id FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        $_SESSION['error_message'] = "Invalid user. Please log in again.";
        header("Location: dashboard.php");
        exit();
    }

    // Insert booking into the database with 'Pending' status
    $stmt = $conn->prepare("
        INSERT INTO bookings (user_id, court_id, fullname, contact_number, date, start_time, end_time, hours, people, payment_method, total_payment, status)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'Pending')
    ");
    $stmt->bind_param("iissssiissd", $user_id, $court_id, $fullname, $contact, $date, $start_time, $end_time, $hours, $people, $payment_method, $total_payment);

    if ($stmt->execute()) {
        // Success: Alert and redirect
        echo "<script>
            alert('Your booking has been submitted. Please wait for confirmation.');
            window.location.href = 'dashboard.php';
        </script>";
    } else {
        // Error: Display an error message
        $_SESSION['error_message'] = "An error occurred while processing your booking. Please try again.";
        header("Location: booking_form.php");
        exit();
    }

    $stmt->close();
}
?>