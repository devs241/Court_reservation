
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Details</title>
   
</head>
<body>
 <style>
        body {
            font-family: Arial, sans-serif;
            background-image: url('1.png');
            background-size: cover;
            background-position: center;
            margin: 0;
            padding: 0;
        }
        .header {
            background-color: #007D14;
            padding: 15px;
            text-align: center;
            color: white;
            font-size: 24px;
            font-weight: bold;
        }
        .booking-form {
            width: 50%;
            margin: 40px auto;
            padding: 30px;
            background-color: rgba(255, 255, 255, 0.9);
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.2);
        }
        .form-group {
            display: flex;
            flex-direction: column;
            margin-bottom: 15px;
        }
        label {
            font-weight: bold;
            margin-bottom: 5px;
        }
        input, select {
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 16px;
        }
        .two-columns {
            display: flex;
            justify-content: space-between;
            gap: 15px;
        }
        .two-columns .form-group {
            width: 48%;
        }
        button {
            background-color: #007D14;
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
            width: 100%;
        }
        button:hover {
            background-color: #005f0e;
        }
        .gcash-info {
            display: none;
            padding: 10px;
            background-color: #e0f7e9;
            border: 1px solid #007D14;
            border-radius: 8px;
            color: #007D14;
            font-weight: bold;
        }
        .gcash-receipt {
            display: none;
            padding: 20px;
            background-color: #e0f7e9;
            border: 1px solid #007D14;
            border-radius: 8px;
            color: #007D14;
            font-weight: bold;
            margin-top: 20px;
        }
        .gcash-receipt-dialog {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: white;
            border-radius: 15px;
            padding: 30px;
            z-index: 1000;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.2);
            width: 400px;
            max-width: 90%;
        }
        .gcash-receipt-dialog h2 {
            color: blue;
            text-align: center;
            margin-bottom: 20px;
            font-size: 24px;
        }
        .gcash-receipt-dialog h3 {
            color: blue;
            text-align: center;
            margin-bottom: 30px;
            font-size: 28px;
        }
        .receipt-details {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
        }
        .receipt-details p {
            margin: 10px 0;
            display: flex;
            justify-content: space-between;
            color: #333;
        }
        .receipt-details p strong {
            color: blue;
        }
        .receipt-divider {
            border-top: 2px dashed blue;
            margin: 20px 0;
        }
        .gcash-logo {
            text-align: center;
            margin-bottom: 20px;
        }
        .gcash-logo img {
            width: 150px;
            height: auto;
        }
        .close-btn {
            background-color: blue;
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 25px;
            cursor: pointer;
            font-size: 16px;
            width: 100%;
            transition: all 0.3s ease;
        }
        .close-btn:hover {
            background-color: blue;
            transform: translateY(-2px);
        }
        .receipt-status {
            text-align: center;
            color: blue;
            font-weight: bold;
            margin-bottom: 20px;
        }
        .receipt-status i {
            margin-right: 5px;
        }
    </style>

<div class="header">BOOKING DETAILS</div>

<?php
// Get the court ID from the URL parameter
$court_id = isset($_GET['court']) ? intval($_GET['court']) : 0;
?>

<div class="booking-form">
    <form action="confirm_booking.php" method="post" onsubmit="return validatePeople();">
        <input type="hidden" name="court_id" value="<?php echo $court_id; ?>">
        
        <div class="form-group">
            <label for="fullname">Name:</label>
            <input type="text" id="fullname" name="fullname" required>
        </div>

        <div class="form-group">
            <label for="contact">Contact Number:</label>
            <input type="text" id="contact" name="contact" required>
        </div>

        <div class="two-columns">
            <div class="form-group">
                <label for="date">Date:</label>
                <input type="date" id="date" name="date" required>
            </div>

            <div class="form-group">
                <label for="start_time">Start Time:</label>
                <input type="time" id="start_time" name="start_time" required>
            </div>

            <div class="form-group">
                <label for="end_time">End Time:</label>
                <input type="time" id="end_time" name="end_time" required>
            </div>
        </div>

        <div class="form-group">
            <label for="people">Number of Players:</label>
            <input type="number" id="people" name="people" min="8" max="8" required placeholder="Minimum of 8 players">
        </div>

        <div class="form-group">
            <label for="payment">Payment Method:</label>
            <select id="payment" name="payment" required onchange="displayGcashInfo()">
                <option value="gcash">GCash</option>
                <option value="onsite">Onsite Payment</option>
            </select>
        </div>

        <div id="gcash-info" class="gcash-info">
            GCash Number: 09350336111
        </div>

        <div class="form-group">
            <label for="total_payment">Total Payment (₱):</label>
            <input type="text" id="total_payment" name="total_payment" readonly>
        </div>

        <button type="button" onclick="confirmGcashPayment()">Confirm Booking</button>
    </form>
</div>

