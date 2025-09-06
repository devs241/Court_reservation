<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Badminton Court Rental</title>
    <link href="https://fonts.googleapis.com/css2?family=Anton&display=swap" rel="stylesheet">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body, html {    
            height: 100%;
            font-family: Arial, sans-serif;
        }

        .container {
            height: 100vh;
            background: url('1.png') no-repeat center center/cover;
            display: flex;
            justify-content: center;
            align-items: center;
            text-align: center;
            position: relative;
        }

        .overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.6);
        }

        .top-bar {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 50px;
            display: flex;
            justify-content: flex-end;
            align-items: center;
            padding-right: 20px;
        }

        .login-btn {
            text-decoration: none;
            color: white;
            font-size: 16px;
            background: #007D14;
            padding: 5px 15px;
            border-radius: 5px;
            border: 1px solid black;
            transition: 0.3s;
        }

        .login-btn:hover {
            background: #007D14;
            color: white;
        }

        .content {
            position: relative;
            color: white;
            padding: 20px;
        }

        .content h1 {
            font-size: 60px;
            font-family: Arial, sans-serif;
            text-transform: uppercase;
\        }
    </style>
</head>
<body>
<div class="container">
    <div class="overlay"></div>
    <div class="top-bar">
        <a href="login.php" class="login-btn">LOGIN</a>
        
    </div>
    <div class="content">
        <h1>BADMINTON COURT RESERVATION </h1>
    </div>
</div>

</body>
</html>
