<?php
require 'db.php';

if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM admins WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $admin = $result->fetch_assoc();

        if (password_verify($password, $admin['password'])) {
            session_start();
            $_SESSION['admin_id'] = $admin['id']; // Store the user ID or other data
            $_SESSION['username'] = $admin['username']; // Optionally store username
            header("Location: dashboard.php"); // Redirect to the dashboard or home page
        } else {
            $error_message = "Invalid credentials.";
        }
    } else {
        $error_message = "No user found with this email.";
    }

    $stmt->close();
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-image: url('uploads/bg1.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-weight: bolder;
        }
        .login-container {
            background: rgba(255, 255, 255, 0.7); /* Semi-transparent background */
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 10px 15px rgba(0, 0, 0, 0.1);
            text-align: center;
            width: 100%;
            max-width: 450px;
            margin: 100px auto;
            
        }
        h2 {
            color: #343a40;
            font-size: 28px;
            margin-bottom: 20px;
            font-weight: bolder;
        }
        .form-control {
            border-radius: 8px;
        }
        .btn-custom {
            background-color: #007BFF;
            color: white;
            border: none;
            width: 100%;
            padding: 12px;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .btn-custom:hover {
            background-color: #0056b3;
        }
        .btn-custom:active {
            background-color: #004085;
        }
        .error {
            color: #dc3545;
            font-size: 0.9em;
            margin-top: 10px;
        }
        .footer-text {
            margin-top: 20px;
            color:rgb(30, 31, 31);
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
        <h2>Admin Login</h2>

        <?php if (isset($error_message)) : ?>
            <div class="error"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <form action="admin_login.php" method="POST">
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" name="email" id="email" class="form-control" required>
            </div>
            
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" name="password" id="password" class="form-control" required>
            </div>
            
            <button type="submit" name="login" class="btn-custom">Login</button>
        </form>

        <div class="footer-text">
            <p>Don't have an account? <a href="signup.php">Sign Up</a></p>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
