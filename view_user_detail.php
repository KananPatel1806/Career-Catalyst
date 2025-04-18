<?php
require 'db.php';
session_start();

if (!isset($_GET['id'])) {
    header("Location: view_users.php");
    exit();
}

$student_id = $_GET['id'];

// Fetch student details
$sql = "SELECT * FROM students WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $student_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo "<script>alert('Student not found!'); window.location.href = 'view_users.php';</script>";
    exit();
}

$student = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .nav-link {
        font-size: 20px !important;
        color: white;
        font-family: 'Lucida Sans', 'Lucida Sans Regular', 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif;
        transition: color 0.3s ease, background-color 0.3s ease;
        font-weight: bolder;
    }
    .navbar{
        background: linear-gradient(135deg, #004085, #003366);
    }
    .nav-link:hover {
        color: #000102; /* Change text color on hover */
        background-color: rgba(255, 255, 255, 0.1); /* Add a slight background on hover */
        border-radius: 5px; /* Optional: Rounded corners for hover effect */
    }
    ::-webkit-scrollbar {
            display: none;
        }
        
        .admin-image {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 50%;
            margin-right: 10px;
        }
        body {
            background-color: #f8f9fa;
            font-family: 'Arial', sans-serif;
            font-weight: bolder;
        }
        .card {
            border-radius: 15px;
            box-shadow: 0 10px 15px rgba(0, 0, 0, 0.1);
        }
        .profile-img {
            border-radius: 50%;
            width: 180px;
            height: 180px;
            object-fit: cover;
            border: 5px solid #007BFF;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .list-group-item {
            font-size: 1.1em;
            padding: 15px;
            border: none;
        }
        .list-group-item:nth-child(even) {
            background-color: #f1f1f1;
        }
        .btn-back {
            font-size: 16px;
            padding: 10px 20px;
            border-radius: 25px;
            background-color:rgb(255, 217, 0);
            color: black;
            border: none;
        }
        .btn-back:hover {
            background-color:rgb(255, 217, 0);
        }
        .container {
            max-width: 900px;
            margin-top: 40px;
        }
        .header-text {
            color: #343a40;
            font-weight: bold;
            margin-bottom: 30px;
        }
        .footer {
            background: linear-gradient(135deg, #004085, #003366);
            color: white;
            text-align: center;
            padding: 10px 0;
            position: fixed;
            bottom: 0;
            width: 100%;
          
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid">
            <a class="navbar-brand d-flex align-items-center" href="dashboard.php">
                <img src="uploads/admin.jpg" alt="Admin" class="admin-image">
                
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    
                    <li class="nav-item">
                        <a class="nav-link" href="admin_jobs.php">Manage Jobs</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="create_job.php">Create Job</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="upload_questions.php">Upload  Material</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="view_questions_admin.php">Uploaded  Material</a>
                    </li>
                </ul>
                <div class="d-flex align-items-center">
                    <img src="uploads/logout.jpg" alt="Logout" class="rounded-circle me-2" style="width: 40px; height: 40px; object-fit: cover;">
                    <a href="logout.php" class="text-white text-decoration-none"></a>
                </div>
            </div>
        </div>
    </nav>
    <div class="container">
        <h1 class="header-text text-center">Student Details</h1>

        <div class="card mb-4">
            <div class="card-body text-center">
                <!-- Profile Image -->
                <img src="uploads/<?php echo htmlspecialchars($student['image']); ?>" alt="Student Image" class="profile-img mb-4">
                
                <!-- Student Info List -->
                <ul class="list-group">
                    <li class="list-group-item"><strong>ID:</strong> <?php echo $student['id']; ?></li>
                    <li class="list-group-item"><strong>Name:</strong> <?php echo htmlspecialchars($student['name']); ?></li>
                    <li class="list-group-item"><strong>Email:</strong> <?php echo htmlspecialchars($student['email']); ?></li>
                    <li class="list-group-item"><strong>Phone:</strong> <?php echo htmlspecialchars($student['student_phone']); ?></li>
                    <li class="list-group-item"><strong>Address:</strong> <?php echo htmlspecialchars($student['student_address']); ?></li>
                    <li class="list-group-item"><strong>Course:</strong> <?php echo htmlspecialchars($student['student_course']); ?></li>
                    <li class="list-group-item"><strong>Birthday:</strong> <?php echo htmlspecialchars($student['student_birthday']); ?></li>
                    <li class="list-group-item"><strong>Gender:</strong> <?php echo htmlspecialchars($student['student_gender']); ?></li>
                    <li class="list-group-item"><strong>Student Type:</strong> <?php echo htmlspecialchars($student['student_type']); ?></li>
                    <li class="list-group-item"><strong>CGPA:</strong> <?php echo htmlspecialchars($student['student_cgpa']); ?></li>
                    <li class="list-group-item"><strong>Type:</strong> <?php echo htmlspecialchars($student['course_type']); ?></li>
                </ul>
            </div>
        </div>

        <!-- Back Button -->
        <div class="text-center mb-5 p-2">
            <a href="view_users.php" class="btn btn-back mb-5">Back to List</a>
        </div>
    </div>
     <!-- Footer -->
     <div class="footer">
        <p>&copy; 2025 Your Platform. Delivering innovative solutions for administrators to effectively manage student resources.</p>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
