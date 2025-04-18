<?php
session_start();

// Check if the student is logged in
if (!isset($_SESSION['student_id'])) {
    header("Location: index.php");
    exit();
}

$student_id = $_SESSION['student_id'];

require 'db.php';

// Fetch student details
$sql = "SELECT * FROM students WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $student_id);
$stmt->execute();
$result = $stmt->get_result();
$student = $result->fetch_assoc();

if (!$student) {
    echo "Student details not found.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background: linear-gradient(135deg, #f0f8ff, #e6f7ff); /* Subtle gradient */
        margin: 0;
        padding: 0;
        animation: fadeIn 1s ease-in-out;
    }
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
    .header {
        background: linear-gradient(135deg, #1d5563, #007b83);
        color: white;
        padding: 20px 0;
        text-align: center;
        border-bottom: 4px solid #003b4d;
        animation: slideDown 1s ease-in-out;
    }
    @keyframes slideDown {
        from { transform: translateY(-100%); }
        to { transform: translateY(0); }
    }
    .navbar {
        background: linear-gradient(135deg, #729b9e, #004d61);
        color: white;
        padding: 10px 0;
    }
    .navbar a {
        color: white;
        margin: 0 10px;
        text-decoration: none;
        font-size: 18px;
        padding: 8px 15px;
        border-radius: 5px;
        transition: background-color 0.3s ease, transform 0.3s ease;
    }
    .navbar a:hover {
        background-color: #becfd1;
        transform: scale(1.1);
    }
    .profile-image {
        width: 150px;
        height: 150px;
        border-radius: 50%;
        object-fit: cover;
        margin: 0 auto 20px;
        border: 5px solid #007b83;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        animation: bounceIn 1.5s;
    }
    @keyframes bounceIn {
        0%, 20%, 50%, 80%, 100% {
            transform: translateY(0);
        }
        40% {
            transform: translateY(-30px);
        }
        60% {
            transform: translateY(-15px);
        }
    }

    .card {
        width: 100%;
        max-width: 500px;
        margin: 20px auto;
        background: white;
        border: none;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        border-radius: 10px;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
       
       
    }
    .card:hover {
        transform: scale(1.05);
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
    }
    .footer {
        background: linear-gradient(135deg, #1d5563, #007b83);
        color: white;
        text-align: center;
        padding: 15px 0;
        position: fixed;
        bottom: 0;
        width: 100%;
      
    }
    .card-header{
        background: linear-gradient(135deg, #e89b00, #d68200);
    }

        .btn {
            background: linear-gradient(135deg, #005f6a, #002f3d);
            border: none;
            color: white;
            font-size: 16px;
            padding: 10px 20px;
            border-radius: 5px;
            transition: all 0.3s ease;
        }

        .btn:hover {
            background: linear-gradient(135deg, #006f7a, #003f4d);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>View Your Profile</h1>
        <p>Student ID: <?php echo htmlspecialchars($student['id']); ?></p>
    </div>

    <nav class="navbar d-flex justify-content-center">
        <a href="student_dashboard.php">Dashboard</a>
        <a href="view_jobs.php">Job Opportunities</a>
        <a href="view_questions.php">Interview Material</a>
        <a href="logout.php">Logout</a>
        <a href="notifications.php" class="position-relative">
            <i class="fas fa-bell"></i>
        </a>
    </nav>

    <div class="container">
        <div class="row">
            <div class="col-lg-4 text-center mt-5">
                <img src="uploads/<?php echo htmlspecialchars($student['image']); ?>" alt="Profile Image" class="profile-image">
            </div>
            <div class="col-lg-8 mb-5">
                <div class="card">
                    <div class="card-header text-white">
                        <h4>Student Details</h4>
                    </div>
                    <div class="card-body">
                        <p><strong>Name:</strong> <?php echo htmlspecialchars($student['name']); ?></p>
                        <p><strong>Email:</strong> <?php echo htmlspecialchars($student['email']); ?></p>
                        <p><strong>Phone:</strong> <?php echo htmlspecialchars($student['student_phone']); ?></p>
                        <p><strong>Address:</strong> <?php echo htmlspecialchars($student['student_address']); ?></p>
                        <p><strong>Course:</strong> <?php echo htmlspecialchars($student['student_course']); ?></p>
                        <p><strong>Birthday:</strong> <?php echo htmlspecialchars($student['student_birthday']); ?></p>
                        <p><strong>Gender:</strong> <?php echo htmlspecialchars($student['student_gender']); ?></p>
                        <p><strong>Student Type:</strong> <?php echo htmlspecialchars($student['student_type']); ?></p>
                        <p><strong>CGPA:</strong> <?php echo htmlspecialchars($student['student_cgpa']); ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div><br/>

   <!-- Footer -->
   <div class="footer">
        <p>© <?php echo date("Y"); ?> Your University. Innovating the future of education and career development.</p>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php $conn->close(); ?>