<div id="gcash-receipt-dialog" class="gcash-receipt-dialog">
    <div class="gcash-logo">
        <img src="gcash.png" alt="GCash Logo">
    </div>
    
    <div class="receipt-status">
        <i class="fas fa-check-circle"></i> Payment Successful
    </div>

    <h2>Transaction Receipt</h2>
    <h3>₱<span id="amount-paid"></span></h3>
    
    <div class="receipt-details">
        <p><strong>Transaction ID:</strong> <span id="receipt-number"></span></p>
        <p><strong>Date & Time:</strong> <span id="payment-date"></span></p>
        <p><strong>Payment Method:</strong> GCash</p>
        <p><strong>Account Number:</strong> 09350336111</p>
        <p><strong>Email Receipt:</strong> <span id="email-receipt"></span></p>
    </div>

    <div class="receipt-divider"></div>

    <p style="text-align: center; color: #666; font-size: 14px;">
        This is an electronic receipt. No signature is required.
    </p>
    
    <button class="close-btn" onclick="closeGcashReceipt()">Close Receipt</button>
</div>

<script>
    const pricePerHour = 150;

    document.getElementById('start_time').addEventListener('change', calculatePayment);
    document.getElementById('end_time').addEventListener('change', calculatePayment);

    function calculatePayment() {
        const startTime = document.getElementById('start_time').value;
        const endTime = document.getElementById('end_time').value;
        const totalPaymentInput = document.getElementById('total_payment');

        if (startTime && endTime) {
            const start = new Date(`1970-01-01T${startTime}:00`);
            const end = new Date(`1970-01-01T${endTime}:00`);

            let diffInHours = (end - start) / (1000 * 60 * 60);

            if (diffInHours <= 0) {
                alert('End time must be after start time.');
                totalPaymentInput.value = '';
                return;
            }

            const totalPayment = diffInHours * pricePerHour;
            totalPaymentInput.value = totalPayment.toFixed(2);
        } else {
            totalPaymentInput.value = '';
        }
    }

    function displayGcashInfo() {
        const paymentMethod = document.getElementById('payment').value;
        const gcashInfo = document.getElementById('gcash-info');
        gcashInfo.style.display = paymentMethod === 'gcash' ? 'block' : 'none';
    }

    // Add event listener for the people input field
    document.getElementById('people').addEventListener('input', function(e) {
        const value = parseInt(e.target.value);
        if (value > 8) {
            alert('Warning: Maximum number of players is 8.');
            e.target.value = 8;
        }
    });

    function validatePeople() {
        const people = document.getElementById('people').value;
        if (people < 8) {
            alert('A minimum of 8 players is required for booking.');
            return false;
        }
        if (people > 8) {
            alert('Warning: Maximum number of players is 8. Please adjust your booking.');
            document.getElementById('people').value = 8;
            return false;
        }
        return true;
    }

    function checkAvailability() {
        const date = document.getElementById('date').value;
        const startTime = document.getElementById('start_time').value;
        const endTime = document.getElementById('end_time').value;

        if (!date || !startTime || !endTime) {
            return;
        }

        fetch('check_availability.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `date=${date}&start_time=${startTime}&end_time=${endTime}`
        })
        .then(response => response.text())
        .then(result => {
            if (result === 'unavailable') {
                alert('This time slot is already booked. Please choose a different time.');
                document.getElementById('start_time').value = '';
                document.getElementById('end_time').value = '';
            }
        });
    }

    // Add event listeners for date and time changes
    document.getElementById('date').addEventListener('change', checkAvailability);
    document.getElementById('start_time').addEventListener('change', checkAvailability);
    document.getElementById('end_time').addEventListener('change', checkAvailability);

    function confirmGcashPayment() {
        const date = document.getElementById('date').value;
        const startTime = document.getElementById('start_time').value;
        const endTime = document.getElementById('end_time').value;

        // Check availability before proceeding
        fetch('check_availability.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `date=${date}&start_time=${startTime}&end_time=${endTime}`
        })
        .then(response => response.text())
        .then(result => {
            if (result === 'unavailable') {
                alert('This time slot is already booked. Please choose a different time.');
                return;
            }
            
            // If available, proceed with payment confirmation
            const receiptNumber = 'GC' + Date.now().toString().slice(-8);
            const amountPaid = document.getElementById('total_payment').value;
            const currentDate = new Date().toLocaleString('en-PH', {
                year: 'numeric',
                month: 'long',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit'
            });
            const userEmail = document.getElementById('fullname').value.toLowerCase().replace(/\s+/g, '') + "@example.com";

            document.getElementById('receipt-number').innerText = receiptNumber;
            document.getElementById('amount-paid').innerText = amountPaid;
            document.getElementById('payment-date').innerText = currentDate;
            document.getElementById('email-receipt').innerText = userEmail;
            
            document.getElementById('gcash-receipt-dialog').style.display = 'block';
        });
    }

    function closeGcashReceipt() {
        document.getElementById('gcash-receipt-dialog').style.display = 'none';
        document.querySelector('form').submit();
    }
</script>

</body>
</html>
