<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Selection</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-image: url('uploads/home.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .login-container {
            background: rgba(255, 255, 255, 0.85); /* Semi-transparent background */
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 10px 15px rgba(0, 0, 0, 0.1);
            text-align: center;
            width: 100%;
            max-width: 450px;
        }
        h2 {
            color: #343a40;
            font-size: 28px;
            margin-bottom: 20px;
            font-weight: 600;
        }
        .btn-custom {
            display: block;
            width: 100%;
            padding: 15px;
            margin: 12px 0;
            background: #007BFF;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 18px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .btn-custom:hover {
            background-color: #0056b3;
        }
        .btn-custom:active {
            background-color: #004085;
        }
        .footer-text {
            margin-top: 20px;
            color: #6c757d;
            font-size: 14px;
        }
        .footer-text a {
            color: #007BFF;
            text-decoration: none;
        }
        .footer-text a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <div class="login-container">
        <h2>Career Catalyst
        </h2>
        <a href="admin_login.php" class="btn-custom">Admin Login</a>
        <a href="student_login.php" class="btn-custom">Student Login</a>

        <div class="footer-text">
            <p>Don't have an account? <a href="signup.php">Sign up here</a></p>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
