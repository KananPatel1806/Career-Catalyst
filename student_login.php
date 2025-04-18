<?php
require 'db.php';
session_start();

if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM students WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $student = $result->fetch_assoc();

        if (password_verify($password, $student['password'])) {
            $_SESSION['student_id'] = $student['id'];
            $_SESSION['student_name'] = $student['name'];
            $_SESSION['student_image'] = $student['image'];
            header("Location: student_dashboard.php"); // Redirect to dashboard.php after successful login
            exit();
        } else {
            echo "<script>alert('Invalid password!');</script>";
        }
    } else {
        echo "<script>alert('No student found with this email!');</script>";
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
    <title>Student Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-image: url('uploads/Student_bg.jpeg'); /* Background Image */
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            overflow: hidden;
        }

        .container {
            background: rgba(255, 255, 255, 0.7); /* Light background with slight transparency */
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
            max-width: 450px;
            width: 100%;
           
        }

        @keyframes fadeIn {
            0% {
                opacity: 0;
                transform: translateY(-20px);
            }
            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        h2 {
            text-align: center;
            color: #333;
            font-size: 30px;
            margin-bottom: 20px;
            font-weight: 700;
            letter-spacing: 1px;
        }

        label {
            font-weight: 600;
            color: #444;
            margin-bottom: 8px;
            display: block;
        }

        input {
            width: 100%;
            padding: 14px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 10px;
            font-size: 16px;
            background-color: #f9f9f9;
            transition: all 0.3s ease;
        }

        input:focus {
            border-color: #007BFF;
            background-color: #fff;
            outline: none;
            box-shadow: 0 0 8px rgba(0, 123, 255, 0.2);
        }

        button {
            background-color: #007BFF;
            color: white;
            border: none;
            padding: 14px;
            border-radius: 12px;
            cursor: pointer;
            font-size: 16px;
            width: 100%;
            font-weight: 600;
            letter-spacing: 0.5px;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #0056b3;
        }

        .register-link {
            display: block;
            text-align: center;
            margin-top: 15px;
            color:  #6c757d;;
            font-size: 16px;
            font-weight: 500;
            transition: color 0.3s ease;
        }

        .register-link:hover {
            color: #0056b3;
            text-decoration: underline;
        }

        .error-message {
            color: #dc3545;
            font-size: 0.9em;
            margin-top: 10px;
            text-align: center;
            font-weight: 600;
        }
        .footer-text {
            margin-top: 20px;
            color: #6c757d;
            font-size: 14px;
            padding: 5px;
            text-align: center;
        }
    
        .footer-text a {
            color: #007BFF;
            text-decoration: none;
        }
        .footer-text a:hover {
            text-decoration: underline;
        }

        /* Mobile Responsiveness */
        @media (max-width: 576px) {
            .container {
                padding: 30px;
                max-width: 350px;
            }

            h2 {
                font-size: 24px;
            }

            input {
                padding: 12px;
                font-size: 14px;
            }

            button {
                padding: 12px;
                font-size: 14px;
            }

            .register-link {
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Student Login</h2>

        <?php if (isset($error_message)) : ?>
            <div class="error-message"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <form action="student_login.php" method="POST">
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" name="email" id="email" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" name="password" id="password" class="form-control" required>
            </div>

            <button type="submit" name="login" class="btn btn-primary">Login</button>
        </form>

        <div class="footer-text">
            <p>Don't have an account? <a href="register.php">Register Here</a></p>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
