<?php
require 'db.php';

// Check if student_id is provided
if (!isset($_GET['student_id'])) {
    die("Student ID is required.");
}

$student_id = intval($_GET['student_id']);

// Fetch student details
$student_sql = "SELECT * FROM students WHERE id = ?";
$student_stmt = $conn->prepare($student_sql);
$student_stmt->bind_param("i", $student_id);
$student_stmt->execute();
$student_result = $student_stmt->get_result();
$student = $student_result->fetch_assoc();

if (!$student) {
    die("Student not found.");
}

// Fetch jobs the student has applied for
$jobs_sql = "SELECT j.title, j.location, j.ctc 
             FROM job_applications ja 
             INNER JOIN jobs j ON ja.job_id = j.id 
             WHERE ja.student_id = ?";
$jobs_stmt = $conn->prepare($jobs_sql);
$jobs_stmt->bind_param("i", $student_id);
$jobs_stmt->execute();
$jobs_result = $jobs_stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - View Profile: <?php echo htmlspecialchars($student['name']); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
         .nav-link {
        font-size: 20px !important;
        color: white;
        font-family: 'Lucida Sans', 'Lucida Sans Regular', 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif;
        transition: color 0.3s ease, background-color 0.3s ease;
    }
    .navbar{
        background: linear-gradient(135deg, #004085, #003366);
    }
    .nav-link:hover {
        color: #000102; /* Change text color on hover */
        background-color: rgba(255, 255, 255, 0.1); /* Add a slight background on hover */
        border-radius: 5px; /* Optional: Rounded corners for hover effect */
    }
    .admin-image {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 50%;
            margin-right: 10px;
        }
        body {
            background-image: url('');
            background-size: cover;
            background-position: center;
            font-family: 'Arial', sans-serif;
            background-attachment: fixed;
            color: #fff;
            font-weight: bolder;
        }
        .container {
            margin-top: 50px;
            background: rgba(0, 0, 0, 0.1);
            padding: 30px;
            border-radius: 8px;
        }
        .header {
            background-color:rgb(2, 38, 77);
            color: white;
            padding: 20px;
            text-align: center;
            margin-bottom: 20px;
        }
        .profile-image {
            width: 150px;
            height: 150px;  border-radius: 50%;
            object-fit: cover;
            margin: 0 auto;
            display: block;
            border: 4px solid #02264D;
        }
        .card {
            margin-top: 20px;
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
                        <a class="nav-link" href="view_users.php">Students</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="create_job.php">Create Job</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="admin_jobs.php">Manage Job</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="upload_questions.php">Upload  Material</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="view_questions_admin.php">Uploaded  Material</a>
                    </li>
                </ul>
                <div class="d-flex align-items-center">
                    <a href="logout.php">
                        <img src="uploads/logout.jpg" alt="Logout" class="rounded-circle me-2" style="width: 40px; height: 40px; object-fit: cover;">
                    </a>
                </div>
            </div>
        </div>
    </nav>


    <!-- Header -->
    <div class="header">
        <h1> Student Profile</h1>
    </div>

    <!-- Main Content -->
    <div class="container">
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="text-center">
                    <img src="uploads/<?php echo htmlspecialchars($student['image']); ?>" 
                        alt="Profile Image" class="profile-image mb-4">
                </div>
                <h2 class="text-center"><?php echo htmlspecialchars($student['name']); ?></h2>
                <hr>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($student['email']); ?></p>
                <p><strong>Phone:</strong> <?php echo htmlspecialchars($student['student_phone']); ?></p>
                <p><strong>Address:</strong> <?php echo htmlspecialchars($student['student_address']); ?></p>
                <p><strong>Course:</strong> <?php echo htmlspecialchars($student['student_course']); ?></p>
                <p><strong>Gender:</strong> <?php echo htmlspecialchars($student['student_gender']); ?></p>
            </div>
        </div>

        <!-- Jobs Applied Section -->
        <div class="card shadow-sm mt-4">
            <div class="card-body">
                <h4 class="card-title">Jobs Applied</h4>
                <table class="table table-bordered table-hover mt-3">
                    <thead>
                        <tr>
                            <th>Job Title</th>
                            <th>Location</th>
                            <th>CTC</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($jobs_result->num_rows > 0): ?>
                            <?php while ($job = $jobs_result->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($job['title']); ?></td>
                                    <td><?php echo htmlspecialchars($job['location']); ?></td>
                                    <td><?php echo htmlspecialchars($job['ctc']); ?></td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="3" class="text-center">No jobs applied yet.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div><br/>
    </div><br/>

    <!-- Footer -->
    <div class="footer">
        <p>&copy; <?php echo date("Y"); ?>  Your Platform. Delivering innovative solutions for administrators to effectively manage student resources.</p>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
