<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Court Selection</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: url('1.png');
            background-size: cover;
            margin: 0;
            padding: 0;
        }
        .header {
            background-color: #007D14;
            color: white;
            padding: 15px;
            text-align: center;
            font-size: 24px;
            font-weight: bold;
        }
        .court-container {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 20px;
            margin-top: 30px;
        }
        .court-card {
            background-color: rgba(0, 0, 0, 0.6);
            padding: 15px;
            border-radius: 10px;
            text-align: center;
            color: white;
            width: 300px;
            transition: all 0.3s ease;
            cursor: pointer;
            position: relative;
            overflow: hidden;
        }
        .court-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.3);
            background-color: rgba(0, 0, 0, 0.8);
        }
        .court-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(
                90deg,
                transparent,
                rgba(255, 255, 255, 0.2),
                transparent
            );
            transition: 0.5s;
        }
        .court-card:hover::before {
            left: 100%;
        }
        .court-card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 5px;
            transition: transform 0.3s ease;
        }
        .court-card:hover img {
            transform: scale(1.05);
        }
        .court-card h2 {
            margin: 15px 0 10px;
            transition: color 0.3s ease;
        }
        .court-card:hover h2 {
            color: #4CAF50;
        }
        .book-button {
            background-color: #007D14;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        .book-button:hover {
            background-color: #005c0f;
            transform: scale(1.05);
        }
        .book-button:active {
            transform: scale(0.95);
        }
        .book-button::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            transform: translate(-50%, -50%);
            transition: width 0.6s ease, height 0.6s ease;
        }
        .book-button:hover::after {
            width: 300px;
            height: 300px;
        }
    </style>
</head>
<body>

<div class="header">COURT SELECTION</div>

<div class="court-container">
    <div class="court-card">
        <h2>COURT 1</h2>
        <img src="1.jpg" alt="Court 1">
        <button class="book-button" onclick="window.location.href='booking_process.php?court=1'">Book Now</button>
    </div>
    <div class="court-card">
        <h2>COURT 2</h2>
        <img src="2.jpg" alt="Court 2">
        <button class="book-button" onclick="window.location.href='booking_process.php?court=2'">Book Now</button>
    </div>
    <div class="court-card">
        <h2>COURT 3</h2>
        <img src="3.jpg" alt="Court 3">
        <button class="book-button" onclick="window.location.href='booking_process.php?court=3'">Book Now</button>
    </div>
    <div class="court-card">
        <h2>COURT 4</h2>
        <img src="4.jpg" alt="Court 4">
        <button class="book-button" onclick="window.location.href='booking_process.php?court=4'">Book Now</button>
    </div>
</div>

</body>
</html>
