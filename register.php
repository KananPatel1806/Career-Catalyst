<?php
require 'db.php';

// Fetching the background image dynamically from the 'uploads' folder (if any)
$background_image = 'uploads/background.jpg'; // Change this if you want a specific background image

if (isset($_POST['register'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $image = $_FILES['image']['name'];
    $course = $_POST['course'];
    $birthday = $_POST['birthday'];
    $gender = $_POST['gender'];
    $address = $_POST['address'];
    $phone = $_POST['phone'];
    $type = $_POST['type'];
    $cgpa = $_POST['cgpa'];

    // Image upload directory
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($image);

    // Move uploaded file to directory
    if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
        // SQL statement
        $sql = "INSERT INTO students (name, email, password, image, student_course, student_birthday, student_gender, student_address, student_phone, student_type, student_cgpa) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        // Prepare statement
        if ($stmt = $conn->prepare($sql)) {
            // Bind parameters
            $stmt->bind_param(
                "sssssssssss", 
                $name, $email, $password, $image, $course, $birthday, $gender, $address, $phone, $type, $cgpa
            );

            // Execute statement
            if ($stmt->execute()) {
                echo "Registration successful!";
            } else {
                echo "Error executing query: " . $stmt->error;
            }

            $stmt->close();
        } else {
            echo "Error preparing query: " . $conn->error;
        }
    } else {
        echo "Error uploading the image.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Registration</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-image: url('uploads/Student_bg.jpeg');
            background-size: cover;
            background-position: center;
            display: flex;
            justify-content: center;
            align-items: center;
           
            margin: 0;
            color: #fff;
        }

        .container {
            background-color: rgba(255, 255, 255, 0.5); /* Semi-transparent white background */
            border-radius: 8px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 900px;
            padding: 40px;
            box-sizing: border-box;
        }

        h2 {
            text-align: center;
            color: #333;
            font-size: 30px;
            margin-bottom: 20px;
            margin-top: 10px;
        }

        .row {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }

        .col {
            flex: 1;
            min-width: 300px;
        }

        label {
            font-weight: bold;
            color:black;
            margin-bottom: 8px;
            display: block;
        }

        input, select {
            width: 100%;
            padding: 12px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 16px;
            box-sizing: border-box;
        }

        input[type="file"] {
            padding: 12px;
            border: 1px solid #ddd;
            margin-bottom: 20px;
        }

        button {
            background: #007BFF;
            color: white;
            border: none;
            padding: 15px;
            border-radius: 6px;
            cursor: pointer;
            width: 100%;
            font-size: 18px;
            transition: background 0.3s ease;
        }

        button:hover {
            background: #0056b3;
        }

        .image-upload {
            padding: 15px;
            border-radius: 6px;
           
            border: 1px solid #ddd;
        }

        .image-upload input[type="file"] {
            border: none;
            padding: 10px;
            color: black;
        }

        .footer-text {
            margin-top: 20px;
            color: #6c757d;
            font-size: 14px;
            text-align: center;
            padding: 5px;
        }
        .footer-text a {
            color: #007BFF;
            text-decoration: none;
        }
        .footer-text a:hover {
            text-decoration: underline;
        }

        @media (max-width: 768px) {
            .col {
                flex: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Student Registration</h2>
        <form action="register.php" method="POST" enctype="multipart/form-data">
            <div class="row">
                <div class="col">
                    <label>Full Name:</label>
                    <input type="text" name="name" required>

                    <label>Email:</label>
                    <input type="email" name="email" required>

                    <label>Password:</label>
                    <input type="password" name="password" required>

                    <label>Course:</label>
                    <select name="course" required>
                        <option value="M.Tech">M.Tech</option>
                        <option value="B.Tech">B.Tech</option>
                        <option value="Diploma">Diploma</option>
                    </select>

                    <label>Gender:</label>
                    <select name="gender" required>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                        <option value="Other">Other</option>
                    </select>

                    <label>Phone:</label>
                    <input type="text" name="phone" required>
                </div>

                <div class="col">
                    <label>Upload Image:</label>
                    <div class="image-upload">
                        <input type="file" name="image" accept="image/*" required>
                    </div>

                    <label>Birthday:</label>
                    <input type="date" name="birthday" required>

                    <label>Address:</label>
                    <input type="text" name="address" required>

                    <label>Type:</label>
                    <select name="type" required>
                        <option value="UG">Undergraduate (UG)</option>
                        <option value="PG">Postgraduate (PG)</option>
                    </select>

                    <label>CGPA:</label>
                    <input type="text" name="cgpa" required>

                    <label>Placement Type:</label>
                    <select name="types" required>
                        <option value="Internship">Internship</option>
                        <option value="Full Time">Full Time</option>
                    </select>
                </div>
            </div>
            
            <div class="footer-text">
            <p>Already have an account? <a href="student_login.php">Log in</a></p>
        </div>
            <button type="submit" name="register">Register</button>
        </form>

       
    </div>
</body>
</html>