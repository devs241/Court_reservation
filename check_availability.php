<?php
include 'config.php';

if (isset($_POST['date']) && isset($_POST['start_time']) && isset($_POST['end_time'])) {
    $date = $_POST['date'];
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];

    // Prepare the query to check for overlapping bookings
    $query = "SELECT * FROM bookings 
              WHERE date = ? 
              AND status != 'Cancelled'
              AND (
                  (start_time <= ? AND end_time > ?) OR
                  (start_time < ? AND end_time >= ?) OR
                  (start_time >= ? AND end_time <= ?)
              )";
    
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sssssss", $date, $end_time, $start_time, $end_time, $start_time, $start_time, $end_time);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "unavailable"; // Time slot is already booked
    } else {
        echo "available";   // Time slot is available
    }

    $stmt->close();
}
?>
